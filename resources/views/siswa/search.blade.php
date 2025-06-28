<x-layout>
    <x-slot:title>Courses</x-slot:title>

    <div class="space-y-6" x-data="{
        showModal: false,
        selectedCourse: null,
        courses: @js($courses),
        openModal(courseId) {
            this.selectedCourse = this.courses[courseId];
            if (this.selectedCourse) {
                this.showModal = true;
                document.body.style.overflow = 'hidden';
            }
        },
        closeModal() {
            this.showModal = false;
            this.selectedCourse = null;
            document.body.style.overflow = 'auto';
        }
    }">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Search Courses</h2>

                <!-- Search form -->
                <form method="GET" action="{{ route('search') }}" class="mb-6">
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <x-heroicon-o-magnifying-glass class="h-5 w-5 text-gray-400 dark:text-gray-500" />
                        </div>
                        <input type="text" name="q" value="{{ request('q') }}"
                            placeholder="Search courses, topics, instructors..."
                            class="block w-full rounded-md border-0 py-3 pl-10 pr-3 text-gray-900 dark:text-white ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 bg-white dark:bg-gray-700">
                    </div>
                </form>

                <!-- Search filters -->
                <div class="flex flex-wrap gap-2 mb-6">
                    <a href="{{ route('search', ['q' => request('q')]) }}"
                        class="px-4 py-2 {{ !request('category') ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }} rounded-lg text-sm font-medium transition-colors">
                        All Course
                    </a>
                    @foreach ($categories as $cat)
                        <a href="{{ route('search', ['q' => request('q'), 'category' => $cat->id]) }}"
                            class="px-4 py-2 {{ request('category') == $cat->id ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }} rounded-lg text-sm font-medium transition-colors">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </div>

                <!-- Results count -->
                <div class="mb-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        @if (request('q'))
                            Found {{ count($courses) }} course(s) for "{{ request('q') }}"
                        @else
                            Showing all {{ count($courses) }} available courses
                        @endif
                    </p>
                </div>

                <!-- Course cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($courses as $id => $course)
                        @php
                            $hasThumbnail =
                                isset($course['videoUrl']) && Storage::disk('public')->exists($course['videoUrl']);
                            $cardGradient = $hasThumbnail ? null : $gradients[$loop->index % count($gradients)];
                        @endphp



                        <x-siswa.course-card :courseId="$id" :title="$course['title']" :description="$course['description']" :instructor="$course['instructor']"
                            :level="$course['level']" :rating="$course['rating']" :students="$course['students']" :thumbnail="$hasThumbnail ? asset('storage/' . $course['videoUrl']) : null" :gradient="$cardGradient" />


                    @empty
                        <div class="col-span-full text-center py-12">
                            <x-heroicon-o-magnifying-glass class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" />
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No courses found</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                @if (request('q'))
                                    Try adjusting your search terms or <a href="{{ route('search') }}"
                                        class="text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300">browse
                                        all courses</a>.
                                @else
                                    No courses are currently available.
                                @endif
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Modal -->
        <x-course-modal />
    </div>
</x-layout>
