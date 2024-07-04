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
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">New Custmized Invoice</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
           
                    <form id="createForm" action="{{route('invoice.store_invoice')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                        <div class="form-row">
                            <input type="hidden" name="booking_ref" value="{{request()->input('booking_ref')}}">
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
                        <!-- <div class="form-row">
                            <div class="form-group col-md-3" >
                                <label>Place Of Acceptence</label>
                                <select class="selectpicker form-control" id="place_of_acceptence_id" data-live-search="true" name="place_of_acceptence" data-size="10"
                                title="{{trans('forms.select')}}" >
                                    @foreach($ports as $port)
                                        <option value="{{$port->id}}" {{$port->id == old('place_of_acceptence_id') ? 'selected':''}}>{{$port->name}}</option>
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
                            <div class="form-group col-md-3" >
                                <label>Port of Delivery</label>
                                <select class="selectpicker form-control" id="port_of_delivery" data-live-search="true" name="port_of_delivery" data-size="10"
                                title="{{trans('forms.select')}}">
                                    @foreach($ports as $port)
                                    <option value="{{$port->id}}" {{$port->id == old('port_of_delivery') ? 'selected':''}}>{{$port->name}}</option>
                                    @endforeach
                                    </select>
                                @error('port_of_delivery')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div> 
                        </div>  -->
                            <div class="form-row">
                                <div class="form-group col-md-4" >
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
                                <div class="form-group col-md-4" >
                                    <label for="Date">Date</label>
                                        <input type="date" class="form-control" name="date" placeholder="Date" autocomplete="off" value="{{old('date',date('Y-m-d'))}}">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-3" >
                                    <label>QTY</label>
                                        <input type="text" class="form-control" placeholder="Qty" name="qty" autocomplete="off" style="background-color:#fff" value="1">
                                </div>
                                <div class="form-group col-md-3" >
                                    <label>TAX Hold</label>
                                    <input type="text" class="form-control" placeholder="TAX %" name="tax_discount" autocomplete="off"  style="background-color:#fff" value="0">
                                </div>
                                <div class="form-group col-md-3" >
                                    <label>Exchange Rate</label>
                                        <input type="text" class="form-control" placeholder="Exchange Rate" name="customize_exchange_rate"  value="48" autocomplete="off"  style="background-color:#fff" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <div style="padding: 30px;">
                                        <input class="form-check-input" type="radio" name="add_egp" id="add_egp" value="false" checked>
                                        <label class="form-check-label" for="add_egp">
                                          USD
                                        </label>
                                        <br>
                                        <input class="form-check-input" type="radio" name="add_egp" id="add_egp" value="onlyegp">
                                        <label class="form-check-label" for="add_egp">
                                          EGP
                                        </label> 
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                    <div class="col-md-3 form-group">
                                        <label> VAT % </label>
                                        <input type="text" class="form-control" placeholder="VAT %" name="vat" value={{14}} autocomplete="off"  style="background-color:#fff">
                                    </div> 
                                    <div class="col-md-9 form-group">
                                        <label> Notes </label>
                                        <textarea class="form-control" name="notes"></textarea>
                                    </div> 
                            </div>  
                        <h4>Charges<h4>
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
                                    <th class="text-center"><a id="add"> Add <i class="fas fa-plus"></i></a></th>
                                </tr>
                            </thead>
                            <tbody>
                                    
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
    $('#createForm').submit(function() {
        $('input').removeAttr('disabled');
    });
</script>

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

            row.find('input[name$="[total]"]').val(totalAmount);
            row.find('input[name$="[usd_vat]"]').val(totalAmountAfterVat.toFixed(2));

            var egpAmount = totalAmount * exchangeRate;
            var egpAmountAfterVat = totalAmountAfterVat * exchangeRate;

            row.find('input[name$="[egy_amount]"]').val(egpAmount);
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
    $(document).ready(function(){
        $("#charges").on("click", ".remove", function () {
            $(this).closest("tr").remove();
        });
        var counter  = '<?= isset($key)? ++$key : 0 ?>';
        $("#add").click(function(){
           var tr = '<tr>'+ 
               '<td><select class="selectpicker form-control" data-live-search="true" name="invoiceChargeDesc['+counter+'][charge_description]" required     data-size="10"><option>Select</option>@foreach ($charges as $item)<option value="{{$item->name}}">{{$item->name}}</option>@endforeach</select></td>'+
               '<td><input type="text" name="invoiceChargeDesc['+counter+'][size_small]" class="form-control" autocomplete="off" placeholder="Amount" required></td>'+
               '<td><div class="form-check"><input class="form-check-input" type="radio" name="invoiceChargeDesc['+counter+'][add_vat]" id="item_'+counter+'_enabled_yes" value="1"><label class="form-check-label" for="item_'+counter+'_enabled_yes">Yes</label></div><div class="form-check"><input class="form-check-input" type="radio" name="invoiceChargeDesc['+counter+'][add_vat]" id="item_'+counter+'_enabled_no" value="0" checked><label class="form-check-label" for="item_'+counter+'_enabled_no">No</label></div></td>'+
               '<td><div class="form-check"><input class="form-check-input" type="radio" name="invoiceChargeDesc['+counter+'][enabled]" id="item_'+counter+'_enabled_yes" value="1" checked><label class="form-check-label" for="item_'+counter+'_enabled_yes">Yes</label></div><div class="form-check"><input class="form-check-input" type="radio" name="invoiceChargeDesc['+counter+'][enabled]" id="item_'+counter+'_enabled_no" value="0"><label class="form-check-label" for="item_'+counter+'_enabled_no">No</label></div></td>'+
               '<td><input type="text" name="invoiceChargeDesc['+counter+'][total]" class="form-control" autocomplete="off" placeholder="Total" required></td>'+
               '<td><input type="text" name="invoiceChargeDesc['+counter+'][usd_vat]" class="form-control" autocomplete="off" placeholder="USD After VAT"></td>'+
               '<td><input type="text" name="invoiceChargeDesc['+counter+'][egy_amount]" class="form-control" autocomplete="off" placeholder="Egp Amount"></td>'+
               '<td><input type="text" name="invoiceChargeDesc['+counter+'][egp_vat]" class="form-control" autocomplete="off" placeholder="Egp After VAT"></td>'+
               '<td style="width:85px;"><button type="button" class="btn btn-danger remove"><i class="fa fa-trash"></i></button></td>'
           '</tr>';
           counter++;
          $('#charges').append(tr);
          $('.selectpicker').selectpicker("render");
          $('#selectpickers').selectpicker();
        });
    });
    </script>
@endpush