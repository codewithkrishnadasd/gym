<div>
    @if ($testResult)
        <div class="mb-4">
            <x-ui.alert :tone="$testPassed ? 'positive' : 'critical'"
                :title="$testPassed ? 'Connection works' : 'Connection failed'">
                {{ $testResult }}
            </x-ui.alert>
        </div>
    @endif

    <div class="grid gap-5 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <x-ui.card title="Storage buckets"
                description="Member and staff documents are stored in your own object storage, using credentials you control and can revoke.">

                <x-slot:actions>
                    <x-ui.button size="sm" variant="primary" icon="plus" wire:click="startCreate">Add bucket</x-ui.button>
                </x-slot:actions>

                @if ($buckets->isEmpty())
                    <x-ui.empty icon="circle-stack" title="No storage configured"
                        description="Documents stay switched off until a bucket is added. Nothing is uploaded anywhere until then." />
                @else
                    <ul class="divide-y divide-[var(--c-hairline)]">
                        @foreach ($buckets as $bucket)
                            <li class="flex flex-col gap-3 py-3 lg:flex-row lg:items-center lg:justify-between">
                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="font-medium text-ink">{{ $bucket->name }}</span>
                                        @if ($bucket->is_default)
                                            <x-ui.badge tone="accent" :dot="false">Default</x-ui.badge>
                                        @endif
                                        @if (! $bucket->isActive())
                                            <x-ui.badge :tone="$bucket->status->tone()">{{ $bucket->status->label() }}</x-ui.badge>
                                        @endif
                                        @if ($bucket->last_verified_at)
                                            <x-ui.badge tone="positive">Verified</x-ui.badge>
                                        @elseif ($bucket->last_error)
                                            <x-ui.badge tone="critical">Last test failed</x-ui.badge>
                                        @endif
                                    </div>

                                    <p class="mt-0.5 truncate text-xs text-ink-muted">
                                        {{ $bucket->describeTarget() }}
                                        @if ($bucket->path_prefix)
                                            &middot; folder {{ $bucket->path_prefix }}
                                        @endif
                                        &middot; key {{ $bucket->maskedAccessKey() }}
                                        &middot; {{ $bucket->documents_count }} {{ Str::plural('document', $bucket->documents_count) }}
                                    </p>

                                    @if ($bucket->last_error)
                                        <p class="mt-1 max-w-xl text-xs text-critical">{{ $bucket->last_error }}</p>
                                    @endif
                                </div>

                                <div class="flex min-w-0 flex-wrap items-center gap-1">
                                    <x-ui.button size="sm" variant="ghost" icon="signal"
                                        wire:click="test({{ $bucket->id }})"
                                        wire:loading.attr="disabled" wire:target="test({{ $bucket->id }})">
                                        <span wire:loading.remove wire:target="test({{ $bucket->id }})">Test connection</span>
                                        <span wire:loading wire:target="test({{ $bucket->id }})" class="inline-flex items-center gap-1.5">
                                            <x-ui.spinner /> Testing…
                                        </span>
                                    </x-ui.button>

                                    @if ($bucket->isActive() && ! $bucket->is_default)
                                        <x-ui.button size="sm" variant="ghost" icon="star"
                                            wire:click="makeDefaultBucket({{ $bucket->id }})">Make default</x-ui.button>
                                    @endif

                                    <x-ui.button size="sm" variant="ghost" icon="pencil-square"
                                        wire:click="startEdit({{ $bucket->id }})">Edit</x-ui.button>

                                    @if ($bucket->isActive())
                                        <x-ui.button size="sm" variant="ghost" icon="trash"
                                            wire:click="remove({{ $bucket->id }})"
                                            data-confirm-title="Remove this bucket?" data-confirm-action="Remove bucket" data-confirm-tone="danger" data-confirm="Remove “{{ $bucket->name }}”? New uploads stop going here. The {{ $bucket->documents_count }} document(s) already stored still open.">Remove</x-ui.button>
                                    @else
                                        <x-ui.button size="sm" variant="ghost" icon="arrow-uturn-left"
                                            wire:click="restore({{ $bucket->id }})">Restore</x-ui.button>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </x-ui.card>
        </div>

        <div class="space-y-5">
            <x-ui.card title="How this works">
                <ul class="space-y-2.5 text-sm text-ink-soft">
                    <li class="flex gap-2">
                        <x-heroicon-o-lock-closed class="mt-0.5 h-4 w-4 shrink-0 text-ink-muted" />
                        Keys are encrypted before they are stored and are never shown again after you save them.
                    </li>
                    <li class="flex gap-2">
                        <x-heroicon-o-folder class="mt-0.5 h-4 w-4 shrink-0 text-ink-muted" />
                        Each document records the bucket it went into, so adding or changing the default never
                        orphans an earlier upload.
                    </li>
                    <li class="flex gap-2">
                        <x-heroicon-o-eye-slash class="mt-0.5 h-4 w-4 shrink-0 text-ink-muted" />
                        Files are private. Downloads are streamed through this application so permissions are
                        checked every time, rather than handed out as public links.
                    </li>
                </ul>
            </x-ui.card>

            <x-ui.card title="Firebase / Google Cloud">
                <p class="text-sm text-ink-soft">
                    Firebase Storage buckets work through Google's S3-compatible API. Create an
                    <span class="font-medium text-ink">HMAC key</span> in the Google Cloud console, then use:
                </p>
                <dl class="mt-2 space-y-1 text-xs">
                    <div class="flex gap-2"><dt class="w-20 shrink-0 text-ink-muted">Endpoint</dt>
                        <dd class="font-mono text-ink">https://storage.googleapis.com</dd></div>
                    <div class="flex gap-2"><dt class="w-20 shrink-0 text-ink-muted">Bucket</dt>
                        <dd class="font-mono text-ink">your-app.appspot.com</dd></div>
                    <div class="flex gap-2"><dt class="w-20 shrink-0 text-ink-muted">Region</dt>
                        <dd class="font-mono text-ink">auto</dd></div>
                    <div class="flex gap-2"><dt class="w-20 shrink-0 text-ink-muted">Path style</dt>
                        <dd class="font-mono text-ink">on</dd></div>
                </dl>
            </x-ui.card>
        </div>
    </div>

    <x-ui.modal name="storage-bucket" max-width="xl" :title="$editingId ? 'Edit bucket' : 'Add a storage bucket'"
        description="S3-compatible storage: Firebase, Google Cloud, Cloudflare R2, Amazon S3, MinIO.">

        <div class="grid gap-4 sm:grid-cols-2">
            <x-ui.input class="sm:col-span-2" wire:model="name" name="name" label="Name" required
                placeholder="e.g. Member documents" hint="Shown when choosing where to upload." />

            <x-ui.input wire:model="bucket" name="bucket" label="Bucket" required placeholder="my-gym-documents" />

            <x-ui.input wire:model="region" name="region" label="Region" placeholder="auto"
                hint="Leave blank unless your provider requires one." />

            <x-ui.input class="sm:col-span-2" wire:model="endpoint" name="endpoint" label="Endpoint"
                placeholder="https://storage.googleapis.com"
                hint="Leave blank for Amazon S3 itself." />

            <x-ui.input wire:model="accessKey" name="accessKey"
                :label="$editingId ? 'Access key (leave blank to keep)' : 'Access key'"
                :required="! $editingId" autocomplete="off" />

            <x-ui.input wire:model="secretKey" name="secretKey" type="password"
                :label="$editingId ? 'Secret key (leave blank to keep)' : 'Secret key'"
                :required="! $editingId" autocomplete="new-password" />

            <x-ui.input class="sm:col-span-2" wire:model="pathPrefix" name="pathPrefix" label="Folder prefix"
                placeholder="gym-documents"
                hint="Optional. Confines uploads to one folder inside the bucket." />

            <div class="sm:col-span-2 space-y-1">
                <x-ui.checkbox wire:model="usePathStyle" label="Use path-style addressing"
                    description="Required by Google Cloud Storage and MinIO. Not used by Amazon S3." />

                <x-ui.checkbox wire:model="makeDefault" label="Make this the default bucket"
                    description="New uploads go here unless another is chosen." />
            </div>
        </div>

        <x-slot:footer>
            <x-ui.button variant="ghost" x-on:click="$dispatch('close-modal', 'storage-bucket')">Cancel</x-ui.button>
            <x-ui.button variant="primary" wire:click="save" wire:loading.attr="disabled" wire:target="save">
                <span wire:loading.remove wire:target="save">{{ $editingId ? 'Save bucket' : 'Add bucket' }}</span>
                <span wire:loading wire:target="save" class="inline-flex items-center gap-1.5"><x-ui.spinner /> Saving…</span>
            </x-ui.button>
        </x-slot:footer>
    </x-ui.modal>
</div>
