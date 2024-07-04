@extends('layouts.app')
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            @if($invoice->type == "invoice")
                            <li class="breadcrumb-item"><a a href="{{route('invoice.index')}}">Invoice</a></li> 
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">Edit Invoice</a></li>
                            @else
                            <li class="breadcrumb-item"><a a href="{{route('invoice.index')}}">Invoice</a></li> 
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">Edit Debit</a></li>
                            @endif
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
           
                <form id="editForm" action="{{route('invoice.update',['invoice'=>$invoice])}}" method="POST" >
                            @csrf
                            @method('put')
                        <div class="form-row">
                            <div class="form-group col-md-2">
                                <label for="Invoice">Invoice No</label>
                                    <input type="text" id="Invoice" class="form-control"  name="invoice_no"
                                    placeholder="Invoice No" autocomplete="off" value="{{old('invoice_no',$invoice->invoice_no)}}" disabled>
                            </div> 
                                <div class="form-group col-md-6">
                                <label for="customer">Customer<span class="text-warning"> * (Required.) </span></label>
                                <select class="selectpicker form-control" name="customer_id" id="customer" data-live-search="true" data-size="10" title="{{ trans('forms.select') }}" required>
                                    @if($bldraft != null)
                                        @if(optional($bldraft->forwarder)->name != null)
                                            <option value="{{ optional($bldraft)->ffw_id }}" {{ optional($bldraft)->ffw_id == old('customer_id', $invoice->customer_id) ? 'selected' : '' }}>
                                                {{ optional($bldraft->forwarder)->name }} Forwarder
                                            </option>
                                        @elseif(optional($bldraft->consignee)->name != null)
                                            <option value="{{ optional($bldraft)->customer_consignee_id }}" {{ optional($bldraft)->customer_consignee_id == old('customer_id', $invoice->customer_id) ? 'selected' : '' }}>
                                                {{ optional($bldraft->consignee)->name }} Consignee
                                            </option>
                                        @endif
                                        @if(optional($bldraft->customerNotify)->name != null)
                                            <option value="{{ optional($bldraft)->customer_notifiy_id }}" {{ optional($bldraft)->customer_notifiy_id == old('customer_id', $invoice->customer_id) ? 'selected' : '' }}>
                                                {{ optional($bldraft->customerNotify)->name }} Notify
                                            </option>
                                        @endif
                                        @if(optional($bldraft->customer)->name != null)
                                            <option value="{{ optional($bldraft)->customer_id }}" {{ optional($bldraft)->customer_id == old('customer_id', $invoice->customer_id) ? 'selected' : '' }}>
                                                {{ optional($bldraft->customer)->name }} Shipper
                                            </option>
                                        @endif
                                    @endif
                                </select>
                                @error('customer_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div> 
                            <div class="form-group col-md-4">
                                <label for="customer_id">Customer Name</label>
                                    <input type="text" id="notifiy" class="form-control"  name="customer"
                                    placeholder="Customer Name" autocomplete="off" value="{{old('customer',$invoice->customer)}}" required>
                            </div> 
                        </div>
                        <div class="form-row"> 
                            <div class="form-group col-md-3" >
                                <label for="Date">Booking Ref</label>
                                    <input type="text" class="form-control" placeholder="Booking Ref" autocomplete="off" value="{{(optional($bldraft)->ref_no)}}" style="background-color:#fff" disabled>
                            </div> 
                            <div class="form-group col-md-3" >
                                <label>Load Port</label>
                                    @if(optional($bldraft)->load_port_id != null)
                                    <input type="text" class="form-control" placeholder="Load Port" autocomplete="off" value="{{(optional($bldraft->loadPort)->code)}}" style="background-color:#fff" disabled>
                                    @endif
                            </div> 
                            <div class="form-group col-md-3" >
                                        <label>Discharge Port</label>
                                        @if(optional($bldraft)->discharge_port_id != null)
                                        <input type="text" class="form-control" placeholder="Discharge Port" autocomplete="off" value="{{(optional($bldraft->dischargePort)->code)}}" style="background-color:#fff" disabled>
                                        @endif
                            </div> 
                            <div class="form-group col-md-3">
                                <label for="voyage_id">Vessel / Voyage </label>
                                <select class="selectpicker form-control" id="voyage_id" data-live-search="true" name="voyage_id"  data-size="10"
                                    title="{{trans('forms.select')}}" disabled>
                                    @foreach ($voyages as $item)
                                    @if(optional($bldraft)->voyage_id != null )
                                        <option value="{{$item->id}}" {{$item->id == old('voyage_id',$bldraft->voyage_id) ? 'selected':''}}>{{$item->vessel->name}} / {{$item->voyage_no}} - {{ optional($item->leg)->name }}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>

                            </div> 
                            <div class="form-row">        
                                @if($invoice->invoice_status != "confirm")
                                <div class="form-group col-md-4">
                                    <label for="status">Invoice Status<span class="text-warning"> * </span></label>
                                    <select class="form-control" data-live-search="true" name="invoice_status" title="{{trans('forms.select')}}" required>
                                        @permission('Invoice-Draft')
                                        <option value="draft" {{ old('invoice_status',$invoice->invoice_status) == "draft" ? 'selected':'' }}>Draft</option>
                                        @endpermission
                                        @permission('Invoice-ReadyToConfirm')
                                        <option value="ready_confirm" {{ old('invoice_status',$invoice->invoice_status) == "ready_confirm" ? 'selected':'' }}>Ready To Confirm</option>
                                        @endpermission
                                        @if(auth()->user()->can('Invoice-Confirm') && $invoice->invoice_status == "ready_confirm")
                                        <option value="confirm" {{ old('invoice_status',$invoice->invoice_status) == "confirm" ? 'selected':'' }}>Confirm</option>
                                        @endif
                                    </select>
                                    @error('invoice_status')
                                    <div style="color:red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                @endif
                                <div class="form-group col-md-4" >
                                    <label for="Date">Date</label>
                                        <input type="date" class="form-control" name="date" placeholder="Date" autocomplete="off" value="{{old('date',date('Y-m-d'))}}">
                                </div>
                                <div class="form-group col-md-4" >
                                    <label>QTY</label>
                                        <input type="text" class="form-control" placeholder="Qty"  name="qty" autocomplete="off" value="{{$qty}}" style="background-color:#fff" disabled>
                                </div>
                            </div>
                            <div class="form-row">
                                @if($invoice->type == "invoice")
                                <div class="form-group col-md-3">
                                    <label for="vat">VAT %:</label>
                                    <input type="text" class="form-control" id="vat" name="vat" value="{{old('vat',$invoice->vat)}}">
                                </div>
                                @endif
                                @if($invoice->type == "invoice")
                                <div class="form-group col-md-3" >
                                    <label>TAX</label>
                                        <input type="text" class="form-control" placeholder="TAX %" name="tax_discount"  value="{{old('tax_discount',$invoice->tax_discount)}}" autocomplete="off"  style="background-color:#fff" >
                                </div>
                                @if($invoice->add_egp == 'false')
                                    <div class="col-md-3 form-group">
                                        <label>Exchange Rate</label>
                                        <input class="form-control"  type="text" name="customize_exchange_rate" id="exchange_rate" placeholder="Exchange Rate" autocomplete="off" value="{{old('customize_exchange_rate',$invoice->customize_exchange_rate)}}" required>
                                    </div>
                                @else 
                                    <input class="form-control"  type="hidden" name="customize_exchange_rate" id="exchange_rate" placeholder="Exchange Rate" autocomplete="off" value="1">
                                @endif

                                <div class="form-group col-md-3" >
                                    <div style="padding: 30px;">
                                    @if($invoice->add_egp == "false")
                                        <input class="form-check-input" type="radio" name="add_egp" id="add_egp" value="false" {{ "false" == old('add_egp',$invoice->add_egp) ? 'checked':''}}>
                                        <label class="form-check-label" for="add_egp">
                                          USD
                                        </label>
                                        @else
                                        <br>
                                        <input class="form-check-input" type="radio" name="add_egp" id="add_egp" value="onlyegp" {{ "onlyegp" == old('add_egp',$invoice->add_egp) ? 'checked':''}}>
                                        <label class="form-check-label" for="add_egp">
                                          EGP
                                        </label>
                                    @endif
                                    </div>
                                </div>
                                @else
                                <div class="col-md-2 form-group">
                                    <label>Exchange Rate</label>
                                    <input class="form-control"  type="text" name="customize_exchange_rate" id="custom_rate_input" placeholder="Exchange Rate" autocomplete="off" value="{{old('customize_exchange_rate',$invoice->customize_exchange_rate ?? '48')}}" required>
                                </div>
                                @endif
                            </div> 
                            <div class="form-row">
                                <div class="col-md-12 form-group">
                                    <label> Notes </label>
                                    <textarea class="form-control" name="notes">{{old('notes',$invoice->notes)}}</textarea>
                                </div>
                            </div>
                        <h4>Charges<h4>
                    @if($invoice->type == "invoice")
                        <table id="charges" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">Charge Description</th>
                                        <th class="text-center">Amount</th>
                                        <th class="text-center d-flex flex-column align-items-center">Add VAT
                                            <span class="form-check pb-3">
                                                <input class="form-check-input" type="checkbox" id="selectAllVat">
                                            </span>
                                        </th>                                        
                                        <th class="text-center">Multiply QTY</th>
                                        <th class="text-center">TOTAL USD</th>
                                        <th class="text-center">USD After VAT</th>
                                        <th class="text-center">Total Egp</th>
                                        <th class="text-center">EGP After VAT</th>
                                       @if($invoice->type == "invoice")
                                        <th class="text-center"><a id="add"> Add <i class="fas fa-plus"></i></a></th>
                                        @else
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                            @foreach($invoice_details as $key => $item)
                            <tr>
                                <input type="hidden" value ="{{ $item->id }}" name="invoiceChargeDesc[{{ $key }}][id]">
                                <td>
                                    <select class="selectpicker form-control" id="charge_description" data-live-search="true" name="invoiceChargeDesc[{{$key}}][charge_description]" data-size="10"
                                        title="{{trans('forms.select')}}" autofocus disabled>
                                        @foreach ($charges as $charge)
                                            <option value="{{$charge->name}}" {{$charge->name == old('charge_description',$item->charge_description)? 'selected':''}}>{{$charge->name}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="text" id="size_small" name="invoiceChargeDesc[{{ $key }}][size_small]" class="form-control" 
                                    autocomplete="off" placeholder="" value="{{old('size_small',$item->size_small)}}"  style="background-color: white;" required> 
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input vatRadio" type="radio" name="invoiceChargeDesc[{{$key}}][add_vat]" id="item_{{$key}}_enabled_yes" value="1" {{ $item->add_vat ==  1 ? 'checked="checked"' :''}}>
                                        <label class="form-check-label" for="item_{{$key}}_enabled_yes">Yes</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input vatRadio" type="radio" name="invoiceChargeDesc[{{$key}}][add_vat]" id="item_{{$key}}_enabled_no" value="0" {{ $item->add_vat ==  0 ? 'checked="checked"' :''}}>
                                        <label class="form-check-label" for="item_{{$key}}_enabled_no">No</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="invoiceChargeDesc[{{$key}}][enabled]" id="item_{{$key}}_enabled_yes" value="1" {{ $item->enabled ==  1 ? 'checked="checked"' :''}} disabled>
                                        <label class="form-check-label" for="item_{{$key}}_enabled_yes">Yes</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="invoiceChargeDesc[{{$key}}][enabled]" id="item_{{$key}}_enabled_no"  value="0" {{$item->enabled == 0 ? 'checked="checked"' :''}} disabled>
                                        <label class="form-check-label" for="item_{{$key}}_enabled_no">No</label>
                                    </div>
                                </td>


                                <td><input type="text" class="form-control" id="usd_{{ $key }}" name="invoiceChargeDesc[{{ $key }}][total_amount]" value="{{old('total_amount',$item->total_amount)}}"
                                    placeholder="Total" autocomplete="off"  style="background-color: white;" required disabled>
                                </td>
                                <td><input type="text" id="usd_vat_{{ $key }}" name="invoiceChargeDesc[{{ $key }}][usd_vat]" class="form-control" autocomplete="off" placeholder="USD After VAT" disabled></td>


                                <td><input type="text" class="form-control" id="egp_{{ $key }}" name="invoiceChargeDesc[{{ $key }}][total_egy]" value="{{old('total_egy',$item->total_egy)}}"
                                    placeholder="Egp Amount" autocomplete="off" style="background-color: white;" required disabled>
                                </td>
                                
                                <td><input id="egp_vat_{{ $key }}" type="text" name="invoiceChargeDesc[{{ $key }}][egp_vat]" class="form-control" autocomplete="off" placeholder="Egp After VAT" disabled></td>

                            @if($invoice->type == "invoice")
                                    <td style="width:85px;">
                                    <button type="button" class="btn btn-danger remove" onclick="removeItem({{$item->id}})"><i class="fa fa-trash"></i></button>
                                    </td>
                                @endif
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @else
                        <table id="containerDetails" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">Charge Description</th>
                                    <th class="text-center">Rate 20/40</th>
                                    <th class="text-center">Amount 20/40</th>
                                </tr>
                            </thead>
                            <tbody>
                     @foreach($invoice_details as $key => $item)
                        <tr>
                            <input type="hidden" value ="{{ $item->id }}" name="invoiceChargeDesc[{{ $key }}][id]">
                            <td>
                                <select class="selectpicker form-control" id="charge_description" data-live-search="true" name="invoiceChargeDesc[{{$key}}][charge_description]" data-size="10"
                                    title="{{trans('forms.select')}}" autofocus required>
                                    @foreach ($charges as $charge)
                                        <option value="{{$charge->name}}" {{$charge->name == old('charge_description',$item->charge_description)? 'selected':''}}>{{$charge->name}}</option>
                                    @endforeach
                                </select>
                                <!-- <input type="text" id="Charge Description" name="invoiceChargeDesc[{{ $key }}][charge_description]" class="form-control" autocomplete="off" placeholder="Charge Description" value="{{(old('charge_description',$item->charge_description))}}" > -->
                            </td>
                            <td><input type="text" class="form-control" id="size_small_1" name="invoiceChargeDesc[{{ $key }}][size_small]" value="{{(old('size_small',$item->size_small))}}"
                                placeholder="Rate" autocomplete="off" disabled style="background-color: white;">
                            </td> 
                            <td><input type="text" class="form-control" id="total_amount_1" name="invoiceChargeDesc[{{ $key }}][total_amount]" value="{{(old('total_amount',$item->total_amount))}}"
                                placeholder="Ofr" autocomplete="off" disabled style="background-color: white;">
                            </td>
                            <td style="display:none;"><input type="hidden" class="form-control" id="egy_amount_1" name="invoiceChargeDesc[0][egy_amount]" disabled></td>
                            <td style="display:none;"><input type="hidden" class="form-control" id="total_egy_1" name="invoiceChargeDesc[0][total_egy]" disabled></td>
                            <td style="display:none;"><input type="hidden" class="form-control" id="egp_vat_1" name="invoiceChargeDesc[0][egp_vat]" disabled></td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @endif
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-primary mt-3">Confirm</button>
                                    <a href="{{route('invoice.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                                </div>
                           </div>
                           <input name="removed" id="removed" type="hidden"  value="">
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
        
        // Function to handle the select all checkbox for VAT
        $('#selectAllVat').change(function() {
            var isChecked = $(this).is(':checked');
            $('#charges tbody tr').each(function() {
                $(this).find('input[name$="[add_vat]"][value="' + (isChecked ? '1' : '0') + '"]').prop('checked', true);
            });
        });

        function calculateTotals() {
            var exchangeRate = parseFloat($('#exchange_rate').val());
            var qty = parseFloat($('input[name="qty"]').val());
            var vatRate = parseFloat($('#vat').val()) / 100;
            var totalUsd = 0;
            var totalEgp = 0;

            $('#charges tbody tr').each(function() {
                var sizeSmall = parseFloat($(this).find('input[name$="[size_small]"]').val());
                var enabled = $(this).find('input[name$="[enabled]"]:checked').val();
                var addVat = $(this).find('input[name$="[add_vat]"]:checked').val();
                var totalAmount = enabled == 1 ? sizeSmall * qty : sizeSmall;
                var egpAmount = totalAmount * exchangeRate;
                var egpVat = addVat == 1 ? egpAmount * (1 + vatRate) : egpAmount;
                var usdVat = addVat == 1 ? totalAmount * (1 + vatRate) : totalAmount;

                $(this).find('input[name$="[total_amount]"]').val(totalAmount.toFixed(2));
                $(this).find('input[name$="[total_egy]"]').val(egpAmount.toFixed(2));
                $(this).find('input[name$="[egp_vat]"]').val(egpVat.toFixed(2));
                $(this).find('input[name$="[usd_vat]"]').val(usdVat.toFixed(2));

                totalUsd += totalAmount;
                totalEgp += egpAmount;
            });

            $('#total_usd').val(totalUsd.toFixed(2));
            $('#total_egp').val(totalEgp.toFixed(2));
        }

        // Perform calculations on exchange rate input change
        $('#exchange_rate').on('input', function() {
            calculateTotals();
        });

        // Perform calculations on page load
        calculateTotals();

        // Event listeners for changes in the charge rows
        $('body').on('input', 'input[name$="[size_small]"], input[name="qty"], input[name$="[enabled]"], input[name$="[add_vat]"]', function() {
            calculateTotals();
        });

        // Handle VAT calculation
        function handleVatInput() {
            $('#charges tbody tr').each(function() {
                var addVat = $(this).find('input[name$="[add_vat]"]:checked').val();
                if (addVat == 1) {
                    var egpAmount = parseFloat($(this).find('input[name$="[total_egy]"]').val());
                    var vatRate = parseFloat($('#vat').val()) / 100;
                    var egpVat = egpAmount * (1 + vatRate);
                    $(this).find('input[name$="[egp_vat]"]').val(egpVat.toFixed(2));
                }
            });
        }

        // Event listener for VAT input change
        $('#vat').on('input', function() {
            handleVatInput();
            calculateTotals();
        });

        // Initialize customer change event
        $('#customer').on('change', function() {
            var customer = $(this).val();
            $.get(`/api/master/customers/${customer}`).then(function(data) {
                var notify = data.customer[0];
                $('#notifiy').val(' ' + notify.name);
            });
        });

        // Submit form
        $('#editForm').submit(function() {
            $('input, select').removeAttr('disabled');
        });

        $(document).ready(function() {
            calculateTotals();
        });
    });
</script>

<script>
    var removed = [];
    function removeItem(item) {
        removed.push(item);
        console.log(removed);
        document.getElementById("removed").value = removed;
    }
    $(document).ready(function() {
        $("#charges").on("click", ".remove", function() {
            $(this).closest("tr").remove();
            // update total_egp and usd to calculate all rows 
            calculateTotals();
        });
    });
</script>

<script>
    function calculateAmounts() {
        let exchangeRate = parseFloat($('#custom_rate_input').val());
        let sizeSmall = parseFloat($('#size_small_1').val()); 
        let totalAmount = parseFloat($('#total_amount_1').val()); 

        let egyAmount = sizeSmall * exchangeRate;
        let totalEgy = totalAmount * exchangeRate;

        $('#egy_amount_1').val(egyAmount.toFixed(2)); 
        $('#total_egy_1').val(totalEgy.toFixed(2));
        $('#egp_vat_1').val(totalEgy.toFixed(2));
    }
    
    $(document).on('input', '#custom_rate_input_1, #size_small_1, #total_amount_1', function() {
        calculateAmounts();
    });

    $(document).ready(function() {
        calculateAmounts();
    });
</script>

@include('invoice.invoice._js_vat_table')

<script>
    $('#editForm').submit(function() {
        $('input').removeAttr('disabled');
        $('select').removeAttr('disabled');
    });
</script>

<script>
    $(document).ready(function() {
        $('#editForm').submit(function() {
            $('input, select').removeAttr('disabled');
        });
    });
</script>

@endpush