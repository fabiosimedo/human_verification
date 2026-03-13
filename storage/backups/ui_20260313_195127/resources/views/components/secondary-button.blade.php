<button {{ $attributes->merge(['type' => 'button', 'class' => 'hum-btn-secondary']) }}>
    {{ $slot }}
</button>