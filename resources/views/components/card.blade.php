<div {{ $attributes->merge(['class' => 'bg-white rounded-2xl shadow-sm border border-gray-100 p-5 transition-all duration-300 hover:shadow-md relative']) }}>
    @if(isset($title))
        <h3 class="text-base font-extrabold text-gray-800 mb-4 pb-3 border-b border-gray-100 flex items-center gap-2">
            {{ $title }}
        </h3>
    @endif
    {{ $slot }}
</div>
