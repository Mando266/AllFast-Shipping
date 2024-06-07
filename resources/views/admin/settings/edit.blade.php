@extends('layouts.app')
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Master Data </a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">System Serial Setting</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol> 
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                <form id="editForm" action="{{route('settings.update',['setting'=>$setting])}}" method="POST" >
                            @csrf
                            @method('put')
                        <div class="form-row">
                            <div class="form-group col-md-3" >
                                <label>Invoice Draft Serial</label>
                                    <input type="text" class="form-control" placeholder="Invoice Draft Serial" autocomplete="off" value="{{old('invoice_draft',$setting->invoice_draft)}}" style="background-color:#fff" name="invoice_draft">
                                    
                            </div> 

                            <div class="form-group col-md-3" >
                                <label>Invoice EGP Confirm Serial</label>
                                    <input type="text" class="form-control" placeholder="Invoice EGP Confirmed Serial" autocomplete="off" value="{{old('invoice_confirm_egp',$setting->invoice_confirm_egp)}}" style="background-color:#fff" name="invoice_confirm_egp">
                                    
                            </div>

                            <div class="form-group col-md-3" >
                                <label>Invoice USD Confirm Serial</label>
                                    <input type="text" class="form-control" placeholder="Invoice USD Confirmed Serial" autocomplete="off" value="{{old('invoice_confirm_usd',$setting->invoice_confirm_usd)}}" style="background-color:#fff" name="invoice_confirm_usd">
                                    
                            </div>

                            <div class="form-group col-md-3" >
                                <label>Debit Draft Serial</label>
                                    <input type="text" class="form-control" placeholder="Debit Draft Serial" autocomplete="off" value="{{old('debit_draft',$setting->debit_draft)}}" style="background-color:#fff" name="debit_draft">      
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3" >
                                <label>Debit Confirmed Serial</label>
                                    <input type="text" class="form-control" placeholder="Debit Confirmed Serial" autocomplete="off" value="{{old('debit_confirm',$setting->debit_confirm)}}" style="background-color:#fff" name="debit_confirm">
                            </div>
                            <div class="form-group col-md-3" >
                                <label>Receipt No</label>
                                    <input type="text" class="form-control" placeholder="Receipt No" autocomplete="off" value="{{old('receipt_no',$setting->receipt_no )}}" style="background-color:#fff" name="receipt_no">
                            </div>
                            <div class="form-group col-md-3" >
                                <label>Booking REf NO</label>
                                    <input type="text" class="form-control" placeholder="Booking REf NO" autocomplete="off" value="{{old('booking_ref_no',$setting->booking_ref_no )}}" style="background-color:#fff" name="booking_ref_no">
                            </div>
                            <div class="form-group col-md-3" >
                                <label>Deleviry Order NO</label>
                                    <input type="text" class="form-control" placeholder="Deleviry Order NO" autocomplete="off" value="{{old('delivery_no',$setting->delivery_no )}}" style="background-color:#fff" name="delivery_no">
                            </div>
                        </div>

                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-primary mt-3">{{trans('forms.edit')}}</button>
                                </div>
                           </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
