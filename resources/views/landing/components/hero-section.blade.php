<section class="animate-fade-in-up py-20 bg-gradient-to-br from-purple-50 to-blue-50 dark:from-gray-900 dark:to-gray-800"
    id="hero-up">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 items-center">

            <div class="animate-fade-in-left" id="hero-left">
                <h1 class="text-4xl md:text-5xl font-bold mb-6 leading-tight dark:text-white text-gray-900">
                    Grow Your
                    <span class="text-primary dark:text-purple-400"> Education</span> Level up
                    with Our Online Course
                </h1>
                <p class="text-lg md:text-xl mb-8 dark:text-gray-300 text-gray-600">
                    Enhance your skills with our comprehensive online courses designed by industry experts.
                </p>
                <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                    <button
                        class="bg-primary hover:bg-primary-hover text-white px-6 py-3 rounded-full font-medium transition-colors text-sm cursor-pointer">
                        <a href="{{ route('login') }}">Get Started</a>
                    </button>
                    <button
                        class="flex items-center space-x-2 border border-gray-300 dark:border-gray-700 rounded-full px-6 py-3 font-medium transition-colors dark:text-gray-300 dark:hover:bg-gray-800 hover:bg-gray-100 text-sm cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"
                                clip-rule="evenodd" />
                        </svg>
                        <a href="{{ route('login') }}"><span>Watch Demo</span></a>
                    </button>
                </div>
            </div>

            <div class="animate-fade-in-right" id="hero-right">
                <div class="relative">
                    <img src="images/person.png" class="w-80 h-auto ml-20 relative top-2">
                    <div
                        class="rounded-2xl shadow-2xl p-8 transform rotate-3 hover:rotate-0 transition-transform duration-300 dark:bg-gray-800 bg-white cursor-pointer">
                        <div class="bg-primary text-white p-4 rounded-lg mb-4">
                            <h3 class="font-bold text-lg">Featured Course</h3>
                            <p class="text-purple-100">Web Development Bootcamp</p>
                        </div>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="dark:text-gray-300 text-gray-600">Progress</span>
                                <span class="text-primary font-semibold">75%</span>
                            </div>
                            <div class="w-full rounded-full h-2 dark:bg-gray-700 bg-gray-200">
                                <div class="bg-primary h-2 rounded-full w-3/4"></div>
                            </div>
                            <div
                                class="flex flex-col sm:flex-row sm:items-center sm:space-x-4 text-sm dark:text-gray-400 text-gray-500 mt-2">
                                <span class="flex items-center mb-2 sm:mb-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    40 hours
                                </span>
                                <span class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-1">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                                    </svg>

                                    1,234 students
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="absolute top-100 -right-4 w-16 h-16 rounded-full bg-purple-500 opacity-20"></div>
                    <div class="absolute -bottom-4 -left-4 w-12 h-12 rounded-full bg-blue-500 opacity-20"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function restartAnimation(el, animationClass) {
        el.classList.remove(animationClass);
        void el.offsetWidth;
        el.classList.add(animationClass);
    }

    document.addEventListener("DOMContentLoaded", function() {
        const elements = [{
                id: "hero-left",
                animation: "animate-fade-in-left"
            },
            {
                id: "hero-right",
                animation: "animate-fade-in-right"
            },
            {
                id: "hero-up",
                animation: "animate-fade-in-up"
            }
        ];

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const el = entry.target;
                    const animationClass = el.dataset.animation;
                    restartAnimation(el, animationClass);
                }
            });
        }, {
            threshold: 0.3
        });

        elements.forEach(({
            id,
            animation
        }) => {
            const el = document.getElementById(id);
            el.dataset.animation = animation;
            observer.observe(el);
        });
    });
</script>
