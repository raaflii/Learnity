<x-pengajar.layout>
    <x-slot:title>Videos for {{ $topic->title }}</x-slot:title>

    <div class="space-y-6"
        x-data="{
            showDeleteModal: false,
            selectedVideo: null,
            openDeleteModal(video) {
                this.selectedVideo = video;
                this.showDeleteModal = true;
                document.body.style.overflow = 'hidden';
            },
            closeDeleteModal() {
                this.selectedVideo = null;
                this.showDeleteModal = false;
                document.body.style.overflow = 'auto';
            }
        }">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white">Topic Videos</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            For topic: <strong>{{ $topic->title }}</strong> in course:
                            <strong>{{ $course->title }}</strong>
                        </p>
                    </div>
                    <a href="{{ route('pengajar.courses.topics.video.create', [$course, $topic]) }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors">
                        <x-heroicon-o-plus class="h-4 w-4 mr-2" />
                        Add New Video
                    </a>
                </div>

                @if (session('success'))
                    <div class="mb-4 rounded-md bg-green-50 dark:bg-green-900/20 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <x-heroicon-o-check-circle class="h-5 w-5 text-green-400" />
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800 dark:text-green-200">
                                    {{ session('success') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-600">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Title</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Duration</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Order</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
                            @forelse ($videos as $video)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $video->title }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300 capitalize">{{ $video->video_type }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">{{ gmdate('i:s', $video->duration_seconds) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">{{ $video->order_index }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center space-x-2 justify-end">
                                            @if ($video->video_type === 'youtube' || $video->video_type === 'vimeo')
                                                <a href="{{ $video->video_path }}" target="_blank"
                                                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300"
                                                    title="Watch Video">
                                                    <x-heroicon-o-play-circle class="h-5 w-5" />
                                                </a>
                                            @endif

                                            <a href="{{ route('pengajar.courses.topics.video.show', [$course, $topic, $video]) }}"
                                                class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                                title="View Video Details">
                                                <x-heroicon-o-eye class="h-5 w-5" />
                                            </a>

                                            <a href="{{ route('pengajar.courses.topics.video.edit', [$course, $topic, $video]) }}"
                                                class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300"
                                                title="Edit Video">
                                                <x-heroicon-o-pencil class="h-5 w-5" />
                                            </a>

                                            <button type="button"
                                                @click="openDeleteModal({ id: {{ $video->id }}, title: '{{ addslashes($video->title) }}' })"
                                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                                title="Delete Video">
                                                <x-heroicon-o-trash class="h-5 w-5 cursor-pointer" />
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <x-heroicon-o-film class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" />
                                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No videos found</h3>
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">This topic doesn't have any videos yet.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($videos->hasPages())
                    <div class="mt-6">
                        {{ $videos->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Delete Modal -->
        <div x-show="showDeleteModal" x-transition class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-50" style="display: none;">
            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div x-show="showDeleteModal"
                        x-transition
                        class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                        <div>
                            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30">
                                <x-heroicon-o-exclamation-triangle class="h-6 w-6 text-red-600 dark:text-red-400" />
                            </div>
                            <div class="mt-3 text-center sm:mt-5">
                                <h3 class="text-base font-semibold text-gray-900 dark:text-white">Delete Video</h3>
                                <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                    Are you sure you want to delete
                                    <span x-text="selectedVideo?.title" class="font-medium text-gray-900 dark:text-white"></span>?
                                    This action cannot be undone.
                                </div>
                            </div>
                        </div>
                        <div class="mt-5 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3">
                            <form :action="selectedVideo ? '{{ route('pengajar.courses.topics.video.destroy', [$course->id, $topic->id, '__ID__']) }}'.replace('__ID__', selectedVideo.id) : ''" method="POST" class="sm:col-start-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 cursor-pointer">
                                    Delete
                                </button>
                            </form>
                            <button @click="closeDeleteModal()" type="button"
                                class="mt-3 inline-flex w-full justify-center rounded-md bg-white dark:bg-gray-700 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 sm:col-start-1 sm:mt-0 cursor-pointer">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-pengajar.layout>
