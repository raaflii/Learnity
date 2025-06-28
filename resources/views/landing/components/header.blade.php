<header
    class="purple-bottom-shadow border-b bg-white dark:bg-gray-900 border-gray-100 dark:border-gray-800 sticky top-0 z-40 py-1.5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center">
                <div class="text-2xl font-bold text-primary">
                    <a href="{{ route('landing-page') }}">Learnity</a>
                </div>
            </div>
            <nav class="hidden md:flex space-x-8">
                <a href="#home"
                    class="text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary-hover transition-colors text-[15px] font-[500]">Home</a>
                <a href="#course"
                    class="text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary-hover transition-colors text-[15px] font-[500]">Courses</a>
                <a href="#review"
                    class="text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary-hover transition-colors text-[15px] font-[500]">Review</a>
                <a href="#about"
                    class="text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary-hover transition-colors text-[15px] font-[500]">About</a>
            </nav>
            <div class="flex items-center space-x-4">
                <button id="darkModeToggle"
                    class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors cursor-pointer relative">
                    <div class="relative w-5 h-5">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 text-gray-600 dark:text-gray-300 absolute top-0 left-0 transition-opacity duration-500 opacity-100 dark:opacity-0"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            class="h-5 w-5 text-gray-600 dark:text-gray-300 absolute top-0 left-0 transition-opacity duration-500 opacity-0 dark:opacity-100"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                        </svg>
                    </div>
                </button>
                <button
                    class="border-none text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 px-4 py-2 text-xs rounded-lg transition-colors cursor-pointer">
                    <a href="{{ route('login') }}">Login</a>
                </button>
                <button
                    class="bg-primary hover:bg-primary-hover text-white px-4 py-2 text-xs rounded-lg transition-colors cursor-pointer">
                    <a href="{{ route('register') }}">Sign Up</a>
                </button>
            </div>
        </div>
    </div>
</header>


<script>
    const toggle = document.getElementById('darkModeToggle');
    const html = document.documentElement;

    toggle.addEventListener('click', () => {
        const isDark = html.classList.toggle('dark');
        localStorage.setItem('darkMode', isDark);
    });

    window.addEventListener('DOMContentLoaded', () => {
        const savedDarkMode = localStorage.getItem('darkMode');
        if (savedDarkMode === 'true') {
            html.classList.add('dark');
        } else {
            html.classList.remove('dark');
        }
    });
</script>
