<section
    class="py-20 bg-gradient-to-br from-white to-gray-150 dark:from-gray-900 dark:to-gray-800 relative overflow-hidden">
    <div class="absolute top-20 left-100 w-64 h-64 rounded-full bg-purple-100 dark:bg-purple-900 opacity-70 blur-3xl">
    </div>
    <div class="absolute bottom-20 right-180 w-64 h-64 rounded-full bg-purple-100 dark:bg-primary opacity-80 blur-3xl">
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md mx-auto text-center animate-fade-in-up" id="quality-up">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">
                High quality online course at the best class
            </h2>
            <p class="text-gray-300 mb-6 text-xs">Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                Nobis illum in, iste totam labore accusantium error
                incidunt.
            </p>
        </div>
        <div class="grid lg:grid-cols-2 gap-12 items-center">

            <div class="animate-fade-in-left z-10 w-170 h-auto" id="quality-left">
                <img src="images/person2.png">
            </div>

            <div class="animate-fade-in-right mb-60" id="quality-right">
                <div class="space-y-6">

                    <x-feature-card></x-feature-card>

                    <div class="flex items-start space-x-4">
                        <div class="bg-purple-100 p-3 rounded-full dark:bg-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary dark:text-white"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg mb-2 dark:text-white">Comprehensive Curriculum</h3>
                            <p class="text-gray-600 dark:text-gray-300">Well-structured courses covering all
                                essential topics</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4">
                        <div class="bg-purple-100 p-3 rounded-full dark:bg-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary dark:text-white"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg mb-2 dark:text-white">Community Support</h3>
                            <p class="text-gray-600 dark:text-gray-300">Join a community of learners and get help
                                when needed</p>
                        </div>
                    </div>

                    <x-feature-card></x-feature-card>
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
                id: "quality-left",
                animation: "animate-fade-in-left"
            },
            {
                id: "quality-right",
                animation: "animate-fade-in-right"
            },
            {
                id: "quality-up",
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
