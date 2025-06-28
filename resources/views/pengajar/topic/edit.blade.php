<x-pengajar.layout>
    <x-slot:title>Edit Topic</x-slot:title>

    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="mb-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white">Edit Topic</h2>
                        <a href="{{ route('pengajar.courses.topics.index', $course) }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-md transition-colors">
                            <x-heroicon-o-arrow-left class="h-4 w-4 mr-2" />
                            Back to Topics
                        </a>
                    </div>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Update the topic details below.
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

                <form method="POST" action="{{ route('pengajar.courses.topics.update', [$course, $topic]) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $topic->title) }}" required
                                class="mt-1 block w-full rounded-md py-2 pl-3 bg-white dark:bg-gray-700 text-gray-900 dark:text-white ring-1 ring-inset ring-gray-300 dark:ring-gray-600 focus:ring-2 focus:ring-blue-600 sm:text-sm">
                        </div>

                        <div>
                            <label for="order_index" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Order</label>
                            <input type="number" name="order_index" id="order_index" value="{{ old('order_index', $topic->order_index) }}" min="0"
                                class="mt-1 block w-full rounded-md py-2 pl-3 bg-white dark:bg-gray-700 text-gray-900 dark:text-white ring-1 ring-inset ring-gray-300 dark:ring-gray-600 focus:ring-2 focus:ring-blue-600 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                        <textarea name="description" id="description" rows="4"
                            class="mt-1 block w-full rounded-md bg-white py-2 dark:bg-gray-700 text-gray-900 dark:text-white ring-1 ring-inset pl-3 ring-gray-300 dark:ring-gray-600 focus:ring-2 focus:ring-blue-600 sm:text-sm">{{ old('description', $topic->description) }}</textarea>
                    </div>

                    <div class="flex items-center">
                        <input id="is_published" name="is_published" type="checkbox" value="1"
                            class="h-4 w-4 text-blue-600 border-gray-300 rounded cursor-pointer"
                            {{ old('is_published', $topic->is_published) ? 'checked' : '' }}>
                        <label for="is_published" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Publish Topic</label>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4">
                        <a href="{{ route('pengajar.courses.topics.index', $course) }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            Cancel
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors cursor-pointer">
                            <x-heroicon-o-pencil class="h-4 w-4 mr-2" />
                            Update Topic
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-pengajar.layout>
