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
<x-pengajar.sidebar></x-pengajar.sidebar>

@if(isset($title))
  <x-pengajar.header>{{ $title }}</x-pengajar.header>
@endif

<main class="transition-all duration-300 lg:pl-20">
  <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
    {{ $slot }}
  </div>
</main>
</div>
</body>
</html>
