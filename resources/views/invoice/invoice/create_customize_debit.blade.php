@extends('layouts.app')
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a a href="{{route('invoice.index')}}">Invoice</a></li> 
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">New Debit</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
           
                    <form id="createForm" action="{{route('invoice.store')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                        <div class="form-row">
                            <input type="hidden" name="bldraft_id" value="{{request()->input('bldraft_id')}}">
                                <div class="form-group col-md-6">
                                <label for="customer">Customer<span class="text-warning"> * (Required.) </span></label>
                                <select class="selectpicker form-control" name="customer_id" id="customer" data-live-search="true" data-size="10"
                                 title="{{trans('forms.select')}}" required>
                                        @foreach($ffws as $ffw)
                                            <option value="{{$ffw->id}}">{{ $ffw->name }} Forwarder</option>
                                        @endforeach
                                        @foreach($shippers as $shipper)
                                            <option value="{{$shipper->id}}">{{ $shipper->name }} Shipper</option>
                                        @endforeach
                                        @foreach($suppliers as $supplier)
                                            <option value="{{$supplier->id}}">{{ $supplier->name }} Supplier</option>
                                        @endforeach
                                        @foreach($notify as $notifys)
                                            <option value="{{$notifys->id}}">{{ $notifys->name }} Notify</option>
                                        @endforeach
                                </select>
                                @error('customer_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div> 
                            <div class="form-group col-md-6">
                                <label for="customer_id">Customer Name</label>
                                    <input type="text" id="notifiy" class="form-control"  name="customer"
                                    placeholder="Customer Name" autocomplete="off" required>
                            </div> 
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3" >
                                <label for="Date">Booking Ref</label>
                                    <select class="selectpicker form-control" id="booking_ref" data-live-search="true" name="booking_ref" data-size="10"
                                        title="{{trans('forms.select')}}">
                                        @foreach($bookings as $booking)
                                        <option value="{{$booking->id}}" {{$booking->id == old('booking_ref') ? 'selected':''}}>{{$booking->ref_no}}</option>
                                        @endforeach
                                        </select>
                                    @error('booking_ref')
                                    <div style="color: red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                            </div> 
                            <div class="form-group col-md-3" >
                                <label>Load Port</label>
                                <select class="selectpicker form-control" id="load_port" data-live-search="true" name="load_port" data-size="10"
                                title="{{trans('forms.select')}}" >
                                    @foreach($ports as $port)
                                    <option value="{{$port->id}}" {{$port->id == old('load_port') ? 'selected':''}}>{{$port->name}}</option>
                                    @endforeach
                                    </select>
                                @error('load_port')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div> 
                            <div class="form-group col-md-3" >
                                <label>Discharge Port</label>
                                <select class="selectpicker form-control" id="discharge_port" data-live-search="true" name="discharge_port" data-size="10"
                                    title="{{trans('forms.select')}}">
                                    @foreach($ports as $port)
                                        <option value="{{$port->id}}" {{$port->id == old('discharge_port') ? 'selected':''}}>{{$port->name}}</option>
                                    @endforeach
                                </select>
                                @error('discharge_port')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div> 
                            <div class="form-group col-md-3">
                                <label for="voyage_id">Vessel / Voyage </label>
                                <select class="selectpicker form-control" id="voyage_id" name="voyage_id" data-live-search="true" data-size="10"
                                    title="{{trans('forms.select')}}">
                                    @foreach ($voyages as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('voyage_id') ? 'selected':''}}>{{$item->vessel->name}} / {{$item->voyage_no}} - {{ optional($item->leg)->name }}</option>
                                    @endforeach
                                        </select>
                                    @error('voyage_id')
                                    <div style="color: red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                            </div>
                        </div> 
                            <div class="form-row">
                                <div class="form-group col-md-3">
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
                                <div class="form-group col-md-3" >
                                    <label for="Date">Date</label>
                                        <input type="date" class="form-control" name="date" placeholder="Date" autocomplete="off" value="{{old('date',date('Y-m-d'))}}">
                                </div>
                                <div class="form-group col-md-3" >
                                    <label>QTY<span class="text-warning"> *</span></label>
                                        <input type="text" class="form-control" placeholder="Qty" name="qty" autocomplete="off" style="background-color:#fff" value="1" required>
                                </div>
                                <div class="form-group col-md-3" >
                                    <label>Exchange Rate</label>
                                        <input type="text" class="form-control" placeholder="Exchange Rate" name="customize_exchange_rate"  value="48" autocomplete="off"  style="background-color:#fff" required>
                                </div>
                            </div> 
                            <div class="form-row">
                                <div class="col-md-12 form-group">
                                    <label> Notes </label>
                                    <textarea class="form-control" name="notes"></textarea>
                                </div> 
                            </div>
                        <h4>Charges<h4>
                            <table id="debit" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">Charge Description</th>
                                        <th class="text-center">Rate</th>
                                        <th class="text-center">Multiply QTY</th>
                                        <th class="text-center">Total</th>
                                        <th class="text-center"><a id="add"> Add <i class="fas fa-plus"></i></a></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                        <select class="selectpicker form-control"
                                                id="Charge Description" data-live-search="true"
                                                name="invoiceChargeDesc[0][charge_description]"
                                                data-size="10"
                                                title="{{trans('forms.select')}}" requierd>
                                            @foreach ($charges as $item)
                                                <option value="{{$item->name}}" {{$item->name == old($item->charge_description) ? 'selected':''}}>{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                            <!-- <input type="text" id="Charge Description" name="invoiceChargeDesc[0][charge_description]" class="form-control" autocomplete="off" placeholder="Charge Description" value ="Ocean Freight" required> -->
                                        </td>
                                        <td><input type="text" class="form-control" id="size_small" name="invoiceChargeDesc[0][size_small]" value=""
                                            placeholder="Amount" autocomplete="off"  style="background-color: white;"  required>
                                        </td>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="invoiceChargeDesc[0][enabled]" id="item_0_enabled_yes" value="1" checked>
                                                <label class="form-check-label" for="item_0_enabled_yes">Yes</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="invoiceChargeDesc[0][enabled]" id="item_0_enabled_no" value="0">
                                                <label class="form-check-label" for="item_0_enabled_no">No</label>
                                            </div>
                                        </td>
                                        <td><input type="text" class="form-control" id="ofr" name="invoiceChargeDesc[0][total_amount]" value=""
                                            placeholder="Total" autocomplete="off" disabled style="background-color: white;" required>
                                        </td>
                                        <td style="display: none;"><input type="hidden" class="form-control" id="egy_amount" name="invoiceChargeDesc[0][egy_amount]" disabled></td>
                                        <td style="display: none;"><input type="hidden" class="form-control" id="total_egy" name="invoiceChargeDesc[0][total_egy]" disabled></td>
                                        <td style="display: none;"><input type="hidden" class="form-control" id="egp_vat" name="invoiceChargeDesc[0][egp_vat]" disabled></td>

                                    </tr>
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
    $('#createForm').submit(function() {
        $('input').removeAttr('disabled');
    });
</script>
<script>
        $(function(){
                let customer = $('#customer');
                $('#customer').on('change',function(e){
                    let value = e.target.value;
                    let response =    $.get(`/api/master/customers/${customer.val()}`).then(function(data){
                        let notIfiy = data.customer[0] ;
                        let notifiy = $('#notifiy').val(' ' + notIfiy.name);
                    notifiy.html(list2.join(''));
                });
            });
        });
</script>

<script>
    $(document).ready(function(){
        $("#debit").on("click", ".remove", function () {
            $(this).closest("tr").remove();
        });
        var counter  = 1;
        $("#add").click(function(){
           var tr = '<tr>'+
               '<td><select class="selectpicker form-control" id="selectpickers" name="invoiceChargeDesc['+counter+'][charge_description]" data-size="10" title="{{trans('forms.select')}}"> @foreach ($charges as $item) <option value="{{$item->name}}" {{$item->name == old($item->charge_description) ? 'selected':''}} requierd>{{$item->name}}</option>@endforeach</select></td>' +
               '<td><input type="text" name="invoiceChargeDesc['+counter+'][size_small]" class="form-control" autocomplete="off" placeholder="Rate" required></td>'+
               '<td><div class="form-check"><input class="form-check-input" type="radio" name="invoiceChargeDesc['+counter+'][enabled]" id="item_'+counter+'_enabled_yes" value="1" checked><label class="form-check-label" for="item_'+counter+'_enabled_yes">Yes</label></div><div class="form-check"><input class="form-check-input" type="radio" name="invoiceChargeDesc['+counter+'][enabled]" id="item_'+counter+'_enabled_no" value="0"><label class="form-check-label" for="item_'+counter+'_enabled_no">No</label></div></td>'+
               '<td><input type="text" name="invoiceChargeDesc['+counter+'][total_amount]" class="form-control" autocomplete="off" placeholder="Total" disabled required></td>'+
               '<td style="display: none;"><input type="hidden" class="form-control" id="egy_amount" name="invoiceChargeDesc['+counter+'][egy_amount]" disabled></td>'+
               '<td style="display: none;"><input type="hidden" class="form-control" id="total_egy" name="invoiceChargeDesc['+counter+'][total_egy]" disabled></td>'+
               '<td style="display: none;"><input type="hidden" class="form-control" id="egp_vat" name="invoiceChargeDesc['+counter+'][egp_vat]" disabled></td>'+
               '<td style="width:85px;"><button type="button" class="btn btn-danger remove"><i class="fa fa-trash"></i></button></td>'
           '</tr>';
           counter++;
          $('#debit').append(tr);
          $('.selectpicker').selectpicker("render");
          $('#selectpickers').selectpicker();

        });
    }); 
    </script>

    <script>
        function calculateAmounts() {
            $('#debit tbody tr').each(function() {
                let row = $(this);
                let qty = parseFloat($('input[name="qty"]').val()) || 0;
                let exchangeRate = parseFloat($('input[name="customize_exchange_rate"]').val()) || 0;
                let sizeSmall = parseFloat(row.find('input[name*="[size_small]"]').val()) || 0;
                let enabled = row.find('input[name*="[enabled]"]:checked').val() == "1";

                let totalAmount = enabled ? sizeSmall * qty : sizeSmall;
                let egyAmount = sizeSmall * exchangeRate;
                let totalEgy = totalAmount * exchangeRate;

                row.find('input[name*="[total_amount]"]').val(totalAmount.toFixed(2));
                row.find('input[name*="[egy_amount]"]').val(egyAmount.toFixed(2));
                row.find('input[name*="[total_egy]"]').val(totalEgy.toFixed(2));
                row.find('input[name*="[egp_vat]"]').val(totalEgy.toFixed(2));
            });
        }

        $(document).on('input', 'input[name="qty"], input[name="customize_exchange_rate"], input[name*="[size_small]"]', function() {
            calculateAmounts();
        });

        $(document).on('change', 'input[name*="[enabled]"]', function() {
            calculateAmounts();
        });

        calculateAmounts();
    </script>
@endpush