@props([
    'courseId',
    'title',
    'description',
    'instructor',
    'level',
    'progress' => 0,
    'lastAccessed' => null,
    'status' => 'not-started',
    'totalLessons' => 0,
    'completedLessons' => 0,
    'gradient' => 'from-blue-500 to-purple-600',
    'thumbnail' => null,
])

<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">
    <!-- Course Header with Gradient -->
    <div class="h-64 relative overflow-hidden">
        @if ($thumbnail)
            <img src="{{ Str::startsWith($thumbnail, 'http') ? $thumbnail : asset('storage/' . $thumbnail) }}"
                alt="{{ $title }}" class="object-cover w-full h-full">
        @else
            <div class="h-full w-full bg-gradient-to-r {{ $gradient }}"></div>
            <!-- Light overlay untuk gradient -->
            <div class="absolute inset-0 bg-black bg-opacity-20"></div>
        @endif

        <!-- Badge Status -->
        <div class="absolute top-4 right-4">
            @if ($status === 'completed')
                <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 shadow-sm">
                    <x-heroicon-s-check-circle class="w-4 h-4 mr-1" />
                    Completed
                </span>
            @elseif($status === 'in-progress')
                <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300 shadow-sm">
                    <x-heroicon-s-play-circle class="w-4 h-4 mr-1" />
                    In Progress
                </span>
            @else
                <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 shadow-sm">
                    <x-heroicon-s-clock class="w-4 h-4 mr-1" />
                    Not Started
                </span>
            @endif
        </div>

        <!-- Progress bar dengan warna yang kontras -->
        <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-30 backdrop-blur-sm">
            <div class="px-4 py-2">
                <div class="flex items-center justify-between text-white text-xs mb-1">
                    <span class="font-medium">Progress</span>
                    <span class="font-semibold">{{ $progress }}%</span>
                </div>
                <div class="w-full bg-white bg-opacity-20 rounded-full h-2.5 shadow-inner">
                    <div class="bg-blue-500 rounded-full h-2.5 transition-all duration-500 shadow-sm"
                        style="width: {{ $progress }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Course Content -->
    <div class="p-5">
        <!-- Course Title - Clickable -->
        <a href="{{ route('courses.show', $courseId) }}" class="block mb-3 group">
            <h3
                class="text-lg font-semibold text-gray-900 dark:text-white leading-tight group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-200">
                {{ $title }}</h3>
        </a>

        <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 line-clamp-2">{{ $description }}</p>

        <!-- Course Info -->
        <div class="space-y-2 mb-4">
            <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                <x-heroicon-o-user class="w-4 h-4 mr-2 text-gray-400 dark:text-gray-500" />
                <span>{{ $instructor }}</span>
            </div>

            <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
                <div class="flex items-center">
                    <x-heroicon-o-signal class="w-4 h-4 mr-2 text-gray-400 dark:text-gray-500" />
                    <span>{{ ucfirst($level) }}</span>
                </div>

                <div class="flex items-center">
                    <x-heroicon-o-play class="w-4 h-4 mr-1 text-gray-400 dark:text-gray-500" />
                    <span>{{ $completedLessons }}/{{ $totalLessons }} lessons</span>
                </div>
            </div>

            @if ($lastAccessed)
                <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                    <x-heroicon-o-clock class="w-4 h-4 mr-2 text-gray-400 dark:text-gray-500" />
                    <span>Last accessed {{ $lastAccessed }}</span>
                </div>
            @endif
        </div>

        <!-- Action Buttons -->
        <div class="flex space-x-2">
            @if ($status === 'completed')
                <a href="{{ route('courses.show', $courseId) }}"
                    class="flex-1 bg-green-600 hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600 text-white text-sm font-medium py-2 px-4 rounded-md transition-colors duration-200 text-center shadow-sm">
                    <div class="flex items-center justify-center">
                        <x-heroicon-s-eye class="w-4 h-4 mr-2" />
                        Review Course
                    </div>
                </a>
            @elseif($status === 'in-progress')
                @if ($completedLessons > 0)
                    <a href="{{ route('courses.lessons.show', [$courseId, $course['next_lesson_id'] ?? 1]) }}"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white text-sm font-medium py-2 px-4 rounded-md transition-colors duration-200 text-center shadow-sm">
                        Continue Learning
                    </a>
                @else
                    <a href="{{ route('courses.show', $courseId) }}"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white text-sm font-medium py-2 px-4 rounded-md transition-colors duration-200 text-center shadow-sm">
                        Start Learning
                    </a>
                @endif
                <a href="{{ route('courses.show', $courseId) }}"
                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-md transition-colors duration-200 shadow-sm">
                    <x-heroicon-o-information-circle class="w-4 h-4" />
                </a>
            @else
                <a href="{{ route('courses.show', $courseId) }}"
                    class="flex-1 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white text-sm font-medium py-2 px-4 rounded-md transition-colors duration-200 text-center shadow-sm">
                    Start Course
                </a>
            @endif
        </div>
    </div>
</div>
