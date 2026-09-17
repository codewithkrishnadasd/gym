<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Organisation 2's brand palette, derived from its logo (violet #4B1DC6
     * and orange #F5841F): violet as the accent, the orange in the caution
     * role, lavender-tinted greys. Applied as the Appearance overrides the
     * platform admin would otherwise type in by hand; every text/background
     * pair clears 4.5:1. Nothing else about the organisation is touched, and
     * an organisation with another id is left alone.
     */
    private const ORGANISATION_ID = 2;

    /** @var array<string, array<string, string>> */
    private const PALETTE = [
        'light' => [
            'app' => '#f7f5fc',
            'surface' => '#ffffff',
            'raised' => '#faf8fe',
            'sunken' => '#f1edfa',
            'list' => '#ffffff',
            'list-hover' => '#f6f2fd',
            'hairline' => '#e6e1f3',
            'hairline-strong' => '#d3cbea',
            'ink' => '#1b1237',
            'ink-soft' => '#4a4370',
            'ink-muted' => '#7e7899',
            'accent' => '#4b1dc6',
            'on-accent' => '#ffffff',
            'accent-soft' => '#efe9fc',
            'accent-ink' => '#3a16a0',
            'button-secondary' => '#ffffff',
            'button-secondary-ink' => '#1b1237',
            'button-secondary-border' => '#d3cbea',
            'positive' => '#0f7a5a',
            'positive-soft' => '#e8f7f1',
            'caution' => '#c2610a',
            'caution-soft' => '#fef1e6',
            'critical' => '#b4123d',
            'critical-soft' => '#fdecf0',
            'info' => '#1c6fb8',
            'info-soft' => '#e8f1fb',
        ],
        'dark' => [
            'app' => '#0e0a1f',
            'surface' => '#171130',
            'raised' => '#1f1840',
            'sunken' => '#120d27',
            'list' => '#171130',
            'list-hover' => '#211a45',
            'hairline' => '#2a2352',
            'hairline-strong' => '#3a3168',
            'ink' => '#f3f0ff',
            'ink-soft' => '#b9b2d6',
            'ink-muted' => '#837ca6',
            'accent' => '#9b7cff',
            'on-accent' => '#1a0f45',
            'accent-soft' => '#2b1f5e',
            'accent-ink' => '#c9b8ff',
            'button-secondary' => '#171130',
            'button-secondary-ink' => '#f3f0ff',
            'button-secondary-border' => '#3a3168',
            'positive' => '#3dd6a2',
            'positive-soft' => '#0f3328',
            'caution' => '#f5a046',
            'caution-soft' => '#3d250a',
            'critical' => '#fb7185',
            'critical-soft' => '#3f1526',
            'info' => '#6fb4ff',
            'info-soft' => '#14304f',
        ],
    ];

    public function up(): void
    {
        DB::table('organisations')
            ->where('id', self::ORGANISATION_ID)
            ->update([
                'theme_colors' => json_encode(self::PALETTE),
                // The light primary is the brand accent (tab colour, app icon).
                'accent_color' => self::PALETTE['light']['accent'],
                'updated_at' => now(),
            ]);
    }

    /**
     * Back to the built-in palette. The previous colours are not kept: the
     * Appearance card is where a palette is edited, this only seeds one.
     */
    public function down(): void
    {
        DB::table('organisations')
            ->where('id', self::ORGANISATION_ID)
            ->update(['theme_colors' => null, 'accent_color' => null, 'updated_at' => now()]);
    }
};
