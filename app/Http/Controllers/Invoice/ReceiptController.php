<?php

namespace App\Http\Controllers\Invoice;

use App\Filters\Receipt\ReceiptIndexFilter;
use App\Models\Bl\BlDraft;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice\Invoice;
use App\Models\Master\Bank;
use App\Models\Master\CustomerHistory;
use App\Models\Master\Customers;
use App\Models\Receipt\Receipt;
use App\Setting;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use setasign\Fpdi\PdfParser\Type\PdfNull;

class ReceiptController extends Controller
{

    public function index()
    {
        $this->authorize(__FUNCTION__,Receipt::class);

        $paginator = Receipt::where('company_id',Auth::user()->company_id)->filter(new ReceiptIndexFilter(request()))
                    ->where('status', 'valid')
                    ->orderBy('id', 'desc')
                    ->paginate(30); // Get all receipts from the database
        // $sortedReceipts = $receipts->sortByDesc(function ($receipt) {
        //     $matches = [];
        //     preg_match('/\d+/', $receipt->receipt_no, $matches); // Extract the number from the receipt number using a regular expression
        //     return (int) $matches[0]; // Return the number as an integer for sorting
        // });
        // $perPage = 30;
        // $page = request('page', 1);
        // $offset = ($page - 1) * $perPage;

        // // Create a new collection containing the receipts for the current page
        // $currentPageReceipts = collect($sortedReceipts->slice($offset, $perPage));

        // // Create a paginator for the receipts
        // $paginator = new LengthAwarePaginator(
        //     $currentPageReceipts,
        //     $sortedReceipts->count(),
        //     $perPage,
        //     $page,
        //     ['path' => request()->url(), 'query' => request()->query()]
        // );

        // // Set the current page on the paginator
        // $paginator->setPageName('page');


        $receiptno = Receipt::where('company_id',Auth::user()->company_id)->orderBy('id','desc')->where('status','valid')->get();
        $customers  = Customers::where('company_id',Auth::user()->company_id)->get();
        $invoices  = Invoice::where('company_id',Auth::user()->company_id)->get();
        $bldrafts = BlDraft::where('has_bl',1)->where('bl_status','=',1)->where('company_id',Auth::user()->company_id)->get();
        $receiptexport = Receipt::where('company_id',Auth::user()->company_id)->filter(new ReceiptIndexFilter(request()))->orderBy('id','desc')->get();
        session()->flash('receipts',$receiptexport);

        return view('invoice.receipt.index',[
            'receipts'=>$paginator,
            'receiptno'=>$receiptno,
            'customers'=>$customers,
            'invoices'=>$invoices,
            'bldrafts'=>$bldrafts,
        ]);

    }

    public function selectinvoice()
    {
        $bldrafts = BlDraft::where('has_bl',1)->where('bl_status','=',1)->where('company_id',Auth::user()->company_id)->get();
        $customers  = Customers::where('company_id',Auth::user()->company_id)->get();
        $invoiceRef = Invoice::where('invoice_status','confirm')->orderBy('id','desc')->where('company_id',Auth::user()->company_id)->where('paymentstauts',0)->get();

        return view('invoice.receipt.selectinvoice',[
            'bldrafts'=>$bldrafts,
            'customers'=>$customers,
            'invoiceRef'=>$invoiceRef,
        ]);
    }

    public function create(Request $request)
    {
        $this->authorize(__FUNCTION__, Receipt::class);
    
        $selectedInvoiceIds = $request->input('invoice_id', []);
        $invoices = Invoice::whereIn('id', $selectedInvoiceIds)->with('chargeDesc')->get();
        $total = 0;
        $total_eg = 0;
        $oldPayment = 0;
        // Initialize $oldReceipts as an empty collection
        $oldReceipts = collect();
    
        foreach ($invoices as $invoice) {
            $invoiceOldReceipts = Receipt::where('invoice_id', $invoice->id)->get();
            // Aggregate old receipts from all invoices
            $oldReceipts = $oldReceipts->merge($invoiceOldReceipts);
    
            foreach ($invoiceOldReceipts as $oldReceipt) {
                $oldPayment += $oldReceipt->paid;
            }
    
            $vat = $invoice->vat / 100;
            foreach ($invoice->chargeDesc as $chargeDesc) {
                $total += $chargeDesc->total_amount;
                $total_eg += $chargeDesc->total_egy;
                if ($chargeDesc->add_vat == 1) {
                    $total += ($vat * $chargeDesc->total_amount);
                    $total_eg += ($vat * $chargeDesc->total_egy);
                }
            }
        }
    
        $total -= $oldPayment;
        $total_eg -= $oldPayment;
        $banks = Bank::get();
    
        return view('invoice.receipt.create', [
            'invoices' => $invoices,
            'banks' => $banks,
            'oldReceipts' => $oldReceipts,
            'total' => $total,
            'total_eg' => $total_eg,
        ]);
    }
    

    public function store(Request $request)
    {
        // if ($request->input('bank_transfer') < $request->input('total_payment')){
        //     return redirect()->back()->with('error','Receipt Amount Can Not Be Less Than Invoice Amount');
        // }
        // elseif( $request->input('bank_cash') < $request->input('total_payment') ){
        //     return redirect()->back()->with('error','Receipt Amount Can Not Be Less Than Invoice Amount');
        // }
        // elseif( $request->input('matching') < $request->input('total_payment') ){
        //     return redirect()->back()->with('error','Receipt Amount Can Not Be Less Than Invoice Amount');
        // }
        // elseif ( $request->input('bank_check') < $request->input('total_payment') )
        // {
        //     return redirect()->back()->with('error','Receipt Amount Can Not Be Less Than Invoice Amount');
        // }
        // elseif( $request->input('bank_deposit') < $request->input('total_payment') ){
        //     return redirect()->back()->with('error','Receipt Amount Can Not Be Less Than Invoice Amount');
        // }

        if ($request->input('bank_deposit') != Null){
            $request->validate([
                'bank_id' => ['required'],
            ],[
                'bank_id.required'=>'Please Choose Bank Account',
            ]);
        }

        if ($request->input('bank_transfer') != Null){
            $request->validate([
                'bank_transfer_id' => ['required'],
            ],[
                'bank_transfer_id.required'=>'Please Choose Bank Account',

            ]);
        }
        if($request->input('bank_check') != Null){

            $request->validate([
                'cheak_no' => ['required'],
                'bank_cheque_id' => ['required'],
            ],[
                'cheak_no.required'=>'Please Enter Cheak No',
                'bank_cheque_id.required'=>'Please Choose Bank Account',
            ]);
        }

        $invoice = Invoice::where('id',request('invoice_id'))->with('chargeDesc')->first();
        $oldReceipts = Receipt::where('invoice_id',request('invoice_id'))->get();
        $customer = Customers::where('id',$invoice->customer_id)->first();
        // dd($request->input(),$invoice);
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
        // i need to take from cus credit
        if(isset($request->matching)){
            if($invoice->add_egp == "false"){
                // currency USD
                if($request->matching <= $customer->credit){
                    $paid += $request->matching;
                    $customer->credit = $customer->credit - $request->matching;
                    $customer->save();
                }else{
                    return redirect()->back()->with('error','Matching Amount Is Bigger Than Customer Credit USD')->withInput($request->input());
                }
            }elseif($invoice->add_egp == "onlyegp"){
                // currency EGP
                if($request->matching <= $customer->credit_egp){
                    $paid += $request->matching;
                    $customer->credit_egp = $customer->credit_egp - $request->matching;
                    $customer->save();
                }else{
                    return redirect()->back()->with('error','Matching Amount Is Bigger Than Customer Credit EGP')->withInput($request->input());
                }
            }
        }
        if($paid == 0){
            return redirect()->back()->with('error','Total payment on receipt cannot be equal zero')->withInput($request->input());
        }
        // to Decrease Customer Debit
        if($invoice->add_egp == "false"){
            if($customer->debit != 0){
                if($customer->debit > $paid){
                    $customer->debit = $customer->debit - $paid;
                    $customer->save();
                }else{
                    $customer->debit = 0;
                    $customer->save();
                }
            }
        }elseif($invoice->add_egp == "onlyegp"){
            if($customer->debit_egp != 0){
                if($customer->debit_egp > $paid){
                    $customer->debit_egp = $customer->debit_egp - $paid;
                    $customer->save();
                }else{
                    $customer->debit_egp = 0;
                    $customer->save();
                }
            }
        }

        $receipt = Receipt::create([
            'company_id'=>Auth::user()->company_id,
            'invoice_id'=>$request->invoice_id,
            'cheak_no'=>$request->cheak_no,
            'bank_id'=>$request->bank_id,
            'bldraft_id'=>$request->bldraft_id ?? $invoice->bldraft_id,
            'bank_transfer'=>$request->bank_transfer,
            'bank_deposit'=>$request->bank_deposit,
            'bank_cash'=>$request->bank_cash,
            'bank_check'=>$request->bank_check,
            'matching'=>$request->matching,
            'total'=>$request->total_payment,
            'notes'=>$request->notes,
            'paid'=>($paid - $request->matching),
            'user_id'=>Auth::user()->id,
            'bank_transfer_id'=>$request->bank_transfer_id,
            'bank_cheque_id'=>$request->bank_cheque_id,
            'bank_id'=>$request->bank_id,

        ]);

        if(request('receipt_no') != null){
            $receipt->receipt_no = request('receipt_no');
        }else{
            $setting = Setting::find(1);
            $receipt->receipt_no = 'ALY/ '.$setting->receipt_no.' / 24';
            $setting->receipt_no += 1;
            $setting->save();
        }
        $receipt->save();


        if($oldReceipts->count() == 0){
            // ADD to Credit
            if($paid > (int)$request->total_payment){

                if($invoice->add_egp == "false"){
                    // currency USD
                    $customer->credit = $customer->credit + ($paid - (int)$request->total_payment);
                    CustomerHistory::create([
                        'credit'=>($paid - (int)$request->total_payment),
                        'receipt_id'=>$receipt->id,
                        'user_id'=>$customer->id
                    ]);
                }elseif($invoice->add_egp == "onlyegp"){
                    // currency EGP
                    $customer->credit_egp = $customer->credit_egp + ($paid - (int)$request->total_payment);
                    CustomerHistory::create([
                        'credit_egp'=>($paid - (int)$request->total_payment),
                        'receipt_id'=>$receipt->id,
                        'user_id'=>$customer->id
                    ]);
                }
            }elseif($paid < (int)$request->total_payment){
                // ADD to Debit
                if($invoice->add_egp == "false"){
                    // currency USD
                    $customer->debit = $customer->debit + ((int)$request->total_payment - $paid);
                    CustomerHistory::create([
                        'debit'=>((int)$request->total_payment - $paid),
                        'receipt_id'=>$receipt->id,
                        'user_id'=>$customer->id
                    ]);
                }elseif($invoice->add_egp == "onlyegp"){
                    // currency EGP
                    $customer->debit_egp = $customer->debit_egp + ((int)$request->total_payment - $paid);
                    CustomerHistory::create([
                        'debit_egp'=>((int)$request->total_payment - $paid),
                        'receipt_id'=>$receipt->id,
                        'user_id'=>$customer->id
                    ]);
                }
            }
        }else{
            if($paid > (int)$request->total_payment){

                if($invoice->add_egp == "false"){
                    // currency USD
                    $customer->credit = $customer->credit + ($paid - (int)$request->total_payment);
                    CustomerHistory::create([
                        'credit'=>($paid - (int)$request->total_payment),
                        'receipt_id'=>$receipt->id,
                        'user_id'=>$customer->id
                    ]);
                }elseif($invoice->add_egp == "onlyegp"){
                    // currency EGP
                    $customer->credit_egp = $customer->credit_egp + ($paid - (int)$request->total_payment);
                    CustomerHistory::create([
                        'credit_egp'=>($paid - (int)$request->total_payment),
                        'receipt_id'=>$receipt->id,
                        'user_id'=>$customer->id
                    ]);
                }

            }
        }
        if($paid >= (int)$request->total_payment){
            $invoice->paymentstauts = 1;
        }
        $customer->save();
        $invoice->save();
        return redirect()->route('receipt.index')->with('success',trans('Receipt.created'));
    }


    public function show($id)
    {
        $this->authorize(__FUNCTION__,Receipt::class);

        $receipt = Receipt::find($id);
        $now = Carbon::now();

        $exp = explode('.', $receipt->paid);
        $f = new \NumberFormatter("en_US", \NumberFormatter::SPELLOUT);
        if(count($exp) >1){
            $total =  ucfirst($f->format($exp[0])) . ' and ' . ucfirst($f->format($exp[1]));

        }else{
            $total =  ucfirst($f->format($exp[0]));
        }



        return view('invoice.receipt.show',[
            'receipt'=>$receipt,
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
