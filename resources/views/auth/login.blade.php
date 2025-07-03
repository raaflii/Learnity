<!DOCTYPE html>
<html lang="en" class="dark"> 
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  @vite('resources/css/app.css')
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-100 dark:bg-gray-900">

  <div class="w-full max-w-5xl bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden flex">
    
    {{-- Left side: Logo --}}
    <div class="w-1/2 bg-primary dark:bg-gray-700 text-white p-10 flex flex-col justify-center items-center">
      <x-heroicon-o-cube class="h-26 w-26 text-white mb-4" />
      <h2 class="text-3xl font-bold mb-4">Welcome Back!</h2>
      <p class="text-center text-lg">Sign in to access your dashboard</p>
    </div>

    {{-- Right side: Login Form --}}
    <div class="w-1/2 p-10">
      <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200 mb-6">Login</h2>
      
      {{-- Error Messages --}}
      @if ($errors->any())
        <div class="mb-4 p-3 bg-red-100 dark:bg-red-900 border border-red-300 dark:border-red-700 rounded-md">
          <ul class="text-sm text-red-700 dark:text-red-300">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="/login" method="POST" class="space-y-4">
        @csrf
        <div>
          <label class="block text-gray-600 dark:text-gray-300 mb-1">Email</label>
          <input type="email" name="email" value="{{ old('email') }}" required
                 class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('email') border-red-500 @enderror">
          @error('email')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>
        <div>
          <label class="block text-gray-600 dark:text-gray-300 mb-1">Password</label>
          <input type="password" name="password" required
                 class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('password') border-red-500 @enderror">
          @error('password')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>
        <div class="flex justify-between items-center">
          <label class="flex items-center text-gray-600 dark:text-gray-300">
            <input type="checkbox" name="remember" class="mr-2 cursor-pointer">
            <span class="text-sm">Remember me</span>
          </label>
        </div>
        <button type="submit"
                class="w-full bg-primary dark:bg-primary-hover text-white py-2 rounded hover:bg-primary-hover transition cursor-pointer">
          Login
        </button>
      </form>
      <p class="mt-6 text-sm text-center text-gray-600 dark:text-gray-400">
        Don't have an account?
        <a href="{{ route('register') }}" class="text-primary dark:text-primary-hover hover:underline">Sign up</a>
      </p>
    </div>
  </div>

</body>
</html>