<div>
    @if ($reminders->isNotEmpty())
        {{-- Opens once per real page load (sign-in, refresh), not on every
             in-app navigation: the flag lives on `window`, which a wire:navigate
             swap keeps and a reload resets. --}}
        <div x-data="{ open: false }"
            x-init="if (! window.__taskRemindersShown) { window.__taskRemindersShown = true; open = true }"
            x-show="open" x-cloak x-on:keydown.escape.window="open = false"
            class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true" aria-labelledby="task-reminders-title">
            <div x-show="open" x-transition.opacity x-on:click="open = false" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm"></div>

            <div class="flex min-h-full items-end justify-center p-0 sm:items-center sm:p-4">
                <div x-show="open" x-trap.noscroll="open"
                    x-transition:enter="transition duration-150 ease-out" x-transition:enter-start="translate-y-4 opacity-0 sm:translate-y-0 sm:scale-95"
                    class="relative flex max-h-[85vh] w-full max-w-lg flex-col rounded-t-2xl border border-hairline bg-surface elevate-lg sm:rounded-xl">
                    <header class="flex items-start justify-between gap-3 border-b border-hairline px-5 py-4">
                        <div class="flex items-center gap-3">
                            <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-caution-soft text-caution">
                                <x-heroicon-o-bell-alert class="h-5 w-5" />
                            </span>
                            <div>
                                <h2 id="task-reminders-title" class="font-[family-name:var(--font-display)] text-sm font-semibold">
                                    {{ $reminders->count() }} {{ $reminders->count() === 1 ? 'reminder' : 'reminders' }}
                                </h2>
                                <p class="text-xs text-ink-muted">Open tasks with a reminder date that has arrived.</p>
                            </div>
                        </div>
                        <button type="button" x-on:click="open = false" class="grid h-8 w-8 place-items-center rounded-lg text-ink-muted hover:bg-sunken hover:text-ink" aria-label="Close">
                            <x-heroicon-o-x-mark class="h-4 w-4" />
                        </button>
                    </header>

                    {{-- Scrolls on its own so a long list never pushes the Close button away. --}}
                    <ul class="min-h-0 flex-1 divide-y divide-[var(--c-hairline)] overflow-y-auto">
                        @foreach ($reminders as $reminder)
                            @php $task = $reminder->task; $daysAgo = (int) $reminder->remind_on->diffInDays($today); @endphp
                            <li>
                                <a href="{{ route('tenant.tasks.show', $task) }}" wire:navigate x-on:click="open = false"
                                    class="flex items-start gap-3 px-5 py-3 transition hover:bg-raised">
                                    <span @class(['mt-1 h-2.5 w-2.5 shrink-0 rounded-full', 'bg-critical' => $daysAgo > 0, 'bg-caution' => $daysAgo === 0])></span>
                                    <span class="min-w-0 flex-1">
                                        <span class="block text-sm font-medium text-ink">{{ $reminder->label }}</span>
                                        <span class="mt-0.5 flex flex-wrap items-center gap-x-1.5 text-xs text-ink-muted">
                                            <span class="numeric @if ($daysAgo > 0) font-medium text-critical @else font-medium text-caution @endif">
                                                {{ $daysAgo === 0 ? 'Today' : $reminder->remind_on->format('d M Y').' · '.$daysAgo.' '.($daysAgo === 1 ? 'day' : 'days').' ago' }}
                                            </span>
                                            <span>·</span>
                                            <x-ui.reference :value="$organisation->reference('task', $task->id)" />
                                            <span class="truncate">{{ $task->title }}</span>
                                        </span>
                                    </span>
                                    <x-heroicon-o-chevron-right class="mt-1 h-4 w-4 shrink-0 text-ink-muted" />
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    <footer class="flex items-center justify-between gap-3 border-t border-hairline bg-raised px-5 py-3">
                        <p class="text-xs text-ink-muted">A reminder stays here until its task is done.</p>
                        <x-ui.button size="sm" variant="primary" x-on:click="open = false">Close</x-ui.button>
                    </footer>
                </div>
            </div>
        </div>
    @endif
</div>
