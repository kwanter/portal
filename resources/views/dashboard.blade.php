<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <!-- Total Applications -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Applications</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $stats['total_applications'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kesekretariatan -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Kesekretariatan</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $stats['kesekretariatan_apps'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kepaniteraan -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Kepaniteraan</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $stats['kepaniteraan_apps'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Users -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Users</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $stats['total_users'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Recent Applications -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Recent Applications</h3>
                        <div class="space-y-3">
                            @forelse($recentApplications as $app)
                                <div class="border-l-4 border-blue-500 pl-4 py-2">
                                    <a href="{{ route('applications.show', $app) }}" class="text-blue-600 dark:text-blue-400 hover:underline font-medium">
                                        {{ $app->name }}
                                    </a>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $app->category == 'kesekretariatan' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                            {{ ucfirst($app->category) }}
                                        </span>
                                        • By {{ $app->creator->name ?? 'Unknown' }} • {{ $app->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            @empty
                                <p class="text-gray-500 dark:text-gray-400">No applications yet.</p>
                            @endforelse
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('applications.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline text-sm">
                                View all applications →
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Recent Users -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Recent Users</h3>
                        <div class="space-y-3">
                            @forelse($recentUsers as $user)
                                <div class="border-l-4 border-purple-500 pl-4 py-2">
                                    <a href="{{ route('users.show', $user) }}" class="text-purple-600 dark:text-purple-400 hover:underline font-medium">
                                        {{ $user->name }}
                                    </a>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $user->email }} • Joined {{ $user->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            @empty
                                <p class="text-gray-500 dark:text-gray-400">No users yet.</p>
                            @endforelse
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('users.index') }}" class="text-purple-600 dark:text-purple-400 hover:underline text-sm">
                                View all users →
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Quick Actions</h3>
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('applications.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            + Add Application
                        </a>
                        <a href="{{ route('users.create') }}" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                            + Add User
                        </a>
                        <a href="{{ route('applications.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            View All Applications
                        </a>
                        <a href="{{ route('users.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            View All Users
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
