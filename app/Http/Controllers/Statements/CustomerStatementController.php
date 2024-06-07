<?php

namespace App\Http\Controllers\Statements;

use App\Filters\Statements\CustomerStatementIndexFilter;
use App\Filters\Statements\CustomerStatementRefundIndexFilter;
use App\Http\Controllers\Controller;
use App\Models\Bl\BlDraft;
use App\Models\Invoice\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Voyages\Voyages;
use App\Models\Master\Customers;

class CustomerStatementController extends Controller
{

    public function index()
    {

        $statements = Customers::filter(new CustomerStatementIndexFilter(request()))
        ->orderBy('id', 'desc')
        ->where('company_id', Auth::user()->company_id)
        ->with('invoices.receipts', 'creditNotes', 'refunds')
        ->where(function ($query) {
            $query->WhereHas('invoices');
            $query->orWhereHas('creditNotes');
            $query->orWhereHas('refunds');
        })
        ->paginate(30);
    
        $exportinvoices = Customers::filter(new CustomerStatementIndexFilter(request()))
        ->orderBy('id', 'desc')
        ->where('company_id', Auth::user()->company_id)
        ->with('invoices.receipts', 'creditNotes', 'refunds')
        ->where(function ($query) {
            $query->WhereHas('invoices');
            $query->orWhereHas('creditNotes');
            $query->orWhereHas('refunds');
        })
        ->get();
        session()->flash('statements',$exportinvoices);


        $invoiceRef = Invoice::orderBy('id','desc')->where('company_id',Auth::user()->company_id)->get();
        $bldrafts = BlDraft::where('company_id',Auth::user()->company_id)->get();
        $voyages    = Voyages::where('company_id',Auth::user()->company_id)->get();
        $customers  = Customers::where('company_id',Auth::user()->company_id)->get();

        return view('statements.customerstatements.index',[
            'statements'=>$statements, 
            'invoiceRef'=>$invoiceRef,
            'bldrafts'=>$bldrafts,
            'customers'=>$customers,
            'voyages'=>$voyages,
        ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
