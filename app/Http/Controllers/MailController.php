<?php

namespace App\Http\Controllers;

use App\Mail\temperatureDiscrepancyMail;
use App\Services\MailControllerService;
use Illuminate\Http\Request;
use Mail;

class MailController extends Controller
{
    protected MailControllerService $mailService;
    
    public function __construct()
    {
        $this->mailService = new MailControllerService();
    }

    public function sendTemperatureDiscrepancyMail()
    {
        $ccEmails = $this->mailService->emailStringToArray(request()->ccEmails ?? "");
        $emails = $this->mailService->emailInputToArray(request()->emails);
        $data = request()->except(['emails', '_token', 'ccEmails','booking_no']);
        $details = $this->mailService->seperateInputByIndex($data);
        $bookingNo = request()->booking_no;
        
        Mail::to($emails)->cc($ccEmails)->send(new temperatureDiscrepancyMail($details, $bookingNo));
        return redirect()->route('booking.index')->with('success',"Email Sent Successfully!");
    }
}
