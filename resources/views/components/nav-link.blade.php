@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-block px-5 py-1.5 text-white hover:text-black hover:border-black hover:bg-white focus:bg-white rounded-xl font-semibold focus:text-black text-base transition duration-150 ease-in-out'
            : 'inline-block px-5 py-1.5 text-white hover:text-black hover:border-black hover:bg-white focus:bg-white rounded-xl font-semibold focus:text-black text-base transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
