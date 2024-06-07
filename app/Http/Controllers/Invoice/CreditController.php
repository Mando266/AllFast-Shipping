<?php

namespace App\Http\Controllers\invoice;

use App\Filters\Invoice\CreditNote\CreditIndexFilter;
use App\Http\Controllers\Controller;
use App\Models\Bl\BlDraft;
use Illuminate\Http\Request;
use App\Models\Invoice\CreditNote;
use App\Models\Invoice\CreditNoteDesc;
use App\Models\Master\Customers;
use Illuminate\Support\Facades\Auth;
use App\Setting;
use Carbon\Carbon;

class CreditController extends Controller
{
     
    public function index()
    {
        $creditNotes = CreditNote::where('company_id',Auth::user()->company_id)->filter(new CreditIndexFilter(request()))->orderBy('id','desc')->paginate(30);
        $customers  = Customers::where('company_id',Auth::user()->company_id)->get();
        
        return view('invoice.creditNote.index',[
            'creditNotes'=>$creditNotes,
            'customers'=>$customers,
        ]);
    }

    public function create()
    {
        $customers  = Customers::where('company_id',Auth::user()->company_id)->get();
        $blnos = BlDraft::where('company_id',Auth::user()->company_id)->get();
        return view('invoice.creditNote.create',[
            'customers'=>$customers,
            'blnos'=>$blnos,
        ]);
    }

 
    public function store(Request $request)
    {
        $request->validate([
            'creditNoteDesc' => ['required'],
            'customer_id' => ['required'],
        ],[
            'creditNoteDesc.required'=>'Table Cannot be empty',
            'customer_id.required'=>'Please Select Customer',
        ]);
        $customer = Customers::find($request->customer_id);
        $total_amount = 0;
        foreach($request->input('creditNoteDesc',[])  as $desc){
            if($desc['amount'] != 0){
                $total_amount += $desc['amount'];
            }else{
                return redirect()->back()->with('error','Credit Note Total Amount Can not be equal zero')->withInput($request->input());
            }
        }
        if($request->currency == "credit_usd"){
            $customer->credit = $customer->credit +$total_amount;
        }elseif($request->currency == "credit_egp"){
            $customer->credit_egp = $customer->credit_egp +$total_amount;
        }
        $customer->save();
        $credit = CreditNote::create([
            'company_id'=>Auth::user()->company_id,
            'customer_id'=>$request->customer_id,
            'notes'=>$request->notes,
            'total_amount'=>$total_amount,
            'currency'=>$request->currency,
            'bl_no'=>$request->bl_no,
        ]);
        if(request('credit_no') != null){
            $credit->credit_no = request('credit_no');
        }else{
            $setting = Setting::find(1);
            $credit->credit_no = 'CN'.sprintf('%03u', $setting->credit_no).' / 23';
            $setting->credit_no += 1;
            $setting->save();
        }
        $credit->save();
        foreach($request->input('creditNoteDesc',[])  as $description){
            CreditNoteDesc::create([
                'credit_note_id'=>$credit->id,
                'description'=>$description['description'],
                'amount'=>$description['amount'],
            ]);
        }
        return redirect()->route('creditNote.index')->with('success',trans('Credit Note.created'));
    }

  
    public function show($id)
    {
        $creditNote = CreditNote::find($id);
        $now = Carbon::now();

        $exp = explode('.', $creditNote->total_amount);
        $f = new \NumberFormatter("en_US", \NumberFormatter::SPELLOUT);
        if(count($exp) >1){
            $total =  ucfirst($f->format($exp[0])) . ' and ' . ucfirst($f->format($exp[1]));

        }else{
            $total =  ucfirst($f->format($exp[0]));
        }

        return view('invoice.creditNote.show',[
            'creditNote'=>$creditNote,
            'now'=>$now,
            'total'=>$total,

        ]);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
