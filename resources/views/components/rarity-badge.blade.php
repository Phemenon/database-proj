@props(['rarity'])
@php
    $styles = [
        'common'    => 'bg-stone-100 text-stone-600',
        'uncommon'  => 'bg-green-100 text-green-700',
        'rare'      => 'bg-blue-100 text-blue-700',
        'epic'      => 'bg-purple-100 text-purple-700',
        'legendary' => 'bg-amber-100 text-amber-700',
    ];
@endphp
<span {{ $attributes->class(['inline-block rounded-full px-2 py-0.5 text-xs font-medium', $styles[$rarity] ?? $styles['common']]) }}>
    {{ ucfirst($rarity) }}
</span>