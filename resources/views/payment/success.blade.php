<x-layout>
    <x-slot:title>Payment Success</x-slot:title>

    <div class="flex justify-center mt-20">
        <div class="bg-white shadow-lg rounded-lg p-8 max-w-xl text-center">
            <x-heroicon-o-check-circle class="w-16 h-16 text-green-500 mx-auto mb-4" />
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Payment Successful!</h2>
            <p class="text-gray-600 mb-4">
                Thank you for your payment. You are now enrolled in <span class="font-semibold text-blue-600">{{ session('course_title') ?? 'the course' }}</span>.
            </p>
            <a href="{{ route('dashboard') }}"
                class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                Go to Dashboard
            </a>
        </div>
    </div>
</x-layout>
