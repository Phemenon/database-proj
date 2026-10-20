@props(['name' => 'quantity', 'value' => 1, 'max' => null])
<div class="inline-flex items-center rounded-lg border border-line bg-white">
    <button type="button" onclick="this.nextElementSibling.stepDown()"
            class="px-3 py-2 hover:bg-cream" aria-label="ลดจำนวน">−</button>
    <input type="number" name="{{ $name }}" value="{{ $value }}" min="1"
           @if ($max) max="{{ $max }}" @endif
           class="w-12 border-x border-line py-2 text-center focus:outline-none [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none">
    <button type="button" onclick="this.previousElementSibling.stepUp()"
            class="px-3 py-2 hover:bg-cream" aria-label="เพิ่มจำนวน">+</button>
</div>