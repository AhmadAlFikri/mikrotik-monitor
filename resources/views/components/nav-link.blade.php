@props(['href', 'active' => false])

@php
$classes = 'flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors duration-200';
if ($active) {
    $classes .= ' bg-indigo-600 text-white font-semibold shadow';
} else {
    $classes .= ' text-gray-500 hover:bg-indigo-100 hover:text-indigo-600';
}
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
