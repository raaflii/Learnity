<x-pengajar.layout>
    <x-slot:title>Video Details</x-slot:title>

    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-white">Video Details</h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Detailed view of video: <strong>{{ $video->title }}</strong>
                            </p>
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('pengajar.courses.topics.video.index', [$course, $topic]) }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-md transition-colors">
                                <x-heroicon-o-arrow-left class="h-4 w-4 mr-2" />
                                Back to Videos
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Basic Info -->
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                            <x-heroicon-o-video-camera class="h-5 w-5 inline mr-2" />
                            Video Info
                        </h3>
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Title</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $video->title }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Type</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ ucfirst($video->video_type) }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Duration</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ gmdate('i:s', $video->duration_seconds) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Order Index</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $video->order_index }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Video URL</dt>
                                <dd class="mt-1 text-sm text-blue-600 dark:text-blue-400 break-all">
                                    <a href="{{ $video->video_path }}" target="_blank" class="hover:underline">
                                        {{ $video->video_path }}
                                    </a>
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
                            {{ $video->description ?: 'No description provided.' }}
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
                                {{ $video->created_at->format('F j, Y \a\t g:i A') }}
                                <span class="text-gray-500 dark:text-gray-400">
                                    ({{ $video->created_at->diffForHumans() }})
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Updated</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $video->updated_at->format('F j, Y \a\t g:i A') }}
                                <span class="text-gray-500 dark:text-gray-400">
                                    ({{ $video->updated_at->diffForHumans() }})
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>

                <!-- Actions -->
                <div class="mt-8 flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-600">
                    <a href="{{ route('pengajar.courses.topics.video.edit', [$course, $topic, $video]) }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors cursor-pointer">
                        <x-heroicon-o-pencil class="h-4 w-4 mr-2" />
                        Edit Video
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-pengajar.layout>
