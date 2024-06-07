<?php

namespace App\Http\Controllers\Trucker;

use App\Http\Controllers\Controller;
use App\Filters\Trucker\TruckerIndexFilter;
use App\Models\Trucker\TruckerGates;
use App\Models\Booking\Booking;
use App\Models\Trucker\Trucker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\MailableName ;
use App\Setting;
class TruckerGateController extends Controller
{

    public function index()
    {
        $this->authorize(__FUNCTION__,TruckerGates::class);
            $truckerGates = TruckerGates::where('company_id',Auth::user()
            ->company_id)->with('booking.bookingContainerDetails')->filter(new TruckerIndexFilter(request()))->orderBy('id','desc')->paginate(30);
            // dd($truckerGates);
            $exportTruckerGate = TruckerGates::where('company_id',Auth::user()
            ->company_id)->with('booking.bookingContainerDetails')->filter(new TruckerIndexFilter(request()))->get();
            $certificate = TruckerGates::where('company_id',Auth::user()->company_id)->get();
           // dd($truckerGates);
            $booking = Booking::where('company_id',Auth::user()
            ->company_id)->where('booking_confirm','!=',3)->get();
            $truckers = Trucker::where('company_id',Auth::user()
            ->company_id)->get();
            session()->flash('truckergates',$exportTruckerGate);

            return view('trucker.truckergate.index',[
                'items'=>$truckerGates,
                'booking'=>$booking,
                'certificate'=>$certificate,
                'truckers'=>$truckers,
                'exportTruckerGate'=>$exportTruckerGate,
            ]);
        }

    public function create(Request $request)
    {
        $this->authorize(__FUNCTION__,TruckerGates::class);
            $booking = Booking::where('company_id',Auth::user()->company_id)->where('booking_confirm','!=',0)->get();
            $truckers = Trucker::get();
            return view('trucker.truckergate.create',[
                'booking'=>$booking,
                'truckers'=>$truckers,
            ]);
    }

    public function store(Request $request)
    {
        $this->authorize(__FUNCTION__,TruckerGates::class);
        $user = Auth::user();
        $BookingDublicate  = TruckerGates::where('company_id',$user->company_id)->where('booking_id',$request->booking_id)->first();
        //dd($BookingDublicate);
        if($BookingDublicate != null){
            return back()->with('error','This Booking No Already Exists');
        }

        $certificate_type = 'PL-100-61-6004-2023-84/';
        $truckerGate = TruckerGates::create($request->except('_token'));
        $setting = Setting::find(1);
        $certificate_type .= $setting->trucker_cer_no += 1;
        $truckerGate->certificate_type = $certificate_type;
        $truckerGate->company_id = $user->company_id;
        $truckerGate->save();
        $setting->save();

        return redirect()->route('truckergate.index')->with('success',trans('Trucker Gate.Created'));
    }

    public function show($id)
    {
        //
    }

    public function edit(TruckerGates $truckergate)
    {
        $this->authorize(__FUNCTION__,TruckerGates::class);
        $booking = Booking::where('company_id',Auth::user()->company_id)->where('booking_confirm','!=',0)->get();
        $truckers = Trucker::where('company_id',Auth::user()->company_id)->get();
        return view('trucker.truckergate.edit',[
            'booking'=>$booking,
            'truckers'=>$truckers,
            'truckergate'=>$truckergate,
        ]);
    }


    public function update(Request $request, TruckerGates $truckergate)
    {
        $this->authorize(__FUNCTION__,TruckerGates::class);

        $user = Auth::user();
        $BookingDublicate  = TruckerGates::where('id','!=',$truckergate->id)->where('company_id',$user->company_id)->where('booking_id',$request->booking_id)->count();
        if($BookingDublicate > 0){
            return back()->with('alert','This Booking No Already Exists');
        }

        $truckergate->update($request->except('_token'));
        return redirect()->route('truckergate.index')->with('success',trans('Trucker Gate.Updated'));

    }

        public function basic_email() {
            $details = [
                'title' => 'Mail from ItSolutionStuff.com',
                'body' => 'This is for testing email using smtp this is Mohamed Fadl test 25-25-2022' ,
             ];
                $mailTest = Mail::to('moataz.elnabawi@meastline.com')->send(new MailableName($details));
                //dd($mailTest);

            echo "Basic Email Sent. Check your inbox.";
    }

    public function destroy($id)
    {
        $truckergate = TruckerGates::find($id);
        $truckergate->delete();
        return back()->with('Success',trans('Trucker Gate.Deleted'));
    }
}
