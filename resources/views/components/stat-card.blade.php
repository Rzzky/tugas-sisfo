@props([
    'title',
    'icon',
    'value',
    'change',
    'isPositive' => true
])

<div class="stats-card rounded-lg p-5 border border-gray-700/50 hover:shadow-card">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-gray-300 font-medium">{{ $title }}</h2>
        <div class="p-2 rounded-lg bg-blue-900/30 text-blue-400">
            <i class="fas fa-{{ $icon }}"></i>
        </div>
    </div>
    <div class="flex flex-col">
        <span class="text-white text-4xl font-bold mb-1">{{ $value }}</span>
        <span class="{{ $isPositive ? 'text-green-500' : 'text-red-500' }} text-sm">
            {{ $isPositive ? '+' : '-' }}{{ $change }} dari bulan lalu
        </span>
    </div>
</div>
