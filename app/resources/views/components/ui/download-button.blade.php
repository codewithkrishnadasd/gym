{{-- A button that fetches a file. Every download asks first through the
     styled confirm dialog, and carries `data-download` so the global loading
     bar knows the page itself is not going anywhere. --}}
@props([
    'what' => 'this file',
    'note' => null,
])

<x-ui.button {{ $attributes->merge([
    'data-download' => true,
    'data-confirm' => 'Download '.$what.'?'.($note ? ' '.$note : ''),
    'data-confirm-title' => 'Download file',
    'data-confirm-action' => 'Download',
    'data-confirm-tone' => 'accent',
]) }}>{{ $slot }}</x-ui.button>
