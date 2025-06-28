@props(['title'])

<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <title>{{ $title}}</title>
  
  <!-- Theme initialization -->
  <script>
    if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
      document.documentElement.classList.add('dark');
    }
  </script>
  
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="h-full bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 theme-transition">
<div class="min-h-full">
<x-sidebar></x-sidebar>

@if(isset($title))
  <x-header>{{ $title }}</x-header>
@endif

<!-- Success/Error Messages -->
@if (session('success'))
    <div class="transition-all duration-300 lg:pl-20">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-300 px-4 py-3 rounded relative mt-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
      </div>
    </div>
@endif

@if (session('error'))
    <div class="transition-all duration-300 lg:pl-20">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-300 px-4 py-3 rounded relative mt-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
      </div>
    </div>
@endif

@if (session('password_success'))
    <div class="transition-all duration-300 lg:pl-20">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-300 px-4 py-3 rounded relative mt-4" role="alert">
            <span class="block sm:inline">{{ session('password_success') }}</span>
        </div>
      </div>
    </div>
@endif

<main class="transition-all duration-300 lg:pl-20">
  <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
    {{ $slot }}
  </div>
</main>
</div>
</body>
</html>
