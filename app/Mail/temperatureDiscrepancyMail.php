<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class temperatureDiscrepancyMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $details;
    protected string $authMail;
    protected string $bookingNo;

    public function __construct($details, $bookingNo = "")
    {
        $this->details = $details;
        $this->bookingNo = $bookingNo;
        $this->authMail = auth()->user()->email ?? 'info-meastline@meastline.com';
        if (!str_ends_with($this->authMail, '@meastline.com')) {
            $this->authMail = 'info-meastline@meastline.com';
        }
    }


    public function build()
    {
        return $this->markdown('emails.temperature-discrepancy-mail')
            ->subject("Temperature Discrepancy: Booking No {$this->bookingNo}")
            ->from('temperature.discrepancies@middleeastshipping.net')
            ->with([
                'details' => $this->details
            ]);
    }
}
