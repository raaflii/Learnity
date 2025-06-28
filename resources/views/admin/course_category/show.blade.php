<x-admin.layout>
    <x-slot:title>Category Details</x-slot:title>

    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-white">Category Details</h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Complete information for this course category
                            </p>
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('admin.categories.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-md transition-colors">
                                <x-heroicon-o-arrow-left class="h-4 w-4 mr-2" />
                                Back to Categories
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Category Details -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Basic Info -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                            <x-heroicon-o-rectangle-group class="h-5 w-5 inline mr-2" />
                            Basic Information
                        </h3>
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Category Name</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $courseCategory->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                                <dd class="mt-1">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $courseCategory->is_active
                                            ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300'
                                            : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' }}">
                                        {{ $courseCategory->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Category ID</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">#{{ $courseCategory->id }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Timestamps -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                            <x-heroicon-o-clock class="h-5 w-5 inline mr-2" />
                            Timestamps
                        </h3>
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Created At</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ $courseCategory->created_at->format('F j, Y \a\t g:i A') }}
                                    <span class="text-gray-500 dark:text-gray-400">
                                        ({{ $courseCategory->created_at->diffForHumans() }})
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Updated At</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ $courseCategory->updated_at->format('F j, Y \a\t g:i A') }}
                                    <span class="text-gray-500 dark:text-gray-400">
                                        ({{ $courseCategory->updated_at->diffForHumans() }})
                                    </span>
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Action Buttons -->
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
                        Delete Category
                    </button>

                    <a href="{{ route('admin.categories.edit', $courseCategory) }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <x-heroicon-o-pencil class="h-4 w-4 mr-2" />
                        Edit Category
                    </a>

                    <!-- Modal -->
                    <div x-show="showDeleteModal" x-transition
                        class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-50" style="display: none;">
                        <div class="fixed inset-0 z-10 overflow-y-auto">
                            <div
                                class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                                <div x-show="showDeleteModal" x-transition
                                    class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                                    <div>
                                        <div
                                            class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30">
                                            <x-heroicon-o-exclamation-triangle
                                                class="h-6 w-6 text-red-600 dark:text-red-400" />
                                        </div>
                                        <div class="mt-3 text-center sm:mt-5">
                                            <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                                                Delete Category
                                            </h3>
                                            <div class="mt-2">
                                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                                    Are you sure you want to delete <span
                                                        class="font-medium text-gray-900 dark:text-white">
                                                        {{ $courseCategory->name }}</span>? This action cannot be
                                                    undone.
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tombol Delete dan Cancel -->
                                    <div class="mt-5 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3">
                                        <form method="POST"
                                            action="{{ route('admin.categories.destroy', $courseCategory) }}"
                                            class="sm:col-start-2">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 cursor-pointer">
                                                Delete
                                            </button>
                                        </form>

                                        <button @click="close" type="button"
                                            class="inline-flex w-full justify-center rounded-md bg-white dark:bg-gray-700 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 sm:col-start-1 sm:mt-0 mt-3 cursor-pointer">
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
</x-admin.layout>
