<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class DevPasswordReset extends Notification
{
    use Queueable;

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        // In development, just log it
        Log::info('Password Reset Token Generated', [
            'email' => $notifiable->getEmailForPasswordReset(),
            'token' => $this->token,
            'reset_url' => url(route('password.reset', [
                'token' => $this->token,
                'email' => $notifiable->getEmailForPasswordReset()
            ], false))
        ]);
        
        // Return empty array to prevent email sending
        return [];
    }

    public function toMail($notifiable)
    {
        // This won't be called since via() returns empty array
        return null;
    }
}