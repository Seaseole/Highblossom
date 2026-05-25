<?php

namespace App\Actions;

use App\Events\ContactMessageReceived;
use App\Http\Requests\ContactFormRequest;
use App\Models\ContactMessage;
use App\Services\IdempotencyService;

class SendContactMessage
{
    private IdempotencyService $idempotencyService;

    public function __construct(IdempotencyService $idempotencyService)
    {
        $this->idempotencyService = $idempotencyService;
    }

    public function handle(ContactFormRequest $request): array
    {
        // Check for duplicate submission using idempotency token
        $token = $request->input('_idempotency_token');

        if (! empty($token) && $this->idempotencyService->isProcessed($token)) {
            return [
                'success' => true,
                'duplicate' => true,
                'message' => 'Your message has already been sent. Please wait a moment before sending another.',
            ];
        }

        $validated = $request->validated();

        // Mark token as processed before database operation
        if (! empty($token)) {
            $this->idempotencyService->markProcessed($token);
        }

        // Save to database
        $contactMessage = ContactMessage::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'is_read' => false,
        ]);

        // Trigger event for async processing
        event(new ContactMessageReceived($contactMessage));

        return [
            'success' => true,
            'message' => 'Thank you for your message. We will get back to you soon!',
        ];
    }
}
