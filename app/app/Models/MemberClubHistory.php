<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToOrganisation;
use Database\Factories\MemberClubHistoryFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['organisation_id', 'member_id', 'from_club_id', 'to_club_id', 'reason', 'changed_at', 'changed_by'])]
class MemberClubHistory extends Model
{
    /** @use HasFactory<MemberClubHistoryFactory> */
    use BelongsToOrganisation, HasFactory;

    protected $table = 'member_club_history';

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'changed_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<Member, $this>
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * @return BelongsTo<Club, $this>
     */
    public function fromClub(): BelongsTo
    {
        return $this->belongsTo(Club::class, 'from_club_id');
    }

    /**
     * @return BelongsTo<Club, $this>
     */
    public function toClub(): BelongsTo
    {
        return $this->belongsTo(Club::class, 'to_club_id');
    }

    /**
     * @return BelongsTo<OrganisationUser, $this>
     */
    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(OrganisationUser::class, 'changed_by');
    }
}
