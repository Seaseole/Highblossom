<?php

namespace App\Events;

use App\Domains\Content\Models\ContactMessage;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ContactMessageReceived
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public ContactMessage $contactMessage
    ) {}
}
