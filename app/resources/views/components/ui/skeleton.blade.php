@props(['rows' => 5])

{{-- Row skeletons for table loading states (MEP 9.2). --}}
<div {{ $attributes->class('space-y-2 p-4') }} aria-hidden="true">
    @for ($i = 0; $i < $rows; $i++)
        <div class="flex items-center gap-3">
            <div class="h-8 w-8 shrink-0 animate-pulse rounded-full bg-sunken"></div>
            <div class="h-3 flex-1 animate-pulse rounded bg-sunken" style="animation-delay: {{ $i * 60 }}ms"></div>
            <div class="hidden h-3 w-24 animate-pulse rounded bg-sunken sm:block"></div>
            <div class="hidden h-3 w-16 animate-pulse rounded bg-sunken sm:block"></div>
        </div>
    @endfor
</div>
