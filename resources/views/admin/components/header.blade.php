<div class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 shadow-sm sm:gap-x-6 sm:px-6 transition-all duration-300 lg:pl-28">

    <!-- Separator -->
    <div class="h-6 w-px bg-gray-200 dark:bg-gray-700 lg:hidden" aria-hidden="true"></div>

    <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
        <div class="relative flex flex-1 items-center">
            <h1 class="text-xl font-bold leading-6 text-primary dark:text-white">{{ $slot }}</h1>
        </div>
        <div class="flex items-center gap-x-4 lg:gap-x-6">

            <!-- Simple Theme Toggle -->
            <x-admin.theme-toggle />
            
            <!-- Separator -->
            <div class="hidden lg:block lg:h-6 lg:w-px lg:bg-gray-200 dark:lg:bg-gray-700" aria-hidden="true"></div>

            <!-- Profile dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button type="button" class="-m-1.5 flex items-center p-1.5 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-colors duration-200 cursor-pointer" @click="open = !open"
                    @click.away="open = false">
                    <span class="sr-only">Open user menu</span>
                    @php
                        use Illuminate\Support\Str;

                        $getAvatarColor = function ($name) {
                            $colors = [
                                '1abc9c',
                                '3498db',
                                '9b59b6',
                                'f39c12',
                                'e67e22',
                                'e74c3c',
                                '2ecc71',
                                '16a085',
                                '2980b9',
                                '8e44ad',
                            ];
                            $hash = crc32($name);
                            return $colors[$hash % count($colors)];
                        };

                        $user = auth()->user() ?? \App\Models\User::find(9);
                        $name = trim($user->first_name . ' ' . $user->last_name);
                        $color = $getAvatarColor($name);

                        $avatar = $user->avatar
                            ? (Str::startsWith($user->avatar, 'avatars/')
                                ? asset('storage/' . $user->avatar)
                                : asset($user->avatar))
                            : 'https://ui-avatars.com/api/?name=' .
                                urlencode($name) .
                                '&background=' .
                                $color .
                                '&color=fff&bold=true';
                    @endphp
                    <img class="h-8 w-8 rounded-full bg-gray-50 dark:bg-gray-600" src="{{ $avatar }}" alt="{{ $name }}">
                    <span class="hidden lg:flex lg:items-center">
                        @php
                            $user = auth()->user() ?? \App\Models\User::find(9);
                        @endphp
                        <span class="ml-4 text-sm font-semibold leading-6 text-gray-900 dark:text-white">{{ $user->full_name }}</span>
                        <x-heroicon-o-chevron-down class="ml-2 h-5 w-5 text-gray-400 dark:text-gray-500" />
                    </span>
                </button>

                <!-- Dropdown menu -->
                <div x-show="open" x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="absolute right-0 z-10 mt-2.5 w-48 origin-top-right rounded-md bg-white dark:bg-gray-800 py-2 shadow-lg ring-1 ring-gray-900/5 dark:ring-gray-700/50">
                    
                    <!-- User Info Section -->
                    <div class="px-3 py-2 border-b border-gray-100 dark:border-gray-700">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->full_name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $user->email }}</p>
                    </div>
                    
                    <!-- Menu Items -->
                    <div class="py-1">
                        <a href="{{ route('profile') }}"
                            class="block px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">Your profile</a>
                        <a href="{{ route('profile.edit') }}"
                            class="block px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">Settings</a>
                        
                        <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="block w-full text-left px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
