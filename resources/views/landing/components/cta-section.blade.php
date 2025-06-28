<section class="py-20 bg-gradient-to-r from-primary-hover to-primary" id="cta-target">
    <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8 animate-fade-in-up">
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">
            Are you ready to start your learning journey?
        </h2>
        <p class="text-xl text-purple-100 mb-8">
            Join thousands of students who have already transformed their careers with our courses.
        </p>
        <button
            class="bg-white text-primary hover:bg-gray-200 px-6 py-3 rounded-lg font-medium transition-colors cursor-pointer text-base">
            <a href="{{ route('login') }}">Start Learning Today</a>
        </button>
    </div>
</section>

<script>
    const targetCTA = document.getElementById('cta-target');

    const observerCTA = new IntersectionObserver((entries) => {
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

    observerCTA.observe(targetCTA);
</script>
