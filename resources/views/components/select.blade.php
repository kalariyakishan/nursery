@props(['label', 'icon' => null])
<div class="mb-4 relative">
    @if($label)
        <label class="block text-[13px] font-bold text-gray-700 mb-1.5">{{ $label }}</label>
    @endif
    <div class="relative group">
        @if($icon)
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 group-focus-within:text-green-500 transition-colors">
                {!! $icon !!}
            </div>
        @endif
        <select {{ $attributes->merge(['class' => 'block w-full rounded-xl border-gray-200 shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all duration-200 bg-gray-50/50 hover:bg-white text-sm py-2.5 ' . ($icon ? 'pl-9' : '')]) }}>
            {{ $slot }}
        </select>
    </div>
</div>
