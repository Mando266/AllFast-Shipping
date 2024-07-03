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
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">Add New Import Booking</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>

                <div class="widget-content widget-content-area">
                    <form novalidate id="createForm" action="{{route('booking.store')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                        <div class="form-row">
                            <input type="hidden" value="{{$quotation->id}}" name="quotation_id">
                            <div class="form-group col-md-3">
                                    <label for="ref_no">Booking Ref No <span class="text-warning"> * </span></label>
                                    <input type="text" class="form-control" id="ref_no" name="ref_no" value="{{old('ref_no')}}"
                                        placeholder="Booking Ref No" autocomplete="off" required>
                                </div>
                                <input type="hidden" class="form-control"  name ="shipment_type"  value="Import">
                                    @if($quotation->id != 0)
                                        <input type="hidden" class="form-control" name="booking_type" value="{{$quotation->quotation_type}}">
                                    @else
                                    <div class="form-group col-md-2">
                                        <label>Booking Type <span class="text-warning"> * </span></label>
                                        <select class="selectpicker form-control" data-live-search="true" name="booking_type" title="{{trans('forms.select')}}" required>
                                        <option value="Empty">Empty</option>
                                        <option value="Full">Full</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="bl_release">BL Type <span class="text-warning"> * </span></label>
                                        <select class="selectpicker form-control" data-live-search="true" name="bl_kind" title="{{trans('forms.select')}}" required>
                                            <option value="Original" @isset($booking){{"Original" == $booking->bl_kind?? "selected"}} @endisset>Original</option>
                                            @permission('BlDraft-Seaway')
                                                <option value="Seaway BL" @isset($booking){{"Seaway BL" == $booking->bl_kind?? "selected"}} @endisset>Seaway BL</option>
                                            @endpermission
                                        </select>
                                    </div>
                                    @endif
                            <div class="form-group col-md-2">
                                <label for="status">Booking Status<span class="text-warning"> * </span></label>
                                <select class="selectpicker form-control" data-live-search="true" name="booking_confirm" title="{{trans('forms.select')}}" required>
                                    <option value="1">Confirm</option>
                                    <option value="3">Draft</option>
                                </select>
                                @error('booking_confirm')
                                <div style="color:red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            
                            @php
                                $fields = ['coc' => 'COC', 'soc' => 'SOC', 'imo' => 'IMO', 'oog' => 'OOG', 'rf' => 'RF'];
                                $isDraft = request('quotation_id') == '0';
                            @endphp

                            <div class="form-group col-md-3" style="padding-top: 30px;">
                            <label for="special_requirements">Special Requirements</label>
                                <div class="form-check">
                                    @foreach ($fields as $field => $label)
                                        <input type="checkbox" id="{{ $field }}" name="{{ $field }}" value="1"
                                            {{ $quotation->$field == 1 ? 'checked' : 'disabled' }}
                                            {{ $isDraft ? '' : '' }}>
                                        <a style="font-size: 15px; color: #3b3f5c; letter-spacing: 1px; margin-right: 10px;"> {{ $label }} </a>
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
                                        <option value="{{$item->id}}" {{$item->id == old('customer_consignee_id') ? 'selected':''}}>{{$item->name}}</option>
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
                                <input type="text" class="form-control" id="reciver_customer" name="reciver_customer" value="{{old('reciver_customer')}}"
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
                                        <option value="{{$item->id}}" {{$item->id == old('customer_id',$quotation->customer_id) ? 'selected':''}}>{{$item->name}} @foreach($item->CustomerRoles as $itemRole) - {{optional($itemRole->role)->name}}@endforeach</option>
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
                                    @if($quotation->principal_name != null)
                                        <option value="{{$item->id}}" {{$item->id == old('principal_name',$quotation->principal_name) ? 'selected':'disabled'}}>{{$item->name}}</option>
                                        @else
                                        <option value="{{$item->id}}" {{$item->id == old('principal_name',$quotation->principal_name) ? 'selected':''}}>{{$item->name}}</option>
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
                                    @if($quotation->vessel_name != null)
                                        <option value="{{$item->id}}" {{$item->id == old('vessel_name',$quotation->vessel_name) ? 'selected':'disabled'}}>{{$item->name}}</option>
                                        @else
                                        <option value="{{$item->id}}" {{$item->id == old('vessel_name',$quotation->vessel_name) ? 'selected':''}}>{{$item->name}}</option>
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
                                 title="{{trans('forms.select')}}" required>
                                    @foreach ($ports as $item)
                                        @if($quotation->load_port_id != null)
                                        <option value="{{$item->id}}" {{$item->id == old('load_port_id',$quotation->load_port_id) ? 'selected':'disabled'}}>{{$item->name}}</option>
                                        @else
                                        <option value="{{$item->id}}" {{$item->id == old('load_port_id',$quotation->load_port_id) ? 'selected':''}}>{{$item->name}}</option>
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
                                    @if($quotation->discharge_port_id != null)
                                        <option value="{{$item->id}}" {{$item->id == old('discharge_port_id',$quotation->discharge_port_id) ? 'selected':'disabled'}}>{{$item->name}}</option>
                                        @else
                                        <option value="{{$item->id}}" {{$item->id == old('discharge_port_id',$quotation->discharge_port_id) ? 'selected':''}}>{{$item->name}}</option>
                                    @endif
                                    @endforeach
                                </select>
                                @error('discharge_port_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="place_of_delivery_id">Place Of Delivery <span class="text-warning"> * </span></label>
                                <select class="selectpicker form-control" id="place_of_delivery_id" data-live-search="true" name="place_of_delivery_id" data-size="10"
                                 title="{{trans('forms.select')}}" required>
                                    @foreach ($ports as $item)
                                        @if($quotation->place_of_delivery_id != null)
                                        <option value="{{$item->id}}" {{$item->id == old('place_of_delivery_id',$quotation->place_of_delivery_id) ? 'selected':'disabled'}}>{{$item->name}}</option>
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
                        </div>
                        <div class="form-row">
                        <div class="form-group col-md-4">
                                <label for="shipper_ref_no">Shipper Ref No</label>
                                <input type="text" class="form-control" id="shipper_ref_no" name="shipper_ref_no" value="{{old('shipper_ref_no')}}"
                                    placeholder="Shipper Ref No" autocomplete="off">
                                @error('shipper_ref_no')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="forwarder_ref_no">Carrier Ref No</label>
                                <input type="text" class="form-control" id="forwarder_ref_no" name="forwarder_ref_no" value="{{old('forwarder_ref_no')}}"
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
                                            <option value="{{$item->id}}" {{$item->id == old('transhipment_port') ? 'selected' : ''}}>{{$item->name}}</option>
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
                                            <option value="{{$item->id}}" {{$item->id == old('terminal_id') ? 'selected' : ''}}>{{$item->name}}</option>
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
                                            <option value="{{$item->id}}" {{$item->id == old('voyage_id') ? 'selected' : ''}}>{{$item->vessel->name}} / {{$item->voyage_no}} - {{ optional($item->leg)->name }}</option>
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
                                    <option value="FCL/FCL">FCL/FCL</option>
                                    <option value="LCL/LCL">LCL/LCL</option>
                                </select>
                                @error('movement')
                                <div style="color:red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="acid">ACID <span class="text-warning"> * </span></label>
                                <input type="text" class="form-control" id="acid" name="acid" value="{{old('acid')}}"
                                    placeholder="ACID" autocomplete="off" required>
                                @error('acid')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label>Importer ID <span class="text-warning"> * </span></label>
                                <input type="text" class="form-control"  style="background-color:#fff" name="importer_id" placeholder="Exporter Number"  autocomplete="off"  required>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Exporter ID <span class="text-warning"> * </span></label>
                                <input type="text" class="form-control"  style="background-color:#fff" name="exportal_id" placeholder="Exporter Number"  autocomplete="off"  required>
                            </div>
                        </div>
                        <div class="form-row">
                            @if($isDraft)
                                <div class="form-group col-md-3">
                                    <label for="status">Bl Payment<span class="text-warning"> * </span></label>
                                    <select class="selectpicker form-control" data-live-search="true" name="payment_kind" title="{{trans('forms.select')}}" required>
                                        <option value="Prepaid">Prepaid</option>
                                        <option value="Collect">Collect</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Import Free Time <span class="text-warning"> * </span></label>
                                    <input type="text" class="form-control" id="free_time" name="free_time" value="{{old('free_time')}}"
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
                                <input type="text" class="form-control" id="tariff_service" name="tariff_service" value="{{old('tariff_service',$quotation->ref_no)}}"
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
                                <input type="text" class="form-control" id="commodity_code" name="commodity_code" value="{{old('commodity_code')}}"
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
                                <textarea name="commodity_description" class="form-control" placeholder="Cargo Descripion" autocomplete="off" required>@isset($booking){{$blDraft->booking}} @endisset</textarea>
                                @error('commodity_description')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="details">Notes</label>
                                <textarea class="form-control" id="details" name="notes" value="{{old('notes')}}"
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

                        <h4>Container Details</h4>
                        <table id="containerDetails" class="table table-bordered">
                            <thead>
                            <tr>
                                <th class="text-center">Container No</th>
                                <th class="text-center">Container Type</th>
                                <th class="text-center">QTY</th>
                                <th class="text-center">Return Location</th>
                                <th class="text-center">Seal No</th>
                                <th class="text-center">HAZ / Reefer/ OOG Details / Haz Approval Ref</th>
                                <th class="text-center">Packs</th>
                                <th class="text-center">Packs Type</th>
                                <th class="text-center">Commodity Des</th>
                                <th class="text-center">Gross Weight Kgs</th>
                                <th class="text-center">Net Weight Kgs</th>
                                <th class="text-center">Add Container</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr id="initialRow">
                                <td>
                                    <input type="text" style="width: 155px;" name="containerDetails[0][container_number]" class="form-control container-number" placeholder="Container No" autocomplete="off" required>
                                    <input type="hidden" name="containerDetails[0][container_id]" class="container-id">
                                </td>
                                <td class="container_type">
                                    <select class="selectpicker form-control" name="containerDetails[0][container_type]" data-live-search="true" title="Select" required>
                                        @foreach ($equipmentTypes as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="text" name="containerDetails[0][qty]" class="form-control qty" placeholder="QTY" value = '1' disabled required></td>
                                <td class="ports">
                                    <select class="selectpicker form-control" name="containerDetails[0][activity_location_id]" data-live-search="true" title="Select" required>
                                        @foreach ($activityLocations as $location)
                                            <option value="{{ $location->id }}">{{ $location->code }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="text" name="containerDetails[0][seal_no]" class="form-control" placeholder="Seal No" autocomplete="off"></td>
                                <td><input type="text" name="containerDetails[0][haz]" class="form-control" placeholder="HAZ / REEFER/ OOG DETAILS / HAZ APPROVAL REF"></td>
                                <td>
                                    <input type="text" id="Packs" name="containerDetails[0][packs]" class="form-control input"  autocomplete="off" placeholder="Packs" required>
                                </td>
                                <td>
                                    <input type="text" id="Packs" name="containerDetails[0][pack_type]" class="form-control input"  autocomplete="off" placeholder="Packs Type" required>
                                </td>
                                <td>
                                    <input type="text" id="Packs" name="containerDetails[0][descripion]" class="form-control input"  autocomplete="off" placeholder="Commodity Des">
                                </td>  
                                <td>
                                    <input type="text" id="gross_weight"  name="containerDetails[0][weight]" class="form-control input"  autocomplete="off" placeholder="Gross Weight"  required>
                                </td>

                                <td>
                                    <input type="text" id="net_weight" name="containerDetails[0][net_weight]" class="form-control input"  autocomplete="off" placeholder="Net Weight">
                                </td>
                               <td><button type="button" id="addContainerRow" class="btn btn-primary"><i class="fas fa-plus"></i></button></td>
                            </tr>
                            </tbody>
                        </table>
 
                        <!-- Modal for error messages -->
                        <div class="modal fade" id="containerErrorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="errorModalLabel">Container Error</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p id="errorModalMessage"></p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-primary mt-3">{{trans('forms.create')}}</button>
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
@include('booking.booking._validate&AddContainer')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
    // Function to load terminals
    function loadTerminals(value) {
        $.get(`/api/master/terminals/${value}`).then(function(data) {
            let terminals = data.terminals || [];
            let list2 = [`<option value=''>Select...</option>`];
            for (let i = 0; i < terminals.length; i++) {
                list2.push(`<option value='${terminals[i].id}'>${terminals[i].name}</option>`);
            }
            $('#terminal').html(list2.join('')).selectpicker('refresh'); // Refresh the selectpicker
        }).fail(function() {
            console.error("Failed to load terminals.");
        });
    }

    // Trigger the change event to load terminals when the discharge port changes
    $('#discharge_port_id').on('change', function(e) {
        let value = e.target.value;
        loadTerminals(value);
    });

    // Load terminals on page load if there's a pre-selected discharge port
    let initialDischargePort = $('#discharge_port_id').val();
    if (initialDischargePort) {
        loadTerminals(initialDischargePort);
    } else {
        $('#terminal').selectpicker('refresh'); // Refresh if no initial value
    }
    // Ensure the selectpicker is initialized on page load
    $('.selectpicker').selectpicker();
});
</script>
@endpush

