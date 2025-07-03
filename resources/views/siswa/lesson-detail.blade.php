<x-siswa.layout>
    <x-slot:title>{{ $lesson->title }} - {{ $course->title }}</x-slot:title>

    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <!-- Header -->
        <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('courses.show', $course->id) }}"
                            class="inline-flex items-center text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                            <x-heroicon-o-arrow-left class="h-4 w-4 mr-2" />
                            Back to Course
                        </a>
                        <div class="h-6 border-l border-gray-300 dark:border-gray-600"></div>
                        <h1 class="text-lg font-semibold text-gray-900 dark:text-white truncate">{{ $lesson->title }}
                        </h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                Videos {{ $lessonNumber }} of {{ $totalLessons }}
                            </span>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto flex">
            <!-- Main Content - Video Player -->
            <div class="flex-1">
                @php
                    // Check if user is enrolled
                    $isEnrolled = \App\Models\CourseEnrollment::where('user_id', Auth::id())
                        ->where('course_id', $course->id)
                        ->exists();

                    // Get lesson order/position
                    $lessonOrder = $lesson->order ?? 1;

                    // Check if this lesson is accessible (first 2 lessons for non-enrolled users)
                    $canAccessLesson = $isEnrolled || $lessonOrder <= 2;
                @endphp

                <div class="aspect-video bg-gray-900 flex items-center justify-center">
                    @if ($canAccessLesson)
                        @if ($lesson->video_type === 'youtube')
                            <iframe src="{{ $lesson->video_embed_url }}" class="w-full h-full" frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen>
                            </iframe>
                        @elseif ($lesson->video_type === 'vimeo')
                            <iframe src="{{ $lesson->video_url }}" class="w-full h-full" frameborder="0"
                                allow="autoplay; fullscreen; picture-in-picture" allowfullscreen>
                            </iframe>
                        @elseif ($lesson->video_type === 'local')
                            <video controls class="w-full h-full" preload="metadata">
                                <source src="{{ asset($lesson->video_url) }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        @else
                            <div class="text-center text-white p-4">Video tidak tersedia</div>
                        @endif
                    @else
                        <div class="text-center text-white p-8">
                            <x-heroicon-o-lock-closed class="h-16 w-16 mx-auto mb-4 text-gray-400" />
                            <h3 class="text-xl font-semibold mb-2">Video Terkunci</h3>
                            <p class="text-gray-300 mb-4">Anda perlu mendaftar kursus ini untuk mengakses video ini.</p>
                            <a href="{{ route('courses.show', $course->id) }}"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                Lihat Detail Kursus
                                <x-heroicon-o-arrow-right class="h-4 w-4 ml-2" />
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Video Controls/Info -->
                <div class="bg-white dark:bg-gray-800 p-6">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ $lesson->title }}</h2>
                    @if ($lesson->description)
                        <p class="text-gray-600 dark:text-gray-400 mb-4">{{ $lesson->description }}</p>
                    @endif
                    <div class="flex items-center space-x-6 text-sm text-gray-500 dark:text-gray-400">
                        <div class="flex items-center">
                            <x-heroicon-o-clock class="h-4 w-4 mr-1" />
                            {{ round($lesson->duration_seconds / 60) }} minutes
                        </div>
                        <div class="flex items-center">
                            <x-heroicon-o-user class="h-4 w-4 mr-1" />
                            {{ $course->instructor?->full_name }}
                        </div>
                    </div>

                    @if (!$isEnrolled)
                        <div
                            class="mt-4 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                            <div class="flex items-center">
                                <x-heroicon-o-information-circle class="h-5 w-5 text-yellow-600 mr-2" />
                                <p class="text-sm text-yellow-800 dark:text-yellow-200">
                                    Anda sedang menonton sebagai preview. Daftar kursus untuk akses penuh dan tracking
                                    progress.
                                </p>
                            </div>
                        </div>
                    @endif

                    <!-- Navigation Buttons -->
                    <div
                        class="flex items-center justify-between mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        @if ($previousLesson)
                            @php
                                $canAccessPrevious = $isEnrolled || ($previousLesson->order ?? 1) <= 2;
                            @endphp
                            @if ($canAccessPrevious)
                                <a href="{{ route('courses.lessons.show', [$course->id, $previousLesson->id]) }}"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <x-heroicon-o-chevron-left class="h-4 w-4 mr-2" />
                                    Previous Lesson
                                </a>
                            @else
                                <div></div>
                            @endif
                        @else
                            <div></div>
                        @endif

                        @if ($nextLesson)
                            @php
                                $canAccessNext = $isEnrolled || ($nextLesson->order ?? 1) <= 2;
                            @endphp
                            @if ($canAccessNext)
                                <a href="{{ route('courses.lessons.show', [$course->id, $nextLesson->id]) }}"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-blue-700">
                                    Next Lesson
                                    <x-heroicon-o-chevron-right class="h-4 w-4 ml-2" />
                                </a>
                            @else
                                <button disabled
                                    class="inline-flex items-center px-4 py-2 bg-gray-400 border border-transparent rounded-md shadow-sm text-sm font-medium text-white cursor-not-allowed">
                                    <x-heroicon-o-lock-closed class="h-4 w-4 mr-2" />
                                    Locked
                                </button>
                            @endif
                        @else
                            @if ($isEnrolled)
                                <a href="{{ route('courses.show', $course->id) }}"
                                    class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-green-700">
                                    Course Complete!
                                    <x-heroicon-o-check-circle class="h-4 w-4 ml-2" />
                                </a>
                            @else
                                <a href="{{ route('courses.show', $course->id) }}"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-blue-700">
                                    Enroll Now
                                    <x-heroicon-o-arrow-right class="h-4 w-4 ml-2" />
                                </a>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar - Course Materials -->
            <aside
                class="w-80 bg-white dark:bg-gray-800 border-l border-gray-200 dark:border-gray-700 flex flex-col max-h-screen">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ $course->title }}</h3>
                    <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
                        <span>{{ $course->lessons->count() }} lessons</span>
                        <span>{{ $course->duration_hours }} hours</span>
                    </div>
                    @if ($isEnrolled)
                        <div class="mt-3 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $progressPercentage }}%"></div>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            {{ round($progressPercentage) }}% Complete
                        </p>
                    @else
                        <div class="mt-3 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="bg-yellow-500 h-2 rounded-full"
                                style="width: {{ min(($lessonNumber / $totalLessons) * 100, (2 / $totalLessons) * 100) }}%">
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            Preview Mode - 2 videos available
                        </p>
                    @endif
                </div>

                <div class="flex-1 overflow-y-auto">
                    <div class="p-4">
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Course Content</h4>
                        <div class="space-y-2">
                            @php
                                $completedLessonIds = [];
                                if ($isEnrolled) {
                                    $completedLessonIds = \App\Models\CourseProgressLog::where('user_id', Auth::id())
                                        ->where('course_id', $course->id)
                                        ->pluck('video_id')
                                        ->toArray();
                                }
                            @endphp

                            @foreach ($course->lessons as $courseLesson)
                                @php
                                    $isCompleted = in_array($courseLesson->id, $completedLessonIds);
                                    $lessonOrderNum = $courseLesson->order ?? $loop->iteration;
                                    $isLocked = !$isEnrolled && $lessonOrderNum > 2;
                                    $canAccessThisLesson = $isEnrolled || $lessonOrderNum <= 2;
                                @endphp

                                @if ($canAccessThisLesson)
                                    <a href="{{ route('courses.lessons.show', [$course->id, $courseLesson->id]) }}"
                                        class="block p-3 rounded-lg transition
            {{ $courseLesson->id === $lesson->id ? 'bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-700' : 'hover:bg-gray-50 dark:hover:bg-gray-700 border border-transparent' }}">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex-shrink-0">
                                                @if ($courseLesson->id === $lesson->id)
                                                    <div
                                                        class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                                                        <x-heroicon-s-play class="h-4 w-4 text-white" />
                                                    </div>
                                                @elseif ($isCompleted)
                                                    <div
                                                        class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                                                        <x-heroicon-s-check class="h-4 w-4 text-green-600" />
                                                    </div>
                                                @else
                                                    <div
                                                        class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                                                        <x-heroicon-o-academic-cap
                                                            class="h-4 w-4 text-gray-500 dark:text-gray-400" />
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-1">
                                                <p
                                                    class="text-sm font-medium text-gray-900 dark:text-white {{ $courseLesson->id === $lesson->id ? 'text-blue-700 dark:text-blue-300' : '' }}">
                                                    {{ $courseLesson->order }}. {{ $courseLesson->title }}
                                                </p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $courseLesson->duration }}</p>
                                            </div>
                                        </div>
                                    </a>
                                @else
                                    <div class="block p-3 rounded-lg border border-transparent opacity-60">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex-shrink-0">
                                                <div
                                                    class="w-8 h-8 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                                    <x-heroicon-o-lock-closed
                                                        class="h-4 w-4 text-gray-400 dark:text-gray-500" />
                                                </div>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm font-medium text-gray-500 dark:text-gray-500">
                                                    {{ $courseLesson->order }}. {{ $courseLesson->title }}
                                                </p>
                                                <p class="text-xs text-gray-400 dark:text-gray-600">
                                                    {{ $courseLesson->duration }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('courses.show', $course->id) }}"
                        class="w-full bg-gray-900 dark:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium hover:bg-gray-800 dark:hover:bg-gray-600 transition flex items-center justify-center">
                        <x-heroicon-o-academic-cap class="h-4 w-4 mr-2" />
                        Course Overview
                    </a>
                    @if ($isEnrolled)
                        <button
                            class="w-full mt-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-lg font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            Download Materials
                        </button>
                    @else
                        <a href="{{ route('courses.show', $course->id) }}"
                            class="w-full mt-3 bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700 transition flex items-center justify-center">
                            <x-heroicon-o-lock-open class="h-4 w-4 mr-2" />
                            Enroll Course
                        </a>
                    @endif
                </div>
            </aside>
        </div>
    </div>
</x-siswa.layout>
