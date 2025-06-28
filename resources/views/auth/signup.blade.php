<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 dark:bg-gray-900 min-h-screen flex items-center justify-center">

    <div class="w-full max-w-5xl bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden flex">

        {{-- Left side --}}
        <div class="w-1/2 bg-primary dark:bg-gray-700 text-white p-10 flex flex-col justify-center items-center">
            <x-heroicon-o-cube class="h-26 w-26 text-white mb-4" />
            <h2 class="text-3xl font-bold mb-4">Join Us!</h2>
            <p class="text-center text-lg">Create your student account</p>
        </div>

        {{-- Right side --}}
        <div class="w-1/2 p-10">
            <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200 mb-6">Register</h2>
            <form action="{{ route('register') }}" method="POST" class="space-y-4 text-sm">
                @csrf

                <div class="flex space-x-4">
                    <div class="w-1/2">
                        <label class="block text-gray-600 dark:text-gray-300 mb-1">First Name</label>
                        <input type="text" name="first_name" required
                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div class="w-1/2">
                        <label class="block text-gray-600 dark:text-gray-300 mb-1">Last Name</label>
                        <input type="text" name="last_name" required
                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                </div>

                <div>
                    <label class="block text-gray-600 dark:text-gray-300 mb-1">Email</label>
                    <input type="email" name="email" required
                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                <div>
                    <label class="block text-gray-600 dark:text-gray-300 mb-1">Password</label>
                    <input type="password" name="password" required
                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                <div>
                    <label class="block text-gray-600 dark:text-gray-300 mb-1">Confirm Password</label>
                    <input type="password" name="password_confirmation" required
                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                <button type="submit"
                    class="w-full bg-primary text-white py-2 rounded hover:bg-primary-hover transition cursor-pointer">
                    Register
                </button>
            </form>

            <p class="mt-6 text-sm text-center text-gray-600 dark:text-gray-400">
                Already have an account?
                <a href="{{ route('login') }}" class="text-primary hover:underline dark:text-primary-hover">Login</a>
            </p>
        </div>
    </div>

</body>

</html>
