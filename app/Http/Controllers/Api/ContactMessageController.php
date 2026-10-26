<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\ContactMessageResource;
use App\Models\ContactMessage;
use App\Services\ContactMessageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Inbox triage for the staff app: read, mark-read and delete contact messages.
 *
 * Unlike the web admin (which marks a message read as a side effect of opening
 * it), `show` here is read-only; marking read is an explicit PATCH so the app can
 * preview a message without changing its state. Authorization mirrors the admin
 * routes, which gate every action behind `can:view contact messages`.
 */
final class ContactMessageController extends Controller
{
    public function __construct(private readonly ContactMessageService $contactMessageService) {}

    /**
     * List contact messages, optionally filtered by read state and a search term.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $messages = ContactMessage::query()
            ->when($request->query('status'), function ($query, $status) {
                match ($status) {
                    'read' => $query->read(),
                    'unread' => $query->unread(),
                    default => null,
                };
            })
            ->when($request->query('search'), fn ($q, $search) => $q->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%");
            }))
            ->latest()
            ->paginate(min(100, max(1, $request->integer('per_page', 15))));

        return ContactMessageResource::collection($messages);
    }

    /**
     * Show one contact message without changing its read state.
     */
    public function show(ContactMessage $message): ContactMessageResource
    {
        return new ContactMessageResource($message);
    }

    /**
     * Mark a contact message as read.
     */
    public function markAsRead(ContactMessage $message): JsonResponse
    {
        $message = $this->contactMessageService->markAsRead($message);

        return response()->json([
            'data' => new ContactMessageResource($message),
            'message' => __('messages.contact_message_marked_read'),
        ]);
    }

    /**
     * Delete a contact message.
     */
    public function destroy(ContactMessage $message): JsonResponse
    {
        $this->contactMessageService->delete($message);

        return response()->json(['message' => __('messages.contact_message_deleted')]);
    }
}
