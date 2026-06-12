<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InheritanceResultMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $recipient;
    public $estate;
    public string $deceasedName;

    public function __construct(array $recipient, $estate = null)
    {
        $this->recipient = $recipient;
        $this->estate = $estate;
        $this->deceasedName = $estate->deceased_name ?? 'the deceased';
    }

    public function build()
    {
        $type = $this->recipient['type'] ?? 'beneficiary';
        $subjectType = match($type) {
            'heir' => 'Heir',
            'wasiyyah' => 'Wasiyyah Beneficiary',
            'trustee' => 'Trustee',
            default => 'Beneficiary',
        };

        return $this->subject("Inheritance Distribution - {$this->deceasedName} ({$subjectType})")
            ->view('emails.inheritance-result', [
                'recipient' => $this->recipient,
                'estate' => $this->estate,
                'deceasedName' => $this->deceasedName,
            ]);
    }
}