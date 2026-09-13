@props(['align' => 'left', 'numeric' => false])

<td {{ $attributes->class([
    'px-4 py-2.5 align-middle text-ink-soft',
    'text-right' => $align === 'right',
    'text-center' => $align === 'center',
    'numeric' => $numeric,
]) }}>{{ $slot }}</td>
