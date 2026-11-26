@props(['label', 'name', 'type' => 'text', 'value' => null])
<div>
    <label class="block text-sm font-medium mb-1">{{ $label }}</label>
    <input type="{{ $type }}" name="{{ $name }}" value="{{ old($name, $value) }}"
           {{ $attributes->merge(['class' => 'w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500']) }}>
</div>