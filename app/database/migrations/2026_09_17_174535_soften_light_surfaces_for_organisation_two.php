<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Organisation 2's light theme, off pure white: the surfaces come down a
     * few points and the greys lean toward the violet brand ("lavender
     * mist"), which takes the glare off long sessions without the page
     * reading as grey. Only the light theme's surfaces, hairlines, inks and
     * the accent tint change — the brand accent, status colours and the whole
     * dark theme stay as the earlier palette migration set them. Text keeps
     * clearing 4.5:1 on every surface it sits on (ink 15:1, muted 4.7:1).
     */
    private const ORGANISATION_ID = 2;

    /** @var array<string, string> */
    private const LIGHT = [
        'app' => '#f1eef8',
        'surface' => '#f9f7fd',
        'raised' => '#f4f1fb',
        'sunken' => '#ebe6f5',
        'list' => '#f9f7fd',
        'list-hover' => '#f2eefa',
        'hairline' => '#dfd8ee',
        'hairline-strong' => '#cbc1e3',
        'ink' => '#1b1237',
        'ink-soft' => '#4a4370',
        'ink-muted' => '#776f95',
        // A touch deeper than before so it still reads as a tint on the
        // softer surface.
        'accent-soft' => '#ebe4fb',
        'button-secondary' => '#f9f7fd',
        'button-secondary-ink' => '#1b1237',
        'button-secondary-border' => '#cbc1e3',
    ];

    public function up(): void
    {
        $organisation = DB::table('organisations')->where('id', self::ORGANISATION_ID)->first(['theme_colors']);

        if ($organisation === null) {
            return;
        }

        $theme = is_string($organisation->theme_colors) ? (array) json_decode($organisation->theme_colors, true) : [];
        $theme['light'] = array_merge((array) ($theme['light'] ?? []), self::LIGHT);

        DB::table('organisations')
            ->where('id', self::ORGANISATION_ID)
            ->update(['theme_colors' => json_encode($theme), 'updated_at' => now()]);
    }

    /**
     * Drops these overrides so the surfaces follow the built-in palette
     * again; the earlier brand palette stays in place.
     */
    public function down(): void
    {
        $organisation = DB::table('organisations')->where('id', self::ORGANISATION_ID)->first(['theme_colors']);

        if ($organisation === null || ! is_string($organisation->theme_colors)) {
            return;
        }

        $theme = (array) json_decode($organisation->theme_colors, true);
        $theme['light'] = array_diff_key((array) ($theme['light'] ?? []), self::LIGHT);

        DB::table('organisations')
            ->where('id', self::ORGANISATION_ID)
            ->update(['theme_colors' => json_encode($theme), 'updated_at' => now()]);
    }
};
