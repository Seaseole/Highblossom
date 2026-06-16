<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ContactMessage;

/**
 * Service for managing contact form messages.
 */
final class ContactMessageService
{
    /**
     * Mark a contact message as read.
     */
    public function markAsRead(ContactMessage $message): ContactMessage
    {
        if (! $message->is_read) {
            $message->markAsRead();
        }

        return $message;
    }

    /**
     * Delete a contact message.
     */
    public function delete(ContactMessage $message): void
    {
        $message->delete();
    }
}
