<?php

declare(strict_types=1);

use App\Enums\AttendanceAction;
use App\Models\Attendance;
use App\Models\Club;
use App\Models\Member;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use Illuminate\Database\QueryException;

/**
 * Verifies the two foundational guarantees the whole schema depends on:
 * the OrganisationScope global scope (MEP.md 5, technology.md 2.2) and the
 * attendance idempotency unique index (MEP.md 5.9).
 */
it('scopes queries to the resolved tenant and hides other organisations data', function (): void {
    $orgA = Organisation::factory()->create();
    $orgB = Organisation::factory()->create();

    $memberA = Member::factory()->create(['organisation_id' => $orgA->id]);
    $memberB = Member::factory()->create(['organisation_id' => $orgB->id]);

    app()->instance('tenant', $orgA);

    expect(Member::all())->toHaveCount(1);
    expect(Member::find($memberA->id))->not->toBeNull();
    expect(Member::find($memberB->id))->toBeNull();

    app()->instance('tenant', $orgB);

    expect(Member::all())->toHaveCount(1);
    expect(Member::find($memberB->id))->not->toBeNull();
    expect(Member::find($memberA->id))->toBeNull();
});

it('stamps organisation_id from the bound tenant when creating without one', function (): void {
    $org = Organisation::factory()->create();
    app()->instance('tenant', $org);

    $orgUser = OrganisationUser::factory()->create(['organisation_id' => $org->id]);
    $club = Club::factory()->create(['organisation_id' => $org->id]);

    $member = Member::factory()->make(['organisation_id' => null]);
    $member->save();

    expect($member->fresh()->organisation_id)->toBe($org->id);
});

it('rejects a second attendance record for the same subject, club, and day', function (): void {
    $org = Organisation::factory()->create();
    $club = Club::factory()->create(['organisation_id' => $org->id]);
    $member = Member::factory()->create(['organisation_id' => $org->id]);
    $marker = OrganisationUser::factory()->create(['organisation_id' => $org->id]);

    $attributes = [
        'organisation_id' => $org->id,
        'club_id' => $club->id,
        'subject_type' => 'member',
        'subject_id' => $member->id,
        'attendance_date' => '2026-01-01',
        'action' => AttendanceAction::Present,
        'marked_by' => $marker->id,
    ];

    Attendance::create($attributes);

    expect(fn () => Attendance::create($attributes))
        ->toThrow(QueryException::class);
});
