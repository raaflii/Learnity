<x-pengajar.layout>
    <x-slot:title>Create Video for {{ $topic->title }}</x-slot:title>

    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="mb-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white">Create Topic Video</h2>
                        <a href="{{ route('pengajar.courses.topics.video.index', [$course, $topic]) }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-md transition-colors">
                            <x-heroicon-o-arrow-left class="h-4 w-4 mr-2" />
                            Back to Topic Videos
                        </a>
                    </div>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Fill in the information below to add a new video to this topic.
                    </p>
                </div>

                @if ($errors->any())
                    <div class="mb-6 rounded-md bg-red-50 dark:bg-red-900/20 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <x-heroicon-o-exclamation-circle class="h-5 w-5 text-red-400" />
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                                    There were some errors with your submission
                                </h3>
                                <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                                    <ul class="list-disc pl-5 space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('pengajar.courses.topics.video.store', [$course, $topic]) }}" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="title"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Video Title</label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" required
                                class="mt-1 block w-full rounded-md py-2 pl-3 bg-white dark:bg-gray-700 text-gray-900 dark:text-white ring-1 ring-inset ring-gray-300 dark:ring-gray-600 focus:ring-2 focus:ring-blue-600 sm:text-sm">
                        </div>

                        <div>
                            <label for="video_type"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Video Type</label>
                            <select name="video_type" id="video_type" required
                                class="mt-1 block w-full rounded-md py-2 pl-3 pr-10 bg-white dark:bg-gray-700 text-gray-900 dark:text-white ring-1 ring-inset ring-gray-300 dark:ring-gray-600 focus:ring-2 focus:ring-blue-600 sm:text-sm">
                                <option value="youtube" {{ old('video_type') == 'youtube' ? 'selected' : '' }}>YouTube</option>
                                <option value="vimeo" {{ old('video_type') == 'vimeo' ? 'selected' : '' }}>Vimeo</option>
                                <option value="local" {{ old('video_type') == 'local' ? 'selected' : '' }}>Local Upload</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="video_path"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Video Path / URL</label>
                        <input type="text" name="video_path" id="video_path" value="{{ old('video_path') }}" required
                            class="mt-1 block w-full rounded-md py-2 pl-3 bg-white dark:bg-gray-700 text-gray-900 dark:text-white ring-1 ring-inset ring-gray-300 dark:ring-gray-600 focus:ring-2 focus:ring-blue-600 sm:text-sm"
                            placeholder="e.g. https://youtube.com/watch?v=xxxxx or local/path/to/video.mp4">
                    </div>

                    <div>
                        <label for="description"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                        <textarea name="description" id="description" rows="4"
                            class="mt-1 block w-full rounded-md bg-white py-2 dark:bg-gray-700 text-gray-900 dark:text-white ring-1 ring-inset pl-3 ring-gray-300 dark:ring-gray-600 focus:ring-2 focus:ring-blue-600 sm:text-sm">{{ old('description') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="duration_seconds"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Duration (in seconds)</label>
                            <input type="number" name="duration_seconds" id="duration_seconds"
                                value="{{ old('duration_seconds', 0) }}" min="0"
                                class="mt-1 block w-full rounded-md py-2 pl-3 bg-white dark:bg-gray-700 text-gray-900 dark:text-white ring-1 ring-inset ring-gray-300 dark:ring-gray-600 focus:ring-2 focus:ring-blue-600 sm:text-sm">
                        </div>

                        <div>
                            <label for="order_index"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Order Index</label>
                            <input type="number" name="order_index" id="order_index"
                                value="{{ old('order_index', 0) }}" min="0"
                                class="mt-1 block w-full rounded-md py-2 pl-3 bg-white dark:bg-gray-700 text-gray-900 dark:text-white ring-1 ring-inset ring-gray-300 dark:ring-gray-600 focus:ring-2 focus:ring-blue-600 sm:text-sm">
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4">
                        <a href="{{ route('pengajar.courses.topics.video.index', [$course, $topic]) }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            Cancel
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors cursor-pointer">
                            <x-heroicon-o-plus class="h-4 w-4 mr-2" />
                            Add Video
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-pengajar.layout>
