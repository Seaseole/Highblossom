<?php

use App\Actions\Bookings\CreateBookingAction;
use App\Http\Requests\Bookings\StoreBookingRequest;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Schedule Your Vehicle Inspection')] class extends Component {
    public string $client_name = '';
    public string $client_email = '';
    public string $client_phone = '';
    public string $vehicle_details = '';
    public string $scheduled_at = '';
    public string $location = 'workshop';

    protected function rules(): array
    {
        return (new StoreBookingRequest())->rules();
    }

    public function submit(CreateBookingAction $createBookingAction)
    {
        $validated = $this->validate();

        try {
            $booking = $createBookingAction($validated);
            return redirect()->route('bookings.confirmation', $booking);
        } catch (\Exception $e) {
            $this->addError('scheduled_at', $e->getMessage());
        }
    }
}; ?>

    <flux:main class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-10 text-center">
                <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-3">Schedule Your Vehicle Inspection</h1>
                <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                    Professional, thorough, and reliable inspections at your convenience. Fill in the details below to secure your slot.
                </p>
            </div>
            
            <div class="max-w-2xl mx-auto bg-white dark:bg-gray-800 p-8 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700">
                <form wire:submit="submit" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <flux:input wire:model="client_name" :label="__('Full Name')" type="text" required />
                        <flux:input wire:model="client_email" :label="__('Email Address')" type="email" required />
                    </div>

                    <flux:input wire:model="client_phone" :label="__('Phone Number')" type="text" required />

                    <flux:textarea wire:model="vehicle_details" :label="__('Vehicle Details (Make, Model, Year)')" rows="3" required />

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <flux:input wire:model="scheduled_at" :label="__('Preferred Inspection Date')" type="datetime-local" required />

                        <flux:select wire:model="location" :label="__('Location')">
                            <option value="workshop">Workshop</option>
                            <option value="mobile">Mobile (On-site)</option>
                        </flux:select>
                    </div>

                    <div class="pt-4">
                        <flux:button type="submit" variant="primary" class="w-full">
                            {{ __('Book Inspection') }}
                        </flux:button>
                    </div>
                </form>
            </div>
        </div>
    </flux:main>
