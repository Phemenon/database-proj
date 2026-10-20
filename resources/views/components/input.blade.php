@props(['name', 'label', 'type' => 'text'])
<div>
    <label for="{{ $name }}" class="mb-1 block text-sm font-medium">{{ $label }}</label>
    <input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}"
           value="{{ $type === 'password' ? '' : old($name) }}"
           {{ $attributes->class([
               'w-full rounded-lg border px-3 py-2 text-sm focus:outline-none',
               'border-line focus:border-primary' => ! $errors->has($name),
               'border-red-400 bg-red-50' => $errors->has($name),
           ]) }}>
    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>