@props(['collapsed' => false, 'mobile' => false])

<nav class="flex flex-1 flex-col">
    <ul role="list" class="flex flex-1 flex-col gap-y-7">
        <li>
            <ul role="list" class="-mx-2 space-y-1">
                <li>
                    <a href="{{ route('pengajar.dashboard') }}"
                        class="group flex items-center gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('dashboard') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}"
                        :class="{ 'justify-center': {{ $collapsed ? 'true' : 'collapsed' }} }">
                        <x-heroicon-o-home class="h-6 w-6 shrink-0" />
                        <span
                            :class="{
                                'opacity-0 hidden': {{ $collapsed ? 'true' : 'collapsed' }},
                                'opacity-100': !
                                    {{ $collapsed ? 'true' : 'collapsed' }}
                            }"
                            class="transition-opacity duration-300">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('pengajar.courses.index') }}"
                        class="group flex items-center gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('calendar*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}"
                        :class="{ 'justify-center': {{ $collapsed ? 'true' : 'collapsed' }} }">
                        <x-heroicon-o-calendar class="h-6 w-6 shrink-0" />
                        <span
                            :class="{
                                'opacity-0 hidden': {{ $collapsed ? 'true' : 'collapsed' }},
                                'opacity-100': !
                                    {{ $collapsed ? 'true' : 'collapsed' }}
                            }"
                            class="transition-opacity duration-300">Courses</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('profile') }}"
                        class="group flex items-center gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('profile') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}"
                        :class="{ 'justify-center': {{ $collapsed ? 'true' : 'collapsed' }} }">
                        <x-heroicon-o-user class="h-6 w-6 shrink-0" />
                        <span
                            :class="{
                                'opacity-0 hidden': {{ $collapsed ? 'true' : 'collapsed' }},
                                'opacity-100': !
                                    {{ $collapsed ? 'true' : 'collapsed' }}
                            }"
                            class="transition-opacity duration-300">Profile</span>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Account section - shown differently based on collapsed state -->
        @if ($collapsed && !$mobile)
            <li>
                <ul role="list" class="-mx-2 space-y-1">
                    <li>
                        <a href="{{ route('settings') }}"
                            class="group flex items-center justify-center gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('settings') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                            <x-heroicon-o-cog-6-tooth class="h-6 w-6 shrink-0" />
                        </a>
                    </li>
                </ul>
            </li>
        @else
            <li>
                <ul role="list" class="-mx-2 mt-2 space-y-1">
                    <li>
                        <a href="{{ route('profile.edit') }}"
                            class="group flex items-center gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('profile.edit') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}"
                            :class="{ 'justify-center': {{ $collapsed ? 'true' : 'collapsed' }} }">
                            <x-heroicon-o-cog-6-tooth class="h-6 w-6 shrink-0" />
                            <span
                                :class="{
                                    'opacity-0 hidden': {{ $collapsed ? 'true' : 'collapsed' }},
                                    'opacity-100': !
                                        {{ $collapsed ? 'true' : 'collapsed' }}
                                }"
                                class="transition-opacity duration-300">Settings</span>
                        </a>
                    </li>
                    <li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>

                        <a href="#"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            class="group flex items-center gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-gray-400 hover:text-white hover:bg-gray-800"
                            :class="{ 'justify-center': {{ $collapsed ? 'true' : 'collapsed' }} }">
                            <x-heroicon-o-globe-alt class="h-6 w-6 shrink-0" />
                            <span
                                :class="{ 'opacity-0 hidden': {{ $collapsed ? 'true' : 'collapsed' }}, 'opacity-100': !
                                        {{ $collapsed ? 'true' : 'collapsed' }} }"
                                class="transition-opacity duration-300">
                                Landing Page
                            </span>
                        </a>
                    </li>
                </ul>
            </li>
        @endif

        <!-- Logout section -->
        <li class="mt-auto">
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit"
                    class="group -mx-2 flex items-center gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 text-gray-400 hover:bg-gray-800 hover:text-white w-full text-left cursor-pointer"
                    :class="{ 'justify-center mx-0': {{ $collapsed ? 'true' : 'collapsed' }} }"
                    onclick="return confirm('Are you sure you want to logout?')">
                    <x-heroicon-o-arrow-right-on-rectangle class="h-6 w-6 shrink-0" />
                    <span
                        :class="{
                            'opacity-0 hidden': {{ $collapsed ? 'true' : 'collapsed' }},
                            'opacity-100': !
                                {{ $collapsed ? 'true' : 'collapsed' }}
                        }"
                        class="transition-opacity duration-300">Logout</span>
                </button>
            </form>
        </li>
    </ul>
</nav>
