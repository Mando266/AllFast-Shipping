@extends('layouts.app')
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('invoice.index')}}">Invoice</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">New Debit</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">

                    <form id="createForm" action="{{route('invoice.store')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-row">
                            @if(request()->has('bldraft_id'))
                                <input type="hidden" name="bldraft_id" value="{{request()->input('bldraft_id')}}">
                            @else    
                                <input type="hidden" name="booking_ref" value="{{request()->input('booking_ref')}}">
                            @endif 
                           <div class="form-group col-md-6">
                                <label for="customer">Customer<span class="text-warning"> *</span></label> 
                                <select class="selectpicker form-control" name="customer_id" id="customer" data-live-search="true" data-size="10" title="{{trans('forms.select')}}">
                                    @if($bldraft != null && request()->has('booking_ref'))
                                        @if(optional($bldraft->consignee)->name != null)
                                            <option value="{{optional($bldraft)->customer_consignee_id}}">{{ optional($bldraft->consignee)->name }}
                                                Consignee
                                            </option>
                                        @endif
                                    @elseif($bldraft != null && request()->has('bldraft_id'))
                                        @if(optional($bldraft->customer)->name != null)
                                        <option value="{{optional($bldraft)->customer_id}}">{{ optional($bldraft->customer)->name }}
                                            Shipper
                                        </option>
                                        @endif
                                        @if(optional($bldraft->booking->forwarder)->name != null)
                                            <option value="{{optional($bldraft)->ffw_id}}">{{ optional($bldraft->booking->forwarder)->name }}
                                                Forwarder
                                            </option>
                                        @endif
                                        @if(optional($bldraft->customerNotify)->name != null)
                                            <option value="{{optional($bldraft)->customer_notifiy_id}}">{{ optional($bldraft->customerNotify)->name }}
                                                Notify
                                            </option>
                                        @endif
                                    @endif
                                </select>
                                @error('customer')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="customer">Customer Name</label>
                                <input type="text" id="notifiy" class="form-control" name="customer" placeholder="Customer Name" autocomplete="off">
                                @error('customer')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="Date">Bill Of Lading No</label>
                                    <input type="text" class="form-control" placeholder="Booking Ref" autocomplete="off" value="{{(optional($bldraft)->ref_no)}}" style="background-color:#fff" disabled>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Load Port</label>
                                @if(optional($bldraft)->load_port_id != null)
                                    <input type="text" class="form-control" placeholder="Load Port" autocomplete="off" value="{{(optional($bldraft->loadPort)->code)}}" style="background-color:#fff" disabled>
                                @endif
                            </div>
                            <div class="form-group col-md-3">
                                <label>Discharge Port</label>
                                @if(optional($bldraft)->discharge_port_id != null)
                                    <input type="text" class="form-control" placeholder="Discharge Port" autocomplete="off" value="{{(optional($bldraft->dischargePort)->code)}}" style="background-color:#fff" disabled>
                                @endif
                            </div>
                            <div class="form-group col-md-3">
                                <label for="voyage_id">Vessel / Voyage </label>
                                <select class="selectpicker form-control" id="voyage_id" name="voyage_id" data-live-search="true" data-size="10" title="{{trans('forms.select')}}" disabled>
                                    @foreach ($voyages as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('voyage_id',$bldraft->voyage_id) ? 'selected':''}}>{{$item->vessel->name}} / {{$item->voyage_no}} - {{ optional($item->leg)->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-row">

                            <div class="form-group col-md-4">
                                <label for="status">Invoice Status<span class="text-warning"> * </span></label>
                                <select class="form-control" data-live-search="true" name="invoice_status" title="{{trans('forms.select')}}" required>
                                    @permission('Invoice-Draft')
                                        <option value="draft">Draft</option>
                                    @endpermission
                                    @permission('Invoice-ReadyToConfirm')
                                        <option value="ready_confirm">Ready To Confirm</option>
                                    @endpermission
                                </select>
                                @error('invoice_status')
                                <div style="color:red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label>QTY</label>
                                <input type="text" class="form-control" placeholder="Qty" name="qty" autocomplete="off" value="{{$qty}}" style="background-color:#fff" disabled>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="Date">Date</label>
                                <input type="date" class="form-control" name="date" placeholder="Date" autocomplete="off" required value="{{old('date',date('Y-m-d'))}}">
                            </div>
                            <!-- <div class="form-group col-md-3"> -->
                                <!-- <label>Exchange Rate</label> -->
                                <input class="form-control" type="hidden" name="customize_exchange_rate" id="custom_rate_input" placeholder="Exchange Rate" value='48'>
                            <!-- </div> -->
                        </div>
                        <div class="form-row">
                            <div class="col-md-12 form-group">
                                <label> Notes </label>
                                <textarea class="form-control" name="notes" rows="4">{{ isset($notes) ? implode("\n", $notes) : '' }}</textarea>
                            </div>
                        </div>
                        <h4>Charges</h4>
                        <table id="containerDepit" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">Charge Description</th>
                                    <th class="text-center">Amount</th>
                                    <th class="text-center">Total Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!isset($detentionAmount))
                                    <tr>
                                        <td>
                                            <select class="selectpicker form-control" id="Charge Description" data-live-search="true" name="invoiceChargeDesc[0][charge_description]" data-size="10" title="{{trans('forms.select')}}">
                                                @foreach ($charges as $item)
                                                    <option value="{{$item->name}}" {{$item->name == old($item->charge_description)? 'selected':''}}>{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </td> 
                                        <td><input type="text" class="form-control" id="size_small" name="invoiceChargeDesc[0][size_small]" value="" placeholder="Amount" autocomplete="off"  style="background-color: white;" disabled></td>
                                        <td><input type="text" class="form-control" id="ofr" name="invoiceChargeDesc[0][total_amount]" value="" placeholder="Total Amount" autocomplete="off" style="background-color: white;" disabled></td>
                                    
                                        <td style="display: none;"><input type="hidden" class="form-control" id="calculated_amount" name="invoiceChargeDesc[0][egy_amount]"></td>
                                        <td style="display: none;"><input type="hidden" class="form-control" id="calculated_total_amount" name="invoiceChargeDesc[0][total_egy]"></td>
                                        <td style="display: none;"><input type="hidden" class="form-control" id="calculated_total_amount_vat" name="invoiceChargeDesc[0][egp_vat]"></td>
                                    </tr>
                                @else
                                    <tr>
                                        <td>
                                            <select class="selectpicker form-control" id="Charge Description" data-live-search="true" name="invoiceChargeDesc[0][charge_description]" data-size="10" title="{{trans('forms.select')}}" required>
                                                @foreach ($charges as $item)
                                                    <option value="{{$item->name}}" {{($item->name == old($item->charge_description)|| $item->code =='EG-560161093-ID')  ? 'selected':''}}>{{$item->name}}</option>

                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input type="text" class="form-control" id="size_small" name="invoiceChargeDesc[0][size_small]" value="{{$detentionAmount  / $qty}} " placeholder="Weight" autocomplete="off" disabled style="background-color: white;"></td>
                                        <td><input type="text" class="form-control" id="ofr" name="invoiceChargeDesc[0][total_amount]" value="{{$detentionAmount}}" placeholder="Ofr" autocomplete="off" disabled style="background-color: white;"></td>
                                        <td style="display: none;"><input type="hidden" class="form-control" id="calculated_amount" name="invoiceChargeDesc[0][egy_amount]"></td>
                                        <td style="display: none;"><input type="hidden" class="form-control" id="calculated_total_amount" name="invoiceChargeDesc[0][total_egy]"></td>
                                        <td style="display: none;"><input type="hidden" class="form-control" id="calculated_total_amount_vat" name="invoiceChargeDesc[0][egp_vat]"></td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary mt-3">{{trans('forms.create')}}</button>
                                <a href="{{route('invoice.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
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
    $(document).ready(function() {
        localStorage.removeItem('cart');
        $("#createForm").validate();
    });
    $('#createForm').submit(function() {
        $('input').removeAttr('disabled');
        $('select').removeAttr('disabled');
    });

    $(function(){
        let customer = $('#customer');
        $('#customer').on('change',function(e){
            let value = e.target.value;
            let response = $.get(`/api/master/customers/${customer.val()}`).then(function(data){
                let notIfiy = data.customer[0];
                let notifiy = $('#notifiy').val(' ' + notIfiy.name);
                notifiy.html(list2.join(''));
            });
        });
    });

    function calculateAmounts() {
        let exchangeRate = parseFloat($('#custom_rate_input').val());
        let sizeSmall = parseFloat($('input[name="invoiceChargeDesc[0][size_small]"]').val());
        let totalAmount = parseFloat($('input[name="invoiceChargeDesc[0][total_amount]"]').val());
        let qty = parseFloat($('input[name="qty"]').val());

        let totalUsd = sizeSmall * qty;
        let egyAmount = sizeSmall * exchangeRate;
        let totalEgy = totalUsd * exchangeRate;
        let totalEgyaftervat = totalUsd * exchangeRate;

        $('input[name="invoiceChargeDesc[0][total_amount]"]').val(totalUsd.toFixed(2));
        $('input[name="invoiceChargeDesc[0][egy_amount]"]').val(egyAmount.toFixed(2));
        $('input[name="invoiceChargeDesc[0][total_egy]"]').val(totalEgy.toFixed(2));
        $('input[name="invoiceChargeDesc[0][egp_vat]"]').val(totalEgy.toFixed(2));
    }

    $(document).on('input', '#custom_rate_input, input[name="invoiceChargeDesc[0][size_small]"], input[name="invoiceChargeDesc[0][total_amount]"]', function() {
        calculateAmounts();
    });

    $(document).ready(function() {
        calculateAmounts();
    });
</script>
@endpush
