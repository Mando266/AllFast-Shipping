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
                                <li class="breadcrumb-item active"><a href="javascript:void(0);">Edit Booking</a></li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                    </div>

                    <div class="widget-content widget-content-area">
                        <form id="editForm" action="{{ route('booking.update', $booking->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Form Fields -->
                            <div class="form-row">
                                <input type="hidden" value="{{ $quotation->id }}" name="quotation_id">
                                <div class="form-group col-md-3">
                                    <label for="ref_no">Booking Ref No <span class="text-warning"> * </span></label>
                                    <input type="text" class="form-control" id="ref_no" name="ref_no" value="{{ old('ref_no', $booking->ref_no) }}" placeholder="Booking Ref No" autocomplete="off" required>
                                </div>
                                <input type="hidden" class="form-control" name="shipment_type" value="Import">
                                @if($quotation->id != 0)
                                    <input type="hidden" class="form-control" name="booking_type" value="{{ $quotation->quotation_type }}">
                                @else
                                    <div class="form-group col-md-2">
                                        <label>Booking Type <span class="text-warning"> * </span></label>
                                        <select class="selectpicker form-control" data-live-search="true" name="booking_type" title="{{ trans('forms.select') }}" required>
                                            <option value="Empty" {{ old('booking_type', $booking->booking_type) == 'Empty' ? 'selected' : '' }}>Empty</option>
                                            <option value="Full" {{ old('booking_type', $booking->booking_type) == 'Full' ? 'selected' : '' }}>Full</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="bl_release">BL Type <span class="text-warning"> * </span></label>
                                        <select class="selectpicker form-control" data-live-search="true" name="bl_kind" title="{{ trans('forms.select') }}" required>
                                            <option value="Original" {{ old('bl_kind', $booking->bl_kind) == 'Original' ? 'selected' : '' }}>Original</option>
                                            <option value="Seaway BL" {{ old('bl_kind', $booking->bl_kind) == 'Seaway BL' ? 'selected' : '' }}>Seaway BL</option>
                                        </select>
                                    </div>
                                @endif
                                <div class="form-group col-md-2">
                                    <label for="status">Booking Status<span class="text-warning"> * </span></label>
                                    <select class="selectpicker form-control" data-live-search="true" name="booking_confirm" title="{{ trans('forms.select') }}" required>
                                        <option value="1" {{ old('booking_confirm', $booking->booking_confirm) == 1 ? 'selected' : '' }}>Confirm</option>
                                        <option value="3" {{ old('booking_confirm', $booking->booking_confirm) == 3 ? 'selected' : '' }}>Draft</option>
                                    </select>
                                    @error('booking_confirm')
                                    <div style="color:red;">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-3" style="padding-top: 30px;">
                                    <label for="special_requirements">Special Requirements</label>
                                    <div class="form-check">
                                        @php
                                            $fields = ['coc' => 'COC', 'soc' => 'SOC', 'imo' => 'IMO', 'oog' => 'OOG', 'rf' => 'RF'];
                                        @endphp
                                        @foreach ($fields as $field => $label)
                                            <input type="checkbox" id="{{ $field }}" name="{{ $field }}" value="1"
                                                {{ old($field, $booking->$field) ? 'checked' : '' }}>
                                            <a style="font-size: 15px; color: #3b3f5c; letter-spacing: 1px; margin-right: 10px;">{{ $label }}</a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="customer_consignee_id">Consignee Name <span class="text-warning"> * </span></label>
                                    <select class="selectpicker form-control" id="customer_consignee_id" data-live-search="true" name="customer_consignee_id" data-size="10" title="{{ trans('forms.select') }}" required>
                                        @foreach ($consignee as $item)
                                            <option value="{{ $item->id }}" {{ old('customer_consignee_id', $booking->customer_consignee_id) == $item->id ? 'selected' : '' }}>{{ $item->name }} @foreach($item->CustomerRoles as $itemRole) - {{ optional($itemRole->role)->name }}@endforeach</option>
                                        @endforeach
                                    </select>
                                    @error('customer_consignee_id')
                                    <div style="color: red;">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="reciver_customer">New Receiver Name</label>
                                    <input type="text" class="form-control" id="reciver_customer" name="reciver_customer" value="{{ old('reciver_customer', $booking->reciver_customer) }}" placeholder="New Receiver Name" autocomplete="off">
                                    @error('reciver_customer')
                                    <div style="color: red;">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="ffw_id">Forwarder Name</label>
                                    <select class="selectpicker form-control" id="ffw_id" data-live-search="true" name="ffw_id" data-size="10" title="{{ trans('forms.select') }}">
                                        <option value="">Select....</option>
                                        @foreach ($ffw as $item)
                                            <option value="{{ $item->id }}" {{ old('ffw_id', $booking->ffw_id) == $item->id ? 'selected' : '' }}>{{ $item->name }} @foreach($item->CustomerRoles as $itemRole) - {{ optional($itemRole->role)->name }}@endforeach</option>
                                        @endforeach
                                    </select>
                                    @error('ffw_id')
                                    <div style="color: red;">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="customer_id">Shipper Name</label>
                                    <select class="selectpicker form-control" id="customer_id" data-live-search="true" name="customer_id" data-size="10" title="{{ trans('forms.select') }}">
                                        <option value="">Select....</option>
                                        @foreach ($customers as $item)
                                            <option value="{{ $item->id }}" {{ old('customer_id', $booking->customer_id) == $item->id ? 'selected' : '' }}>{{ $item->name }} @foreach($item->CustomerRoles as $itemRole) - {{ optional($itemRole->role)->name }}@endforeach</option>
                                        @endforeach
                                    </select>
                                    @error('customer_id')
                                    <div class="color: red;">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="principal_name">Principal Name <span class="text-warning"> * </span></label>
                                    <select class="selectpicker form-control" id="principal_name" data-live-search="true" name="principal_name" data-size="10" title="{{ trans('forms.select') }}" required>
                                        @foreach ($line as $item)
                                            <option value="{{ $item->id }}" {{ old('principal_name', $booking->principal_name) == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('principal_name')
                                    <div style="color: red;">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="vessel_name">Vessel Operator </label>
                                    <select class="selectpicker form-control" id="vessel_name" data-live-search="true" name="vessel_name" data-size="10" title="{{ trans('forms.select') }}">
                                        @foreach ($line as $item)
                                            <option value="{{ $item->id }}" {{ old('vessel_name', $booking->vessel_name) == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('vessel_name')
                                    <div style="color: red;">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="place_of_acceptence_id">Place Of Acceptance </label>
                                    <select class="selectpicker form-control" id="place_of_acceptence_id" data-live-search="true" name="place_of_acceptence_id" data-size="10" title="{{ trans('forms.select') }}">
                                        @foreach ($ports as $item)
                                            <option value="{{ $item->id }}" {{ old('place_of_acceptence_id', $booking->place_of_acceptence_id) == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('place_of_acceptence_id')
                                    <div style="color: red;">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="load_port_id">Load Port <span class="text-warning"> * </span></label>
                                    <select class="selectpicker form-control" id="load_port_id" data-live-search="true" name="load_port_id" data-size="10" title="{{ trans('forms.select') }}" required>
                                        @foreach ($ports as $item)
                                            <option value="{{ $item->id }}" {{ old('load_port_id', $booking->load_port_id) == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('load_port_id')
                                    <div style="color: red;">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="shipper_ref_no">Shipper Ref No</label>
                                    <input type="text" class="form-control" id="shipper_ref_no" name="shipper_ref_no" value="{{ old('shipper_ref_no', $booking->shipper_ref_no) }}" placeholder="Shipper Ref No" autocomplete="off">
                                    @error('shipper_ref_no')
                                    <div style="color: red;">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="place_of_delivery_id">Place Of Delivery <span class="text-warning"> * </span></label>
                                    <select class="selectpicker form-control" id="place_of_delivery_id" data-live-search="true" name="place_of_delivery_id" data-size="10" title="{{trans('forms.select')}}" required>
                                        @foreach ($ports as $item)
                                            <option value="{{ $item->id }}" {{ old('place_of_delivery_id', $booking->place_of_delivery_id) == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('place_of_delivery_id')
                                    <div style="color: red;">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="discharge_port_id">Discharge Port <span class="text-warning"> * </span></label>
                                    <select class="selectpicker form-control" id="discharge_port_id" data-live-search="true" name="discharge_port_id" data-size="10" title="{{trans('forms.select')}}" required>
                                        @foreach ($ports as $item)
                                            <option value="{{ $item->id }}" {{ old('discharge_port_id', $booking->discharge_port_id) == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('discharge_port_id')
                                    <div style="color: red;">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="forwarder_ref_no">Carrier Ref No</label>
                                    <input type="text" class="form-control" id="forwarder_ref_no" name="forwarder_ref_no" value="{{ old('forwarder_ref_no', $booking->forwarder_ref_no) }}" placeholder="Carrier Ref No" autocomplete="off">
                                    @error('forwarder_ref_no')
                                    <div style="color: red;">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="transhipment_port">Transhipment Port</label>
                                    <select class="selectpicker form-control" id="transhipment_port" data-live-search="true" name="transhipment_port" data-size="10" title="{{trans('forms.select')}}">
                                        <option value="">Select...</option>
                                        @foreach ($activityLocations as $item)
                                            <option value="{{$item->id}}" {{ old('transhipment_port', $booking->transhipment_port) == $item->id ? 'selected' : '' }}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('transhipment_port')
                                    <div style="color: red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="terminal_id">Discharge Terminal <span class="text-warning"> * </span></label>
                                    <select class="selectpicker form-control" id="terminal_id" data-live-search="true" name="terminal_id" data-size="10" title="{{trans('forms.select')}}" required>
                                        <option value="">Select..</option>
                                        @foreach ($terminals as $item)
                                            <option value="{{$item->id}}" {{ old('terminal_id', $booking->terminal_id) == $item->id ? 'selected' : '' }}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('terminal_id')
                                    <div style="color: red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="voyage_id">Vessel / Voyage <span class="text-warning"> * </span></label>
                                    <select class="selectpicker form-control" id="voyage_id" data-live-search="true" name="voyage_id" data-size="10" title="{{ trans('forms.select') }}" required>
                                        <option value="">Select..</option>
                                        @foreach ($voyages as $item)
                                            <option value="{{ $item->id }}" {{ old('voyage_id', $booking->voyage_id) == $item->id ? 'selected' : '' }}>{{ $item->vessel->name }} / {{ $item->voyage_no }} - {{ optional($item->leg)->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('voyage_id')
                                    <div style="color: red;">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label for="movement">Movement</label>
                                    <select class="selectpicker form-control" data-live-search="true" name="movement" title="{{ trans('forms.select') }}">
                                        <option value="FCL/FCL" {{ old('movement', $booking->movement) == 'FCL/FCL' ? 'selected' : '' }}>FCL/FCL</option>
                                        <option value="LCL/LCL" {{ old('movement', $booking->movement) == 'LCL/LCL' ? 'selected' : '' }}>LCL/LCL</option>
                                    </select>
                                    @error('movement')
                                    <div style="color:red;">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="acid">ACID <span class="text-warning"> * </span></label>
                                    <input type="text" class="form-control" id="acid" name="acid" value="{{ old('acid', $booking->acid) }}" placeholder="ACID" autocomplete="off" required>
                                    @error('acid')
                                    <div style="color: red;">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="importer_id">Importer ID <span class="text-warning"> * </span></label>
                                    <input type="text" class="form-control" name="importer_id" value="{{ old('importer_id', $booking->importer_id) }}" placeholder="Importer Number" autocomplete="off" required>
                                    @error('importer_id')
                                    <div style="color: red;">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="exportal_id">Exporter ID <span class="text-warning"> * </span></label>
                                    <input type="text" class="form-control" name="exportal_id" value="{{ old('exportal_id', $booking->exportal_id) }}" placeholder="Exporter Number" autocomplete="off" required>
                                    @error('exportal_id')
                                    <div style="color: red;">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row">
                                @if(request()->input('quotation_id') == "0")
                                    <div class="form-group col-md-3">
                                        <label for="payment_kind">Bl Payment<span class="text-warning"> * </span></label>
                                        <select class="selectpicker form-control" data-live-search="true" name="payment_kind" title="{{ trans('forms.select') }}" required>
                                            <option value="Prepaid" {{ old('payment_kind', $booking->payment_kind) == 'Prepaid' ? 'selected' : '' }}>Prepaid</option>
                                            <option value="Collect" {{ old('payment_kind', $booking->payment_kind) == 'Collect' ? 'selected' : '' }}>Collect</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="import_free_time">Import Free Time <span class="text-warning"> * </span></label>
                                        <input type="text" class="form-control" id="import_free_time" name="import_free_time" value="{{ old('import_free_time', $booking->import_free_time) }}" placeholder="Import Free Time" autocomplete="off">
                                    </div>
                                @else
                                    <input type="hidden" name="payment_kind" class="form-control" autocomplete="off" value="{{ optional($quotation)->payment_kind }}">
                                    <input type="hidden" name="import_free_time" class="form-control" autocomplete="off" value="{{ optional($quotation)->import_detention }}">
                                @endif
                                <div class="form-group col-md-3">
                                    <label for="tariff_service">Tariff Service</label>
                                    <input type="text" class="form-control" id="tariff_service" name="tariff_service" value="{{ old('tariff_service', $booking->tariff_service) }}" placeholder="Tariff Service" autocomplete="off">
                                    @error('tariff_service')
                                    <div style="color: red;">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="commodity_code">Commodity Code</label>
                                    <input type="text" class="form-control" id="commodity_code" name="commodity_code" value="{{ old('commodity_code', $booking->commodity_code) }}" placeholder="Commodity Code" autocomplete="off">
                                    @error('commodity_code')
                                    <div style="color: red;">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="commodity_description">Cargo Description <span class="text-warning"> * </span></label>
                                    <textarea name="commodity_description" class="form-control" placeholder="Cargo Description" autocomplete="off" required>{{ old('commodity_description', $booking->commodity_description) }}</textarea>
                                    @error('commodity_description')
                                    <div style="color: red;">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="notes">Notes</label>
                                    <textarea class="form-control" id="notes" name="notes" placeholder="Notes" autocomplete="off">{{ old('notes', $booking->notes) }}</textarea>
                                    @error('notes')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <div class="custom-file-container" data-upload-id="certificat">
                                        <label> <span style="color:#3b3f5c;"> Certificat </span><a href="javascript:void(0)" class="custom-file-container__image-clear" title="Clear Image"></a></label>
                                        <label class="custom-file-container__custom-file">
                                            <input type="file" class="custom-file-container__custom-file__custom-file-input" name="certificat" accept="pdf">
                                            <input type="hidden" name="MAX_FILE_SIZE" disabled value="10485760" />
                                            <span class="custom-file-container__custom-file__custom-file-control"></span>
                                        </label>
                                        <div class="custom-file-container__image-preview"></div>
                                    </div>
                                    @error('certificat')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Container Details -->
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
                                @foreach($booking->bookingContainerDetails as $index => $containerDetail)

{{--                                    @dd( $containerDetail)--}}
                                    <tr id="row{{ $index }}">
                                        <td>
                                            <input type="text" style="width: 155px;" name="containerDetails[{{ $index }}][container_number]" class="form-control container-number" value="{{ old('containerDetails.'.$index.'.container_number', $containerDetail->container->code) }}" placeholder="Container No" autocomplete="off" required>
                                            <input type="hidden" name="containerDetails[{{ $index }}][container_id]" class="container-id" value="{{ old('containerDetails.'.$index.'.container_id', $containerDetail->container_id) }}">
                                        </td>
                                        <td class="container_type">
                                            <select class="selectpicker form-control" name="containerDetails[{{ $index }}][container_type]" data-live-search="true" title="Select" required>
                                                @foreach ($equipmentTypes as $item)
                                                    <option value="{{ $item->id }}" {{ old('containerDetails.'.$index.'.container_type', $containerDetail->container_type) == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input type="text" name="containerDetails[{{ $index }}][qty]" class="form-control qty" value="{{ old('containerDetails.'.$index.'.qty', $containerDetail->qty) }}" placeholder="QTY" disabled required></td>
                                        <td class="ports">
                                            <select class="selectpicker form-control" name="containerDetails[{{ $index }}][activity_location_id]" data-live-search="true" title="Select" required>
                                                @foreach ($activityLocations as $location)
                                                    <option value="{{ $location->id }}" {{ old('containerDetails.'.$index.'.activity_location_id', $containerDetail->activity_location_id) == $location->id ? 'selected' : '' }}>{{ $location->code }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input type="text" name="containerDetails[{{ $index }}][seal_no]" class="form-control" value="{{ old('containerDetails.'.$index.'.seal_no', $containerDetail->seal_no) }}" placeholder="Seal No" autocomplete="off"></td>
                                        <td><input type="text" name="containerDetails[{{ $index }}][haz]" class="form-control" value="{{ old('containerDetails.'.$index.'.haz', $containerDetail->haz) }}" placeholder="HAZ / REEFER/ OOG DETAILS / HAZ APPROVAL REF"></td>
                                        <td>
                                            <input type="text" id="Packs" name="containerDetails[{{ $index }}][packs]" class="form-control input" value="{{ old('containerDetails.'.$index.'.packs', $containerDetail->packs) }}" autocomplete="off" placeholder="Packs" required>
                                        </td>
                                        <td>
                                            <input type="text" id="Packs" name="containerDetails[{{ $index }}][pack_type]" class="form-control input" value="{{ old('containerDetails.'.$index.'.pack_type', $containerDetail->pack_type) }}" autocomplete="off" placeholder="Packs Type" required>
                                        </td>
                                        <td>
                                            <input type="text" id="Packs" name="containerDetails[{{ $index }}][descripion]" class="form-control input" value="{{ old('containerDetails.'.$index.'.descripion', $containerDetail->descripion) }}" autocomplete="off" placeholder="Commodity Description">
                                        </td>
                                        <td>
                                            <input type="text" id="gross_weight" name="containerDetails[{{ $index }}][weight]" class="form-control input" value="{{ old('containerDetails.'.$index.'.weight', $containerDetail->weight) }}" autocomplete="off" placeholder="Gross Weight" required>
                                        </td>
                                        <td>
                                            <input type="text" id="net_weight" name="containerDetails[{{ $index }}][net_weight]" class="form-control input" value="{{ old('containerDetails.'.$index.'.net_weight', $containerDetail->net_weight) }}" autocomplete="off" placeholder="Net Weight">
                                        </td>
                                        <td><button type="button" class="btn btn-primary" id="addContainerRow"><i class="fas fa-plus"></i></button></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <!-- Modal for error messages -->
                            <div class="modal fade" id="containerErrorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="errorModalLabel">Container Error</h5>
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
                                    <button type="submit" class="btn btn-primary mt-3">{{ trans('forms.update') }}</button>
                                    <a href="{{ route('booking.index') }}" class="btn btn-danger mt-3">{{ trans('forms.cancel') }}</a>
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
            function loadTerminals(value) {
                $.get(`/api/master/terminals/${value}`).then(function (data) {
                    let terminals = data.terminals || [];
                    let list2 = [`<option value=''>Select...</option>`];
                    for (let i = 0; i < terminals.length; i++) {
                        list2.push(`<option value='${terminals[i].id}'>${terminals[i].name}</option>`);
                    }
                    $('#terminal').html(list2.join('')).selectpicker('refresh'); // Refresh the selectpicker
                }).fail(function () {
                    console.error("Failed to load terminals.");
                });
            }

            $('#discharge_port_id').on('change', function (e) {
                let value = e.target.value;
                loadTerminals(value);
            });
            let initialDischargePort = $('#discharge_port_id').val();
            if (initialDischargePort) {
                loadTerminals(initialDischargePort);
            } else {
                $('#terminal').selectpicker('refresh'); // Refresh if no initial value
            }
            $('.selectpicker').selectpicker();

            $('#addContainerRow').on('click', function() {
                var containerIndex = $('#containerDetails tbody tr').length; // Get the number of current rows to continue indexing properly

                var newRow = `
            <tr>
                <td>
                    <input type="text" name="containerDetails[${containerIndex}][container_number]" class="form-control container-number" placeholder="Container No" required>
                </td>
                <td>
                    <select class="selectpicker form-control" name="containerDetails[${containerIndex}][container_type]" data-live-search="true" title="Select" required>
                        @foreach ($equipmentTypes as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="text" name="containerDetails[${containerIndex}][qty]" class="form-control qty" placeholder="QTY" required>
                </td>
                <td>
                <select class="selectpicker form-control" name="containerDetails[${containerIndex}][activity_location_id]" data-live-search="true" title="Select" required>
                    @foreach ($activityLocations as $location)
                <option value="{{ $location->id }}">{{ $location->name }}</option>
                    @endforeach
                </select>
                </td>
                <td>
                    <input type="text" name="containerDetails[${containerIndex}][seal_no]" class="form-control" placeholder="Seal No">
                </td>
                <td>
                    <input type="text" name="containerDetails[${containerIndex}][haz]" class="form-control" placeholder="HAZ / REEFER/ OOG DETAILS / HAZ APPROVAL REF">
                </td>
                <td>
                    <input type="text" name="containerDetails[${containerIndex}][packs]" class="form-control" placeholder="Packs">
                </td>
                <td>
                    <input type="text" name="containerDetails[${containerIndex}][pack_type]" class="form-control" placeholder="Packs Type">
                </td>
                <td>
                    <input type="text" name="containerDetails[${containerIndex}][description]" class="form-control" placeholder="Commodity Description">
                </td>
                <td>
                    <input type="text" name="containerDetails[${containerIndex}][weight]" class="form-control" placeholder="Gross Weight">
                </td>
                <td>
                    <input type="text" name="containerDetails[${containerIndex}][net_weight]" class="form-control" placeholder="Net Weight">
                </td>
                <td>
                    <button type="button" class="btn btn-danger removeRow"><i class="fas fa-trash"></i></button>
                </td>
                </tr>`;
                $('#containerDetails tbody').append(newRow);
                $('.selectpicker').selectpicker('refresh'); // Refresh selectpicker to render the dropdown correctly
                containerIndex++; // Increment the index for future additions
            });

            // Logic to remove a row dynamically
            $(document).on('click', '.removeRow', function() {
                $(this).closest('tr').remove();
            });

            // Handler for changes in the container number
            $(document).on('change', '.container-number', function() {
                var containerNumber = $(this).val();
                var row = $(this).closest('tr');
                $.ajax({
                    url: '/booking/check-container',
                    method: 'GET',
                    data: { number: containerNumber },
                    success: function(response) {
                        if (response.exists) {
                            row.find('[name^="containerDetails"]').each(function() {
                                var name = $(this).attr('name');
                                if (name.includes('[container_type]')) $(this).val(response.type).selectpicker('refresh');
                                if (name.includes('[activity_location_id]')) $(this).val(response.ownership).selectpicker('refresh');
                                if (name.includes('[packs]')) $(this).val(response.packs);
                                if (name.includes('[pack_type]')) $(this).val(response.pack_type);
                                if (name.includes('[haz]')) $(this).val(response.haz);
                                if (name.includes('[weight]')) $(this).val(response.weight);
                                if (name.includes('[net_weight]')) $(this).val(response.net_weight);
                            });
                            row.find('.container-id').val(response.id);
                        } else {
                            $('#errorModalMessage').text('Container not found! Please enter the container type manually.');
                            $('#containerErrorModal').modal('show');
                            row.find('[name*="[container_type]"]').val('').selectpicker('refresh');
                            row.find('[name*="[activity_location_id]"]').val('').selectpicker('refresh');
                            row.find('[name*="[packs]"]').val('');
                            row.find('[name*="[pack_type]"]').val('');
                            row.find('[name*="[haz]"]').val('');
                            row.find('[name*="[weight]"]').val('');
                            row.find('[name*="[net_weight]"]').val('');
                            row.find('.container-id').val('');
                        }
                    },
                    error: function(xhr) {
                        console.error('An error occurred:', xhr);
                    }
                });
            });

            // Handler for form submission
            $('#bookingForm').on('submit', function(e) {
                e.preventDefault();
                var formData = $(this).serializeArray();

                // Collect container data separately
                var containerDetails = [];
                $('#containerDetails tbody tr').each(function() {
                    var containerNumber = $(this).find('.container-number').val();
                    var containerId = $(this).find('.container-id').val();
                    if (!containerId) {
                        containerDetails.push({
                            container_number: containerNumber,
                            // Add more fields if required
                        });
                    }
                });

                // Create containers if necessary
                if (containerDetails.length > 0) {
                    $.ajax({
                        url: '/booking/create-container',
                        method: 'POST',
                        data: JSON.stringify({containers: containerDetails}),
                        contentType: 'application/json',
                        success: function(response) {
                            if (response.success) {
                                response.containers.forEach(function(container) {
                                    $('#containerDetails tbody tr').each(function() {
                                        var row = $(this);
                                        if (row.find('.container-number').val() === container.container_number) {
                                            row.find('.container-id').val(container.id);
                                        }
                                    });
                                });
                                $('#bookingForm').off('submit').submit();
                            } else {
                                alert('Error creating containers!');
                            }
                        },
                        error: function(xhr) {
                            console.error('An error occurred:', xhr);
                        }
                    });
                } else {
                    $('#bookingForm').off('submit').submit();
                }
            });
        });
    </script>
@endpush
