@props(['disabled' => false])

<input
    @disabled($disabled)
    {{ $attributes->merge([
        'class' => 'rounded-2xl border border-white/10 bg-white/10 px-4 py-3 text-white placeholder:text-white/35 shadow-sm focus:border-emerald-300/60 focus:ring focus:ring-emerald-300/20'
    ]) }}
>