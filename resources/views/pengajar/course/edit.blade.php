<x-pengajar.layout>
    <x-slot:title>Edit Course</x-slot:title>

    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="mb-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white">Edit Course</h2>
                        <a href="{{ route('pengajar.courses.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-md transition-colors">
                            <x-heroicon-o-arrow-left class="h-4 w-4 mr-2" />
                            Back to Courses
                        </a>
                    </div>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Update the information below to edit this course.
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

                <form method="POST" action="{{ route('pengajar.courses.update', $course) }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $course->title) }}" required
                                class="mt-1 block w-full rounded-md py-2 pl-3 bg-white dark:bg-gray-700 text-gray-900 dark:text-white ring-1 ring-inset ring-gray-300 dark:ring-gray-600 focus:ring-2 focus:ring-blue-600 sm:text-sm">
                        </div>

                        <div x-data="{ previewUrl: '{{ $course->thumbnail ? asset('storage/'.$course->thumbnail) : '' }}' }" class="space-y-2">
                            <label for="thumbnail" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Course Thumbnail</label>

                            <input type="file" name="thumbnail" id="thumbnail" accept="image/*"
                                @change="previewUrl = URL.createObjectURL($event.target.files[0])"
                                class="block w-full text-sm text-gray-900 dark:text-white
                                   file:mr-4 file:py-2 file:px-4
                                   file:rounded-md file:border-0
                                   file:text-sm file:font-semibold
                                   file:bg-blue-600 file:text-white
                                   hover:file:bg-blue-700
                                   dark:file:bg-blue-500 dark:hover:file:bg-blue-600
                                   cursor-pointer">

                            <template x-if="previewUrl">
                                <img :src="previewUrl" alt="Preview" class="h-32 rounded shadow-md object-cover border mt-2" />
                            </template>

                            @error('thumbnail')
                                <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price</label>
                            <input type="number" name="price" id="price" value="{{ old('price', $course->price) }}" step="0.01" min="0"
                                class="mt-1 block w-full rounded-md py-2 pl-3 bg-white dark:bg-gray-700 text-gray-900 dark:text-white ring-1 ring-inset ring-gray-300 dark:ring-gray-600 focus:ring-2 focus:ring-blue-600 sm:text-sm">
                        </div>

                        <div>
                            <label for="duration_hours" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Duration (Hours)</label>
                            <input type="number" name="duration_hours" id="duration_hours" value="{{ old('duration_hours', $course->duration_hours) }}" min="0"
                                class="mt-1 block w-full rounded-md py-2 pl-3 bg-white dark:bg-gray-700 text-gray-900 dark:text-white ring-1 ring-inset ring-gray-300 dark:ring-gray-600 focus:ring-2 focus:ring-blue-600 sm:text-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="level" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Level</label>
                            <select name="level" id="level"
                                class="mt-1 block w-full rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white py-2.5 pl-3 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 focus:ring-2 focus:ring-blue-600 sm:text-sm">
                                <option value="beginner" {{ old('level', $course->level) == 'beginner' ? 'selected' : '' }}>Beginner</option>
                                <option value="intermediate" {{ old('level', $course->level) == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                <option value="advanced" {{ old('level', $course->level) == 'advanced' ? 'selected' : '' }}>Advanced</option>
                            </select>
                        </div>

                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
                            <select name="category_id" id="category_id" required
                                class="mt-1 block w-full rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white ring-1 py-2.5 pl-3 ring-inset ring-gray-300 dark:ring-gray-600 focus:ring-2 focus:ring-blue-600 sm:text-sm">
                                <option value="">Select category</option>
                                @foreach (App\Models\CourseCategory::all() as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $course->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <input type="hidden" name="instructor_id" value="{{ auth()->id() }}">

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                        <textarea name="description" id="description" rows="4"
                            class="mt-1 block w-full rounded-md bg-white py-2 dark:bg-gray-700 text-gray-900 dark:text-white ring-1 ring-inset pl-3 ring-gray-300 dark:ring-gray-600 focus:ring-2 focus:ring-blue-600 sm:text-sm">{{ old('description', $course->description) }}</textarea>
                    </div>

                    <div>
                        <label for="short_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Short Description</label>
                        <textarea name="short_description" id="short_description" rows="3"
                            class="mt-1 block w-full rounded-md bg-white py-2 dark:bg-gray-700 text-gray-900 dark:text-white ring-1 ring-inset pl-3 ring-gray-300 dark:ring-gray-600 focus:ring-2 focus:ring-blue-600 sm:text-sm">{{ old('short_description', $course->short_description) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div class="flex items-center">
                            <input id="is_published" name="is_published" type="checkbox" value="1"
                                class="h-4 w-4 text-blue-600 border-gray-300 rounded cursor-pointer"
                                {{ old('is_published', $course->is_published) ? 'checked' : '' }}>
                            <label for="is_published" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Publish Course</label>
                        </div>

                        <div class="flex items-center">
                            <input id="is_featured" name="is_featured" type="checkbox" value="1"
                                class="h-4 w-4 text-blue-600 border-gray-300 rounded cursor-pointer"
                                {{ old('is_featured', $course->is_featured) ? 'checked' : '' }}>
                            <label for="is_featured" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Featured</label>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3 pt-4">
                        <a href="{{ route('pengajar.courses.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            Cancel
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors cursor-pointer">
                            <x-heroicon-o-pencil class="h-4 w-4 mr-2" />
                            Update Course
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-pengajar.layout>