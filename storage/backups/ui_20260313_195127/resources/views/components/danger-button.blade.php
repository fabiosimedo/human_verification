<button {{ $attributes->merge(['type' => 'submit', 'class' => 'hum-btn-danger']) }}>
    {{ $slot }}
</button>