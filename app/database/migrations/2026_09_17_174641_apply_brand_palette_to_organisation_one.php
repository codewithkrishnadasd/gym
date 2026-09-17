<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Organisation 1's brand palette, from the Sanitas Taekwon-Do Academy
     * logo: the um-yang blue (#1F5FC7) as the accent, its red in the
     * critical role, and cool blue-leaning off-white surfaces rather than
     * pure white so long sessions are easy on the eyes. Applied as the
     * Appearance overrides the platform admin would otherwise type in by
     * hand; every text/background pair clears 4.5:1. Nothing else about the
     * organisation is touched, and a database without this id is left alone.
     */
    private const ORGANISATION_ID = 1;

    /** @var array<string, array<string, string>> */
    private const PALETTE = [
        'light' => [
            'app' => '#eef2f9',
            'surface' => '#f8fafd',
            'raised' => '#f2f5fb',
            'sunken' => '#e6ecf5',
            'list' => '#f8fafd',
            'list-hover' => '#f0f4fa',
            'hairline' => '#d9e1ee',
            'hairline-strong' => '#c3cfe2',
            'ink' => '#0f1a33',
            'ink-soft' => '#3f4d6b',
            'ink-muted' => '#66738e',
            'accent' => '#1d4fb8',
            'on-accent' => '#ffffff',
            'accent-soft' => '#e4ecfb',
            'accent-ink' => '#173f94',
            'button-secondary' => '#f8fafd',
            'button-secondary-ink' => '#0f1a33',
            'button-secondary-border' => '#c3cfe2',
            'positive' => '#0f7a5a',
            'positive-soft' => '#e6f6ef',
            'caution' => '#b45309',
            'caution-soft' => '#fdf1e3',
            'critical' => '#c4161f',
            'critical-soft' => '#fdeaea',
            'info' => '#4a4fb5',
            'info-soft' => '#ecebfa',
        ],
        'dark' => [
            'app' => '#070c17',
            'surface' => '#0e1627',
            'raised' => '#152039',
            'sunken' => '#0a1020',
            'list' => '#0e1627',
            'list-hover' => '#172342',
            'hairline' => '#1e2b48',
            'hairline-strong' => '#2c3d63',
            'ink' => '#eef3fb',
            'ink-soft' => '#a7b5cf',
            'ink-muted' => '#7a89a7',
            'accent' => '#5b8cf5',
            'on-accent' => '#06122e',
            'accent-soft' => '#16294f',
            'accent-ink' => '#9dbcff',
            'button-secondary' => '#0e1627',
            'button-secondary-ink' => '#eef3fb',
            'button-secondary-border' => '#2c3d63',
            'positive' => '#3dd6a2',
            'positive-soft' => '#0f3328',
            'caution' => '#f5a046',
            'caution-soft' => '#3d250a',
            'critical' => '#ff6b72',
            'critical-soft' => '#431419',
            'info' => '#9a93ff',
            'info-soft' => '#231f4f',
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
     * Appearance tab is where a palette is edited, this only seeds one.
     */
    public function down(): void
    {
        DB::table('organisations')
            ->where('id', self::ORGANISATION_ID)
            ->update(['theme_colors' => null, 'accent_color' => null, 'updated_at' => now()]);
    }
};
