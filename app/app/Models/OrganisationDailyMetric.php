<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToOrganisation;
use Database\Factories\OrganisationDailyMetricFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Reporting accelerator, not the source of truth — rebuilt from
 * source-of-truth tables by a scheduled job. See MEP.md 8.4 and
 * technology.md 7.1/7.2.
 */
#[Fillable([
    'organisation_id', 'metric_date', 'total_members', 'new_members', 'active_members',
    'attendance_present', 'revenue_collected_minor', 'expenses_recorded_minor',
    'net_movement_minor', 'by_club',
])]
class OrganisationDailyMetric extends Model
{
    /** @use HasFactory<OrganisationDailyMetricFactory> */
    use BelongsToOrganisation, HasFactory;

    const CREATED_AT = null;

    protected function casts(): array
    {
        return [
            'metric_date' => 'date',
            'by_club' => 'array',
        ];
    }
}
