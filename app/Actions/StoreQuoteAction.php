<?php

namespace App\Actions;

use App\Domains\Bookings\Models\Quote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StoreQuoteAction
{
    public function __construct(
        protected Quote $quote
    ) {}

    public function execute(Request $request): array
    {
        try {
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('quotes', 'public');
            }

            $quote = $this->quote->create([
                'name' => $request->input('name'),
                'phone' => $request->input('phone'),
                'email' => $request->input('email'),
                'vehicle_type' => $request->input('vehicle_type'),
                'make_model' => $request->input('make_model'),
                'reg_number' => $request->input('reg_number'),
                'year' => $request->input('year'),
                'glass_type_id' => $request->input('glass_type_id'),
                'service_type_id' => $request->input('service_type_id'),
                'image_path' => $imagePath,
                'mobile_service' => $request->boolean('mobile_service', false),
                'status' => 'pending',
            ]);

            return [
                'success' => true,
                'message' => 'Your quote request has been submitted successfully. We will contact you within 24 hours.',
                'quote' => $quote,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'There was an error submitting your quote request. Please try again.',
            ];
        }
    }

    public function __invoke(Request $request): array
    {
        return $this->execute($request);
    }
}
