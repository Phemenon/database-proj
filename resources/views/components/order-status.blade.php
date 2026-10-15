@props(['status'])
@php
    $styles = [
        'pending'   => 'bg-amber-100 text-amber-700',
        'paid'      => 'bg-green-100 text-green-700',
        'shipped'   => 'bg-blue-100 text-blue-700',
        'completed' => 'bg-emerald-100 text-emerald-700',
    ];
@endphp
<span {{ $attributes->class(['inline-block rounded-full px-2.5 py-0.5 text-xs font-medium', $styles[strtolower($status)] ?? 'bg-stone-100 text-stone-600']) }}>
    {{ ucfirst($status) }}
</span>