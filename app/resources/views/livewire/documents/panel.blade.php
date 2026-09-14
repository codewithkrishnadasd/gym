<div>
    <x-ui.card title="Documents"
        :description="$storageConfigured
            ? 'Scans, certificates, and signed paperwork. Stored in your organisation\'s own bucket.'
            : null">

        @if (! $storageConfigured)
            {{-- Documents need the organisation's own storage credentials, so an
                 upload form here could only ever fail. --}}
            <x-ui.alert tone="info" title="Storage is not set up yet">
                Documents are kept in your organisation's own storage bucket. An administrator can add one under
                @can('manage', \App\Models\StorageBucket::class)
                    <a href="{{ route('tenant.settings.organisation', ['tab' => 'storage']) }}"
                        class="font-medium text-accent underline underline-offset-2" wire:navigate>Settings → Storage</a>.
                @else
                    Settings → Storage.
                @endcan
            </x-ui.alert>
        @else
            @can('create', \App\Models\Document::class)
                <form wire:submit="upload" class="mb-4 rounded-xl border border-hairline bg-raised p-3">
                    <div class="grid gap-3 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label for="doc-file-{{ $subjectId }}"
                                class="flex cursor-pointer items-center justify-center gap-2 rounded-lg border border-dashed border-hairline-strong bg-surface px-4 py-4 text-center transition hover:border-accent">
                                <x-heroicon-o-paper-clip class="h-4 w-4 shrink-0 text-ink-muted" />
                                <span class="text-sm text-ink">
                                    {{ $file?->getClientOriginalName() ?? 'Choose a file' }}
                                </span>
                                <span class="text-xs text-ink-muted">up to 20&nbsp;MB</span>
                                <input id="doc-file-{{ $subjectId }}" type="file" class="sr-only" wire:model="file">
                            </label>

                            <div wire:loading wire:target="file" class="mt-2 inline-flex items-center gap-1.5 text-sm text-ink-soft">
                                <x-ui.spinner /> Reading file…
                            </div>

                            @error('file')<p class="mt-1.5 text-sm text-critical">{{ $message }}</p>@enderror
                        </div>

                        <x-ui.input wire:model="title" name="title" label="Title" required
                            placeholder="e.g. Aadhaar card" />

                        <x-ui.field label="Type" name="category" for="doc-category-{{ $subjectId }}"
                            hint="Optional. Pick one or type your own.">
                            <input list="doc-categories-{{ $subjectId }}" id="doc-category-{{ $subjectId }}"
                                wire:model="category" placeholder="e.g. Identity proof"
                                class="min-h-[40px] w-full rounded-lg border border-hairline-strong bg-surface px-3 py-2 text-sm text-ink placeholder:text-ink-muted focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/25 max-lg:min-h-[44px]">
                            <datalist id="doc-categories-{{ $subjectId }}">
                                @foreach ($categories as $option)
                                    <option value="{{ $option }}"></option>
                                @endforeach
                            </datalist>
                        </x-ui.field>

                        @if ($buckets->count() > 1)
                            <x-ui.select class="sm:col-span-2" wire:model="bucketId" name="bucketId" label="Store in"
                                required hint="Your organisation has more than one bucket configured.">
                                @foreach ($buckets as $option)
                                    <option value="{{ $option->id }}">
                                        {{ $option->name }}{{ $option->is_default ? ' (default)' : '' }}
                                    </option>
                                @endforeach
                            </x-ui.select>
                        @endif
                    </div>

                    @if ($uploadError)
                        <div class="mt-3"><x-ui.alert tone="critical" title="Could not store the file">{{ $uploadError }}</x-ui.alert></div>
                    @endif

                    <div class="mt-3 flex items-center gap-2">
                        <x-ui.button type="submit" variant="primary" size="sm" icon="arrow-up-tray"
                            wire:loading.attr="disabled" wire:target="upload,file">
                            <span wire:loading.remove wire:target="upload">Upload</span>
                            <span wire:loading wire:target="upload" class="inline-flex items-center gap-1.5">
                                <x-ui.spinner /> Uploading…
                            </span>
                        </x-ui.button>

                        @if ($buckets->count() === 1)
                            <span class="text-xs text-ink-muted">Stored in {{ $buckets->first()->name }}</span>
                        @endif
                    </div>
                </form>
            @endcan

            @if ($documents->isEmpty())
                <p class="py-6 text-center text-sm text-ink-muted">No documents yet.</p>
            @else
                <ul class="divide-y divide-[var(--c-hairline)]">
                    @foreach ($documents as $document)
                        <li class="flex items-center justify-between gap-3 py-2.5">
                            <div class="flex min-w-0 items-center gap-2.5">
                                <span class="grid h-8 w-8 shrink-0 place-items-center rounded-lg bg-sunken text-ink-soft">
                                    <x-dynamic-component
                                        :component="'heroicon-o-'.match (true) {
                                            $document->isImage() => 'photo',
                                            $document->isPdf() => 'document-text',
                                            default => 'document',
                                        }" class="h-4 w-4" />
                                </span>

                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-1.5">
                                        <span class="truncate text-sm font-medium text-ink">{{ $document->title }}</span>
                                        @if ($document->category)
                                            <x-ui.badge tone="neutral" :dot="false">{{ $document->category }}</x-ui.badge>
                                        @endif
                                        @if ($document->bucket && ! $document->bucket->isActive())
                                            <x-ui.badge tone="caution">Bucket removed</x-ui.badge>
                                        @endif
                                    </div>
                                    <p class="truncate text-xs text-ink-muted">
                                        {{ $document->humanSize() }}
                                        &middot; {{ $document->created_at?->diffForHumans() }}
                                        @if ($document->uploadedBy?->user)
                                            by {{ $document->uploadedBy->user->name }}
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="flex shrink-0 items-center gap-1">
                                <x-ui.download-button size="sm" variant="ghost" icon="arrow-down-tray" :what="'“'.$document->title.'”'"
                                    :href="route('tenant.documents.download', $document)">Download</x-ui.download-button>

                                @can('delete', $document)
                                    <x-ui.button size="sm" variant="ghost" icon="trash"
                                        wire:click="remove({{ $document->id }})"
                                        data-confirm-title="Delete this document?" data-confirm-action="Delete" data-confirm-tone="danger" data-confirm="Remove “{{ $document->title }}”? The file is deleted from your bucket and cannot be recovered.">Remove</x-ui.button>
                                @endcan
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        @endif
    </x-ui.card>
</div>
