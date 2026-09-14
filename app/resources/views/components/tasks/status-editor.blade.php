{{-- The ordered list of statuses for one owner (a category or a sub-category)
     with reorder, edit, remove, and add. Used inside Settings\TaskCategories,
     whose methods it calls. --}}
@props(['statuses', 'owner', 'ownerId', 'compact' => false])

<div class="flex flex-wrap items-center gap-1.5">
    @foreach ($statuses as $index => $status)
        <div class="group inline-flex items-center rounded-full border border-hairline bg-surface p-0.5 shadow-sm" wire:key="status-{{ $status->id }}">
            <span class="inline-flex items-center gap-1 rounded-full {{ $compact ? 'px-2 py-0.5 text-xs' : 'px-2.5 py-1 text-sm' }} font-medium" style="{{ $status->style() }}">
                @if ($status->completes)<x-heroicon-s-check class="h-3.5 w-3.5" />@endif
                {{ $status->name }}
            </span>
            <span class="flex items-center pl-0.5 pr-1 text-ink-muted">
                @if ($index > 0)
                    <button type="button" wire:click="moveStatus({{ $status->id }}, -1)" class="rounded p-1 hover:bg-sunken hover:text-ink" title="Move earlier">
                        <x-heroicon-o-chevron-left class="h-3.5 w-3.5" />
                    </button>
                @endif
                @if (! $loop->last)
                    <button type="button" wire:click="moveStatus({{ $status->id }}, 1)" class="rounded p-1 hover:bg-sunken hover:text-ink" title="Move later">
                        <x-heroicon-o-chevron-right class="h-3.5 w-3.5" />
                    </button>
                @endif
                <button type="button" wire:click="startEditStatus({{ $status->id }})" class="rounded p-1 hover:bg-sunken hover:text-ink" title="Edit">
                    <x-heroicon-o-pencil-square class="h-3.5 w-3.5" />
                </button>
                <button type="button" wire:click="deleteStatus({{ $status->id }})" class="rounded p-1 hover:bg-critical-soft hover:text-critical" title="Remove"
                    data-confirm-title="Remove this status?" data-confirm-action="Remove"
                    data-confirm="Remove “{{ $status->name }}”? Only possible while nothing is currently in it.">
                    <x-heroicon-o-trash class="h-3.5 w-3.5" />
                </button>
            </span>
        </div>
    @endforeach

    <button type="button" wire:click="startCreateStatus('{{ $owner }}', {{ $ownerId }})"
        class="inline-flex items-center gap-1 rounded-full border border-dashed border-hairline-strong {{ $compact ? 'px-2 py-1 text-xs' : 'px-2.5 py-1.5 text-sm' }} font-medium text-ink-soft transition hover:border-accent hover:text-accent">
        <x-heroicon-o-plus class="h-3.5 w-3.5" /> Add status
    </button>

    @if ($statuses->isEmpty())
        <span class="text-xs text-ink-muted">No statuses — add at least one so tasks have somewhere to start.</span>
    @endif
</div>
