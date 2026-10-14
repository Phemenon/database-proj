@props(['rating' => 0, 'count' => null, 'showNumber' => true])
@php
    $rating = (float) $rating;
    $filled = (int) round($rating);
@endphp
<div class="flex items-center gap-1 text-sm">
    <span class="text-amber-500" aria-hidden="true">{{ str_repeat('★', $filled) }}<span class="text-stone-300">{{ str_repeat('★', 5 - $filled) }}</span></span>
    @if ($showNumber)
        <span class="text-muted">{{ number_format($rating, 1) }}@if (! is_null($count)) ({{ $count }})@endif</span>
    @endif
</div>