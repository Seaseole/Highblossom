<?php

namespace App\Actions;

use App\Actions\SendQuoteEmailNotificationsAction;
use App\Domains\Bookings\Models\Quote;
use App\Domains\Content\Models\CompanySetting;
use App\Mail\QuoteSubmittedMail;
use App\Services\IdempotencyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class StoreQuoteAction
{
    public function __construct(
        protected Quote $quote,
        protected IdempotencyService $idempotencyService,
        protected SendQuoteEmailNotificationsAction $sendEmailNotificationsAction
    ) {}

    public function execute(Request $request): array
    {
        $idempotencyResult = $this->checkIdempotency($request);
        if ($idempotencyResult) {
            return $idempotencyResult;
        }

        try {
            $imagePath = $this->handleImageUpload($request);
            $this->markIdempotencyProcessed($request);
            
            $quote = $this->createQuote($request, $imagePath);
            $this->sendEmailNotificationsAction->execute($quote);

            return [
                'success' => true,
                'message' => 'Your quote request has been submitted successfully. We will contact you within 24 hours.',
                'quote' => $quote,
            ];
        } catch (\Exception $e) {
            Log::error('Quote submission error: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'There was an error submitting your quote request. Please try again.',
            ];
        }
    }

    /**
     * Check for duplicate submission using idempotency token.
     */
    private function checkIdempotency(Request $request): ?array
    {
        $token = $request->input('_idempotency_token');

        if (!empty($token) && $this->idempotencyService->isProcessed($token)) {
            return [
                'success' => true,
                'duplicate' => true,
                'message' => 'Your quote request has already been submitted. Please wait a moment before submitting another.',
            ];
        }

        return null;
    }

    /**
     * Mark idempotency token as processed.
     */
    private function markIdempotencyProcessed(Request $request): void
    {
        $token = $request->input('_idempotency_token');
        if (!empty($token)) {
            $this->idempotencyService->markProcessed($token);
        }
    }

    /**
     * Handle image upload from request.
     */
    private function handleImageUpload(Request $request): ?string
    {
        // Use AJAX uploaded path if provided
        if (!empty($request->input('image_path'))) {
            return $request->input('image_path');
        }

        // Handle traditional file upload
        if ($request->hasFile('image')) {
            try {
                $file = $request->file('image');
                if ($file && $file->isValid()) {
                    return $file->store('quotes', 'public');
                }
            } catch (\Exception $e) {
                Log::error('Failed to store quote image: ' . $e->getMessage());
            }
        }

        return null;
    }

    /**
     * Create quote record in database.
     */
    private function createQuote(Request $request, ?string $imagePath): Quote
    {
        $quote = $this->quote->create([
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'vehicle_type' => $request->input('vehicle_type'),
            'make_model' => $request->input('make_model'),
            'reg_number' => $request->input('reg_number'),
            'year' => $request->input('year'),
            'glass_type_id' => $request->input('glass_type_id'),
            'glass_sub_category_id' => $request->input('glass_sub_category_id'),
            'service_type_id' => $request->input('service_type_id'),
            'image_path' => $imagePath,
            'mobile_service' => $request->boolean('mobile_service', false),
            'status' => 'pending',
        ]);

        // Load relationships for email
        $quote->load(['glassType', 'glassSubCategory', 'serviceType']);

        return $quote;
    }

    public function __invoke(Request $request): array
    {
        return $this->execute($request);
    }
}
