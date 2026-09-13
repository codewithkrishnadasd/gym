@props(['head' => null])

{{-- Wide data tables scroll horizontally rather than forcing the page body
     to scroll sideways; list pages pair this with a card layout under lg. --}}
<div {{ $attributes->class('overflow-x-auto') }}>
    <table class="w-full min-w-full border-collapse text-left text-sm">
        @if ($head)
            <thead class="border-b border-hairline bg-raised">
                <tr>{{ $head }}</tr>
            </thead>
        @endif

        <tbody class="divide-y divide-[var(--c-hairline)]">
            {{ $slot }}
        </tbody>
    </table>
</div>
