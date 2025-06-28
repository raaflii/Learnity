<section class="py-20 bg-gradient-to-br from-purple-50 to-blue-50 dark:from-gray-900 dark:to-gray-800 ">
    <div class="max-w-6xl mx-auto .fade-hidden featured-target">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4 transition-colors duration-300">Explore
                Featured
                Course</h1>
            <p class="text-gray-600 dark:text-gray-400 max-w-2xl mx-auto transition-colors duration-300">
                In a series of studies, Mayer and his colleagues tested Paivio's dual-coding
                theory, with multimedia lesson materials. They repeatedly found that
            </p>
        </div>

        <div class="flex flex-wrap justify-center gap-6 mb-12 .fade-hidden featured-target">
            <button
                class="px-6 py-2 text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-primary hover:border-b-2 hover:border-primary transition-all duration-300 border-b-2 border-transparent text-sm cursor-pointer">UI/UX
                Design</button>
            <button
                class="px-6 py-2 text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-primary hover:border-b-2 hover:border-primary transition-all duration-300 border-b-2 border-transparent text-sm cursor-pointer">Illustration</button>
            <button
                class="px-6 py-2 text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-primary hover:border-b-2 hover:border-primary transition-all duration-300 border-b-2 border-transparent text-sm cursor-pointer">Web
                Development</button>
            <button
                class="px-6 py-2 text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-primary hover:border-b-2 hover:border-primary transition-all duration-300 border-b-2 border-transparent text-sm cursor-pointer">Mobile
                Development</button>
            <button
                class="px-6 py-2 text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-primary hover:border-b-2 hover:border-primary transition-all duration-300 border-b-2 border-transparent text-sm cursor-pointer">Photography</button>
            <button
                class="px-6 py-2 text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-primary hover:border-b-2 hover:border-primary transition-all duration-300 border-b-2 border-transparent text-sm cursor-pointer">Marketing</button>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
            <x-course-card style="animation-delay: 0.1s;"></x-course-card>
            <x-course-card style="animation-delay: 0.3s;"></x-course-card>
            <x-course-card style="animation-delay: 0.5s;"></x-course-card>
        </div>
    </div>
</section>

<script>
    const targetFeatured = document.querySelectorAll('.featured-target');

    targetFeatured.forEach((val) => {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    val.classList.remove('animate-fade-in-up');
                    void val.offsetWidth;
                    val.classList.add('animate-fade-in-up');
                }
            });
        }, {
            threshold: 0.3
        });

        observer.observe(val);
    });
</script>
