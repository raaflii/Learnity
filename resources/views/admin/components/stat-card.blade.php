@props(['label', 'value', 'icon', 'color', 'growth' => null, 'subtitle' => null])

@php
    $colors = [
        'indigo' => 'text-indigo-500 bg-indigo-100 dark:bg-indigo-900/30',
        'blue' => 'text-blue-500 bg-blue-100 dark:bg-blue-900/30',
        'green' => 'text-green-500 bg-green-100 dark:bg-green-900/30',
        'purple' => 'text-purple-500 bg-purple-100 dark:bg-purple-900/30',
        'yellow' => 'text-yellow-500 bg-yellow-100 dark:bg-yellow-900/30',
        'red' => 'text-red-500 bg-red-100 dark:bg-red-900/30',
        'teal' => 'text-teal-500 bg-teal-100 dark:bg-teal-900/30',
        'amber' => 'text-amber-500 bg-amber-100 dark:bg-amber-900/30',
        'emerald' => 'text-emerald-500 bg-emerald-100 dark:bg-emerald-900/30',
        'orange' => 'text-orange-500 bg-orange-100 dark:bg-orange-900/30',
    ];

    $gradients = [
        'indigo' => 'from-indigo-500 to-indigo-600',
        'blue' => 'from-blue-500 to-blue-600',
        'green' => 'from-green-500 to-green-600',
        'purple' => 'from-purple-500 to-purple-600',
        'yellow' => 'from-yellow-500 to-yellow-600',
        'red' => 'from-red-500 to-red-600',
        'teal' => 'from-teal-500 to-teal-600',
        'amber' => 'from-amber-500 to-amber-600',
        'emerald' => 'from-emerald-500 to-emerald-600',
        'orange' => 'from-orange-500 to-orange-600',
    ];
@endphp

<div
    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-all duration-200 group">
    <div class="flex items-center justify-between">
        <div class="flex-1">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $label }}</p>
            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $value }}</p>

            @if ($growth !== null)
                <div class="flex items-center mt-2">
                    @if ($growth > 0)
                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        <span class="text-sm text-green-600 dark:text-green-400 ml-1">+{{ $growth }}%</span>
                    @elseif($growth < 0)
                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                        </svg>
                        <span class="text-sm text-red-600 dark:text-red-400 ml-1">{{ $growth }}%</span>
                    @else
                        <span class="text-sm text-gray-500 dark:text-gray-400 ml-1">0%</span>
                    @endif
                    <span class="text-xs text-gray-500 dark:text-gray-400 ml-1">bulan ini</span>
                </div>
            @elseif($subtitle)
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">{{ $subtitle }}</p>
            @endif
        </div>

        <div
            class="w-14 h-14 rounded-xl bg-gradient-to-r {{ $gradients[$color] ?? $gradients['blue'] }} flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-200">
            <x-dynamic-component :component="'heroicon-o-' . $icon" class="h-7 w-7 text-white" />
        </div>
    </div>
</div>
