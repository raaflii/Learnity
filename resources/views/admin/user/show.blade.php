<x-admin.layout>
    <x-slot:title>User Details</x-slot:title>

    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-white">User Details</h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Complete information for this user account
                            </p>
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('admin.users.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-md transition-colors">
                                <x-heroicon-o-arrow-left class="h-4 w-4 mr-2" />
                                Back to Users
                            </a>
                        </div>
                    </div>
                </div>

                <!-- User Profile Section -->
                <div class="mb-8">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-20 w-20">
                            @if ($user->avatar && Storage::disk('public')->exists($user->avatar))
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->first_name }}"
                                    class="h-20 w-20 rounded-full object-cover border-2 border-white shadow" />
                            @else
                                <div
                                    class="h-20 w-20 rounded-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
                                    <span class="text-xl font-medium text-white">
                                        {{ strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="ml-6">
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ $user->first_name }} {{ $user->last_name }}
                            </h1>
                            <p class="text-lg text-gray-600 dark:text-gray-400">{{ $user->email }}</p>
                            <div class="mt-2 flex items-center space-x-3">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                    <x-heroicon-o-shield-check class="h-3 w-3 mr-1" />
                                    {{ ucfirst($user->role->name) ?? 'No Role' }}
                                </span>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                    <x-heroicon-o-check-circle class="h-3 w-3 mr-1" />
                                    Active
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Information Grid -->
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <!-- Basic Information -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                            <x-heroicon-o-user class="h-5 w-5 inline mr-2" />
                            Basic Information
                        </h3>
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Full Name</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $user->first_name }}
                                    {{ $user->last_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email Address</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $user->email }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ $user->phone ?? '-' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">User ID</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">#{{ $user->id }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Account Information -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                            <x-heroicon-o-cog-6-tooth class="h-5 w-5 inline mr-2" />
                            Account Information
                        </h3>
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Account Status</dt>
                                <dd class="mt-1">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
    {{ $user->is_active == 1
        ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300'
        : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' }}">
                                        @if ($user->is_active == 1)
                                            <x-heroicon-o-check-circle class="h-3 w-3 mr-1" />
                                            Active
                                        @else
                                            <x-heroicon-o-x-circle class="h-3 w-3 mr-1" />
                                            Inactive
                                        @endif
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email Verified</dt>
                                <dd class="mt-1">
                                    @if ($user->email_verified_at)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                            <x-heroicon-o-check-circle class="h-3 w-3 mr-1" />
                                            Verified
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300">
                                            <x-heroicon-o-exclamation-triangle class="h-3 w-3 mr-1" />
                                            Not Verified
                                        </span>
                                    @endif
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Timestamps -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                            <x-heroicon-o-clock class="h-5 w-5 inline mr-2" />
                            Timeline
                        </h3>
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Account Created</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ $user->created_at->format('F j, Y \a\t g:i A') }}
                                    <span class="text-gray-500 dark:text-gray-400">
                                        ({{ $user->created_at->diffForHumans() }})
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Updated</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ $user->updated_at->format('F j, Y \a\t g:i A') }}
                                    <span class="text-gray-500 dark:text-gray-400">
                                        ({{ $user->updated_at->diffForHumans() }})
                                    </span>
                                </dd>
                            </div>
                            @if ($user->email_verified_at)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email Verified</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                        {{ $user->email_verified_at->format('F j, Y \a\t g:i A') }}
                                        <span class="text-gray-500 dark:text-gray-400">
                                            ({{ $user->email_verified_at->diffForHumans() }})
                                        </span>
                                    </dd>
                                </div>
                            @endif
                        </dl>
                    </div>

                    <!-- Role Permissions (if role exists) -->
                    @if ($user->role)
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                                <x-heroicon-o-key class="h-5 w-5 inline mr-2" />
                                Role & Permissions
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Current Role
                                    </dt>
                                    <dd>
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                            <x-heroicon-o-shield-check class="h-4 w-4 mr-2" />
                                            {{ ucfirst($user->role->name) }}
                                        </span>
                                    </dd>
                                </div>
                                @if ($user->role->description)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Description
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                            {{ ucfirst($user->role->description) }}</dd>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="mt-8 flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-600"
                    x-data="{
                        showDeleteModal: false,
                        open() { this.showDeleteModal = true;
                            document.body.style.overflow = 'hidden'; },
                        close() { this.showDeleteModal = false;
                            document.body.style.overflow = 'auto'; }
                    }">

                    <button @click="open"
                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors cursor-pointer">
                        <x-heroicon-o-trash class="h-4 w-4 mr-2" />
                        Delete User
                    </button>

                    <a href="{{ route('admin.users.edit', $user) }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors cursor-pointer">
                        <x-heroicon-o-pencil class="h-4 w-4 mr-2" />
                        Edit User
                    </a>

                    <!-- Modal -->
                    <div x-show="showDeleteModal" x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-50" style="display: none;">
                        <div class="fixed inset-0 z-10 overflow-y-auto">
                            <div
                                class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                                <div x-show="showDeleteModal" x-transition:enter="ease-out duration-300"
                                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                    x-transition:leave="ease-in duration-200"
                                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                    class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                                    <div>
                                        <div
                                            class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30">
                                            <x-heroicon-o-exclamation-triangle
                                                class="h-6 w-6 text-red-600 dark:text-red-400" />
                                        </div>
                                        <div class="mt-3 text-center sm:mt-5">
                                            <h3
                                                class="text-base font-semibold leading-6 text-gray-900 dark:text-white">
                                                Delete User</h3>
                                            <div class="mt-2">
                                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                                    Are you sure you want to delete <span
                                                        class="font-medium text-gray-900 dark:text-white">{{ $user->first_name }}
                                                        {{ $user->last_name }}</span>? This action cannot be undone.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-5 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3">
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                            class="sm:col-start-2">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 cursor-pointer">
                                                Delete
                                            </button>
                                        </form>
                                        <button @click="close" type="button"
                                            class="mt-3 inline-flex w-full justify-center rounded-md bg-white dark:bg-gray-700 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 sm:col-start-1 sm:mt-0 cursor-pointer">
                                            Cancel
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin.layout>
