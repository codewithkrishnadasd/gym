<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\DomainStatus;
use Database\Factories\DomainFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['organisation_id', 'hostname', 'status', 'is_primary'])]
class Domain extends Model
{
    /** @use HasFactory<DomainFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'status' => DomainStatus::class,
            'is_primary' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<Organisation, $this>
     */
    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }
}
