@props(['label' => 'Password', 'name' => 'password'])
<div x-data="{ show: false }">
    <label class="block text-sm font-medium mb-1">{{ $label }}</label>
    <div class="relative">
        <input :type="show ? 'text' : 'password'" name="{{ $name }}"
               {{ $attributes->merge(['class' => 'w-full rounded-lg border border-gray-300 px-3 py-2 pr-16 focus:outline-none focus:ring-2 focus:ring-blue-500']) }}>
        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 px-3 text-sm text-gray-500"
                x-text="show ? 'Sembunyi' : 'Lihat'"></button>
    </div>
</div>