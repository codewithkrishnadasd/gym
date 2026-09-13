{{-- Session flash surfaces after a redirect; Livewire in-place actions use
     their own inline states instead. --}}
@if (session('status'))
    <div class="mb-4" x-data="{ show: true }" x-show="show" x-transition>
        <x-ui.alert tone="positive">
            <div class="flex items-start justify-between gap-3">
                <span>{{ session('status') }}</span>
                <button type="button" @click="show = false" aria-label="Dismiss" class="shrink-0 opacity-60 hover:opacity-100">
                    <x-heroicon-o-x-mark class="h-4 w-4" />
                </button>
            </div>
        </x-ui.alert>
    </div>
@endif

@if (session('error'))
    <div class="mb-4">
        <x-ui.alert tone="critical">{{ session('error') }}</x-ui.alert>
    </div>
@endif
