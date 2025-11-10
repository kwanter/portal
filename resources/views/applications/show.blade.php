<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Application Details') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('applications.edit', $application) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                    Edit
                </a>
                <a href="{{ route('applications.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Application Details -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Application Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Name</label>
                            <p class="mt-1 text-lg">{{ $application->name }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Category</label>
                            <p class="mt-1">
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full
                                    {{ $application->category == 'kesekretariatan' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                    {{ ucfirst($application->category) }}
                                </span>
                            </p>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">URL</label>
                            <p class="mt-1">
                                <a href="{{ $application->url }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline">
                                    {{ $application->url }}
                                </a>
                            </p>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Description</label>
                            <p class="mt-1">{{ $application->description }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Metadata -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Metadata</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Created By</label>
                            <p class="mt-1">{{ $application->creator->name ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $application->created_at->format('d M Y, H:i') }}</p>
                        </div>

                        @if($application->updated_by)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Last Updated By</label>
                            <p class="mt-1">{{ $application->updater->name ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $application->updated_at->format('d M Y, H:i') }}</p>
                        </div>
                        @endif

                        @if($application->deleted_at)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Deleted By</label>
                            <p class="mt-1">{{ $application->deleter->name ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $application->deleted_at->format('d M Y, H:i') }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Audit Trail -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Audit Trail</h3>

                    @if($application->audits->count() > 0)
                        <div class="space-y-4">
                            @foreach($application->audits as $audit)
                                <div class="border-l-4 {{ $audit->event == 'created' ? 'border-green-500' : ($audit->event == 'updated' ? 'border-yellow-500' : 'border-red-500') }} pl-4 py-2">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="font-semibold">
                                                {{ ucfirst($audit->event) }}
                                                <span class="px-2 py-1 text-xs rounded-full
                                                    {{ $audit->event == 'created' ? 'bg-green-100 text-green-800' : ($audit->event == 'updated' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                    {{ $audit->event }}
                                                </span>
                                            </p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                By {{ $audit->user->name ?? 'System' }} on {{ $audit->created_at->format('d M Y, H:i:s') }}
                                            </p>
                                        </div>
                                    </div>

                                    @if($audit->event == 'updated' && ($audit->old_values || $audit->new_values))
                                        <div class="mt-2 text-sm">
                                            <details class="cursor-pointer">
                                                <summary class="text-blue-600 dark:text-blue-400 hover:underline">View Changes</summary>
                                                <div class="mt-2 bg-gray-50 dark:bg-gray-900 p-3 rounded">
                                                    @foreach($audit->getModified() as $attribute => $modified)
                                                        <div class="mb-2">
                                                            <span class="font-semibold">{{ ucfirst($attribute) }}:</span>
                                                            <div class="ml-4">
                                                                <p class="text-red-600 dark:text-red-400">- {{ $modified['old'] ?? 'null' }}</p>
                                                                <p class="text-green-600 dark:text-green-400">+ {{ $modified['new'] ?? 'null' }}</p>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </details>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400">No audit trail available.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
