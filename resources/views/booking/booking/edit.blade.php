@extends('layouts.app')
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a a href="{{route('booking.index')}}">Booking</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">Booking Edit</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>

                </div>
                <div class="widget-content widget-content-area">
                    <!-- @if($booking->quotation_id != null)
                    <form>
                    <div class="form-row">
                        <div class="form-group col-md-10">
                            <label for="ref_no">Change Quotation Rate </label>
                            <select class="selectpicker form-control" id="quotation_id" name="quotation_id" data-live-search="true" data-size="10"
                                title="{{trans('forms.select')}}">
                                @foreach ($quotationRate as $item)
                                    <option value="{{$item->id}}" {{$item->id == old('quotation_id',$quotation->id) ? 'selected':''}}>{{$item->ref_no}} - {{optional($item->equipmentsType)->name}} - {{optional($item->customer)->name}} - {{$item->validity_from}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label style="color:#fff" >.</label>
                        </br>
                            <button type="submit" class="btn btn-primary mt show_confirm">Apply</button>
                        </div>
                    </div>
                </form>
                @endif -->
                    <form novalidate id="createForm" action="{{route('booking.update',['booking'=>$booking])}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <div class="form-row">
                            <div class="form-group col-md-3">
                                    <label for="ref_no">Booking Ref No <span class="text-warning"> * </span></label>
                                    <input type="text" class="form-control" id="ref_no" name="ref_no" value="{{old('ref_no',$booking->ref_no)}}"
                                        placeholder="Booking Ref No" autocomplete="off" required>
                                </div>
                                <input type="hidden" class="form-control"  name ="shipment_type"  value="Import">
                                    @if($quotation->id != 0)
                                        <input type="hidden" class="form-control" name="booking_type" value="{{$quotation->quotation_type}}">
                                    @else
                                    <div class="form-group col-md-2">
                                        <label>Booking Type <span class="text-warning"> * </span></label>
                                        <select class="selectpicker form-control" data-live-search="true" name="booking_type" title="{{trans('forms.select')}}" required>
                                        <option value="Empty"  {{$booking->id == old('booking_type') ||  $booking->booking_type == "Full"? 'selected':''}}>Full</option>
                                        <option value="Full"  {{$booking->id == old('booking_type') ||  $booking->booking_type == "Full"? 'selected':''}}>Full</option>

                                        </select>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>BL Type <span class="text-warning"> * </span></label>
                                        <select class="selectpicker form-control" data-live-search="true" name="bl_kind" title="{{trans('forms.select')}}" required>
                                            <option value="Original"  {{$booking->id == old('bl_kind') ||  $booking->bl_kind == "Original"? 'selected':''}}>Original</option>
                                            @permission('BlDraft-Seaway')
                                                <option value="Seaway BL"  {{$booking->id == old('bl_kind') ||  $booking->bl_kind == "Seaway BL"? 'selected':''}}>Seaway BL</option>
                                            @endpermission
                                        </select>
                                    </div>
                                    @endif
                            <div class="form-group col-md-2">
                                <label for="status">Booking Status<span class="text-warning"> * </span></label>
                                <select class="selectpicker form-control" data-live-search="true" name="booking_confirm" title="{{trans('forms.select')}}" required>
                                    <option value="1"  {{$booking->id == old('booking_confirm') ||  $booking->booking_confirm == "1"? 'selected':''}}>Confirm</option>
                                    <option value="3"  {{$booking->id == old('booking_confirm') ||  $booking->booking_confirm == "3"? 'selected':''}}>Draft</option>
                                </select>
                                @error('booking_confirm')
                                <div style="color:red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            
                            @php
                                $fields = ['coc' => 'COC', 'soc' => 'SOC', 'imo' => 'IMO', 'oog' => 'OOG', 'rf' => 'RF'];
                                $isDraft = $quotation->id == '0';
                            @endphp

                            <div class="form-group col-md-3" style="padding-top: 30px;">
                                <label for="special_requirements">Special Requirements</label>
                                <div class="form-check">
                                    @foreach ($fields as $field => $label)
                                        <div class="form-check-inline">
                                            <input type="checkbox" class="form-check-input" id="{{ $field }}" name="{{ $field }}" value="1"
                                                {{ old($field, $booking->$field) == 1 ? 'checked' : '' }}
                                                {{ $isDraft ? '' : 'disabled' }}>
                                            <label class="form-check-label" for="{{ $field }}" style="font-size: 15px; color: #3b3f5c; letter-spacing: 1px; margin-right: 10px;">
                                                {{ $label }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="customer_consignee_id">Consignee Name <span class="text-warning"> * </span></label>
                                <select class="selectpicker form-control" id="customer_consignee_id" data-live-search="true" name="customer_consignee_id" data-size="10"
                                 title="{{trans('forms.select')}}" required>
                                    @foreach ($consignee as $item)
                                        @if($quotation->customer_consignee_id != null)
                                        <option value="{{$item->id}}" {{$item->id == old('customer_consignee_id,$quotation->customer_consignee_id') ? 'selected':'disabled'}}>{{$item->name}}</option>
                                        @else
                                        <option value="{{$item->id}}" {{$item->id == old('customer_consignee_id',$booking->customer_consignee_id) ? 'selected':''}}>{{$item->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('customer_consignee_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label>New Reciver Name</label>
                                <input type="text" class="form-control" id="reciver_customer" name="reciver_customer" value="{{old('reciver_customer',$booking->reciver_customer)}}"
                                    placeholder="New Reciver Name" autocomplete="off">
                                @error('shipper_ref_no')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="ffw_id">Forwarder Name</label>
                                <select class="selectpicker form-control" id="ffw_id" data-live-search="true" name="ffw_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                 <option value="">Select....</option>
                                    @foreach ($ffw as $item)
                                        @if($quotation->customer_id != null)
                                            @if(in_array(6, optional($quotation->customer)->CustomerRoles->pluck('role_id')->toarray()))
                                            <option value="{{$item->id}}" {{$item->id == old('ffw_id',$quotation->customer_id) ? 'selected':'disabled'}}>{{$item->name}} @foreach($item->CustomerRoles as $itemRole) - {{optional($itemRole->role)->name}}@endforeach</option>
                                            @else
                                            <option value="{{$item->id}}" {{$item->id == old('ffw_id',$quotation->customer_id) ? 'selected':''}}>{{$item->name}} @foreach($item->CustomerRoles as $itemRole) - {{optional($itemRole->role)->name}}@endforeach</option>
                                            @endif
                                        @else
                                        <option value="{{$item->id}}" {{$item->id == old('ffw_id') ? 'selected':''}}>{{$item->name}} @foreach($item->CustomerRoles as $itemRole) - {{optional($itemRole->role)->name}}@endforeach</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('ffw_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="customer_id">Shipper Name</label>
                                <select class="selectpicker form-control" id="customer_id" data-live-search="true" name="customer_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                 <option value="">Select....</option>
                                    @foreach ($customers as $item)
                                        @if($quotation->customer_id != null)
                                            @if(in_array(1, optional($quotation->customer)->CustomerRoles->pluck('role_id')->toarray()))
                                            <option value="{{$item->id}}" {{$item->id == old('customer_id',$quotation->customer_id) ? 'selected':'disabled'}}>{{$item->name}} @foreach($item->CustomerRoles as $itemRole) - {{optional($itemRole->role)->name}}@endforeach</option>
                                            @else
                                            <option value="{{$item->id}}" {{$item->id == old('customer_id',$quotation->customer_id) ? 'selected':''}}>{{$item->name}} @foreach($item->CustomerRoles as $itemRole) - {{optional($itemRole->role)->name}}@endforeach</option>
                                            @endif
                                        @else
                                        <option value="{{$item->id}}" {{$item->id == old('customer_id',$booking->customer_id) ? 'selected':''}}>{{$item->name}} @foreach($item->CustomerRoles as $itemRole) - {{optional($itemRole->role)->name}}@endforeach</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('customer_id')
                                <div class="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="Principal">Principal Name <span class="text-warning"> * </span></label>
                                <select class="selectpicker form-control" id="Principal" data-live-search="true" name="principal_name" data-size="10"
                                title="{{trans('forms.select')}}" required>
                                    @foreach ($line as $item)
                                    @if($quotation->id == 0)
                                        <option value="{{$item->id}}" {{$item->id == old('principal_name',$booking->principal_name) ? 'selected':''}}>{{$item->name}}</option>
                                        @else
                                        <option value="{{$item->id}}" {{$item->id == old('principal_name',$quotation->principal_name) ? 'selected':'disabled'}}>{{$item->name}}</option>
                                    @endif
                                    @endforeach
                                </select>
                                @error('principal_name')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="vessel_name">Vessel Operator </label>
                                <select class="selectpicker form-control" id="vessel_name" data-live-search="true" name="vessel_name" data-size="10"
                                title="{{trans('forms.select')}}">
                                    @foreach ($line as $item)
                                    @if($quotation->id == 0)
                                        <option value="{{$item->id}}" {{$item->id == old('vessel_name',$booking->vessel_name) ? 'selected':''}}>{{$item->name}}</option>
                                        @else
                                        <option value="{{$item->id}}" {{$item->id == old('vessel_name',$quotation->vessel_name) ? 'selected':'disabled'}}>{{$item->name}}</option>
                                    @endif
                                    @endforeach
                                </select>
                                @error('vessel_name')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="load_port_id">Load Port <span class="text-warning"> * </span></label>
                                <select class="selectpicker form-control" id="load_port_id" data-live-search="true" name="load_port_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($ports as $item)
                                    @if($quotation->id == 0)
                                        <option value="{{$item->id}}" {{$item->id == old('load_port_id',$booking->load_port_id) ? 'selected':''}}>{{$item->name}}</option>
                                    @else
                                        <option value="{{$item->id}}" {{$item->id == old('load_port_id',$quotation->load_port_id) ? 'selected':'disabled'}}>{{$item->name}}</option>
                                    @endif
                                    @endforeach
                                </select>
                                @error('load_port_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="discharge_port_id">Discharge Port <span class="text-warning"> * </span></label>
                                <select class="selectpicker form-control" id="discharge_port_id" data-live-search="true" name="discharge_port_id" data-size="10"
                                 title="{{trans('forms.select')}}" required>
                                    @foreach ($ports as $item)
                                    @if($quotation->id == 0)
                                        <option value="{{$item->id}}" {{$item->id == old('discharge_port_id',$booking->discharge_port_id) ? 'selected':''}}>{{$item->name}}</option>
                                        @else
                                        <option value="{{$item->id}}" {{$item->id == old('discharge_port_id',$quotation->discharge_port_id) ? 'selected':'disabled'}}>{{$item->name}}</option>
                                    @endif
                                    @endforeach
                                </select>
                                @error('discharge_port_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        @if($booking->shipment_type == 'Import')
                            <div class="form-group col-md-4">
                                <label for="place_of_delivery_id">Place Of Delivery <span class="text-warning"> * </span></label>
                                <select class="selectpicker form-control" id="place_of_delivery_id" data-live-search="true" name="place_of_delivery_id" data-size="10"
                                 title="{{trans('forms.select')}}" required>
                                    @foreach ($ports as $item)
                                        @if($quotation->id == 0)
                                        <option value="{{$item->id}}" {{$item->id == old('place_of_delivery_id',$booking->place_of_delivery_id) ? 'selected':'disabled'}}>{{$item->name}}</option>
                                        @else
                                        <option value="{{$item->id}}" {{$item->id == old('place_of_delivery_id',$quotation->place_of_delivery_id) ? 'selected':''}}>{{$item->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('place_of_delivery_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        @else
                            <div class="form-group col-md-4">
                                <label for="place_of_delivery_id">Place Of Acceptence <span class="text-warning"> * </span></label>
                                <select class="selectpicker form-control" id="place_of_delivery_id" data-live-search="true" name="place_of_acceptence_id" data-size="10"
                                 title="{{trans('forms.select')}}" required>
                                    @foreach ($ports as $item)
                                        @if($quotation->id == 0)
                                        <option value="{{$item->id}}" {{$item->id == old('place_of_acceptence_id',$booking->place_of_acceptence_id) ? 'selected':'disabled'}}>{{$item->name}}</option>
                                        @else
                                        <option value="{{$item->id}}" {{$item->id == old('place_of_acceptence_id',$quotation->place_of_acceptence_id) ? 'selected':''}}>{{$item->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        </div>
                        <div class="form-row">
                        <div class="form-group col-md-4">
                                <label for="shipper_ref_no">Shipper Ref No</label>
                                <input type="text" class="form-control" id="shipper_ref_no" name="shipper_ref_no" value="{{old('shipper_ref_no',$booking->shipper_ref_no)}}"
                                    placeholder="Shipper Ref No" autocomplete="off">
                                @error('shipper_ref_no')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="forwarder_ref_no">Carrier Ref No</label>
                                <input type="text" class="form-control" id="forwarder_ref_no" name="forwarder_ref_no" value="{{old('forwarder_ref_no',$booking->forwarder_ref_no)}}"
                                    placeholder="Carrier Ref No" autocomplete="off">
                                @error('forwarder_ref_no')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                                @php
                                    $isDraft = request()->input('quotation_id') == "0";
                                @endphp
                                <div class="form-group col-md-4">
                                    <label for="Transhipment">Transhipment Port</label>
                                    <select class="selectpicker form-control" id="transhipment_port" data-live-search="true" name="transhipment_port" data-size="10" title="{{trans('forms.select')}}">
                                        <option value="">Select...</option>
                                        @foreach ($ports as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('transhipment_port',$booking->transhipment_port) ? 'selected' : ''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('transhipment_port')
                                        <div style="color: red;">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="terminal_id">Discharge Terminal <span class="text-warning"> * </span></label>
                                    <select class="selectpicker form-control" id="terminal" data-live-search="true" name="terminal_id" data-size="10" title="{{trans('forms.select')}}" required>
                                        <option value="">Select..</option>
                                        @foreach ($terminals as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('terminal_id',$booking->terminal_id) ? 'selected' : ''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('terminal_id')
                                        <div style="color: red;">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="voyage_id">Vessel / Voyage <span class="text-warning"> * </span></label>
                                    <select class="selectpicker form-control" id="voyage_id" data-live-search="true" name="voyage_id" data-size="10" title="{{trans('forms.select')}}" required>
                                        <option value="">Select..</option>
                                        @foreach ($voyages as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('voyage_id',$booking->voyage_id) ? 'selected' : ''}}>{{$item->vessel->name}} / {{$item->voyage_no}} - {{ optional($item->leg)->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('voyage_id')
                                        <div style="color: red;">{{$message}}</div>
                                    @enderror
                                </div>
                            </div>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="status">Movement</label>
                                <select class="selectpicker form-control" data-live-search="true" name="movement" title="{{trans('forms.select')}}">
                                    <option value="FCL/FCL"  {{$booking->id == old('movement') ||  $booking->movement == "FCL/FCL"? 'selected':''}}>FCL/FCL</option>
                                    <option value="LCL/LCL"  {{$booking->id == old('movement') ||  $booking->movement == "LCL/LCL"? 'selected':''}}>LCL/LCL</option>
                                </select>
                                @error('movement')
                                <div style="color:red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        @if($booking->shipment_type == 'Import')
                            <div class="form-group col-md-3">
                                <label for="acid">ACID <span class="text-warning"> * </span></label>
                                <input type="text" class="form-control" id="acid" name="acid" value="{{old('acid',$booking->acid)}}"
                                    placeholder="ACID" autocomplete="off" required>
                                @error('acid')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label>Importer ID <span class="text-warning"> * </span></label>
                                <input type="text" class="form-control"  style="background-color:#fff" name="importer_id" placeholder="Exporter Number" value="{{old('importer_id',$booking->importer_id)}}" autocomplete="off"  required>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Exporter ID <span class="text-warning"> * </span></label>
                                <input type="text" class="form-control"  style="background-color:#fff" name="exportal_id" placeholder="Exporter Number" value="{{old('exportal_id',$booking->exportal_id)}}" autocomplete="off"  required>
                            </div>
                        @else
                        <div class="form-group col-md-3">
                                <label for="acid">ACID</label>
                                <input type="text" class="form-control" id="acid" name="acid" value="{{old('acid',$booking->acid)}}"
                                    placeholder="ACID" autocomplete="off">
                                @error('acid')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label>Importer ID</label>
                                <input type="text" class="form-control"  style="background-color:#fff" name="importer_id" placeholder="Exporter Number" value="{{old('importer_id',$booking->importer_id)}}" autocomplete="off">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Exporter ID</label>
                                <input type="text" class="form-control"  style="background-color:#fff" name="exportal_id" placeholder="Exporter Number" value="{{old('exportal_id',$booking->exportal_id)}}" autocomplete="off">
                            </div>
                        @endif
                        </div>
                        <div class="form-row">
                            @if($isDraft)
                                <div class="form-group col-md-3">
                                    <label for="status">Bl Payment<span class="text-warning"> * </span></label>
                                    <select class="selectpicker form-control" data-live-search="true" name="payment_kind" title="{{trans('forms.select')}}" required>
                                        <option value="Prepaid" {{$booking->id == old('payment_kind') ||  $booking->payment_kind == "Prepaid"? 'selected':''}}>Prepaid</option>
                                        <option value="Collect" {{$booking->id == old('payment_kind') ||  $booking->payment_kind == "Collect"? 'selected':''}}>Collect</option>
                                    </select>
                                </div>
                                
                                <div class="form-group col-md-3">
                                    <label>Free Time <span class="text-warning"> * </span></label>
                                    <input type="text" class="form-control" id="free_time" name="free_time" value="{{old('free_time',$booking->free_time)}}"
                                        placeholder="Import Free Time" autocomplete="off">
                                </div>
                                @else
                                <input type="hidden" name="payment_kind" class="form-control" autocomplete="off" value="{{optional($quotation)->payment_kind}}">
                                <input type="hidden" name="free_time" class="form-control" autocomplete="off" value="{{optional($quotation)->import_detention}}">
                            @endif
                            <div class="form-group col-md-3">
                                <label for="tariff_service">Tariff Service</label>
                                @if($quotation->ref_no != null)
                                <input type="text" class="form-control" id="tariff_service" name="tariff_service" value="{{old('tariff_service',$quotation->ref_no)}}"
                                    placeholder="Tariff Service" autocomplete="off" readonly>
                                @else
                                <input type="text" class="form-control" id="tariff_service" name="tariff_service" value="{{old('tariff_service',$booking->ref_no)}}"
                                    placeholder="Tariff Service" autocomplete="off">
                                @endif
                                @error('tariff_service')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="commodity_code">Commodity Code</label>
                                <input type="text" class="form-control" id="commodity_code" name="commodity_code" value="{{old('commodity_code',$booking->commodity_code)}}"
                                    placeholder="Commodity Code" autocomplete="off">
                                @error('commodity_code')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="notes">Cargo Descripion <span class="text-warning"> * </span></label>
                                <textarea name="commodity_description" class="form-control" placeholder="Cargo Descripion" autocomplete="off" required>{{ old('commodity_description',$booking->commodity_description) }}</textarea>
                                @error('commodity_description')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="details">Notes</label>
                                <textarea class="form-control" id="details" name="notes" value="{{old('notes',$booking->notes)}}"
                                 placeholder="Notes" autocomplete="off"></textarea>
                                @error('notes')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <div class="custom-file-container" data-upload-id="certificat">
                                    <label> <span style="color:#3b3f5c";> Certificat </span><a href="javascript:void(0)" class="custom-file-container__image-clear" title="Clear Image"></a></label>
                                    <label class="custom-file-container__custom-file" >
                                        <input type="file" class="custom-file-container__custom-file__custom-file-input" name="certificat" accept="pdf">
                                        <input type="hidden" name="MAX_FILE_SIZE" disabled value="10485760" />
                                        <span class="custom-file-container__custom-file__custom-file-control"></span>
                                    </label>
                                    <div class="custom-file-container__image-preview"></div>
                                </div>
                                @error('certificat')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                    <!-- <input type="hidden" name="quotation_id" value="{{old('quotation_id',$quotation->id)}}"> -->
                            <!-- <div class="form-row"> -->
                        <!-- <h4>Container Details</h4> -->
                            @error('containerDetails')
                                <div style="color: red; font-size: 30px; text-align: center;">
                                    {{$message}}
                                </div>
                            @enderror
                            <table id="containerDetails" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">Container Type</th>
                                        <th class="text-center">Container No</th>
                                        <th class="text-center">QTY</th>
                                        @if(optional($booking)->shipment_type == "Import")
                                        <th class="text-center">Return Location</th>
                                        @else
                                        <th class="text-center">Pick Up Location</th>
                                        @endif 
                                       <th class="text-center">Seal No</th>
                                       <th class="text-center">Packs</th>
                                        <th class="text-center">Packs Type</th>
                                        <th class="text-center">HAZ / Reefer/ OOG Details / Haz Approval Ref</th>
                                        <th class="text-center">Net weight</th>
                                        <th class="text-center">Gross weight</th>
                                        <th class="text-center">
                                            <a id="add"> Add Container <i class="fas fa-plus"></i></a>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($booking_details as $key => $item)
                                <tr>
                                        <input type="hidden" value ="{{ $item->id }}" name="containerDetails[{{ $key }}][id]">
                                    <td class="container_type">
                                        <select class="selectpicker form-control" id="container_type" data-live-search="true" name="containerDetails[{{ $key }}][container_type]" data-size="10"
                                                title="{{trans('forms.select')}}">
                                            @foreach ($equipmentTypes as $equipmentType)
                                                <option value="{{$equipmentType->id}}" {{$equipmentType->id == old('container_type',$item->container_type) ? 'selected':''}}>{{$equipmentType->name}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    @if(optional($booking)->shipment_type == "Import")
                                    <td class="containerDetailsID">
                                        <select class="selectpicker form-control" id="containerDetailsID" name="containerDetails[{{ $key }}][container_id]" data-live-search="true"  data-size="10"
                                                title="{{trans('forms.select')}}">
                                                @foreach ($oldContainers as $container)
                                                    <option value="{{$container->id}}" {{$container->id == old('container_id',$item->container_id) ? 'selected':''}}>{{$container->code}}</option>
                                                @endforeach
                                        </select>
                                    </td>
                                    @else
                                    <td class="containerDetailsID">
                                        <select class="selectpicker form-control" id="containerDetailsID" name="containerDetails[{{ $key }}][container_id]" data-live-search="true"  data-size="10"
                                                title="{{trans('forms.select')}}">
                                                <option value="000" selected>Select</option>                                            
                                                @foreach ($oldContainers as $container)
                                                    <option value="{{$container->id}}" {{$container->id == old('container_id',$item->container_id) ? 'selected':''}}>{{$container->code}}</option>
                                                @endforeach
                                        </select>
                                    </td>
                                    @endif
                                    @if(optional($booking)->shipment_type == "Import")
                                    <td>
                                        <input type="text" name="containerDetails[{{ $key }}][qty]" class="form-control input"  autocomplete="off" placeholder="QTY" value="1" disabled>
                                    </td>   
                                    @else
                                    <td>
                                        <input type="text" id="qyt" onchange="return check();" name="containerDetails[{{ $key }}][qty]" class="form-control input"  autocomplete="off" placeholder="QTY" value="{{old('qty',$item->qty)}}" required>
                                    </td>
                                    @endif
                                    @if($quotation->shipment_type == 'Import')
                                        <td>
                                    @else
                                        <td class="ports">
                                    @endif
                                        <select class="selectpicker form-control" id="activity_location_id" name="containerDetails[{{ $key }}][activity_location_id]" data-live-search="true"  data-size="10"
                                        title="{{trans('forms.select')}}">
                                            @foreach ($activityLocations as $activityLocation)
                                                <option value="{{$activityLocation->id}}" {{$activityLocation->id == old('activity_location_id',$item->activity_location_id) ? 'selected':''}}>{{$activityLocation->code}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" id="seal_no" name="containerDetails[{{ $key }}][seal_no]" class="form-control" autocomplete="off" placeholder="Seal No" value="{{old('seal_no',$item->seal_no)}}">
                                    </td>
                                    <td>
                                        <input type="text" id="packs" name="containerDetails[{{ $key }}][packs]" class="form-control" autocomplete="off" placeholder="Packs" value="{{old('packs',$item->packs)}}">
                                    </td> 
                                   <td>
                                        <input type="text" name="containerDetails[{{ $key }}][pack_type]" class="form-control" autocomplete="off" placeholder="Pack Type" value="{{old('pack_type',$item->pack_type)}}">
                                    </td>
                                    <td>
                                        <input type="text" id="haz" name="containerDetails[{{ $key }}][haz]" class="form-control" autocomplete="off" placeholder="HAZ / REEFER/ OOG DETAILS / HAZ APPROVAL REF" value="{{old('haz',$item->haz)}}">
                                    </td>
                                    </td>
                                    <td>
                                        <input type="text" name="containerDetails[{{ $key }}][net_weight]" class="form-control" autocomplete="off" placeholder="Net Weight" value="{{old('net_weight',$item->net_weight)}}">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" id="weight" name="containerDetails[{{ $key }}][weight]" value="{{old('weight',$item->weight)}}"
                                        placeholder="Weight" autocomplete="off">
                                    </td>
                                    
                                    <td style="width:85px;">
                                        <button type="button" class="btn btn-danger remove" onclick="removeItem({{$item->id}})"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <input name="removed" id="removed" type="hidden"  value="">

                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-primary mt-3">{{trans('forms.edit')}}</button>
                                    <a href="{{route('booking.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
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

<script src="js/jquery.js"></script>
    <script type="text/javascript">
    function check(){
            //get the number
            var number = $('#qyt').val();

                    if(number == 0){
                        //show that the number is not allowed
                        alert("Container Qyt value Not Allowed 0");
                        $("#qyt").val('');
                    }
    }
</script>

<script src="js/jquery.js"></script>
    <script type="text/javascript">
    function check_value(){
            //get the number
            var number = $('#number').val();

                    if(number == 0){
                        //show that the number is not allowed
                        alert("Container Qyt value Not Allowed 0");
                        $("#number").val('');
                    }
    }
</script>
<script>
$(function(){
            $('#discharge_port_id').on('change',function(e){
                let value = e.target.value;
                let response =    $.get(`/api/master/terminals/${value}`).then(function(data){
                    let terminals = data.terminals || '';
                    let list2 = [`<option value=''>Select...</option>`];
                    for(let i = 0 ; i < terminals.length; i++){
                        list2.push(`<option value='${terminals[i].id}'>${terminals[i].name} </option>`);
                    }
            let terminal = $('#terminal');
            terminal.html(list2.join(''));
            });
        });
    });
    $(function(){
            $('#containerDetails').on('change','td.containerDetailsID select' ,function(e){
                let self = $(this);
                let parent = self.closest("tr");
                let name = e.target.name;
                let value = e.target.value;
                if(value == 000){
                    $(".input", parent).removeAttr('readonly');
                }else{
                    let valueee = 1;
                    $(".input", parent).val(valueee);
                    $(".input", parent).attr('readonly', true);
                }
        });
    });
</script>

<script>
  $(document).ready(function (){
        $(function(){
            let company_id = "{{ optional(Auth::user())->company->id }}";
                $('#containerDetails').on('change','td.container_type select' , function(e){
                  let self = $(this);
                  let equipment_id = self.val();
                  let parent = self.closest('tr');
                    let value = e.target.value;
                    let container = $('td.containerDetailsID select' , parent);
                    let response =    $.get(`/api/booking/activityContainers/${company_id}/${equipment_id}`).then(function(data){
                        let containers = data.containers || '';
                       console.log(containers);
                        let list2 = [`<option value=''>Select...</option>`];
                        for(let i = 0 ; i < containers.length; i++){
                        list2.push(`<option value='${containers[i].id}'>${containers[i].code} </option>`);
                    }
               container.html(list2.join(''));
               $(container).selectpicker('refresh');

                });
            });
        });
  });
</script>
@endpush

