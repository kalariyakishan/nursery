@props(['label', 'value' => null, 'icon' => null, 'valueClass' => 'text-gray-900', 'border' => true])
<div class="flex justify-between items-center {{ $border ? 'border-b border-gray-100/50 pb-2.5 mb-2.5' : '' }} gap-3">
    <div class="flex items-center gap-2 text-gray-500 font-medium text-[13px] min-w-0 flex-shrink-0">
        @if($icon) {!! $icon !!} @endif
        <span class="truncate">{{ $label }}</span>
    </div>
    <div class="font-extrabold text-[13px] {{ $valueClass }} text-right break-words leading-tight" {{ $attributes->whereStartsWith('x-') }}>
        {{ $value }}
    </div>
</div>
