<x-public-layout>
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">
                    Application Portal
                </h1>
                <p class="text-xl md:text-2xl mb-8 text-blue-100">
                    Discover all available applications
                </p>

                <!-- Stats -->
                <div class="flex flex-wrap justify-center gap-8 mt-8">
                    <div class="text-center">
                        <div class="text-4xl font-bold">{{ $stats['total'] }}</div>
                        <div class="text-blue-100">Total Applications</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold">{{ $stats['kesekretariatan'] }}</div>
                        <div class="text-blue-100">Kesekretariatan</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold">{{ $stats['kepaniteraan'] }}</div>
                        <div class="text-blue-100">Kepaniteraan</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Search Section -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-8">
            <form method="GET" action="{{ route('home') }}" class="flex gap-4">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Search applications..."
                       class="flex-1 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                    Search
                </button>
                @if(request('search'))
                    <a href="{{ route('home') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        @php
            $kesekretariatanApps = $applications->where('category', 'kesekretariatan');
            $kepaniteraanApps = $applications->where('category', 'kepaniteraan');
        @endphp

        <!-- Kesekretariatan Section -->
        <div class="mb-12">
            <div class="flex items-center mb-6">
                <div class="flex-shrink-0 w-2 h-12 bg-blue-500 rounded-r"></div>
                <h2 class="text-3xl font-bold text-gray-900 dark:text-gray-100 ml-4">Kesekretariatan</h2>
                <div class="flex-grow ml-4 h-1 bg-blue-500 rounded"></div>
            </div>

            @if($kesekretariatanApps->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                    @foreach($kesekretariatanApps as $app)
                        <a href="{{ $app->url }}"
                           target="_blank"
                           class="group bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-xl transition-all duration-300 p-6 text-center border-2 border-transparent hover:border-blue-500 transform hover:scale-105">
                            <div class="flex flex-col items-center">
                                <!-- Icon -->
                                <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mb-4 group-hover:bg-blue-500 transition-colors">
                                    <svg class="w-8 h-8 text-blue-600 dark:text-blue-300 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>

                                <!-- App Name -->
                                <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors line-clamp-2 min-h-[2.5rem]">
                                    {{ $app->name }}
                                </h3>

                                <!-- External Link Icon -->
                                <svg class="w-4 h-4 text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-400 mt-2 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 dark:text-gray-400 text-center py-8">No applications available</p>
            @endif
        </div>

        <!-- Kepaniteraan Section -->
        <div class="mb-12">
            <div class="flex items-center mb-6">
                <div class="flex-shrink-0 w-2 h-12 bg-green-500 rounded-r"></div>
                <h2 class="text-3xl font-bold text-gray-900 dark:text-gray-100 ml-4">Kepaniteraan</h2>
                <div class="flex-grow ml-4 h-1 bg-green-500 rounded"></div>
            </div>

            @if($kepaniteraanApps->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                    @foreach($kepaniteraanApps as $app)
                        <a href="{{ $app->url }}"
                           target="_blank"
                           class="group bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-xl transition-all duration-300 p-6 text-center border-2 border-transparent hover:border-green-500 transform hover:scale-105">
                            <div class="flex flex-col items-center">
                                <!-- Icon -->
                                <div class="w-16 h-16 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center mb-4 group-hover:bg-green-500 transition-colors">
                                    <svg class="w-8 h-8 text-green-600 dark:text-green-300 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>

                                <!-- App Name -->
                                <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors line-clamp-2 min-h-[2.5rem]">
                                    {{ $app->name }}
                                </h3>

                                <!-- External Link Icon -->
                                <svg class="w-4 h-4 text-gray-400 group-hover:text-green-600 dark:group-hover:text-green-400 mt-2 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 dark:text-gray-400 text-center py-8">No applications available</p>
            @endif
        </div>
    </div>
</x-public-layout>
