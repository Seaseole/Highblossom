<?php

namespace Tests\Feature;

use App\Events\ContactMessageReceived;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class ContactMessageTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_message_dispatches_event()
    {
        Event::fake();

        $this->withoutMiddleware();

        $response = $this->post('/contact', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'subject' => 'Hello',
            'message' => 'Test message body here',
        ]);

        Event::assertDispatched(ContactMessageReceived::class);
    }
}
