<?php
// app/Mail/InheritanceReportMail.php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InheritanceReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $deceasedName;
    public $accessToken;
    public $pdfContent;

    public function __construct(string $deceasedName, string $accessToken, ?string $pdfContent = null)
    {
        $this->deceasedName = $deceasedName;
        $this->accessToken = $accessToken;
        $this->pdfContent = $pdfContent;
    }

    public function build()
    {
        $email = $this->subject('Inheritance Report - ' . $this->deceasedName)
            ->view('emails.inheritance-report')
            ->with([
                'deceased_name' => $this->deceasedName,
                'access_token' => $this->accessToken,
                'date' => now()->format('d M Y, h:i A'),
            ]);
        
        if ($this->pdfContent) {
            $email->attachData($this->pdfContent, 'inheritance_report.pdf', [
                'mime' => 'application/pdf',
            ]);
        }
        
        return $email;
    }
}