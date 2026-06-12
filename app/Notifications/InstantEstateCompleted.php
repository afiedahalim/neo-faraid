<?php
// app/Notifications/InstantEstateCompleted.php

namespace App\Notifications;

use App\Models\InstantEstateSession;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class InstantEstateCompleted extends Notification
{
    use Queueable;
    
    protected $session;
    
    public function __construct(InstantEstateSession $session)
    {
        $this->session = $session;
    }
    
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }
    
    public function toMail($notifiable)
    {
        $message = (new MailMessage)
            ->subject('Instant Estate Processing Complete')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your Instant Estate document has been processed successfully.')
            ->line('Document: ' . $this->session->original_filename);
            
        if ($this->session->calculation_id) {
            $message->action('View Calculation', route('calculator.show', $this->session->calculation_id));
        } else {
            $message->action('Continue to Calculator', route('instant-estate.index'));
        }
        
        return $message->line('Thank you for using Neo Faraid!');
    }
    
    public function toArray($notifiable)
    {
        return [
            'session_id' => $this->session->session_id,
            'status' => $this->session->status,
            'filename' => $this->session->original_filename,
            'calculation_id' => $this->session->calculation_id
        ];
    }
}