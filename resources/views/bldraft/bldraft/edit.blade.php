@extends('layouts.app')
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a a href="{{route('bldraft.index')}}">BL Draft</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">BL Draft Edit</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                    <form  novalidate id="createForm"  action="{{route('bldraft.update',['bldraft'=>$bldraft])}}" method="POST">
                            @csrf
                            @method('put')
                            @if(session('alert'))
                                <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ session('alert') }}</p>
                            @endif
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="ref_no">BL Ref No</label>
                                    <input type="text" class="form-control" id="ref_no" name="ref_no" value="{{old('ref_no',$bldraft->ref_no)}}"
                                        placeholder="BL Ref No" autocomplete="off">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Quotation No</label>
                                    <input type="text" class="form-control" value="{{optional($bldraft->booking->quotation)->ref_no}}"
                                        placeholder="Quotation No" autocomplete="off" disabled>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="notes">OFR</label>
                            <input type="text"  class="form-control" style="background-color :#fff" value="{{optional($bldraft->booking->quotation)->ofr}}" disabled>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="customer_id">Shipping Customer <span class="text-warning"> * (Required.) </span></label>
                                <select class="selectpicker form-control" id="customer_id" data-live-search="true" name="customer_id" data-size="10"
                                 title="{{trans('forms.select')}}" disabled>
                                    @foreach ($customershipper as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('customer_id',$booking->customer_id) ? 'selected':'disabled'}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('customer_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div> 
                            <div class="form-group col-md-8" id="summernote">
                                <label for="customer_id">Shipping Customer Details</label>
                                    <textarea class="form-control"  name="customer_shipper_details"    
                                    placeholder="Customer Shipper Details" autocomplete="off"> {{ old('customer_shipper_details',$bldraft->customer_shipper_details) }}</textarea>
                            </div> 
                        </div> 
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="customer_id">Customer Consignee <span class="text-warning"> * (Required.) </span></label>
                                <select class="selectpicker form-control" id="customerconsignee" data-live-search="true" name="customer_consignee_id" data-size="10"
                                 title="{{trans('forms.select')}}" required>
                                    @foreach ($customersConsignee as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('customer_consignee_id',$bldraft->customer_consignee_id) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('customer_consignee_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div> 
                            <div class="form-group col-md-8" id="summernote">
                                <label for="customer_id">Customer Consignee Details</label>
                                    <textarea id="consignee" class="form-control"  name="customer_consignee_details"
                                    placeholder="Customer Consignee Details" autocomplete="off">{{ old('customer_consignee_details',$bldraft->customer_consignee_details) }}</textarea>
                            </div> 
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="customer_id">Customer Notifiy <span class="text-warning"> * (Required.) </span></label>
                                <select class="selectpicker form-control" id="customernotifiy" data-live-search="true" name="customer_notifiy_id" data-size="10"
                                 title="{{trans('forms.select')}}" required>
                                    @foreach ($customersNotifiy as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('customer_notifiy_id',$bldraft->customer_notifiy_id) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('customer_notifiy_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-8" id="summernote">
                                <label for="customer_id">Customer Notifiy Details</label>
                                    <textarea id="notifiy" class="form-control"  name="customer_notifiy_details"
                                    placeholder="Customer Notifiy Details" autocomplete="off">{{ old('customer_notifiy_details',$bldraft->customer_notifiy_details) }}</textarea>
                            </div> 
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="customer_id">Additional Notifiy </label>
                                <select class="selectpicker form-control" id="additionalNotifiy" data-live-search="true" name="additional_notify_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($customersNotifiy as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('additional_notify_id',$bldraft->additional_notify_id) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('additional_notify_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-8" id="summernote">
                                <label for="customer_id">Additional Notifiy Details</label>
                                    <textarea id="additional_notify_details" class="form-control"  name="additional_notify_details"
                                    placeholder="Additional Notifiy Details" autocomplete="off">{{ old('additional_notify_details',$bldraft->additional_notify_details) }}</textarea>
                            </div> 
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="place_of_acceptence_id">Place Of Acceptence <span class="text-warning"> * (Required.) </span></label>
                                    <select class="selectpicker form-control" id="place_of_acceptence_id" data-live-search="true" name="place_of_acceptence_id" data-size="10"
                                    title="{{trans('forms.select')}}" disabled>
                                    @foreach ($ports as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('place_of_acceptence_id',$booking->place_of_acceptence_id) ? 'selected':'disabled'}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('place_of_acceptence_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="load_port_id">Load Port <span class="text-warning"> * (Required.) </span></label>
                                <select class="selectpicker form-control" id="load_port_id" data-live-search="true" name="load_port_id" data-size="10"
                                    title="{{trans('forms.select')}}" disabled>
                                    @foreach ($ports as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('load_port_id',$booking->load_port_id) ? 'selected':'disabled'}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('load_port_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="place_of_delivery_id">Place Of Delivery <span class="text-warning"> * (Required.) </span></label>
                            <select class="selectpicker form-control" id="place_of_delivery_id" data-live-search="true" name="place_of_delivery_id" data-size="10"
                                title="{{trans('forms.select')}}" disabled>
                                @foreach ($ports as $item)
                                    <option value="{{$item->id}}" {{$item->id == old('place_of_delivery_id',$booking->place_of_delivery_id) ? 'selected':''}}>{{$item->name}}</option>
                                @endforeach
                            </select>
                            @error('place_of_delivery_id')
                            <div style="color: red;">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="discharge_port_id">Discharge Port <span class="text-warning"> * (Required.) </span></label>
                            <select class="selectpicker form-control" id="discharge_port_id" data-live-search="true" name="discharge_port_id" data-size="10"
                                title="{{trans('forms.select')}}" disabled>
                                @foreach ($ports as $item)
                                    <option value="{{$item->id}}" {{$item->id == old('discharge_port_id',$booking->discharge_port_id) ? 'selected':'disabled'}}>{{$item->name}}</option>
                                @endforeach
                            </select>
                            @error('discharge_port_id')
                            <div style="color: red;">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="equipment_type_id">Equipment Type <span class="text-warning"> * (Required.) </span></label>
                                <select class="selectpicker form-control" id="equipment_type_id" data-live-search="true" name="equipment_type_id" data-size="10"
                                title="{{trans('forms.select')}}" disabled>
                                @foreach ($equipmentTypes as $item)
                                    <option value="{{$item->id}}" {{$item->id == old('equipment_type_id',$booking->equipment_type_id) ? 'selected':''}}>{{$item->name}}</option>
                                @endforeach
                            </select>
                            @error('equipment_type_id')
                            <div style="color: red;">
                                {{$message}}
                            </div>
                            @enderror
                        </div> 
                        <div class="form-group col-md-3">
                            <label for="voyage_id">Vessel / Voyage <span class="text-warning"> * (Required.) </span></label>
                            <select class="selectpicker form-control" id="voyage_id" data-live-search="true" name="voyage_id" data-size="10"
                                title="{{trans('forms.select')}}">
                                @foreach ($voyages as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('voyage_id',$booking->voyage_id) ? 'selected':''}}>{{$item->vessel->name}} / {{$item->voyage_no}}</option>
                                @endforeach
                            </select>
                            @error('voyage_id')
                            <div style="color: red;">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6" id="summernote">
                            <label for="descripions">DESCRIPTION</label>
                            <textarea name="descripions" class="form-control" placeholder="Descripion" autocomplete="off">{{ old('descripions',$bldraft->descripions) }}</textarea>
                            @error('descripions')
                            <div style="color: red;">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="notes">Date Of Issue</label>
                            <input type="date" name="date_of_issue" class="form-control" placeholder="Date Of Issue" autocomplete="off" value="{{old('date_of_issue',$bldraft->date_of_issue)}}">
                            @error('date_of_issue')
                            <div style="color: red;">
                                {{$message}}
                            </div>
                            @enderror
                        </div>

                        <div class="form-group col-md-3">
                            <label for="notes">Declered Value</label>
                            <input type="text" name="declerd_value" class="form-control" placeholder="Declered Value" autocomplete="off" value="{{old('declerd_value',$bldraft->declerd_value)}}">
                            @error('declerd_value')
                            <div style="color: red;">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-md-3">
                                <label for="status">Movement</label>
                                <select class="selectpicker form-control" data-live-search="true" name="movement" title="{{trans('forms.select')}}">
                                    <option value="FCL/FCL" {{$bldraft->movement == old('movement') ||  $bldraft->movement == "FCL/FCL"? 'selected':''}}>FCL/FCL</option>
                                    <option value="CY/CY" {{$bldraft->movement == old('movement') ||  $bldraft->movement == "CY/CY"? 'selected':''}}>CY/CY</option>
                                </select>
                                @error('movement')
                                <div style="color:red;">
                                    {{$message}}
                                </div>
                                @enderror
                        </div>
                        <div class="form-group col-md-3">
                            <label for="notes">Number Of Original Bills</label>
                            <input type="text" name="number_of_original" class="form-control" placeholder="Number Of Original Bills" autocomplete="off" value="{{old('number_of_original',$bldraft->number_of_original)}}">
                            @error('number_of_original')
                            <div style="color: red;">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                                <label for="status">Bl Payment</label>
                                    <input type="text" name="payment_kind" class="form-control" placeholder="Bl Payment" autocomplete="off" value="{{old('payment_kind',$bldraft->payment_kind)}}" disabled>
                                @error('bl_kind')
                                <div style="color:red;">
                                    {{$message}}
                                </div>
                                @enderror
                        </div>
                        <div class="form-group col-md-3">
                                <label for="status">Bl Kind</label>
                                <select class="selectpicker form-control" data-live-search="true" name="bl_kind" title="{{trans('forms.select')}}">
                                    <option value="Original" {{$bldraft->id == old('bl_kind') ||  $bldraft->bl_kind == "Original"? 'selected':''}}>Original</option>
                                    @permission('BlDraft-Seaway')
                                    <option value="Seaway BL" {{$bldraft->id == old('bl_kind') ||  $bldraft->bl_kind == "Seaway BL"? 'selected':''}}>Seaway BL</option>
                                    @endpermission
                                </select>
                                @error('bl_kind')
                                <div style="color:red;">
                                    {{$message}}
                                </div>
                                @enderror
                        </div>
   
                        <div class="form-group col-md-3">
                            <label for="status">Bl Status<span class="text-warning"> * (Required.) </span></label>
                            <select class="selectpicker form-control" data-live-search="true" name="bl_status" data-live-search="true">
                                <option value="1" {{$bldraft->id == old('bl_status') ||  $bldraft->bl_status == "1"? 'selected':''}}>Confirm</option>
                                <option value="0" {{$bldraft->id == old('bl_status') ||  $bldraft->bl_status == "0"? 'selected':''}}>Draft</option>
                            </select>
                            @error('bl_status')
                            <div style="color:red;">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-md-3">
                            <label for="status">Received For Shipment or Not<span class="text-warning"> * (Required.) </span></label>
                            <select class="selectpicker form-control" data-live-search="true" name="received_shipment" data-live-search="true">
                                <option value="1" {{$bldraft->id == old('received_shipment') ||  $bldraft->received_shipment == "1"? 'selected':''}}>Yes</option>
                                <option value="0" {{$bldraft->id == old('received_shipment') ||  $bldraft->received_shipment == "0"? 'selected':''}}>No</option>
                            </select>
                            @error('received_shipment')
                            <div style="color:red;">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label>shipment Date</label>
                            <input type="date" name="shipment_date" class="form-control" placeholder="Date Of Issue" autocomplete="off" value="{{old('shipment_date',$bldraft->shipment_date)}}">
                            @error('shipment_date')
                            <div style="color: red;">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <table id="blDraft" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th class="text-center">Container</th>
                                <th class="text-center">Seal No.s</th>
                                <th class="text-center">Packs</th>
                                <th class="text-center">Packs Type</th>
                                <th class="text-center">Description</th>
                                <th class="text-center">Gross Weight Kgs</th>
                                <th class="text-center">Net Weight Kgs</th>
                                <th class="text-center">Measure cbm</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bldraft->blDetails as $key => $blDetail)
                            <tr>
                            <input type="hidden" value ="{{ $blDetail->id }}" name="blDraftdetails[{{ $key }}][id]">
                               <td>{{ $loop->iteration }}</td>
                                <td>
                                  <select class="selectpicker form-control" id="containerDetailsID" data-live-search="true" name="" data-size="10"
                                          title="{{trans('forms.select')}}" disabled>
                                            
                                          @foreach ($oldbookingcontainers as $item)
                                              <option value="{{$item->id}}" {{$item->id == old('container_id',$blDetail->container_id) ? 'selected':'disabled'}}>{{$item->code}}</option>
                                          @endforeach
                                  </select>
                                </td>
                                <td>
                                    <input type="text" id="seal_no" value="{{$blDetail->seal_no}}" name="blDraftdetails[{{ $key }}][seal_no]" class="form-control" autocomplete="off" placeholder="Seal No.S" required>
                                </td>
                                
                                <td>
                                    <input type="text" id="Packs" name="blDraftdetails[{{ $key }}][packs]" value="{{old('packs',$blDetail->packs)}}" class="form-control input"  autocomplete="off" placeholder="Packs" required>
                                </td>

                                <td>
                                    <input type="text" id="Packs" name="blDraftdetails[{{ $key }}][pack_type]" value="{{old('pack_type',$blDetail->pack_type)}}" class="form-control input"  autocomplete="off" placeholder="Packs" required>
                                </td>

                                <td>
                                    <input type="text" id="description" name="blDraftdetails[{{ $key }}][description]" value="{{old('description',$blDetail->description)}}" class="form-control input"  autocomplete="off" placeholder="Description">
                                </td>
                                
                                <td>
                                    <input type="text" id="gross_weight" name="blDraftdetails[{{ $key }}][gross_weight]" value="{{old('gross_weight',$blDetail->gross_weight)}}" class="form-control input"  autocomplete="off" placeholder="Gross Weight" required>
                                </td>
                                <td>
                                    <input type="text" id="net_weight" name="blDraftdetails[{{ $key }}][net_weight]" value="{{old('net_weight',$blDetail->net_weight)}}" class="form-control input"  autocomplete="off" placeholder="Net Weight" required>
                                </td>
                                <td>
                                    <input type="text" id="measurement" name="blDraftdetails[{{ $key }}][measurement]" value="{{old('measurement',$blDetail->measurement)}}" class="form-control input"  autocomplete="off" placeholder="Measurement" >
                                </td>
                            </tr>
                            @endforeach
                            
                        </tbody>
                    </table>
                    
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-primary mt-3">{{trans('forms.edit')}}</button>
                                    <a href="{{route('bldraft.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
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
        $('select').removeAttr('disabled');
    });
</script>
<script>
    $('#createForm').submit(function() {
        $('input').removeAttr('disabled');
    });
</script>
<script>
        $(function(){
                let customerconsignee = $('#customerconsignee');
                $('#customerconsignee').on('change',function(e){
                    let value = e.target.value;
                    let response =    $.get(`/api/master/customers/${customerconsignee.val()}`).then(function(data){
                        let consIgnees = data.customer[0] ;
                    let consignee = $('#consignee').val('Phone :' + ' ' + consIgnees.phone + ' ' + '-' + ' ' +'Email :' +' ' + consIgnees.email +  ' ' + '-' + ' ' + 'Address Line1 :' +' ' + consIgnees.address + ' ' + '-' + ' ' + 'Address Line2 :' +' ' + consIgnees.cust_address + ' ' + '-' + ' ' +' Fax :' +' ' + consIgnees.fax);
                    consignee.html(list2.join(''));
                });
            });
        });
</script>

<script>
        $(function(){
                let customernotifiy = $('#customernotifiy');
                $('#customernotifiy').on('change',function(e){
                    let value = e.target.value;
                    let response =    $.get(`/api/master/customers/${customernotifiy.val()}`).then(function(data){
                        let notIfiy = data.customer[0] ;
                        let notifiy = $('#notifiy').val('Phone :' + ' ' + notIfiy.phone + ' ' + '-' + ' ' +'Email :' +' ' + notIfiy.email +  ' ' + '-' + ' ' + 'Address Line1 :' +' ' + notIfiy.address + ' ' + '-' + ' ' + 'Address Line2 :' +' ' + notIfiy.cust_address + ' ' + '-' + ' ' +' Fax :' +' ' + notIfiy.fax);
                    notifiy.html(list2.join(''));
                });
            });
        });
</script>

<script>
    $(function(){
            let additionalnotifiy = $('#additionalNotifiy');
            $('#additionalNotifiy').on('change',function(e){
                let value = e.target.value;
                let response =    $.get(`/api/master/customers/${additionalnotifiy.val()}`).then(function(data){
                    let notIfiy = data.customer[0] ;
                    let notifiy = $('#additional_notify_details').val('Phone :' + ' ' + notIfiy.phone + ' ' + '-' + ' ' +'Email :' +' ' + notIfiy.email +  ' ' + '-' + ' ' + 'Address Line1 :' +' ' + notIfiy.address + ' ' + '-' + ' ' + 'Address Line2 :' +' ' + notIfiy.cust_address + ' ' + '-' + ' ' +' Fax :' +' ' + notIfiy.fax);
                notifiy.html(list2.join(''));
            });
        });
    });
</script>
@endpush

