@props(['variant' => 'primary'])
@php
    $baseClasses = 'w-full flex justify-center py-2.5 px-4 rounded-xl shadow-sm text-[13px] font-extrabold focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all duration-300 transform hover:-translate-y-[1px] active:translate-y-0 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none items-center gap-2';
    $variants = [
        'primary' => 'text-white bg-gradient-to-r from-green-600 to-emerald-500 hover:from-green-500 hover:to-emerald-400 focus:ring-green-500 border border-transparent shadow-green-500/20 hover:shadow-green-500/40',
        'outline' => 'text-gray-700 bg-white hover:bg-gray-50 focus:ring-gray-500 border border-gray-200 hover:border-gray-300 shadow-sm',
    ];
    $classes = $baseClasses . ' ' . $variants[$variant];
@endphp
<button {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</button>
