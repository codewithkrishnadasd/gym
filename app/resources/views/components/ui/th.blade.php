@props(['align' => 'left'])

<th scope="col" {{ $attributes->class([
    'whitespace-nowrap px-4 py-2.5 text-[11px] font-semibold uppercase tracking-[0.06em] text-ink-muted',
    'text-right' => $align === 'right',
    'text-center' => $align === 'center',
]) }}>{{ $slot }}</th>
