<button id="darkModeToggle"
    class="inline-flex items-center justify-center rounded-full p-2 transition-colors duration-300 hover:bg-gray-100 dark:hover:bg-gray-800 cursor-pointer">

    <svg class="h-5 w-5 dark:hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
    </svg>

    <svg class="h-5 w-5 hidden dark:block text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
    </svg>
</button>

<script>
    const toggle = document.getElementById('darkModeToggle');
    const html = document.documentElement;

    toggle.addEventListener('click', () => {
        html.classList.toggle('dark');
        localStorage.setItem('darkMode', html.classList.contains('dark'));
    });

    // Inisialisasi mode gelap
    if (localStorage.getItem('darkMode') === 'true') {
        html.classList.add('dark');
    }
</script>
