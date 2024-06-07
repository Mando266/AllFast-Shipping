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
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">Edit Custmized Invoice</a></li>
                            @else
                            <li class="breadcrumb-item"><a a href="{{route('invoice.index')}}">Invoice</a></li> 
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">Edit Custmized Depit</a></li>
                            @endif
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">

                <form  novalidate id="createForm" action="{{route('invoice.update',['invoice'=>$invoice])}}" method="POST" >
                            @csrf
                            @method('put')
                        <div class="form-row">
                            <div class="form-group col-md-2">
                                <label for="Invoice">Invoice No</label>
                                    <input type="text" id="Invoice" class="form-control"  name="invoice_no"
                                    placeholder="Invoice No" autocomplete="off" value="{{old('invoice_no',$invoice->invoice_no)}}">
                            </div> 
                            <input type="hidden" name="bldraft_id" value="{{request()->input('bldraft_id')}}">
                                <div class="form-group col-md-6">
                                <label for="customer">Customer<span class="text-warning"> * (Required.) </span></label>
                                <select class="selectpicker form-control" name="customer_id" id="customer" data-live-search="true" data-size="10"
                                 title="{{trans('forms.select')}}" required>
                                        @foreach($ffws as $ffw)
                                            <option value="{{$ffw->id}}" {{$ffw->id == old('customer_id',$invoice->customer_id) ? 'selected':''}}>{{ $ffw->name }} Forwarder</option>
                                        @endforeach
                                        @foreach($shippers as $shipper)
                                            <option value="{{$shipper->id}}" {{$shipper->id == old('customer_id',$invoice->customer_id) ? 'selected':''}}>{{ $shipper->name }} Shipper</option>
                                        @endforeach
                                        @foreach($suppliers as $supplier)
                                            <option value="{{$supplier->id}}" {{$supplier->id == old('customer_id',$invoice->customer_id) ? 'selected':''}}>{{ $supplier->name }} Supplier</option>
                                        @endforeach
                                        @foreach($notify as $notifys)
                                            <option value="{{$notifys->id}}" {{ $notifys->id  == old('customer_id',$invoice->customer_id) ? 'selected':''}}>{{ $notifys->name }} Notify</option>
                                        @endforeach
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
                                    <label>Place Of Acceptence</label>
                                        <select class="selectpicker form-control" id="place_of_acceptence_id" data-live-search="true" name="place_of_acceptence" data-size="10"
                                            title="{{trans('forms.select')}}">
                                        @foreach($ports as $port)
                                            <option value="{{$port->id}}" {{$port->id == old('place_of_acceptence_id',$invoice->place_of_acceptence) ? 'selected':''}}>{{$port->name}}</option>
                                        @endforeach
                                        </select>
                                    @error('place_of_acceptence')
                                    <div style="color: red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div> 

                                <div class="form-group col-md-3" >
                                    <label>Load Port</label>
                                    <select class="selectpicker form-control" id="load_port" data-live-search="true" name="load_port" data-size="10"
                                        title="{{trans('forms.select')}}">
                                           @foreach($ports as $port)
                                           <option value="{{$port->id}}" {{$port->id == old('load_port',$invoice->load_port) ? 'selected':''}}>{{$port->name}}</option>
                                           @endforeach
                                           </select>
                                       @error('load_port')
                                       <div style="color: red;">
                                           {{$message}}
                                       </div>
                                       @enderror
                                </div> 
                            <div class="form-group col-md-3" >
                                <label for="Date">Booking Ref</label>
                                <select class="selectpicker form-control" id="booking_ref" data-live-search="true" name="booking_ref" data-size="10"
                                    title="{{trans('forms.select')}}">
                                    <option value="">Select...</option>
                                    @foreach($bookings as $booking)
                                        <option value="{{$booking->id}}" {{$booking->id == old('booking_ref',$invoice->booking_ref) ? 'selected':''}}>{{$booking->ref_no}}</option>
                                    @endforeach
                                </select>
                                @error('booking_ref')
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
                                        <option value="{{$item->id}}" {{$item->id == old('voyage_id',$invoice->voyage_id) ? 'selected':''}}>{{$item->vessel->name}} / {{$item->voyage_no}} - {{ optional($item->leg)->name }}</option>
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
                                <div class="form-group col-md-3" >
                                    <label>Discharge Port</label>
                                    <select class="selectpicker form-control" id="discharge_port" data-live-search="true" name="discharge_port" data-size="10"
                                        title="{{trans('forms.select')}}">
                                        @foreach($ports as $port)
                                            <option value="{{$port->id}}" {{$port->id == old('discharge_port',$invoice->discharge_port) ? 'selected':''}}>{{$port->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('discharge_port')
                                    <div style="color: red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div> 

                                <div class="form-group col-md-3" >
                                    <label>Port of Delivery</label>
                                    <select class="selectpicker form-control" id="port_of_delivery" data-live-search="true" name="port_of_delivery" data-size="10"
                                        title="{{trans('forms.select')}}">
                                        @foreach($ports as $port)
                                            <option value="{{$port->id}}" {{$port->id == old('port_of_delivery',$invoice->port_of_delivery) ? 'selected':''}}>{{$port->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('port_of_delivery')
                                    <div style="color: red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div> 
                                <div class="form-group col-md-3" >
                                    <label>Equipment Type</label>
                                    <select class="selectpicker form-control" id="equipment_type" data-live-search="true" name="equipment_type" data-size="10"
                                        title="{{trans('forms.select')}}">
                                        @foreach($equipmentTypes as $equipmentType)
                                            <option value="{{$equipmentType->id}}" {{$equipmentType->id == old('equipment_type',$invoice->equipment_type) ? 'selected':''}}>{{$equipmentType->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('equipment_type')
                                    <div style="color: red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div> 

                                @if($invoice->invoice_status != "confirm")
                                <div class="form-group col-md-3">
                                    <label for="status">Invoice Status<span class="text-warning"> * </span></label>
                                    <select class="form-control" data-live-search="true" name="invoice_status" title="{{trans('forms.select')}}" required>
                                        <option value="draft" {{ old('invoice_status',$invoice->invoice_status) == "draft" ? 'selected':'' }}>Draft</option>
                                        @permission('Invoice-Ready_to_Confirm')
                                        <option value="ready_confirm" {{ old('invoice_status',$invoice->invoice_status) == "ready_confirm" ? 'selected':'' }}>Ready To Confirm</option>
                                        @endpermission
                                        @if(Auth::user()->id == 15 || Auth::user()->id == 24 )
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
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-2" >
                                    <label for="Date">Date</label>
                                        <input type="date" class="form-control" name="date" placeholder="Date" autocomplete="off" value="{{old('date',date('Y-m-d'))}}">
                                </div>
                                <div class="form-group col-md-2" >
                                    <label>QTY<span class="text-warning"> * (Required.) </span></label>
                                        <input type="text" class="form-control" placeholder="Qty" name="qty" autocomplete="off" value="{{old('qty',$invoice->qty)}}" style="background-color:#fff" required>
                                </div>
                                <div class="form-group col-md-2" >
                                    <label>Bl No</label>
                                        <input type="text" class="form-control"  autocomplete="off" value="Customize" style="background-color:#fff" readonly>
                                </div>
                                @if($invoice->type == "invoice")

                                <div class="form-group col-md-2" >
                                    <label>TAX Hold</label>
                                        <input type="text" class="form-control" placeholder="TAX %" name="tax_discount"  value="{{old('tax_discount',$invoice->tax_discount)}}" autocomplete="off"  style="background-color:#fff" >
                                </div>
                                @endif

                                <div class="form-group col-md-2" >
                                    <label>Exchange Rate</label>
                                        <input type="text" class="form-control" placeholder="Exchange Rate" name="customize_exchange_rate" id="customize_exchange_rate" value="{{old('customize_exchange_rate',$invoice->customize_exchange_rate)}}" autocomplete="off"  style="background-color:#fff" required>
                                </div>
                                @if($invoice->type == "invoice")
                                <div class="form-group col-md-2" >
                                    <div style="padding: 30px;">
                                        <input class="form-check-input" type="radio" name="add_egp" id="add_egp" value="false" {{ "false" == old('add_egp',$invoice->add_egp) ? 'checked':''}}>
                                        <label class="form-check-label" for="add_egp">
                                          USD
                                        </label>
                                        <br>
                                        <input class="form-check-input" type="radio" name="add_egp" id="add_egp" value="onlyegp" {{ "onlyegp" == old('add_egp',$invoice->add_egp) ? 'checked':''}}>
                                        <label class="form-check-label" for="add_egp">
                                          EGP
                                        </label>
                                    </div>
                                </div>
                                @endif
                            </div> 
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label for="status">Booking Status<span class="text-warning">  * (Required.)</span></label>
                                    <select class="form-control" data-live-search="true" name="booking_status" title="{{trans('forms.select')}}" required>
                                        <option value="">Select....</option>
                                        <option value="1" {{ old('booking_status',$invoice->booking_status) == "1" ? 'selected':'' }}>Import</option>
                                        <option value="0" {{ old('booking_status',$invoice->booking_status) == "0" ? 'selected':'' }}>Export</option>
                                    </select>
                                </div>
                            @if($invoice->type == "invoice")
                                <div class="form-group col-md-3">
                                    <label for="vat">VAT %:</label>
                                    <input type="text" class="form-control" id="vat" name="vat" value="{{ old('vat',$invoice->vat) }}" required>
                                </div>
                            @endif    
                                <!-- <div class="form-group col-md-3" >
                                    <label>Total USD</label>
                                        <input type="text" class="form-control" id="total_usd"  value="{{$total}}" autocomplete="off"  style="background-color:#fff" readonly>
                                </div>
                                <div class="form-group col-md-3" >
                                    <label>Total EGP</label>
                                        <input type="text" class="form-control" id="total_egp"  value="{{$total_eg}}" autocomplete="off"  style="background-color:#fff" readonly>
                                </div> -->
                            </div>
                            <div class="form-row">
                                <div class="col-md-12 form-group">
                                    <label> Notes </label>
                                    <textarea class="form-control" name="notes">{{old('notes',$invoice->notes)}}</textarea>
                                </div> 
                            </div>
                            <h4>Charges
                                <h4>        
                                @if($invoice->type == "invoice")
        
                                        <table id="charges" class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <th class="text-center">Charge Description</th>
                                                <th class="text-center">Amount</th>
                                                <th class="text-center">Add VAT</th>
                                                <th class="text-center">Multiply QTY</th>
                                                <th class="text-center">TOTAL USD</th>
                                                <th class="text-center">USD After VAT</th>
                                                <th class="text-center">Total Egp</th>
                                                <th class="text-center">EGP After VAT</th>
                                                @if($invoice->type == "invoice")
                                                    <th class="text-center"><a id="add"> Add <i class="fas fa-plus"></i></a>
                                                    </th>
                                                @else
                                                @endif
                                            </tr>
                                            </thead>
                                                <tbody>
                                                @foreach($invoice_details as $key => $item)
                                                    <tr>
                                                        <input type="hidden" value="{{ $item->id }}"
                                                            name="invoiceChargeDesc[{{ $key }}][id]">
                                                        <td>
                                                            <select class="selectpicker form-control" id="charge_description" data-live-search="true" name="invoiceChargeDesc[{{$key}}][charge_description]" data-size="10"
                                                                title="{{trans('forms.select')}}" autofocus>
                                                                @foreach ($charges as $charge)
                                                                    <option value="{{$charge->name}}" {{$charge->name == old('charge_description',$item->charge_description)? 'selected':''}}>{{$charge->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="text" id="size_small"
                                                                name="invoiceChargeDesc[{{ $key }}][size_small]"
                                                                class="form-control"
                                                                autocomplete="off" placeholder=""
                                                                value="{{old('size_small',$item->size_small)}}"
                                                                style="background-color: white;" required>
                                                        </td>
                                                        <td>
                                                            <div class="form-check">
                                                                <input class="form-check-input vatRadio" type="radio"
                                                                    name="invoiceChargeDesc[{{$key}}][add_vat]"
                                                                    id="item_{{$key}}_enabled_yes" value="1"  {{ $item->add_vat ==  1 ? 'checked="checked"' :''}}>
                                                                <label class="form-check-label"
                                                                    for="item_{{$key}}_enabled_yes">Yes</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input vatRadio" type="radio"
                                                                    name="invoiceChargeDesc[{{$key}}][add_vat]"
                                                                    id="item_{{$key}}_enabled_no" value="0" {{ $item->add_vat ==  0 ? 'checked="checked"' :''}}>
                                                                <label class="form-check-label"
                                                                    for="item_{{$key}}_enabled_no">No</label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio"
                                                                    name="invoiceChargeDesc[{{$key}}][enabled]"
                                                                    id="item_{{$key}}_enabled_yes" value="1"
                                                                    {{ $item->enabled ==  1 ? 'checked="checked"' :''}} disabled>
                                                                <label class="form-check-label"
                                                                    for="item_{{$key}}_enabled_yes">Yes</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio"
                                                                    name="invoiceChargeDesc[{{$key}}][enabled]"
                                                                    id="item_{{$key}}_enabled_no" value="0"
                                                                    {{$item->enabled == 0 ? 'checked="checked"' :''}} disabled>
                                                                <label class="form-check-label"
                                                                    for="item_{{$key}}_enabled_no">No</label>
                                                            </div>
                                                        </td>


                                                        <td><input type="text" class="form-control" id="usd_{{ $key }}"
                                                                name="invoiceChargeDesc[{{ $key }}][total_amount]"
                                                                value="{{old('total_amount',$item->total_amount)}}"
                                                                placeholder="Total" autocomplete="off"
                                                                style="background-color: white;" required disabled>
                                                        </td>
                                                        <td><input type="text" id="usd_vat_{{ $key }}"
                                                                name="invoiceChargeDesc[{{ $key }}][usd_vat]"
                                                                class="form-control" autocomplete="off"
                                                                placeholder="USD After VAT" disabled></td>


                                                        <td><input type="text" class="form-control" id="egp_{{ $key }}"
                                                                name="invoiceChargeDesc[{{ $key }}][total_egy]"
                                                                value="{{old('total_egy',$item->total_egy)}}"
                                                                placeholder="Egp Amount" autocomplete="off"
                                                                style="background-color: white;" required disabled>
                                                        </td>

                                                        <td><input id="egp_vat_{{ $key }}" type="text"
                                                                name="invoiceChargeDesc[{{ $key }}][egp_vat]"
                                                                class="form-control" autocomplete="off"
                                                                placeholder="Egp After VAT" disabled></td>

                                                        @if($invoice->type == "invoice")
                                                            <td style="width:85px;">
                                                                <button type="button" class="btn btn-danger remove"
                                                                        onclick="removeItem({{$item->id}})"><i
                                                                            class="fa fa-trash"></i></button>
                                                            </td>
                                                        @endif
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        @else
                                        <table id="containerDepit" class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <th class="text-center">Charge Description</th>
                                                <th class="text-center">Rate</th>
                                                <th class="text-center">Multiply QTY</th>
                                                <th class="text-center">Total Amount</th>
                                                <th class="text-center"><a id="addDepit"> Add <i class="fas fa-plus"></i></a></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($invoice_details as $key => $item)
        
                                                <tr>
                                                    <input type="hidden" value="{{ $item->id }}"
                                                           name="invoiceChargeDesc[{{ $key }}][id]">
                                                    <td>
                                                        <!-- <input type="text" id="Charge Description"
                                                               name="invoiceChargeDesc[{{ $key }}][charge_description]"
                                                               class="form-control" autocomplete="off"
                                                               placeholder="Charge Description"
                                                               value="{{(old('charge_description',$item->charge_description))}}"> -->
                                                               <select class="selectpicker form-control" id="charge_description" data-live-search="true" name="invoiceChargeDesc[{{$key}}][charge_description]" data-size="10"
                                                                    title="{{trans('forms.select')}}" autofocus >
                                                                    @foreach ($charges as $charge)
                                                                        <option value="{{$charge->name}}" {{$charge->name == old('charge_description',$item->charge_description)? 'selected':''}}>{{$charge->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                    </td>
                                                    <td><input type="text" class="form-control" id="size_small_1"
                                                               name="invoiceChargeDesc[{{ $key }}][size_small]"
                                                               value="{{(old('size_small',$item->size_small))}}"
                                                               placeholder="Rate" autocomplete="off"
                                                               style="background-color: white;">
                                                    </td>
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio"
                                                                   name="invoiceChargeDesc[{{$key}}][enabled]"
                                                                   id="item_{{$key}}_enabled_yes"
                                                                   value="1" {{ "1" == old('enabled',$item->enabled) ? 'checked' : ''}} >
                                                            <label class="form-check-label"
                                                                   for="item_{{$key}}_enabled_yes">Yes</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio"
                                                                   name="invoiceChargeDesc[{{$key}}][enabled]"
                                                                   id="item_{{$key}}_enabled_no"
                                                                   value="0" {{ "0" == old('enabled',$item->enabled) ? 'checked' : ''}}>
                                                            <label class="form-check-label"
                                                                   for="item_{{$key}}_enabled_no">No</label>
                                                        </div>
                                                    </td>
        
                                                    <td><input type="text" class="form-control" id="total_amount_1"
                                                               name="invoiceChargeDesc[{{ $key }}][total_amount]"
                                                               value="{{(old('total_amount',$item->total_amount))}}"
                                                               placeholder="Amount 20/40" autocomplete="off"
                                                               style="background-color: white;">
                                                    </td>
                                                    <td style="display:none;"><input type="hidden" class="form-control" id="egy_amount_1" name="invoiceChargeDesc[0][egy_amount]" disabled></td>
                                                    <td style="display:none;"><input type="hidden" class="form-control" id="total_egy_1" name="invoiceChargeDesc[0][total_egy]" disabled></td>
                                                    <td style="display:none;"><input type="hidden" class="form-control" id="egp_vat_1" name="invoiceChargeDesc[0][egp_vat]" disabled></td>

                                                    <td style="width:85px;">
                                                        <button type="button" class="btn btn-danger remove"
                                                                onclick="removeItem({{$item->id}})"><i
                                                                    class="fa fa-trash"></i></button>
                                                    </td>
                                                </tr>
                                            @endforeach
        
                                            </tbody>
                                        </table>
                                    @endif
                                    <div class="row">
                                        <div class="col-md-12 text-center">
                                            <button type="submit" class="btn btn-primary mt-3">Confirm</button>
                                            <a href="{{route('invoice.index')}}"
                                               class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                                        </div>
                                    </div>
                                    <input name="removed" id="removed" type="hidden" value="">
                </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
@push('scripts')
    @include('invoice.invoice._js_vat_table')
    <script>
    $(document).ready(function() {
        function updateRow(row) {
            var sizeSmall = row.find('input[name$="[size_small]"]').val();
            var enabled = row.find('input[name$="[enabled]"]:checked').val();
            var add_vat = row.find('input[name$="[add_vat]"]:checked').val();
            var qty = $('input[name="qty"]').val();
            var vat = $('input[name="vat"]').val() / 100;
            var exchangeRate = $('input[name="customize_exchange_rate"]').val();

            var totalAmount = enabled == 1 ? sizeSmall * qty : sizeSmall * 1;
            var totalAmountAfterVat = add_vat == 1 ? totalAmount + (totalAmount * vat) : totalAmount;

            row.find('input[name$="[total_amount]"]').val(totalAmount);
            row.find('input[name$="[usd_vat]"]').val(totalAmountAfterVat.toFixed(2));

            var egpAmount = totalAmount * exchangeRate;
            var egpAmountAfterVat = totalAmountAfterVat * exchangeRate;

            row.find('input[name$="[total_egy]"]').val(egpAmount);
            row.find('input[name$="[egp_vat]"]').val(egpAmountAfterVat.toFixed(2));
        }

        function updateAllRows() {
            $('#charges tbody tr').each(function() {
                updateRow($(this));
            });
        }

        $('input[name="qty"], input[name="vat"], input[name="customize_exchange_rate"]').on('input', function() {
            updateAllRows();
        });

        $('body').on('change', 'input[name$="[enabled]"], input[name$="[add_vat]"]', function() {
            updateRow($(this).closest('tr'));
        });

        $('body').on('input', 'input[name$="[size_small]"]', function() {
            updateRow($(this).closest('tr'));
        });

        updateAllRows();
    });
</script>

<script>
    $(document).ready(function() {
    // When the form is submitted, ensure all inputs are enabled
    $('#createForm').submit(function() {
        $('input').removeAttr('disabled');
    });

    // Update the customer name field when a customer is selected
    $('#customer').on('change', function() {
        $.get(`/api/master/customers/${$(this).val()}`).then(function(data) {
            $('#notifiy').val(data.customer[0].name);
        });
    });

    // Remove a row from the charges table
    $("#charges, #containerDepit").on("click", ".remove", function() {
        $(this).closest("tr").remove();
    });

    // Calculate amounts for each row in the charges table
    function calculateAmounts() {
        $('#containerDepit tbody tr').each(function() {
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

    // Recalculate amounts when specific inputs change
    $(document).on('input', 'input[name="qty"], input[name="customize_exchange_rate"], input[name*="[size_small]"]', function() {
        calculateAmounts();
    });

    $(document).on('change', 'input[name*="[enabled]"]', function() {
        calculateAmounts();
    });

    // Initial calculation on page load
    calculateAmounts();

    var counter = '<?= isset($key) ? ++$key : 0 ?>';

    // Handle the removal of an item and track removed items
    var removed = [];
    function removeItem(item) {
        removed.push(item);
        console.log(removed);
        document.getElementById("removed").value = removed;
    }

    // Add a new row to the charges table
    $("#addDepit").click(function() {
        var tr = '<tr>' +
            '<td><select class="selectpicker form-control" id="selectpickers" name="invoiceChargeDesc['+counter+'][charge_description]" data-size="10" title="{{trans('forms.select')}}"> @foreach ($charges as $item) <option value="{{$item->name}}" {{$item->name == old($item->charge_description) ? 'selected':''}} requierd>{{$item->name}}</option>@endforeach</select></td>' +
            '<td><input type="text" class="form-control" id="size_small" name="invoiceChargeDesc[' + counter + '][size_small]" placeholder="Rate" autocomplete="off" style="background-color: white;" required></td>' +
            '<td><div class="form-check"><input class="form-check-input" type="radio" name="invoiceChargeDesc[' + counter + '][enabled]" id="item_' + counter + '_enabled_yes" value="1" checked><label class="form-check-label" for="item_' + counter + '_enabled_yes">Yes</label></div><div class="form-check"><input class="form-check-input" type="radio" name="invoiceChargeDesc[' + counter + '][enabled]" id="item_' + counter + '_enabled_no" value="0"><label class="form-check-label" for="item_' + counter + '_enabled_no">No</label></div></td>' +
            '<td><input type="text" class="form-control" id="ofr" name="invoiceChargeDesc[' + counter + '][total_amount]" placeholder="Total" autocomplete="off" style="background-color: white;" disabled required></td>' +
            '<td style="display:none;"><input type="hidden" class="form-control" name="invoiceChargeDesc[' + counter + '][egy_amount]" disabled></td>' +
            '<td style="display:none;"><input type="hidden" class="form-control" name="invoiceChargeDesc[' + counter + '][total_egy]" disabled></td>' +
            '<td style="display:none;"><input type="hidden" class="form-control" name="invoiceChargeDesc[' + counter + '][egp_vat]" disabled></td>' +
            '<td style="width:85px;"><button type="button" class="btn btn-danger remove"><i class="fa fa-trash"></i></button></td>' +
            '</tr>';
        counter++;
        $('#containerDepit').append(tr);
        $('.selectpicker').selectpicker("render");
        $('#selectpickers').selectpicker();
    });
});
</script>

@endpush