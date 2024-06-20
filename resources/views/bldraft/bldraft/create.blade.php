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
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">Create New BL Draft</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                    <form novalidate id="createForm" action="{{route('bldraft.store')}}" method="POST">
                            @csrf
                        <h5>Booking No :{{$booking_no->ref_no}}<h5>
                        <div class="form-row">
                            @isset($blDraft)
                            <input type="hidden" value="{{$blDraft->id}}" name="blDraft_id">
                            @endisset
                            <input type="hidden" value="{{$booking->ref_no}}" name="ref_no">
                            <input type="hidden" value="{{$booking->id}}" name="booking_id">
                            <div class="form-group col-md-4">
                                <label for="customer_id">Shipping Customer<span class="text-warning"> * (Required.) </span></label>
                                <select class="selectpicker form-control" id="customer_id" data-live-search="true" name="customer_id" data-size="10"
                                 title="{{trans('forms.select')}}" disabled>
                                    @foreach ($customershipper as $item)
                                        @if($booking->customer_id != null)
                                        <option value="{{$item->id}}" {{$item->id == old('customer_id',$booking->customer_id) ? 'selected':'disabled'}}>{{$item->name}}</option>

                                        @endif
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
                                @if($booking->customer_id != null)
                                    <textarea class="form-control"  name="customer_shipper_details"
                                    placeholder="Customer Shipper Details" autocomplete="off">@isset($blDraft){{$blDraft->customer_shipper_details}} @else Phone : {{optional($booking->customer)->phone}} - Email : {{optional($booking->customer)->email}} - Address : {{optional($booking->customer)->address}}  @endisset</textarea>
                                @endif
                            </div>
                        </div>
                    <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="customer_id">Customer Consignee <span class="text-warning"> * (Required.) </span></label>
                                <select class="selectpicker form-control" id="customerconsignee" data-live-search="true" name="customer_consignee_id" data-size="10"
                                 title="{{trans('forms.select')}}" required>
                                    @foreach ($customersConsignee as $item)
                                    @if($booking->customer_consignee_id != null)
                                        <option value="{{$item->id}}" {{$item->id == old('customer_consignee_id',$booking->customer_consignee_id) ? 'selected':''}}>{{$item->name}}</option>
                                        @else
                                        <option value="{{$item->id}}" {{$item->id == old('customer_consignee_id',isset($blDraft) ? $blDraft->customer_consignee_id:'') ? 'selected':''}}>{{$item->name}}</option>
                                    @endif
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
                                @if($booking->customer_consignee_id != null)
                                    <textarea id="consignee" class="form-control"  name="customer_consignee_details"
                                    placeholder="Customer Consignee Details" autocomplete="off">Phone : {{optional($booking->consignee)->phone}} - Email : {{optional($booking->consignee)->email}} - Address : {{optional($booking->consignee)->address}}</textarea>
                                @else
                                    <textarea id="consignee" class="form-control"  name="customer_consignee_details"
                                    placeholder="Customer Consignee Details" autocomplete="off">@isset($blDraft){{$blDraft->customer_consignee_details}} @endisset</textarea>
                                @endif
                            </div>
                    </div>
                    <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="customer_id">Customer Notifiy <span class="text-warning"> * (Required.) </span></label>
                                <select class="selectpicker form-control" id="customernotifiy" data-live-search="true" name="customer_notifiy_id" data-size="10"
                                 title="{{trans('forms.select')}}" required>
                                    @foreach ($customersNotifiy as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('customer_notifiy_id',isset($blDraft) ? $blDraft->customer_notifiy_id:'') ? 'selected':''}}>{{$item->name}}</option>
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
                                    placeholder="Customer Notifiy Details" autocomplete="off">@isset($blDraft){{$blDraft->customer_notifiy_details}} @endisset</textarea>
                            </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="customer_id">Additional Notifiy</label>
                            <select class="selectpicker form-control" id="additionalNotifiy" data-live-search="true" name="additional_notify_id" data-size="10"
                             title="{{trans('forms.select')}}">
                                @foreach ($customersNotifiy as $item)
                                    <option value="{{$item->id}}" {{$item->id == old('additional_notify_id',isset($blDraft) ? $blDraft->additional_notify_id:'') ? 'selected':''}}>{{$item->name}}</option>
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
                                placeholder="Additional Notifiy Details" autocomplete="off">@isset($blDraft){{$blDraft->additional_notifiy_details}} @endisset</textarea>
                        </div>
                </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="place_of_acceptence_id">Place Of Acceptence <span class="text-warning"> * (Required.) </span></label>
                                <select class="selectpicker form-control" id="place_of_acceptence_id" data-live-search="true" name="place_of_acceptence_id" data-size="10"
                                title="{{trans('forms.select')}}" disabled>
                                @foreach ($ports as $item)
                                    @if($booking->place_of_acceptence_id != null)
                                    <option value="{{$item->id}}" {{$item->id == old('place_of_acceptence_id',$booking->place_of_acceptence_id) ? 'selected':'disabled'}}>{{$item->name}}</option>
                                    @endif
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
                                    @if($booking->load_port_id != null)
                                    <option value="{{$item->id}}" {{$item->id == old('load_port_id',$booking->load_port_id) ? 'selected':'disabled'}}>{{$item->name}}</option>
                                    @endif
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
                                    @if($booking->place_of_delivery_id != null)
                                    <option value="{{$item->id}}" {{$item->id == old('place_of_delivery_id',$booking->place_of_delivery_id) ? 'selected':''}}>{{$item->name}}</option>
                                    @endif
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
                                @if($booking->discharge_port_id != null)
                                    <option value="{{$item->id}}" {{$item->id == old('discharge_port_id',$booking->discharge_port_id) ? 'selected':'disabled'}}>{{$item->name}}</option>
                                @endif
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
                                    @if($booking->equipment_type_id != null)
                                    <option value="{{$item->id}}" {{$item->id == old('equipment_type_id',$booking->equipment_type_id) ? 'selected':''}}>{{$item->name}}</option>
                                    @endif
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
                                title="{{trans('forms.select')}}" disabled>
                                @foreach ($voyages as $item)
                                    @if($booking->voyage_id != null)
                                        <option value="{{$item->id}}" {{$item->id == old('voyage_id',$booking->voyage_id) ? 'selected':''}}>{{$item->vessel->name}} / {{$item->voyage_no}}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('voyage_id')
                            <div style="color: red;">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6" id="summernote">
                            <label for="notes">DESCRIPTION</label>
                            <textarea name="descripions" class="form-control" placeholder="Descripion" autocomplete="off">@isset($blDraft){{$blDraft->descripions}} @endisset</textarea>
                            @error('notes')
                            <div style="color: red;">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="notes">Date Of Issue</label>
                            <input type="date" name="date_of_issue" class="form-control" @isset($blDraft) value="{{$blDraft->date_of_issue}}" @endisset placeholder="Date Of Issue" autocomplete="off">
                            @error('date_of_issue')
                            <div style="color: red;">
                                {{$message}}
                            </div>
                            @enderror
                        </div>

                        <div class="form-group col-md-3">
                            <label>Declered Value</label>
                            <input type="text" name="declerd_value"  @isset($blDraft) value="{{$blDraft->declerd_value}}" @endisset class="form-control" placeholder="Declered Value" autocomplete="off">
                            @error('declerd_value')
                            <div style="color: red;">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-md-3">
                                <label for="status">Movement</label>
                                <select class="selectpicker form-control" data-live-search="true" name="movement" title="{{trans('forms.select')}}">
                                    <option value="FCL/FCL">FCL/FCL</option>
                                    <option value="CY/CY">CY/CY</option>
                                    <option value="Empty">Empty</option>
                                </select>
                                @error('movement')
                                <div style="color:red;">
                                    {{$message}}
                                </div>
                                @enderror
                        </div>
                        <div class="form-group col-md-3">
                            <label for="notes">Number Of Original Bills</label>
                            <input type="text" name="number_of_original" @isset($blDraft) value="{{$blDraft->number_of_original}}" @endisset class="form-control" placeholder="Number Of Original Bills" autocomplete="off">
                            @error('number_of_original')
                            <div style="color: red;">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        @if($booking->is_transhipment == 1)
                            <div class="form-group col-md-3">
                                    <label for="status">Bl Payment</label>
                                <select class="selectpicker form-control" data-live-search="true" name="payment_kind" title="{{trans('forms.select')}}" required>
                                    <option value="Prepaid">Prepaid</option>
                                    <option value="Collect">Collect</option>
                                </select>
                                @error('bl_kind')
                                <div style="color:red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            @else
                        <div class="form-group col-md-3">
                                <label for="status">Bl Payment</label>
                                    <input type="text" name="payment_kind" class="form-control" placeholder="Bl Payment" autocomplete="off" value="{{optional($booking->quotation)->payment_kind}}" disabled>
                                @error('bl_kind')
                                <div style="color:red;">
                                    {{$message}}
                                </div>
                                @enderror
                        </div>
                        @endif
                        <div class="form-group col-md-3">
                                <label for="status">Bl Kind</label>
                                <select class="form-control" data-live-search="true" name="bl_kind" title="{{trans('forms.select')}}">
                                    <option value="Original" @isset($blDraft){{"Original" == $blDraft->bl_kind?? "selected"}} @endisset>Original</option>
                                    @permission('BlDraft-Seaway')
                                        <option value="Seaway BL" @isset($blDraft){{"Seaway BL" == $blDraft->bl_kind?? "selected"}} @endisset>Seaway BL</option>
                                    @endpermission
                                </select>
                                @error('bl_kind')
                                <div style="color:red;">
                                    {{$message}}
                                </div>
                                @enderror
                        </div>
                        <div class="form-group col-md-3">
                            <label for="notes">OFR</label>
                        <input type="text"  class="form-control" style="background-color :#fff" @isset($blDraft) value="{{$blDraft->number_of_original}}" @else value="{{optional($booking->quotation)->ofr}}" @endisset disabled>
                        </div>
                        <div class="form-group col-md-3">
                                <label for="status">Bl Status<span class="text-warning"> * (Required.) </span></label>
                                <select class="form-control" data-live-search="true" name="bl_status" title="{{trans('forms.select')}}">
                                    <option value="1" @isset($blDraft){{"1" == $blDraft->bl_status?? "selected"}} @endisset>Confirm</option>
                                    <option value="0" @isset($blDraft){{"0" == $blDraft->bl_status?? "selected"}} @endisset>Draft</option>
                                </select>
                                @error('bl_status')
                                <div style="color:red;">
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
                                        <th class="text-center"></th>
                                    </tr>
                                </thead>
                                <tbody>
                        @if($booking_containers->count() != 0)
                            @foreach($booking_containers as $key => $bookingContainer)
                           {{-- @dd ($bookingContainer->weight); --}}
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                  <select class="selectpicker form-control" id="containerDetailsID" data-live-search="true" name="blDraftdetails[{{ $key }}][container_id]" data-size="10"
                                          title="{{trans('forms.select')}}">
                                          <option value="">Select</option>
                                          @foreach ($oldbookingcontainers as $item)
                                              <option value="{{$item->id}}" {{$item->id == old('container_id',$bookingContainer->container->id) ? 'selected':'disabled'}}>{{$item->code}}</option>
                                          @endforeach
                                  </select>
                                </td>
                                <td>
                                    <input type="text" id="seal_no" value="{{$bookingContainer->seal_no}}" name="blDraftdetails[{{ $key }}][seal_no]" class="form-control" autocomplete="off" placeholder="Seal No.S" required>
                                </td>

                                <td>
                                    <input type="text" id="Packs" value="{{$bookingContainer->packs}}" name="blDraftdetails[{{ $key }}][packs]" class="form-control input"  autocomplete="off" placeholder="Packs" required>
                                </td>

                                <td>
                                    <input type="text" id="Packs" value="{{$bookingContainer->pack_type}}"  name="blDraftdetails[{{ $key }}][pack_type]" class="form-control input"  autocomplete="off" placeholder="Packs Type" required>
                                </td>

                                <td>
                                    <input type="text" id="description" name="blDraftdetails[{{ $key }}][description]" class="form-control input"  autocomplete="off" placeholder="Description">
                                </td>

                                <td>
                                    <input type="text" id="gross_weight" value="{{$bookingContainer->weight}}" name="blDraftdetails[{{ $key }}][gross_weight]" class="form-control input"  autocomplete="off" placeholder="Gross Weight"  required>
                                </td>

                                <td>
                                    <input type="text" id="net_weight" value="{{$bookingContainer->net_weight}}" name="blDraftdetails[{{ $key }}][net_weight]" class="form-control input"  autocomplete="off" placeholder="Net Weight" required>
                                </td>

                                <td>
                                    <input type="text" id="measurement" name="blDraftdetails[{{ $key }}][measurement]" class="form-control input"  autocomplete="off" placeholder="Measurement">
                                </td>
                                <td style="width:85px;"><button type="button" class="btn btn-danger remove"><i class="fa fa-trash"></i></button></td>
                            </tr>
                            @endforeach
                            @endif
                            @php
                            if(!isset($key)){
                                $key = 0;
                            }
                            @endphp
                            @for($i=0 ; $i < $booking_qyt ; $i++)
                            <?php $key = $key + 1; ?>
                            <tr>
                            <td>{{ $i + 1 }}</td>

                                <td>
                                  <select class="selectpicker form-control" id="containerDetailsID" data-live-search="true" name="blDraftdetails[{{ $key }}][container_id]" data-size="10"
                                          title="{{trans('forms.select')}}">
                                          <option value="">Select</option>
                                          @foreach ($containers as $item)
                                              <option value="{{$item->id}}" {{$item->id == old('container_id') ? 'selected':''}}>{{$item->code}}</option>
                                          @endforeach
                                  </select>
                                </td>
                                <td>
                                    <input type="text" id="seal_no" name="blDraftdetails[{{ $key }}][seal_no]" class="form-control" autocomplete="off" placeholder="Seal No.S" required>
                                </td>

                                <td>
                                    <input type="text" id="Packs" name="blDraftdetails[{{ $key }}][packs]" class="form-control input"  autocomplete="off" placeholder="Packs" required>
                                </td>
                                <td>
                                    <input type="text" id="Packs" name="blDraftdetails[{{ $key }}][pack_type]" class="form-control input"  autocomplete="off" placeholder="Packs" required>
                                </td>
                                <td>
                                    <input type="text" id="description" name="blDraftdetails[{{ $key }}][description]" class="form-control input"  autocomplete="off" placeholder="Description">
                                </td>

                                <td>
                                    <input type="text" id="gross_weight" name="blDraftdetails[{{ $key }}][gross_weight]" class="form-control input"  autocomplete="off" placeholder="Gross Weight"  required>
                                </td>
                                <td>
                                    <input type="text" id="net_weight" name="blDraftdetails[{{ $key }}][net_weight]" class="form-control input"  autocomplete="off" placeholder="Net Weight" required>
                                </td>
                                <td>
                                    <input type="text" id="measurement" name="blDraftdetails[{{ $key }}][measurement]" class="form-control input"  autocomplete="off" placeholder="Measurement">
                                </td>
                                <td style="width:85px;"><button type="button" class="btn btn-danger remove"><i class="fa fa-trash"></i></button></td>
                            </tr>
                            @endfor

                            </tbody>
                    </table>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-primary mt-3">{{trans('forms.create')}}</button>
                                    <a href="{{route('bldraft.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                                </div>
                           </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    $(document).ready(function(){
        $("#blDraft").on("click", ".remove", function () {
            console.log(1);
            $(this).closest("tr").remove();
    });

});
</script>
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
