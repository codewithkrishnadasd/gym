<div x-data="{ open: false }" x-on:click.outside="open = false" x-on:keydown.escape.window="open = false" class="relative">
    <x-ui.button icon="chat-bubble-left-right" x-on:click="open = ! open" x-bind:aria-expanded="open">
        WhatsApp
        <x-heroicon-o-chevron-down class="h-3.5 w-3.5 opacity-70" />
    </x-ui.button>

    {{-- The menu, as a small sheet on phones and a dropdown on desks. The
         panel that shows the composed message lives on the page itself. --}}
    <div x-show="open" x-cloak x-on:click="open = false" x-transition.opacity class="fixed inset-0 z-40 bg-slate-950/40 sm:hidden"></div>

    <div x-show="open" x-cloak
        x-transition:enter="transition duration-150 ease-out" x-transition:enter-start="translate-y-3 opacity-0 sm:translate-y-0 sm:scale-95"
        x-transition:leave="transition duration-100 ease-in" x-transition:leave-end="translate-y-3 opacity-0 sm:translate-y-0 sm:scale-95"
        role="menu" aria-label="Send a WhatsApp message"
        class="fixed inset-x-3 bottom-[max(0.75rem,env(safe-area-inset-bottom))] z-50 overflow-hidden rounded-2xl border border-hairline bg-surface elevate-lg sm:absolute sm:inset-x-auto sm:bottom-auto sm:right-0 sm:top-full sm:mt-1.5 sm:w-72 sm:rounded-xl">
        <p class="border-b border-hairline px-3.5 py-2 text-[11px] font-semibold uppercase tracking-wide text-ink-muted">Send on WhatsApp</p>

        <div class="max-h-[60vh] overflow-y-auto py-1" wire:loading.class="opacity-60" wire:target="compose">
            <button type="button" role="menuitem" wire:click="compose('hi')" x-on:click="open = false"
                class="flex w-full items-center gap-2.5 px-3.5 py-2.5 text-left text-sm text-ink transition hover:bg-list-hover">
                <x-heroicon-o-hand-raised class="h-4 w-4 text-ink-muted" /> Say hi
            </button>

            @if ($planLapsed)
                <button type="button" role="menuitem" wire:click="compose('plan_expired')" x-on:click="open = false"
                    class="flex w-full items-center gap-2.5 px-3.5 py-2.5 text-left text-sm text-ink transition hover:bg-list-hover">
                    <x-heroicon-o-clock class="h-4 w-4 text-caution" />
                    <span>Plan expired follow-up <span class="block text-xs text-ink-muted">Their plan has run out</span></span>
                </button>
            @endif

            @if ($templates->isNotEmpty())
                <p class="mt-1 border-t border-hairline px-3.5 pb-1 pt-2 text-[11px] font-semibold uppercase tracking-wide text-ink-muted">Your templates</p>
                @foreach ($templates as $template)
                    <button type="button" role="menuitem" wire:click="compose('template:{{ $template->id }}')" x-on:click="open = false"
                        class="flex w-full items-center gap-2.5 px-3.5 py-2.5 text-left text-sm text-ink transition hover:bg-list-hover">
                        <x-heroicon-o-document-text class="h-4 w-4 text-ink-muted" /> <span class="truncate">{{ $template->name }}</span>
                    </button>
                @endforeach
            @endif

            <div class="mt-1 border-t border-hairline pt-1">
                <button type="button" role="menuitem" wire:click="compose('new')" x-on:click="open = false"
                    class="flex w-full items-center gap-2.5 px-3.5 py-2.5 text-left text-sm font-medium text-accent transition hover:bg-list-hover">
                    <x-heroicon-o-pencil-square class="h-4 w-4" /> New message…
                </button>
                @if ($canManageTemplates)
                    <a href="{{ route('tenant.settings.organisation', ['tab' => 'templates']) }}" wire:navigate role="menuitem"
                        class="flex w-full items-center gap-2.5 px-3.5 py-2.5 text-left text-xs text-ink-muted transition hover:bg-list-hover hover:text-ink">
                        <x-heroicon-o-cog-6-tooth class="h-4 w-4" /> Manage templates
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
