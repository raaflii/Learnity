<x-layout>
    <x-slot:title>{{ $course->title }}</x-slot:title>

    <div class="space-y-6">
        <!-- Course Header -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="relative">
                <!-- Course Banner -->
                @if (!$enrollment)
                    <div class="h-64 bg-gray-900 flex items-center justify-center">
                        <div class="text-center">
                            <button onclick="window.open('{{ $course->video_url }}', '_blank')"
                                class="bg-white bg-opacity-20 hover:bg-opacity-30 rounded-full p-6 transition-colors mb-4">
                                <x-heroicon-s-play class="h-16 w-16 text-white" />
                            </button>
                            <p class="text-white text-lg font-medium">Watch Course Preview</p>
                        </div>
                    </div>
                @endif

                <!-- Course Info -->
                <div class="px-6 py-8">
                    <div class="flex items-center gap-4 mb-4">
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $course->level === 'advanced' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' : ($course->level === 'intermediate' ? 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300' : 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300') }}">
                            {{ ucfirst($course->level) }}
                        </span>
                        <div class="flex items-center">
                            <x-heroicon-s-star class="h-5 w-5 text-yellow-400" />
                            <span class="text-sm font-medium ml-1">{{ $course->average_rating }}</span>
                            <span
                                class="text-sm text-gray-500 dark:text-gray-400 ml-2">({{ number_format($course->enrollments->count()) }}
                                students)</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                            <x-heroicon-o-clock class="h-4 w-4 mr-1" />
                            {{ $course->duration_hours }} hours
                        </div>
                    </div>

                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">{{ $course->title }}</h1>
                    <p class="text-lg text-gray-600 dark:text-gray-400 mb-6">{{ $course->description }}</p>

                    <!-- Instructor Info -->
                    <div class="flex items-center mb-6">
                        <img class="h-12 w-12 rounded-full"
                            src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                            alt="Instructor">
                        <div class="ml-4">
                            <p class="text-lg font-medium text-gray-900 dark:text-white">
                                {{ $course->instructor->full_name }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Expert Instructor</p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    @if (!$enrollment)
                        <div class="flex gap-4">
                            <a href="{{ $course->video_url }}" target="_blank"
                                class="border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 px-8 py-3 rounded-lg font-semibold hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors flex items-center">
                                <x-heroicon-o-play class="h-5 w-5 mr-2" />
                                Preview Course
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Course Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="{{ $enrollment ? 'lg:col-span-3' : 'lg:col-span-2' }} space-y-6">
                <!-- What You'll Learn -->
                @if (!$enrollment)
                    <!-- What You'll Learn -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                        <div class="px-6 py-5">
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">What you'll learn</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach ($course->topics as $topic)
                                    <div class="flex items-center">
                                        <x-heroicon-o-check-circle class="h-5 w-5 text-green-500 mr-3" />
                                        <span class="text-gray-700 dark:text-gray-300">{{ $topic->title }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Course Lessons -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                    <div class="px-6 py-5">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Course Content</h2>
                        <div class="space-y-3 {{ !$enrollment ? 'max-h-[600px] overflow-y-auto pr-2' : '' }}">
                            @php
                                $videoCount = 0;
                                $videoLimit = 2;
                            @endphp

                            @foreach ($course->topics->sortBy('order_index') as $topic)
                                <div class="mb-4">
                                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">
                                        {{ $topic->title }}
                                    </h3>

                                    @foreach ($topic->videos->sortBy('order_index') as $video)
                                        @php
                                            $videoCount++;
                                            $isLocked = !$enrollment && $videoCount > $videoLimit;
                                        @endphp

                                        <div
                                            class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 mb-2 {{ $isLocked ? 'bg-gray-100 dark:bg-gray-700 opacity-70' : 'hover:bg-gray-50 dark:hover:bg-gray-700' }} transition-colors">
                                            <div class="flex items-center justify-between group">
                                                <div class="flex items-center space-x-4">
                                                    <div class="flex-shrink-0">
                                                        <div
                                                            class="w-10 h-10 {{ $isLocked ? 'bg-gray-200 dark:bg-gray-600' : 'bg-blue-100 dark:bg-blue-900/30' }} rounded-full flex items-center justify-center {{ !$isLocked ? 'group-hover:bg-blue-200 dark:group-hover:bg-blue-900/50' : '' }} transition-colors">
                                                            @if ($isLocked)
                                                                <x-heroicon-o-lock-closed
                                                                    class="h-5 w-5 text-gray-400 dark:text-gray-500" />
                                                            @else
                                                                <x-heroicon-o-play class="h-5 w-5 text-blue-600" />
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <p
                                                            class="text-base font-medium {{ $isLocked ? 'text-gray-500 dark:text-gray-400' : 'text-gray-900 dark:text-white group-hover:text-blue-700 dark:group-hover:text-blue-300' }} transition-colors">
                                                            {{ $video->order_index }}. {{ $video->title }}
                                                        </p>
                                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                            Duration: {{ gmdate('H:i:s', $video->duration_seconds) }}
                                                        </p>
                                                    </div>
                                                </div>

                                                @if (!$isLocked)
                                                    <a href="{{ route('courses.lessons.show', [$course->id, $video->id]) }}"
                                                        class="text-sm font-medium text-blue-600 hover:underline dark:text-blue-400">Watch</a>
                                                @else
                                                    <span
                                                        class="text-xs text-gray-400 dark:text-gray-500 italic">Locked</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Course Reviews -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                    <div class="px-6 py-5">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Student Reviews</h2>
                            <div class="flex items-center space-x-2">
                                <div class="flex items-center">
                                    <x-heroicon-s-star class="h-5 w-5 text-yellow-400" />
                                    <span class="text-lg font-semibold ml-1">{{ $course->average_rating }}</span>
                                </div>
                                <span class="text-sm text-gray-500 dark:text-gray-400">({{ $course->reviews->count() }}
                                    reviews)</span>
                            </div>
                        </div>

                        <!-- Add Review Form for Enrolled Users -->
                        @if ($enrollment && !$course->reviews->where('user_id', auth()->id())->first())
                            <div
                                class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-200 dark:border-blue-700 rounded-lg p-6 mb-6">
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0">
                                        <div
                                            class="h-12 w-12 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                            <x-heroicon-o-star class="h-6 w-6 text-blue-600" />
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Share Your
                                            Experience</h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Help other students by
                                            sharing your
                                            thoughts about this course</p>

                                        <form action="{{ route('courses.reviews.store', $course->id) }}" method="POST"
                                            class="space-y-4">
                                            @csrf
                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Your
                                                    Rating</label>
                                                <div class="flex items-center space-x-2">
                                                    <div class="flex items-center space-x-1" id="star-rating">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            <button type="button" class="star-btn focus:outline-none"
                                                                data-rating="{{ $i }}">
                                                                <x-heroicon-s-star
                                                                    class="h-8 w-8 text-gray-300 hover:text-yellow-400 transition-colors cursor-pointer" />
                                                            </button>
                                                        @endfor
                                                    </div>
                                                    <span id="rating-text"
                                                        class="text-sm text-gray-600 dark:text-gray-400 ml-3">Click to
                                                        rate</span>
                                                </div>
                                                <input type="hidden" name="rating" id="rating-input" value=""
                                                    required>
                                            </div>

                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Your
                                                    Review
                                                    (Optional)</label>
                                                <textarea name="review_text" rows="4"
                                                    placeholder="Tell us about your experience with this course. What did you like? What could be improved?"
                                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"></textarea>
                                            </div>

                                            <div class="flex items-center justify-between">
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Your review will be
                                                    visible to other
                                                    students</p>
                                                <button type="submit"
                                                    class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                                    <x-heroicon-s-star class="h-4 w-4 mr-2" />
                                                    Submit Review
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($course->reviews->isEmpty())
                            <div class="text-center py-8">
                                <x-heroicon-o-chat-bubble-left-ellipsis
                                    class="h-12 w-12 text-gray-300 dark:text-gray-600 mx-auto mb-3" />
                                <p class="text-gray-500 dark:text-gray-400 text-lg">No reviews yet</p>
                                <p class="text-gray-400 dark:text-gray-500 text-sm">Be the first to review this course!
                                </p>
                            </div>
                        @else
                            <div class="space-y-6">
                                @foreach ($course->reviews as $review)
                                    <div
                                        class="bg-gray-50 dark:bg-gray-700 rounded-lg p-5 hover:shadow-sm transition-shadow">
                                        <div class="flex items-start space-x-4">
                                            <!-- Avatar -->
                                            <div class="flex-shrink-0">
                                                <div
                                                    class="h-12 w-12 rounded-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
                                                    <span class="text-white font-semibold text-lg">
                                                        {{ strtoupper(substr($review->user->first_name, 0, 1)) }}{{ strtoupper(substr($review->user->last_name, 0, 1)) }}
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Review Content -->
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center justify-between mb-2">
                                                    <div>
                                                        <h4
                                                            class="text-base font-semibold text-gray-900 dark:text-white">
                                                            {{ $review->user->first_name }}
                                                            {{ $review->user->last_name }}
                                                        </h4>
                                                        <div class="flex items-center space-x-1 mt-1">
                                                            @for ($i = 0; $i < 5; $i++)
                                                                @if ($i < $review->rating)
                                                                    <x-heroicon-s-star
                                                                        class="h-4 w-4 text-yellow-400" />
                                                                @else
                                                                    <x-heroicon-s-star class="h-4 w-4 text-gray-300" />
                                                                @endif
                                                            @endfor
                                                            <span
                                                                class="text-sm font-medium text-gray-700 dark:text-gray-300 ml-2">{{ $review->rating }}/5</span>
                                                        </div>
                                                    </div>
                                                    <div class="text-right">
                                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                                            {{ $review->created_at->diffForHumans() }}
                                                        </span>
                                                    </div>
                                                </div>

                                                @if ($review->review_text)
                                                    <div class="mt-3">
                                                        <blockquote
                                                            class="text-gray-700 dark:text-gray-300 italic border-l-4 border-blue-200 dark:border-blue-700 pl-4">
                                                            "{{ $review->review_text }}"
                                                        </blockquote>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Show More Reviews Button (if needed) -->
                            @if ($course->reviews->count() > 3)
                                <div class="text-center mt-6">
                                    <button
                                        class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 font-medium text-sm hover:underline">
                                        Show all {{ $course->reviews->count() }} reviews
                                    </button>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                @if (!$enrollment)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg top-6">
                        <div class="px-6 py-5">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Course Details</h3>

                            <div class="space-y-4">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Duration:</span>
                                    <span
                                        class="font-medium text-gray-900 dark:text-white">{{ $course->duration_hours }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Level:</span>
                                    <span
                                        class="font-medium text-gray-900 dark:text-white">{{ ucfirst($course->level) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Students:</span>
                                    <span
                                        class="font-medium text-gray-900 dark:text-white">{{ number_format($course->students) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Rating:</span>
                                    <div class="flex items-center">
                                        <x-heroicon-s-star class="h-4 w-4 text-yellow-400" />
                                        <span
                                            class="font-medium ml-1 text-gray-900 dark:text-white">{{ $course->average_rating }}</span>
                                    </div>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Lessons:</span>
                                    <span
                                        class="font-medium text-gray-900 dark:text-white">{{ $course->lessons->count() }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Price:</span>
                                    <span class="font-medium text-gray-900 dark:text-white">
                                        @if ($course->price == 0.0)
                                            <span class="text-green-600">Free</span>
                                        @else
                                            Rp{{ number_format($course->price, 2) }}
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <hr class="my-6 border-gray-200 dark:border-gray-700">

                            <div class="space-y-3">
                                <button id="pay-button" type="button" data-course-id="{{ $course->id }}"
                                    class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 transition-colors cursor-pointer">
                                    Enroll Now
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Back Button -->
            <div class="flex justify-start">
                <a href="{{ route('courses') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <x-heroicon-o-arrow-left class="h-4 w-4 mr-2" />
                    Back to Courses
                </a>
            </div>
        </div>
    </div>
</x-layout>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
</script>

<!-- JQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        $('#pay-button').click(function() {
            var courseId = $(this).data('course-id');
            var button = $(this);

            // Disable button
            button.prop('disabled', true).text('Processing...');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '{{ route('payment.create') }}',
                method: 'POST',
                data: {
                    course_id: courseId
                },
                success: function(response) {
                    // Enable button back
                    button.prop('disabled', false).text('Enroll Now');

                    // Open Midtrans popup
                    snap.pay(response.snap_token, {
                        onPending: function(result) {
                            alert(
                                'Payment pending. Please complete your payment.'
                            );
                        },
                        onError: function(result) {
                            alert('Payment failed. Please try again.');
                        },
                        onClose: function() {
                            alert('You closed the payment popup.');
                        }
                    });

                },
                error: function(xhr) {
                    button.prop('disabled', false).text('Enroll Now');
                    alert('Error: ' + xhr.responseJSON.error);
                }
            });
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const starButtons = document.querySelectorAll('.star-btn');
        const ratingInput = document.getElementById('rating-input');
        const ratingText = document.getElementById('rating-text');

        const ratingTexts = {
            1: 'Terrible',
            2: 'Poor',
            3: 'Average',
            4: 'Good',
            5: 'Excellent'
        };

        starButtons.forEach((button, index) => {
            const rating = parseInt(button.dataset.rating);

            // Handle click
            button.addEventListener('click', function() {
                ratingInput.value = rating;
                updateStars(rating);
                ratingText.textContent = ratingTexts[rating];
            });

            // Handle hover
            button.addEventListener('mouseenter', function() {
                highlightStars(rating);
            });

            // Handle mouse leave
            button.addEventListener('mouseleave', function() {
                const currentRating = parseInt(ratingInput.value) || 0;
                updateStars(currentRating);
            });
        });

        function updateStars(rating) {
            starButtons.forEach((button, index) => {
                const star = button.querySelector('svg');
                const buttonRating = parseInt(button.dataset.rating);

                if (buttonRating <= rating) {
                    star.classList.remove('text-gray-300');
                    star.classList.add('text-yellow-400');
                } else {
                    star.classList.remove('text-yellow-400');
                    star.classList.add('text-gray-300');
                }
            });
        }

        function highlightStars(rating) {
            starButtons.forEach((button, index) => {
                const star = button.querySelector('svg');
                const buttonRating = parseInt(button.dataset.rating);

                if (buttonRating <= rating) {
                    star.classList.remove('text-gray-300');
                    star.classList.add('text-yellow-400');
                } else {
                    star.classList.remove('text-yellow-400');
                    star.classList.add('text-gray-300');
                }
            });
        }
    });
</script>
