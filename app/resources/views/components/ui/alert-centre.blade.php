@props(['alerts' => []])

{{--
    Things that need attention, folded into one round button at the top of
    the page — coloured for the most serious of them, with a count — and a
    dialog that lists them. Each can be put away for the session; it returns
    on the next sign-in if it is still true, so nothing is forgotten for good.

    @var array<int, array{tone: string, title: string, detail: string, route?: string}> $alerts
--}}
@php
    $rank = ['critical' => 3, 'caution' => 2, 'info' => 1, 'positive' => 0];
    $top = collect($alerts)->sortByDesc(fn (array $alert): int => $rank[$alert['tone']] ?? 0)->first();

    $badge = [
        'critical' => 'bg-critical text-white',
        'caution' => 'bg-caution text-white',
        'info' => 'bg-info text-white',
        'positive' => 'bg-positive text-white',
    ][$top['tone'] ?? 'info'];

    $stripe = [
        'critical' => 'bg-critical',
        'caution' => 'bg-caution',
        'info' => 'bg-info',
        'positive' => 'bg-positive',
    ];

    $icons = [
        'critical' => 'exclamation-circle',
        'caution' => 'exclamation-triangle',
        'info' => 'information-circle',
        'positive' => 'check-circle',
    ];

    $items = collect($alerts)
        ->sortByDesc(fn (array $alert): int => $rank[$alert['tone']] ?? 0)
        ->values()
        ->map(fn (array $alert): array => [...$alert, 'key' => 'alert:'.md5($alert['title'])])
        ->all();
@endphp

@if ($items !== [])
    <div x-data="{
            dismissed: [],
            init() {
                try { this.dismissed = JSON.parse(sessionStorage.getItem('alerts.dismissed') ?? '[]'); } catch (e) {}
            },
            dismiss(key) {
                this.dismissed = [...this.dismissed, key];
                try { sessionStorage.setItem('alerts.dismissed', JSON.stringify(this.dismissed)); } catch (e) {}
            },
            get count() {
                return @js(array_column($items, 'key')).filter((key) => ! this.dismissed.includes(key)).length;
            },
        }" class="contents">

        {{-- The button: only while something is left to see. --}}
        <button type="button" x-show="count > 0" x-cloak x-on:click="$dispatch('open-modal', 'alerts')"
            :aria-label="count + (count === 1 ? ' alert' : ' alerts')" title="Needs attention"
            class="relative grid h-10 w-10 place-items-center rounded-full border border-hairline bg-surface text-ink-soft elevate transition hover:border-hairline-strong hover:text-ink active:scale-95">
            <span class="text-lg font-bold leading-none" aria-hidden="true">!</span>
            <span class="numeric absolute -right-1 -top-1 grid h-5 min-w-5 place-items-center rounded-full px-1 text-[11px] font-semibold ring-2 ring-[var(--c-app)] {{ $badge }}" x-text="count"></span>
        </button>

        <x-ui.modal name="alerts" title="Needs attention" description="What the numbers say to look at. Put an item away for today with the ×.">
            <ul class="-mx-1 space-y-2">
                @foreach ($items as $alert)
                    <li x-show="! dismissed.includes(@js($alert['key']))"
                        class="relative flex gap-3 overflow-hidden rounded-xl border border-hairline bg-surface p-3 pl-4 pr-10">
                        <span class="absolute inset-y-0 left-0 w-1 {{ $stripe[$alert['tone']] ?? 'bg-info' }}"></span>
                        <span @class(['mt-0.5 grid h-7 w-7 shrink-0 place-items-center rounded-lg', 'bg-critical-soft text-critical' => $alert['tone'] === 'critical', 'bg-caution-soft text-caution' => $alert['tone'] === 'caution', 'bg-info-soft text-info' => $alert['tone'] === 'info', 'bg-positive-soft text-positive' => $alert['tone'] === 'positive'])>
                            <x-dynamic-component :component="'heroicon-o-'.($icons[$alert['tone']] ?? 'information-circle')" class="h-4 w-4" />
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-ink">{{ $alert['title'] }}</p>
                            <p class="mt-0.5 text-xs text-ink-soft">{{ $alert['detail'] }}</p>
                            @isset($alert['route'])
                                <a href="{{ route($alert['route']) }}" wire:navigate x-on:click="$dispatch('close-modal', 'alerts')"
                                    class="mt-1.5 inline-flex items-center gap-1 text-xs font-medium text-accent hover:underline">
                                    Review <x-heroicon-o-arrow-right class="h-3.5 w-3.5" />
                                </a>
                            @endisset
                        </div>
                        <button type="button" x-on:click="dismiss(@js($alert['key']))" aria-label="Put away for today"
                            class="absolute right-2 top-2 grid h-8 w-8 place-items-center rounded-lg text-ink-muted transition hover:bg-sunken hover:text-ink">
                            <x-heroicon-o-x-mark class="h-4 w-4" />
                        </button>
                    </li>
                @endforeach
            </ul>
            <p x-show="count === 0" x-cloak class="py-6 text-center text-sm text-ink-muted">All put away for today.</p>
        </x-ui.modal>
    </div>
@endif
