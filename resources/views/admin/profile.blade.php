<x-admin.layout>
    <x-slot:title>My Profile</x-slot:title>

    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center space-x-6 mb-6">
                    <div class="relative">
                        @php
                            function getAvatarColor($name)
                            {
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
                            }

                            $user = auth()->user();
                            $name = trim($user->first_name . ' ' . $user->last_name);
                            $color = getAvatarColor($name);

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

                        <img class="h-24 w-24 rounded-full object-cover ring-4 ring-gray-100 dark:ring-gray-700" src="{{ $avatar }}"
                            alt="Profile" id="avatar-preview">
                        <div class="absolute bottom-0 right-0 h-6 w-6 bg-green-400 rounded-full border-2 border-white dark:border-gray-800">
                        </div>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}
                        </h2>
                        <p class="text-gray-600 dark:text-gray-400">{{ auth()->user()->email }}</p>
                        <div class="flex items-center mt-1">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                {{ ucfirst(auth()->user()->role->name ?? 'User') }}
                            </span>
                        </div>

                        <a href="{{ route('profile.edit') }}"
                            class="mt-3 inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 hover:border-gray-400 dark:hover:border-gray-500 transition-colors duration-200">
                            <x-heroicon-o-pencil class="h-4 w-4 mr-2" />
                            Edit Profile
                        </a>
                    </div>
                </div>

                <!-- Profile Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left: Personal Info -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                            <x-heroicon-o-user class="h-5 w-5 mr-2 text-gray-600 dark:text-gray-400" />
                            Personal Information
                        </h3>
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone</dt>
                                <dd class="text-sm text-gray-900 dark:text-white mt-1">
                                    {{ auth()->user()->phone ?? '-' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Bio</dt>
                                <dd class="text-sm text-gray-900 dark:text-white mt-1 leading-relaxed">
                                    {{ auth()->user()->profile->bio ?? '-' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Education</dt>
                                <dd class="text-sm text-gray-900 dark:text-white mt-1">
                                    {{ auth()->user()->profile->education ?? '-' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Experience</dt>
                                <dd class="text-sm text-gray-900 dark:text-white mt-1 leading-relaxed">
                                    {{ auth()->user()->profile->experience ?? '-' }}
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Right: Expertise and Social -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                            <x-heroicon-o-briefcase class="h-5 w-5 mr-2 text-gray-600 dark:text-gray-400" />
                            Professional Info
                        </h3>
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Expertise</dt>
                                <dd class="text-sm text-gray-900 dark:text-white mt-1 leading-relaxed">
                                    {{ auth()->user()->profile->expertise ?? '-' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Social Links</dt>
                                <dd class="mt-2">
                                    @php
                                        $links = json_decode(auth()->user()->profile->social_links ?? '[]', true);
                                    @endphp
                                    @if (!empty($links))
                                        <div class="flex flex-wrap gap-3">
                                            @foreach ($links as $platform => $url)
                                                @if ($url)
                                                    <a href="{{ $url }}" target="_blank"
                                                        class="inline-flex items-center px-3 py-1.5 text-sm bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition-colors duration-200 border border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500">
                                                        @switch($platform)
                                                            @case('facebook')
                                                                <svg class="w-4 h-4 mr-2 text-blue-600" fill="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path
                                                                        d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                                                </svg>
                                                            @break

                                                            @case('twitter')
                                                                <svg class="w-4 h-4 mr-2 text-sky-500" fill="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path
                                                                        d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                                                                </svg>
                                                            @break

                                                            @case('linkedin')
                                                                <svg class="w-4 h-4 mr-2 text-blue-700" fill="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path
                                                                        d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                                                                </svg>
                                                            @break

                                                            @case('github')
                                                                <svg class="w-4 h-4 mr-2 text-gray-900 dark:text-gray-100" fill="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path
                                                                        d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z" />
                                                                </svg>
                                                            @break

                                                            @default
                                                                <x-heroicon-o-globe-alt class="w-4 h-4 mr-2 text-gray-500 dark:text-gray-400" />
                                                        @endswitch
                                                        {{ ucfirst($platform) }}
                                                    </a>
                                                @endif
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-500 dark:text-gray-400">-</span>
                                    @endif
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Joined</dt>
                                <dd class="text-sm text-gray-900 dark:text-white mt-1">
                                    {{ auth()->user()->created_at->translatedFormat('F Y') }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin.layout>
