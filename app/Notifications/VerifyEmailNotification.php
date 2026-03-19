<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

//  implements ShouldQueue
class VerifyEmailNotification extends VerifyEmail
{
    use Queueable;

    public function __construct()
    {
        $this->queue = "emails";
    }
    
}
