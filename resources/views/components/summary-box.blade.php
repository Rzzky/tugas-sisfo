@props(['title', 'value', 'diff', 'icon'])

<div class="bg-[#1f1f2c] p-4 rounded-xl shadow text-white">
    <div class="flex items-center justify-between mb-2">
        <span class="text-sm text-gray-400">{{ $title }}</span>
        <i data-feather="{{ $icon }}"></i>
    </div>
    <h3 class="text-2xl font-bold">{{ $value }}</h3>
    <p class="text-green-400 text-sm">{{ $diff }}</p>
</div>
