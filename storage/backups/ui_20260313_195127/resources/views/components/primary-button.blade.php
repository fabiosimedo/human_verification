<button {{ $attributes->merge(['type' => 'submit', 'class' => 'hum-btn-primary']) }}>
    {{ $slot }}
</button>