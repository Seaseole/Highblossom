<x-layouts::app>
    <flux:main class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 text-center">
                    <div class="mb-6">
                        <svg class="mx-auto h-12 w-12 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    
                    <h1 class="text-3xl font-bold mb-4">{{ __('confirmation.title') }}</h1>
                    <p class="text-lg text-gray-600 dark:text-gray-400 mb-8">
                        {!! __('confirmation.message', ['name' => $booking->client_name, 'vehicle' => $booking->vehicle_details]) !!}
                    </p>

                    <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg inline-block text-left mb-8">
                        <p><strong>{{ __('confirmation.scheduled_date') }}</strong> {{ $booking->scheduled_at ? $booking->scheduled_at->format('M d, Y H:i') : __('confirmation.tbc') }}</p>
                        <p><strong>{{ __('confirmation.status') }}</strong> {{ ucfirst($booking->status) }}</p>
                        <p><strong>{{ __('confirmation.confirmation_email') }}</strong> {{ $booking->client_email }}</p>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('home') }}" class="text-indigo-600 hover:text-indigo-900 font-semibold">
                            &larr; {{ __('confirmation.return_home') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </flux:main>
</x-layouts::app>
