<?php

use App\Http\Controllers\MailController;
use App\Mail\temperatureDiscrepancyMail;

Route::prefix('booking')->namespace('Booking')->group(function () {
    Route::post('temperatureDiscrepancy/sendMail', [MailController::class, 'sendTemperatureDiscrepancyMail'])
        ->name('temperature-discrepancy.send');
});
Route::get('test',function() {
    Mail::to('Zakariayahyahamdan@gmail.com')->send(new \App\Mail\gateOutEmptyMail());

});