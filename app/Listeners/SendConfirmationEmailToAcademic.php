<?php

declare(strict_types=1);

namespace App\Listeners;

use Acme\Academic\Event\AcademicWasCreatedEvent;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;

class SendConfirmationEmailToAcademic
{
    public function handle(AcademicWasCreatedEvent $event): void
    {
        $data = [
            'firstName' => (string) $event->academic()->firstName(),
            'lastName' => (string) $event->academic()->lastName(),
        ];

        Mail::send('email.confirmation', $data, function (Message $message) use ($event) {
            $message->subject('Welcome to the scientist');
            $message->to((string) $event->academic()->email());
        });
    }
}
