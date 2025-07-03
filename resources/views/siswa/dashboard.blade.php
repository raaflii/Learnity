<x-siswa.layout>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="space-y-6">
        <!-- Welcome Section -->
        <div class="bg-gray-900 overflow-hidden shadow rounded-lg">
            <div class="px-6 py-8 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold mb-2">Welcome back, {{ $user->first_name }}!</h2>
                        <p class="text-blue-100">
                            Ready to continue your learning journey? You have {{ $upcomingEvents->count() }} events
                            coming up today.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <x-stats-card title="Total Courses" :value="$stats['total_courses']" icon="academic-cap" color="blue" :href="route('search')" />

            <x-stats-card title="Total Lessons" :value="$stats['total_lessons']" icon="play" color="green" :href="route('search')" />

            <x-stats-card title="Upcoming Events" :value="$stats['upcoming_events']" icon="calendar" color="yellow" :href="route('calendar.index')" />
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Recent Courses & Today's Schedule -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Recent Courses -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Recent Courses</h3>
                            <a href="{{ route('search') }}" class="text-sm text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300">View
                                all</a>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @forelse($recentCourses as $course)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:shadow-md transition-shadow">
                                    <div class="flex items-start space-x-3">
                                        <div
                                            class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <x-heroicon-o-play class="h-6 w-6 text-white" />
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $course->title }}
                                            </h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $course->instructor->full_name }}
                                            </p>
                                            <div class="flex items-center mt-2">
                                                <span
                                                    class="text-xs px-2 py-1 rounded-full {{ $course->level === 'advanced' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' : ($course->level === 'intermediate' ? 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300' : 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300') }}">
                                                    {{ ucfirst($course->level) }}
                                                </span>
                                                <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">{{ $course->lessons->count() }}
                                                    lessons</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <a href="{{ route('courses.show', $course->id) }}"
                                            class="text-xs text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                                            Continue Learning →
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-2 text-center py-8">
                                    <x-heroicon-o-academic-cap class="h-12 w-12 text-gray-300 dark:text-gray-600 mx-auto mb-3" />
                                    <p class="text-gray-500 dark:text-gray-400">No courses available</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Today's Schedule -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Today's Schedule</h3>
                            <a href="{{ route('calendar.index') }}"
                                class="text-sm text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300">View
                                calendar</a>
                        </div>
                    </div>
                    <div class="p-6">
                        @forelse($todayEvents as $event)
                            <div
                                class="flex items-center space-x-3 py-3 {{ !$loop->last ? 'border-b border-gray-100 dark:border-gray-700' : '' }}">
                                <div class="w-3 h-3 rounded-full flex-shrink-0"
                                    style="background-color: {{ $event->color }}"></div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $event->title }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $event->formatted_start_time }}
                                        @if ($event->location)
                                            • {{ $event->location }}
                                        @endif
                                    </p>
                                </div>
                                <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                    {{ ucfirst($event->type) }}
                                </span>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <x-heroicon-o-calendar class="h-12 w-12 text-gray-300 dark:text-gray-600 mx-auto mb-3" />
                                <p class="text-gray-500 dark:text-gray-400 mb-2">No events scheduled for today</p>
                                <a href="{{ route('calendar.index') }}"
                                    class="text-sm text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300">
                                    Schedule an event
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Recent Activity</h3>
                    </div>
                    <div class="p-6">
                        <div class="flow-root">
                            <ul class="-mb-8 pb-7">
                                @foreach ($recentActivity as $activity)
                                    <li class="{{ !$loop->last ? 'pb-4' : '' }}">
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span
                                                    class="h-8 w-8 rounded-full bg-{{ $activity['color'] }}-500 flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                                    <x-dynamic-component :component="'heroicon-o-' . $activity['icon']" class="h-4 w-4 text-white" />
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="flex items-start justify-between pt-1.5">
                                                    <p class="text-sm text-gray-500 dark:text-gray-400 pr-2">
                                                        {{ $activity['description'] }}</p>
                                                    <span class="text-xs text-gray-400 dark:text-gray-500 whitespace-nowrap">
                                                        {{ $activity['time'] }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Activity & Quick Actions -->
            <div class="space-y-6">
                <!-- Popular Courses -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Popular Courses</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            @forelse($popularCourses as $course)
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded flex items-center justify-center flex-shrink-0">
                                        <span class="text-white text-xs font-bold">{{ $loop->iteration }}</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $course->title }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ number_format($course->students) }}
                                            students</p>
                                    </div>
                                    <div class="flex items-center">
                                        <x-heroicon-s-star class="h-4 w-4 text-yellow-400" />
                                        <span class="text-xs text-gray-600 dark:text-gray-400 ml-1">{{ $course->rating }}</span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 dark:text-gray-400">No popular courses available</p>
                            @endforelse
                        </div>
                    </div>
                </div>
                <!-- Learning Progress -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Learning Progress</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Courses in Progress</span>
                                <span
                                    class="text-sm font-medium text-gray-900 dark:text-white">{{ $learningProgress['courses_in_progress'] }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Completed This Month</span>
                                <span
                                    class="text-sm font-medium text-gray-900 dark:text-white">{{ $learningProgress['completed_this_month'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Quick Actions -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Quick Actions</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <a href="{{ route('search') }}"
                                class="w-full text-left px-4 py-3 bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/20 dark:hover:bg-blue-900/30 rounded-lg transition-colors border border-blue-200 dark:border-blue-800 block">
                                <div class="flex items-center">
                                    <x-heroicon-o-academic-cap class="h-5 w-5 text-blue-600 dark:text-blue-400 mr-3" />
                                    <div>
                                        <div class="font-medium text-blue-900 dark:text-blue-100">Browse Courses</div>
                                        <div class="text-sm text-blue-600 dark:text-blue-300">Discover new learning opportunities</div>
                                    </div>
                                </div>
                            </a>
                            <a href="{{ route('calendar.index') }}"
                                class="w-full text-left px-4 py-3 bg-green-50 hover:bg-green-100 dark:bg-green-900/20 dark:hover:bg-green-900/30 rounded-lg transition-colors border border-green-200 dark:border-green-800 block">
                                <div class="flex items-center">
                                    <x-heroicon-o-calendar class="h-5 w-5 text-green-600 dark:text-green-400 mr-3" />
                                    <div>
                                        <div class="font-medium text-green-900 dark:text-green-100">Schedule Event</div>
                                        <div class="text-sm text-green-600 dark:text-green-300">Add to your calendar</div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>


            </div>
        </div>

    </div>
</x-siswa.layout>
