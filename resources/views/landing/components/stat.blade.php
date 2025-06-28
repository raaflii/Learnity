<section id="stats-section"
    class="py-16 bg-gradient-to-br from-white to-gray-150 dark:from-gray-900 dark:to-gray-800 relative overflow-hidden">
    <div class="absolute top-10 left-100 w-64 h-64 rounded-full bg-purple-100 dark:bg-purple-900 opacity-60 blur-3xl">
    </div>
    <div
        class="absolute bottom-10 right-100 w-64 h-64 rounded-full bg-purple-100 dark:bg-purple-900 opacity-60 blur-3xl">
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div class="animate-fade-in-up fade-hidden stat-target">
                <div class="text-4xl font-bold text-primary mb-2 stat-number">0+</div>
                <div class="text-gray-600 dark:text-gray-400">Courses Available</div>
            </div>
            <div class="animate-fade-in-up fade-hidden stat-target">
                <div class="text-4xl font-bold text-primary mb-2 stat-number">0+</div>
                <div class="text-gray-600 dark:text-gray-400">Students Enrolled</div>
            </div>
            <div class="animate-fade-in-up fade-hidden stat-target">
                <div class="text-4xl font-bold text-primary mb-2 stat-number">0%</div>
                <div class="text-gray-600 dark:text-gray-400">Success Rate</div>
            </div>
            <div class="animate-fade-in-up fade-hidden stat-target">
                <div class="text-4xl font-bold text-primary mb-2 stat-number">0/7</div>
                <div class="text-gray-600 dark:text-gray-400">Support Available</div>
            </div>
        </div>
    </div>
</section>

<script>
    const initStats = {
        courses: 0,
        students: 0,
        success: 0,
        support: 0
    };

    const targetStats = {
        courses: 250,
        students: 50000,
        success: 95,
        support: 24
    };

    const duration = 2000;
    const steps = 60;
    const stepDuration = duration / steps;
    let currentStep = 0;

    const statElements = document.querySelectorAll(".stat-number");

    function animateStats() {
        let currentStep = 0;

        const timer = setInterval(() => {
            currentStep++;
            const progress = currentStep / steps;

            const currentStats = {
                courses: Math.round(initStats.courses + (targetStats.courses - initStats.courses) *
                    progress),
                students: Math.round(initStats.students + (targetStats.students - initStats.students) *
                    progress),
                success: Math.round(initStats.success + (targetStats.success - initStats.success) *
                    progress),
                support: Math.round(initStats.support + (targetStats.support - initStats.support) *
                    progress)
            };

            statElements[0].innerText = `${currentStats.courses}+`;
            statElements[1].innerText = `${currentStats.students}+`;
            statElements[2].innerText = `${currentStats.success}%`;
            statElements[3].innerText = `${currentStats.support}/7`;

            if (currentStep >= steps) {
                clearInterval(timer);
            }
        }, stepDuration);
    }

    const targets = document.querySelectorAll('.stat-target');

    targets.forEach((val) => {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    val.classList.remove('animate-fade-in-up');
                    animateStats();
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
