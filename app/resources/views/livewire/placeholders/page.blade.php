{{-- The shape of a page while its content loads (App\Livewire\Concerns\LazyPage).
     Drawn in the page's own surfaces, pulsing gently; sized so that the real
     content, when it lands, takes roughly the same room and nothing jumps. --}}
<div class="page-skeleton animate-pulse" aria-busy="true" aria-live="polite" aria-label="Loading">
    {{-- Page header: a back link's worth of space, the title and a line under it. --}}
    <div class="mb-5 flex items-start justify-between gap-3">
        <div class="min-w-0 flex-1 space-y-2.5">
            <div class="h-6 w-48 max-w-[60%] rounded-md bg-sunken sm:h-7"></div>
            <div class="h-3.5 w-72 max-w-[80%] rounded bg-sunken/70"></div>
        </div>
        <div class="flex shrink-0 items-center gap-2">
            <div class="h-11 w-11 rounded-lg bg-sunken sm:h-10 sm:w-24"></div>
            <div class="hidden h-10 w-20 rounded-lg bg-sunken sm:block"></div>
        </div>
    </div>

    @if ($kind === 'dashboard')
        <div class="mb-4 flex gap-2 overflow-hidden">
            @foreach (range(1, 6) as $i)
                <div class="h-8 w-24 shrink-0 rounded-full bg-sunken"></div>
            @endforeach
        </div>
        <div class="mb-5 grid grid-cols-2 gap-3 lg:grid-cols-4">
            @foreach (range(1, 4) as $i)
                <div class="rounded-xl border border-hairline bg-surface p-4 max-sm:rounded-none max-sm:border-x-0">
                    <div class="mb-3 h-3 w-20 rounded bg-sunken/70"></div>
                    <div class="h-7 w-24 rounded-md bg-sunken"></div>
                </div>
            @endforeach
        </div>
        <div class="grid gap-5 lg:grid-cols-3">
            <div class="rounded-xl border border-hairline bg-surface p-5 lg:col-span-2 max-sm:rounded-none max-sm:border-x-0">
                <div class="mb-4 h-4 w-40 rounded bg-sunken"></div>
                <div class="flex h-48 items-end gap-2">
                    @foreach ([40, 65, 50, 80, 60, 90, 70, 55, 75, 45, 85, 60] as $h)
                        <div class="flex-1 rounded-t bg-sunken/70" style="height: {{ $h }}%"></div>
                    @endforeach
                </div>
            </div>
            <div class="rounded-xl border border-hairline bg-surface p-5 max-sm:rounded-none max-sm:border-x-0">
                <div class="mb-4 h-4 w-32 rounded bg-sunken"></div>
                @foreach (range(1, 4) as $i)
                    <div class="flex items-center gap-3 py-2.5">
                        <div class="h-8 w-8 rounded-full bg-sunken"></div>
                        <div class="flex-1 space-y-1.5"><div class="h-3 w-3/5 rounded bg-sunken"></div><div class="h-2.5 w-2/5 rounded bg-sunken/60"></div></div>
                    </div>
                @endforeach
            </div>
        </div>

    @elseif ($kind === 'list')
        <div class="rounded-xl border border-hairline bg-surface max-sm:rounded-none max-sm:border-x-0">
            <div class="flex items-center gap-2 border-b border-hairline px-3 py-3 sm:px-4">
                <div class="h-9 w-56 max-w-[60%] rounded-full bg-sunken"></div>
                <div class="h-9 w-24 rounded-full bg-sunken/70"></div>
            </div>
            <div class="divide-y divide-[var(--c-hairline)]">
                @foreach (range(1, 7) as $i)
                    <div class="flex items-center gap-3 px-4 py-3.5">
                        <div class="h-9 w-9 shrink-0 rounded-full bg-sunken"></div>
                        <div class="min-w-0 flex-1 space-y-2">
                            <div class="h-3.5 rounded bg-sunken" style="width: {{ [55, 40, 65, 45, 60, 35, 50][$i - 1] }}%"></div>
                            <div class="h-2.5 w-1/4 rounded bg-sunken/60"></div>
                        </div>
                        <div class="hidden h-6 w-16 rounded-full bg-sunken/70 sm:block"></div>
                        <div class="hidden h-3.5 w-20 rounded bg-sunken md:block"></div>
                    </div>
                @endforeach
            </div>
        </div>

    @elseif ($kind === 'form')
        <div class="grid gap-5 lg:grid-cols-3">
            <div class="space-y-5 lg:col-span-2">
                @foreach ([4, 2] as $fields)
                    <div class="rounded-xl border border-hairline bg-surface p-5 max-sm:rounded-none max-sm:border-x-0">
                        <div class="mb-5 h-4 w-36 rounded bg-sunken"></div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            @foreach (range(1, $fields) as $i)
                                <div class="space-y-1.5"><div class="h-3 w-24 rounded bg-sunken/70"></div><div class="h-10 rounded-lg bg-sunken max-lg:h-11"></div></div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="rounded-xl border border-hairline bg-surface p-5 max-sm:rounded-none max-sm:border-x-0">
                <div class="mb-4 h-4 w-16 rounded bg-sunken"></div>
                <div class="h-11 rounded-lg bg-sunken"></div>
            </div>
        </div>

    @else
        <div class="mb-4 grid grid-cols-2 gap-3 lg:grid-cols-4">
            @foreach (range(1, 4) as $i)
                <div class="rounded-xl border border-hairline bg-surface p-4 max-sm:rounded-none max-sm:border-x-0">
                    <div class="mb-3 h-3 w-16 rounded bg-sunken/70"></div>
                    <div class="h-6 w-20 rounded-md bg-sunken"></div>
                </div>
            @endforeach
        </div>
        <div class="mb-4 flex gap-4 border-b border-hairline">
            @foreach ([16, 20, 14, 18] as $w)
                <div class="mb-3 h-4 rounded bg-sunken" style="width: {{ $w * 4 }}px"></div>
            @endforeach
        </div>
        <div class="grid gap-5 lg:grid-cols-3">
            <div class="space-y-5 lg:col-span-2">
                <div class="rounded-xl border border-hairline bg-surface p-5 max-sm:rounded-none max-sm:border-x-0">
                    <div class="mb-4 h-4 w-40 rounded bg-sunken"></div>
                    @foreach (range(1, 4) as $i)
                        <div class="flex items-center justify-between gap-3 py-3">
                            <div class="h-3.5 w-1/3 rounded bg-sunken"></div>
                            <div class="h-3.5 w-16 rounded bg-sunken/70"></div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="rounded-xl border border-hairline bg-surface p-5 max-sm:rounded-none max-sm:border-x-0">
                <div class="mb-4 h-4 w-28 rounded bg-sunken"></div>
                @foreach (range(1, 3) as $i)
                    <div class="mb-3 space-y-1.5"><div class="h-2.5 w-20 rounded bg-sunken/60"></div><div class="h-3.5 w-3/4 rounded bg-sunken"></div></div>
                @endforeach
            </div>
        </div>
    @endif
</div>
