<?php

declare(strict_types=1);

namespace App\Services;

use App\Domains\Content\Models\ContactMessage;

final class ContactMessageService
{
    public function markAsRead(ContactMessage $message): ContactMessage
    {
        if (!$message->is_read) {
            $message->markAsRead();
        }

        return $message;
    }

    public function delete(ContactMessage $message): void
    {
        $message->delete();
    }
}
