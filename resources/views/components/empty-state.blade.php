@props(['title', 'message' => null, 'actionLabel' => null, 'href' => null])
<div class="rounded-xl border border-dashed border-line bg-white px-6 py-16 text-center">
    <p class="font-medium">{{ $title }}</p>
    @if ($message)
        <p class="mt-1 text-sm text-muted">{{ $message }}</p>
    @endif
    @if ($actionLabel && $href)
        <a href="{{ $href }}" class="mt-6 inline-block rounded-full bg-primary px-6 py-2 text-sm text-white hover:bg-primary-hover">{{ $actionLabel }}</a>
    @endif
</div>