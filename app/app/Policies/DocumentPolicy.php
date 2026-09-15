<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Feature;
use App\Enums\Permission;
use App\Models\Document;
use App\Models\Member;
use App\Models\User;
use App\Policies\Concerns\EvaluatesMembership;

/**
 * Documents hold the most sensitive material in the system — identity proof,
 * medical clearance, signed contracts — so seeing them is a permission of its
 * own rather than something that comes free with the member list. A staff
 * member who can look up a phone number has no automatic business opening a
 * scan of someone's ID.
 */
class DocumentPolicy
{
    use EvaluatesMembership;

    /**
     * Nothing here is permitted while the organisation has the Documents module
     * switched off (App\Enums\Feature).
     */
    public function before(User $user): ?bool
    {
        return $this->featureEnabled(Feature::Documents) ? null : false;
    }

    public function viewAny(User $user): bool
    {
        return $this->isAdmin($user) || $this->hasPermission($user, Permission::DocumentsView->value);
    }

    public function view(User $user, Document $document): bool
    {
        return $this->viewAny($user) && $this->subjectInReach($user, $document);
    }

    public function create(User $user): bool
    {
        return $this->isAdmin($user) || $this->hasPermission($user, Permission::DocumentsManage->value);
    }

    public function delete(User $user, Document $document): bool
    {
        return $this->create($user) && $this->subjectInReach($user, $document);
    }

    /**
     * A member's documents follow the same club boundary as the member: staff
     * assigned to one branch must not reach another branch's paperwork just
     * because the document permission is a flat key.
     *
     * Staff documents carry no club of their own, so they fall back to the
     * document permission alone.
     */
    private function subjectInReach(User $user, Document $document): bool
    {
        if ($this->isAdmin($user)) {
            return true;
        }

        $subject = $document->subject;

        return $subject instanceof Member
            ? $this->clubAllowed($user, $subject->primary_club_id)
            : true;
    }
}
