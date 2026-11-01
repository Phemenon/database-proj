@props([
    'color' => 'primary',
    'size'  => 'md',
    'type'  => 'button',
    'href'  => null,
])

@php
    $base = 'inline-flex items-center justify-center gap-2 rounded-lg border font-semibold transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/40 disabled:pointer-events-none disabled:opacity-50';

    $palette = match ($color) {
        'secondary' => 'bg-white text-ink border-line hover:bg-cream',
        'danger'    => 'bg-red-600 text-white border-red-600 hover:bg-red-700',
        'success'   => 'bg-green-600 text-white border-green-600 hover:bg-green-700',
        'ghost'     => 'bg-transparent text-ink border-transparent hover:bg-cream',
        default     => 'bg-primary text-cream border-primary hover:bg-primary-hover',
    };

    $padding = match ($size) {
        'sm'    => 'px-3 py-1.5 text-xs',
        'lg'    => 'px-6 py-3 text-base',
        default => 'px-4 py-2 text-sm',
    };

    $classes = "$base $palette $padding";
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->class($classes) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->class($classes) }}>
        {{ $slot }}
    </button>
@endif