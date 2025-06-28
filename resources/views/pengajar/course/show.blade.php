<x-pengajar.layout>
    <x-slot:title>Course Details</x-slot:title>

    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-white">Course Details</h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Detailed view of course: <strong>{{ $course->title }}</strong>
                            </p>
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('pengajar.courses.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-md transition-colors">
                                <x-heroicon-o-arrow-left class="h-4 w-4 mr-2" />
                                Back to Courses
                            </a>
                        </div>
                    </div>
                </div>
                <div class="mb-8 flex items-center">
                    <div class="flex-shrink-0 w-32 h-32">
                        @if ($course->thumbnail && Storage::disk('public')->exists($course->thumbnail))
                            <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}"
                                class="rounded-lg shadow object-cover w-32 h-32 border border-gray-300 dark:border-gray-700">
                        @else
                            <div
                                class="w-32 h-32 rounded-lg bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center text-white font-bold text-xl">
                                {{ strtoupper(substr($course->title, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div class="ml-6">
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $course->title }}</h1>
                        <div class="mt-2 flex flex-wrap gap-2">
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
        {{ $course->level === 'advanced'
            ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300'
            : ($course->level === 'intermediate'
                ? 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300'
                : 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300') }}">
                                <x-heroicon-o-bookmark class="h-4 w-4 mr-1" />
                                {{ ucfirst($course->level) }}
                            </span>
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                <x-heroicon-o-currency-dollar class="h-4 w-4 mr-1" />
                                Rp{{ number_format($course->price, 0, ',', '.') }}
                            </span>
                            @if ($course->is_published)
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

                            @if ($course->is_featured)
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300">
                                    <x-heroicon-o-star class="h-4 w-4 mr-1" />
                                    Featured
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- Horizontal Info Sections -->
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <!-- Basic Info -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                            <x-heroicon-o-academic-cap class="h-5 w-5 inline mr-2" />
                            Basic Info
                        </h3>
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Course Title</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $course->title }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Category</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ $course->category->name ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Instructor</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ $course->instructor->first_name ?? '' }}
                                    {{ $course->instructor->last_name ?? '' }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Course Info -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                            <x-heroicon-o-chart-bar class="h-5 w-5 inline mr-2" />
                            Course Info
                        </h3>
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Price</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                    Rp{{ number_format($course->price, 0, ',', '.') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Duration</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $course->duration_hours }}
                                    Hours</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Level</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ ucfirst($course->level) }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Descriptions -->
                <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Short Description</h3>
                        <p class="text-sm text-gray-800 dark:text-gray-300 whitespace-pre-line">
                            {{ $course->short_description }}</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Full Description</h3>
                        <p class="text-sm text-gray-800 dark:text-gray-300 whitespace-pre-line">
                            {{ $course->description }}</p>
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
                                {{ $course->created_at->format('F j, Y \a\t g:i A') }}
                                <span class="text-gray-500 dark:text-gray-400">
                                    ({{ $course->created_at->diffForHumans() }})
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Updated</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $course->updated_at->format('F j, Y \a\t g:i A') }}
                                <span class="text-gray-500 dark:text-gray-400">
                                    ({{ $course->updated_at->diffForHumans() }})
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>
                <div class="mt-8 flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-600"
                    x-data="{
                        showDeleteModal: false,
                        open() { this.showDeleteModal = true;
                            document.body.style.overflow = 'hidden'; },
                        close() { this.showDeleteModal = false;
                            document.body.style.overflow = 'auto'; }
                    }">

                    <button @click="open"
                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors cursor-pointer">
                        <x-heroicon-o-trash class="h-4 w-4 mr-2" />
                        Delete Course
                    </button>

                    <a href="{{ route('pengajar.courses.edit', $course) }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors cursor-pointer">
                        <x-heroicon-o-pencil class="h-4 w-4 mr-2" />
                        Edit Course
                    </a>

                    <!-- Modal -->
                    <div x-show="showDeleteModal" x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-50" style="display: none;">
                        <div class="fixed inset-0 z-10 overflow-y-auto">
                            <div
                                class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                                <div x-show="showDeleteModal" x-transition:enter="ease-out duration-300"
                                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                    x-transition:leave="ease-in duration-200"
                                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                    class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                                    <div>
                                        <div
                                            class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30">
                                            <x-heroicon-o-exclamation-triangle
                                                class="h-6 w-6 text-red-600 dark:text-red-400" />
                                        </div>
                                        <div class="mt-3 text-center sm:mt-5">
                                            <h3 class="text-base font-semibold leading-6 text-gray-900 dark:text-white">
                                                Delete Course
                                            </h3>
                                            <div class="mt-2">
                                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                                    Are you sure you want to delete <span
                                                        class="font-medium text-gray-900 dark:text-white">{{ $course->title }}</span>?
                                                    This action cannot be undone.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-5 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3">
                                        <form method="POST"
                                            action="{{ route('pengajar.courses.destroy', $course) }}"
                                            class="sm:col-start-2">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 cursor-pointer">
                                                Delete
                                            </button>
                                        </form>
                                        <button @click="close" type="button"
                                            class="mt-3 inline-flex w-full justify-center rounded-md bg-white dark:bg-gray-700 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 sm:col-start-1 sm:mt-0 cursor-pointer">
                                            Cancel
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-pengajar.layout>
