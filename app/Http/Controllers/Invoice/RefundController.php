<?php

namespace App\Http\Controllers\invoice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\Customers;
use App\Models\Receipt\Refund;
use App\Filters\Refund\RefundIndexFilter;
use App\Models\Master\Bank;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Setting;

class RefundController extends Controller
{
    public function index()
    { 
        $this->authorize(__FUNCTION__,Refund::class);

            $refunds = Refund::where('company_id',Auth::user()->company_id)->filter(new RefundIndexFilter(request()))->orderBy('id','desc')->where('status','!=','valid')->paginate(30);
            $refundno = Refund::where('company_id',Auth::user()->company_id)->orderBy('id','desc')->where('status','refund_egp')->orwhere('status','refund_usd')->get();
            $customers  = Customers::where('company_id',Auth::user()->company_id)->get();

        return view('invoice.refund.index',[
            'refunds'=>$refunds,
            'refundno'=>$refundno,
            'customers'=>$customers,
        ]);
    }
 
      
    public function create()
    {
        $this->authorize(__FUNCTION__,Refund::class);
            $customers  = Customers::where('company_id',Auth::user()->company_id)->get();
            $banks = Bank::where('company_id',Auth::user()->company_id)->get();

        return view('invoice.refund.create',[
            'banks'=>$banks,
            'customers'=>$customers,
        ]);
    }

    public function store(Request $request)
    {
        if ($request->input('bank_transfer') != Null || $request->input('bank_deposit') != Null){
            $request->validate([
                'bank_id' => ['required'],
            ],[
                'bank_id.required'=>'Please Choose Bank Account',
                
            ]);
        }
        if($request->input('bank_check') != Null){

            $request->validate([
                'cheak_no' => ['required'],
            ],[
                'cheak_no.required'=>'Please Enter Cheque NO',
            ]);
        }
        $user = Auth::user();

        $customer = Customers::where('id',$request->customer_id)->first();

        $paid = 0;
        if(isset($request->bank_deposit)){
            $paid += $request->bank_deposit;         // Add Bank Deposit to Paid
        }
        if(isset($request->bank_transfer)){
            $paid += $request->bank_transfer;        // Add Bank Transfer to Paid
        }
        if(isset($request->bank_check)){
            $paid += $request->bank_check;
        }
        if(isset($request->bank_cash)){
            $paid += $request->bank_cash;
        }

        if($paid == 0){
            return redirect()->back()->with('error','Total payment on receipt cannot be equal zero')->withInput($request->input());
        }

        if($paid == 0){
            return redirect()->back()->with('error','Total payment on receipt cannot be equal zero')->withInput($request->input());
        }

        if($request->currency == "refund_usd"){
            if($paid > $customer->credit){
                return redirect()->back()->with('error','Total payment on receipt is more than customer credit in USD')->withInput($request->input());
            }else{
                $customer->credit = $customer->credit - $paid;
                $customer->save();
            }
        }elseif($request->currency == "refund_egp"){
            if($paid > $customer->credit_egp){
                return redirect()->back()->with('error','Total payment on receipt is more than customer credit in EGP')->withInput($request->input());
            }else{
                $customer->credit_egp = $customer->credit_egp - $paid;
                $customer->save();
            }
        }else{
            return redirect()->back()->with('error','Currency must be selected')->withInput($request->input());
        }

        $refund = Refund::create([
            'receipt_no'=>$request->receipt_no,
            'company_id'=>Auth::user()->company_id,
            'cheak_no'=>$request->cheak_no,
            'bank_id'=>$request->bank_id,
            'bank_transfer'=>$request->bank_transfer,
            'bank_deposit'=>$request->bank_deposit,
            'bank_cash'=>$request->bank_cash,
            'bank_check'=>$request->bank_check,
            'total'=>$paid,
            'notes'=>$request->notes,
            'paid'=>$paid,
            'status'=>$request->currency,
            'customer_id'=>$request->customer_id,
            'user_id'=>Auth::user()->id,
        ]);
        
        $setting = Setting::find(1);
        $refund->receipt_no = 'REFUND/ '.$setting->refund_no.' / 23';
        $setting->refund_no += 1;
        $setting->save();
        $refund->save();
        
        return redirect()->route('refund.index')->with('success',trans('Receipt.created'));
    }

    public function show($id)
    {
        $this->authorize(__FUNCTION__,Refund::class);

        $receipt = Refund::find($id);
        $now = Carbon::now();

        $exp = explode('.', $receipt->paid);
        $f = new \NumberFormatter("en_US", \NumberFormatter::SPELLOUT);
        if(count($exp) >1){
            $total =  ucfirst($f->format($exp[0])) . ' and ' . ucfirst($f->format($exp[1]));

        }else{
            $total =  ucfirst($f->format($exp[0]));
        }

  

        return view('invoice.refund.show',[
            'receipt'=>$receipt,
            'now'=>$now,
            'total'=>$total,

        ]);
    }
}
