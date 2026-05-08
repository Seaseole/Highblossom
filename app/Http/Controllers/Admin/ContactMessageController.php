<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Domains\Content\Models\ContactMessage;
use App\Services\ContactMessageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class ContactMessageController
{
    public function __construct(
        private readonly ContactMessageService $contactMessageService,
    ) {}

    public function index(Request $request): View
    {
        $query = ContactMessage::query();

        match ($request->input('status')) {
            'read' => $query->read(),
            'unread' => $query->unread(),
            default => null,
        };

        $messages = $query->latest()->paginate(15);

        return view('admin.contact-messages.index', compact('messages'));
    }

    public function show(ContactMessage $message): View
    {
        $this->contactMessageService->markAsRead($message);

        return view('admin.contact-messages.show', compact('message'));
    }

    public function markAsRead(ContactMessage $message): RedirectResponse
    {
        $this->contactMessageService->markAsRead($message);

        return redirect()
            ->route('admin.contact-messages.show', $message)
            ->with('success', __('messages.contact_message_marked_read'));
    }

    public function destroy(ContactMessage $message): RedirectResponse
    {
        $this->contactMessageService->delete($message);

        return redirect()
            ->route('admin.contact-messages.index')
            ->with('success', __('messages.contact_message_deleted'));
    }
}
