@php
    $types = [
        'success' => 'border-green-300 bg-green-50 text-green-800',
        'error'   => 'border-red-300 bg-red-50 text-red-800',
        'warning' => 'border-amber-300 bg-amber-50 text-amber-800',
    ];
@endphp

@foreach ($types as $type => $classes)
    @if (session($type))
        <div role="alert" class="mb-4 flex items-center justify-between rounded-lg border px-4 py-3 text-sm {{ $classes }}">
            <span>{{ session($type) }}</span>
            <button type="button" onclick="this.parentElement.remove()" aria-label="close">✕</button>
        </div>
    @endif
@endforeach