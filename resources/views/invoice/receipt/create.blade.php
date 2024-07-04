@extends('layouts.app')
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a a href="{{route('receipt.index')}}">Receipt</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">New Receipt</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
           
                    <form id="createForm" action="{{route('receipt.store')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                        <h4 style="color:#1b55e2">Invoice Details<h4>

                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label>Invoice No</label>
                                    <input type="text" class="form-control"  value="{{ $invoice->invoice_no }}" style="background-color:#fff" disabled>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>BLdraft No</label>
                                    <input type="text" class="form-control"  value="{{optional($invoice->booking)->ref_no }}" style="background-color:#fff" disabled>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Customer</label>
                                    <input type="text" class="form-control"  value="{{ optional($invoice->customerShipperOrFfw)->name }}" style="background-color:#fff" disabled>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Date</label>
                                    <input type="text" class="form-control"  value="{{ $invoice->date }}" style="background-color:#fff" disabled>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label>Status</label>
                                    <input type="text" class="form-control"  value="{{ $invoice->invoice_status }}" style="background-color:#fff" disabled>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Currency</label>
                                    <input type="text" class="form-control"  value="{{ $invoice->add_egp == "onlyegp"? "EGP" : "USD" }}" style="background-color:#fff" disabled>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Voyage No</label>
                                    <input type="text" class="form-control"  value="{{ $invoice->bldraft_id == 0 ? optional($invoice->voyage)->voyage_no : optional(optional($invoice->bldraft)->voyage)->voyage_no }}" style="background-color:#fff" disabled>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Vessel</label>
                                    <input type="text" class="form-control"  value="{{ $invoice->bldraft_id == 0 ? optional(optional($invoice->voyage)->vessel)->name : optional(optional(optional($invoice->bldraft)->voyage)->vessel)->name }}" style="background-color:#fff" disabled>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label>POL</label>
                                    <input type="text" class="form-control"  value="{{ $invoice->bldraft_id == 0 ? optional($invoice->loadPort)->code : optional(optional($invoice->bldraft)->loadPort)->code }}" style="background-color:#fff" disabled>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>POD</label>
                                    <input type="text" class="form-control"  value="{{ $invoice->bldraft_id == 0 ? optional($invoice->dischargePort)->code : optional(optional($invoice->booking)->dischargePort)->code }}" style="background-color:#fff" disabled>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Final Dest</label>
                                    <input type="text" class="form-control"  value="{{ $invoice->bldraft_id == 0 ? optional($invoice->placeOfDelivery)->code : optional(optional($invoice->booking)->placeOfDelivery)->code }}" style="background-color:#fff" disabled>
                                </div>
                            </div>

                        <h4 style="color:#1b55e2">Payment Methods<h4>
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label>Receipt No</label>
                                    <input type="text" class="form-control"  style="background-color:#fff" name="receipt_no">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-3 form-group">
                                    <label>Cash</label>
                                        <input type="text" class="form-control" name="bank_cash" value="{{old('bank_cash',request()->input('bank_cash'))}}">
                                </div>
                             
                                <div class="col-md-3 form-group">
                                    <label>Matching</label>
                                        <input type="text" class="form-control" name="matching" value="{{old('matching',request()->input('matching'))}}">
                                </div>
                           
                                <div class="col-md-3 form-group">
                                    <label>Cheque Amount</label>
                                    <input type="text" class="form-control" name="bank_check" value="{{old('bank_check',request()->input('bank_check'))}}">
                                </div>

                                <div class="form-group col-md-3" >
                                    <label>Cheque NO</label>
                                    <input type="text" class="form-control" placeholder="Cheque NO" name="cheak_no" autocomplete="off"  style="background-color:#fff">
                                    @error('cheak_no')
                                    <div style="color: red; font-size:14px;">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        <div class="form-row">
                            <input type="hidden" name="bldraft_id" value="{{request()->input('bldraft_id')}}">
                            <input type="hidden" name="invoice_id" value="{{request()->input('invoice_id')}}">
                          
                            <div class="col-md-2 form-group">
                                <label> Bank Deposit </label>
                                <input type="text" class="form-control" name="bank_deposit" value="{{old('bank_deposit',request()->input('bank_deposit'))}}">
                            </div>
                        
                            <div class="col-md-2 form-group">
                                <label> Bank Transfer </label>
                                    <input type="text" class="form-control" name="bank_transfer" value="{{old('bank_transfer',request()->input('bank_transfer'))}}">
                            </div>

                            <div class="form-group col-md-8">
                                <label for="bank_id">Bank Account <span class="text-warning"></span></label>
                                <select class="selectpicker form-control" id="bank_id" data-live-search="true" name="bank_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($banks as $bank)
                                        <option value="{{$bank->id}}" {{$bank->id == old('bank_id',request()->input('bank_id')) ? 'selected':''}}>Name: {{$bank->name}} &nbsp; Account No: {{$bank->account_no}}&nbsp; Iban: {{$bank->iban}} &nbsp; Currency: {{$bank->currancy}}</option>
                                    @endforeach
                                </select>
                                @error('bank_id')
                                <div style="color: red; font-size:14px;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div> 

                        <div class="form-row">
                            <div class="col-md-4 form-group">
                             {{-- @dd($invoice); --}}
                                <label> Total {{$invoice->add_egp == "false"? "USD" : "EGP"}}</label>
                                <input class="form-control" type="text" name="total_payment" value="{{ $invoice->add_egp == 'false' ? $total : $total_eg }}" readonly>
                                </div>
                                <div class="col-md-2 form-group">
                                    <label> Customer Credit USD </label>
                                        <input  class="form-control"  type="text" name="customer_credit" value="{{optional($invoice->customerShipperOrFfw)->credit}}" readonly>
                                </div>
                                <div class="col-md-2 form-group">
                                    <label> Customer Credit EGP </label>
                                        <input  class="form-control"  type="text" name="customer_credit" value="{{optional($invoice->customerShipperOrFfw)->credit_egp}}" readonly>
                                </div>
                                <div class="col-md-2 form-group">
                                    <label> Customer Debit USD </label>
                                        <input  class="form-control"  type="text" name="customer_debit" value="{{optional($invoice->customerShipperOrFfw)->debit}}" readonly>
                                </div>    
                                <div class="col-md-2 form-group">
                                    <label> Customer Debit EGP </label>
                                        <input  class="form-control"  type="text" name="customer_debit" value="{{optional($invoice->customerShipperOrFfw)->debit_egp}}" readonly>
                                </div>  
                        </div> 
                        <div class="form-row">
                            <div class="col-md-12 form-group">
                                <label> Notes </label>
                                <textarea class="form-control" name="notes"></textarea>
                            </div> 
                        </div> 
                        @if($oldReceipts->count() != 0)  
                            <h4 style="color:#1b55e2">Receipts<h4>
                            <table id="charges" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Receipt No</th>
                                            <th class="text-center">Date</th>
                                            <th class="text-center">Customer</th>
                                            <th class="text-center">Invoice No</th>
                                            <th class="text-center">BL Draft</th>
                                            <th class="text-center">Payment Method</th>
                                            <th class="text-center">Total {{$invoice->add_egp == "false"? "USD" : "EGP"}}</th>
                                            <th class="text-center">Amount Paid {{$invoice->add_egp == "false"? "USD" : "EGP"}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($oldReceipts as $oldReceipt)
                                        <tr>
                                            <td class="text-center">{{ $oldReceipt->receipt_no }}</td>
                                            <td class="text-center">{{ $oldReceipt->created_at->format("Y-m-d") }}</td>
                                            <td class="text-center">{{ optional($oldReceipt->invoice->customerShipperOrFfw)->name }}</td>
                                            <td class="text-center">{{ optional($oldReceipt->invoice)->invoice_no }}</td>
                                            <td class="text-center">{{ optional($oldReceipt->bldraft)->ref_no }}</td>
                                            <td class="text-center">
                                                @if($oldReceipt->bank_transfer != null)
                                                    Bank Transfer <br>
                                                @endif
                                                @if($oldReceipt->bank_deposit != null)
                                                    Bank Deposit <br>
                                                @endif
                                                @if($oldReceipt->bank_check != null)
                                                    Bank Check <br>
                                                @endif
                                                @if($oldReceipt->bank_cash != null)
                                                    Cash <br>
                                                @endif
                                                @if($oldReceipt->matching != null)
                                                    Matching <br>
                                                @endif
                                            </td>
                                            <td class="text-center">{{$oldReceipt->total}}</td>
                                            <td class="text-center">{{$oldReceipt->paid}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                                <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-primary mt-3">{{trans('forms.create')}}</button>
                                    <a href="{{route('receipt.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                                </div>
                           </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
