<x-pengajar.layout>
    <x-slot:title>Topic Details</x-slot:title>

    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-white">Topic Details</h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Detailed view of topic: <strong>{{ $topic->title }}</strong>
                            </p>
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('pengajar.courses.topics.index', $course) }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-md transition-colors">
                                <x-heroicon-o-arrow-left class="h-4 w-4 mr-2" />
                                Back to Topics
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Basic Info -->
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                            <x-heroicon-o-academic-cap class="h-5 w-5 inline mr-2" />
                            Topic Info
                        </h3>
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Title</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $topic->title }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Course</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $course->title }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Order Index</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $topic->order_index }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                    @if ($topic->is_published)
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                            <x-heroicon-o-check-circle class="h-4 w-4 mr-1" />
                                            Published
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300">
                                            <x-heroicon-o-clock class="h-4 w-4 mr-1" />
                                            Draft
                                        </span>
                                    @endif
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Description -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                            <x-heroicon-o-document-text class="h-5 w-5 inline mr-2" />
                            Description
                        </h3>
                        <p class="text-sm text-gray-800 dark:text-gray-300 whitespace-pre-line">
                            {{ $topic->description ?: 'No description provided.' }}
                        </p>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="mt-8 bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                        <x-heroicon-o-clock class="h-5 w-5 inline mr-2" />
                        Timeline
                    </h3>
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Created At</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $topic->created_at->format('F j, Y \a\t g:i A') }}
                                <span class="text-gray-500 dark:text-gray-400">
                                    ({{ $topic->created_at->diffForHumans() }})
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Updated</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $topic->updated_at->format('F j, Y \a\t g:i A') }}
                                <span class="text-gray-500 dark:text-gray-400">
                                    ({{ $topic->updated_at->diffForHumans() }})
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>

                <!-- Actions -->
                <div class="mt-8 flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-600">
                    <a href="{{ route('pengajar.courses.topics.edit', [$course, $topic]) }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors cursor-pointer">
                        <x-heroicon-o-pencil class="h-4 w-4 mr-2" />
                        Edit Topic
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-pengajar.layout>
