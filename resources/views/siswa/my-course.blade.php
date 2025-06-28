<x-layout>
    <x-slot:title>My Courses</x-slot:title>

    <div class="space-y-6">
        <!-- Header Section -->
        <div class="bg-gray-900 overflow-hidden shadow rounded-lg">
            <div class="px-6 py-8 sm:p-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-white mb-2">My Learning Journey</h1>
                        <p class="text-blue-100">Continue where you left off and track your progress</p>
                    </div>
                    <div class="text-right">
                        <div class="text-3xl font-bold text-white">{{ count($myCourses) }}</div>
                        <div class="text-blue-100 text-sm">Enrolled Courses</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter and Search Section -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4 sm:mb-0">My Courses</h2>

                    <!-- Search form -->
                    <form method="GET" action="{{ route('my-course') }}" class="w-full sm:w-80">
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <x-heroicon-o-magnifying-glass class="h-5 w-5 text-gray-400 dark:text-gray-500" />
                            </div>
                            <input type="text" name="q" value="{{ request('q') }}"
                                placeholder="Search my courses..."
                                class="block w-full rounded-md border-0 py-2.5 pl-10 pr-3 text-gray-900 dark:text-white ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 bg-white dark:bg-gray-700">
                        </div>
                    </form>
                </div>

                <!-- Progress filters -->
                <div class="flex flex-wrap gap-2 mb-6">
                    <a href="{{ route('my-course', ['q' => request('q')]) }}"
                        class="px-4 py-2 {{ !request('status') ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }} rounded-lg text-sm font-medium transition-colors">
                        All Courses
                    </a>
                    <a href="{{ route('my-course', ['q' => request('q'), 'status' => 'in-progress']) }}"
                        class="px-4 py-2 {{ request('status') == 'in-progress' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }} rounded-lg text-sm font-medium transition-colors">
                        In Progress
                    </a>
                    <a href="{{ route('my-course', ['q' => request('q'), 'status' => 'completed']) }}"
                        class="px-4 py-2 {{ request('status') == 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }} rounded-lg text-sm font-medium transition-colors">
                        Completed
                    </a>
                    <a href="{{ route('my-course', ['q' => request('q'), 'status' => 'not-started']) }}"
                        class="px-4 py-2 {{ request('status') == 'not-started' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }} rounded-lg text-sm font-medium transition-colors">
                        Not Started
                    </a>
                </div>

                <!-- Results count -->
                <div class="mb-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        @if (request('q'))
                            Found {{ count($myCourses) }} course(s) for "{{ request('q') }}"
                        @else
                            Showing {{ count($myCourses) }} enrolled courses
                        @endif
                    </p>
                </div>

                <!-- Course cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($myCourses as $id => $course)
                        <x-my-course-card :courseId="$id" :title="$course['title']" :description="$course['description']" :instructor="$course['instructor']"
                            :level="$course['level']" :progress="$course['progress'] ?? 0" :lastAccessed="$course['last_accessed'] ?? null" :status="$course['status'] ?? 'not-started'" :totalLessons="$course['total_lessons'] ?? 0"
                            :completedLessons="$course['completed_lessons'] ?? 0" :gradient="$gradients[$loop->index % count($gradients)]" :thumbnail="$course['thumbnail'] ?? null" />
                    @empty
                        <div class="col-span-full text-center py-12">
                            <x-heroicon-o-academic-cap class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" />
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">
                                @if (request('q'))
                                    No courses found
                                @else
                                    You haven't enrolled in any courses yet
                                @endif
                            </h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                @if (request('q'))
                                    Try adjusting your search terms or <a href="{{ route('my-course') }}"
                                        class="text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300">view all your courses</a>.
                                @else
                                    <a href="{{ route('search') }}" class="text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300">Browse
                                        available courses</a> to start learning.
                                @endif
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Learning Stats Section -->
        @if (count($myCourses) > 0)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <x-heroicon-o-check-circle class="h-6 w-6 text-green-600" />
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Completed</dt>
                                    <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $completedCount ?? '0' }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <x-heroicon-o-play-circle class="h-6 w-6 text-yellow-600" />
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">In Progress</dt>
                                    <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $inProgressCount ?? '0' }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <x-heroicon-o-clock class="h-6 w-6 text-gray-500" />
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Not Started</dt>
                                    <dd class="text-lg font-medium text-gray-900 dark:text-white">
                                        {{ count($myCourses) - ($completedCount ?? 0) - ($inProgressCount ?? 0) }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-layout>
