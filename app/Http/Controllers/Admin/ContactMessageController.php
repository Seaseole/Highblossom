<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Domains\Content\Models\ContactMessage;
use Illuminate\View\View;

final class ContactMessageController
{
    public function index(): View
    {
        $messages = ContactMessage::query()->latest()->paginate(15);

        return view('admin.contact-messages.index', compact('messages'));
    }

    public function show(ContactMessage $message): View
    {
        if (!$message->is_read) {
            $message->markAsRead();
        }

        return view('admin.contact-messages.show', compact('message'));
    }

    public function destroy(ContactMessage $message)
    {
        $message->delete();

        return redirect()
            ->route('admin.contact-messages.index')
            ->with('success', __('messages.contact_message_deleted'));
    }
}
