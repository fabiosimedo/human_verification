@props(['value'])

<label {{ $attributes->merge(['class' => 'hum-label']) }}>
    {{ $value ?? $slot }}
</label>