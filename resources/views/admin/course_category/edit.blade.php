<x-admin.layout>
    <x-slot:title>Edit Category</x-slot:title>

    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="mb-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white">Edit Category</h2>
                        <a href="{{ route('admin.categories.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-md transition-colors">
                            <x-heroicon-o-arrow-left class="h-4 w-4 mr-2" />
                            Back to Categories
                        </a>
                    </div>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Update course category information for <strong>{{ $courseCategory->name }}</strong>.
                    </p>
                </div>

                {{-- Error --}}
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

                <form method="POST" action="{{ route('admin.categories.update', $courseCategory) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    {{-- Name --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Category Name
                        </label>
                        <div class="mt-1">
                            <input type="text" name="name" id="name"
                                value="{{ old('name', $courseCategory->name) }}" required
                                class="block w-full rounded-md border-0 py-2 pl-3 text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 bg-white dark:bg-gray-700 @error('name') ring-red-500 @enderror">
                        </div>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Description
                        </label>
                        <div class="mt-1">
                            <textarea name="description" id="description" rows="3"
                                class="block w-full rounded-md border-0 py-2 pl-3 text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 bg-white dark:bg-gray-700 @error('description') ring-red-500 @enderror">{{ old('description', $courseCategory->description) }}</textarea>
                        </div>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Icon --}}
                    <div>
                        <label for="icon" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Icon (optional, e.g. heroicon name)
                        </label>
                        <div class="mt-1">
                            <input type="text" name="icon" id="icon"
                                value="{{ old('icon', $courseCategory->icon) }}"
                                class="block w-full rounded-md border-0 py-2 pl-3 text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 bg-white dark:bg-gray-700 @error('icon') ring-red-500 @enderror">
                        </div>
                        @error('icon')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- is_active --}}
                    <div>
                        <label for="is_active" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Active Status
                        </label>
                        <div class="mt-2 flex items-center">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" id="is_active" value="1"
                                {{ old('is_active', $courseCategory->is_active) ? 'checked' : '' }}
                                class="h-4 w-4 text-blue-600 cursor-pointer border-gray-300 rounded focus:ring-blue-500">
                            <label for="is_active" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                Active
                            </label>
                        </div>
                        @error('is_active')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Buttons --}}
                    <div class="flex justify-end space-x-3 pt-4">
                        <a href="{{ route('admin.categories.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            Cancel
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors cursor-pointer">
                            <x-heroicon-o-pencil class="h-4 w-4 mr-2" />
                            Update Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin.layout>
