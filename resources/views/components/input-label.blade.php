@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-sm font-medium text-white/85']) }}>
    {{ $value ?? $slot }}
</label>