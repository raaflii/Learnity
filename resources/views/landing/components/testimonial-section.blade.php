<section
    class="bg-gradient-to-br from-purple-50 to-blue-50 dark:from-gray-900 dark:to-gray-800 p-8 h-170 animate-fade-in-up"
    id="testimonial-target">
    <div class="w-full">
        <h2 class="text-3xl font-bold text-center my-15 text-gray-800 dark:text-white">
            <span class="relative inline-block">
                What Our Students Say
                <span
                    class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 w-16 h-1 bg-gradient-to-r from-primary to-primary-hover rounded-full"></span>
            </span>
        </h2>
        <div class="overflow-hidden">
            <div class="flex animate-scroll-left w-full">
                <x-testimonial-card></x-testimonial-card>
                <x-testimonial-card></x-testimonial-card>
                <x-testimonial-card></x-testimonial-card>
                <x-testimonial-card></x-testimonial-card>
                <x-testimonial-card></x-testimonial-card>
                <x-testimonial-card></x-testimonial-card>
            </div>
        </div>
    </div>
</section>

<script>
    const targetTestimonial = document.getElementById('testimonial-target');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const el = entry.target;
                el.classList.remove('animate-fade-in-up');
                void el.offsetWidth; 
                el.classList.add('animate-fade-in-up');
            }
        });
    }, {
        threshold: 0.3
    });

    observer.observe(targetTestimonial);
</script>
