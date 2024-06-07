@extends('layouts.app')
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a a href="{{route('refund.index')}}">Refund</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">New Refund</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
           
                    <form id="createForm" action="{{route('refund.store')}}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="form-row">
                                <div class="form-group col-md-9">
                                    <label for="Customer">Customer</label>
                                    <select class="selectpicker form-control" id="customer" data-live-search="true" name="customer_id" data-size="10"
                                     title="{{trans('forms.select')}}" required>
                                     @foreach($customers as $customer)
                                            <option value="{{$customer->id}}" {{$customer->id == old('customer_id',request()->input('customer_id')) ? 'selected':''}}>{{$customer->name}}</option>
                                    @endforeach
                                    </select>
                                </div>     
                                <div class="form-group col-md-3">
                                    <label>Refund No</label>
                                    <input type="text" class="form-control"  style="background-color:#fff" name="receipt_no">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-5 form-group">
                                    <label> Customer Credit USD </label>
                                        <input  class="form-control" id="creditusd"  type="text" name="customer_credit" value="" readonly>
                                </div>
                                <div class="col-md-5 form-group">
                                    <label> Customer Credit EGP </label>
                                        <input  class="form-control"  id="credit_egp" type="text" name="customer_credit" value="" readonly>
                                </div>
                                <div class="form-group col-md-2">
                                    <div style="padding: 30px;">
                                        <input class="form-check-input" type="radio" name="currency" id="currency" value="refund_usd" checked>
                                        <label class="form-check-label" for="currency">
                                          USD
                                        </label>
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        <input class="form-check-input" type="radio" name="currency" id="currency" value="refund_egp">
                                        <label class="form-check-label" for="currency">
                                          EGP
                                        </label> 
                                    </div>
                                </div>

                        </div> 
                            <h4 style="color:#1b55e2">Payment Methods<h4>
                            <div class="form-row">
                                <div class="col-md-2 form-group">
                                    <label>Cash</label>
                                        <input type="text" class="form-control"  placeholder="Cash" name="bank_cash" value="{{old('bank_cash',request()->input('bank_cash'))}}"  autocomplete="off">
                                </div>
                    
                                <div class="col-md-2 form-group">
                                    <label>Cheque Amount</label>
                                    <input type="text" class="form-control" placeholder="Cheque Amount"  name="bank_check" value="{{old('bank_check',request()->input('bank_check'))}}"  autocomplete="off">
                                </div>

                                <div class="form-group col-md-8" >
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
                            <div class="col-md-2 form-group">
                                <label> Bank Deposit </label>
                                <input type="text" class="form-control" placeholder="Bank Deposit" name="bank_deposit" value="{{old('bank_deposit',request()->input('bank_deposit'))}}" autocomplete="off">
                            </div>
                        
                            <div class="col-md-2 form-group">
                                <label> Bank Transfer </label>
                                    <input type="text" class="form-control" placeholder="Bank Transfer" name="bank_transfer" value="{{old('bank_transfer',request()->input('bank_transfer'))}}"  autocomplete="off">
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
                            <div class="col-md-12 form-group">
                                <label> Notes </label>
                                <textarea class="form-control" name="notes"></textarea>
                            </div> 
                        </div> 
          
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
@push('scripts')

<script>
$(function(){
    let customer = $('#customer');
    $('#customer').on('change',function(e){
        let value = e.target.value;
        let response =    $.get(`/api/master/customers/${customer.val()}`).then(function(data){
            let customers = data.customer[0] ;
            let creditusd = customers.credit;
            let credit_egp = customers.credit_egp; 
            $('#creditusd').val(creditusd); 
            $('#credit_egp').val(credit_egp);
        });
    });
});
</script>
@endpush
