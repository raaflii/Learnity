@props([
    'title',
    'value',
    'icon',
    'color' => 'blue',
    'href' => null
])

@php
    $colorClasses = [
        'blue' => 'bg-blue-500',
        'green' => 'bg-green-500',
        'yellow' => 'bg-yellow-500',
        'purple' => 'bg-purple-500',
        'red' => 'bg-red-500',
        'orange' => 'bg-orange-500',
        'indigo' => 'bg-indigo-500',
        'pink' => 'bg-pink-500',
        'gray' => 'bg-gray-500',
    ];
    
    $bgColor = $colorClasses[$color] ?? $colorClasses['blue'];
    
    $cardClasses = $href ? 'hover:shadow-lg transition-shadow cursor-pointer' : '';
@endphp

<div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg {{ $cardClasses }}"
     @if($href) onclick="window.location.href='{{ $href }}'" @endif>
    <div class="p-5">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 {{ $bgColor }} rounded-md flex items-center justify-center">
                    <x-dynamic-component :component="'heroicon-o-' . $icon" class="w-5 h-5 text-white" />
                </div>
            </div>
            <div class="ml-5 w-0 flex-1">
                <dl>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">{{ $title }}</dt>
                    <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $value }}</dd>
                </dl>
            </div>
        </div>
    </div>
</div>
