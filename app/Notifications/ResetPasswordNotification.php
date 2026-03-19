<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends ResetPassword
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        parent::__construct($token);
        $this->queue = "emails";
    }

    // use Illuminate\Notifications\Messages\MailMessage;

public function toMail($notifiable)
{
    $url = url(route('password.reset', [
        'token' => $this->token,
        'email' => $notifiable->getEmailForPasswordReset(),
    ], false));

    return (new MailMessage)
        ->subject('Reset Your Password - Car Findal Service')
        ->greeting('Hello ' . $notifiable->name)
        ->line('You requested to reset your password.')
        ->action('Change Password', $url)
        ->line('This link expires in 60 minutes.')
        ->line('If you did not request this, ignore this email.');
}
}
