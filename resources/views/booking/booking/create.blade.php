@extends('layouts.app')
@section('content')

<style>
  #addVoyageModal .modal-dialog {
      max-width: 90%;
  }
</style>
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
                                    <div class="form-group col-md-3">
                                        <label>Booking Type <span class="text-warning"> * </span></label>
                                        <select class="selectpicker form-control" data-live-search="true" name="booking_type" title="{{trans('forms.select')}}" required>
                                        <option value="Empty">Empty</option>
                                        <option value="Full">Full</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="bl_release">BL Type <span class="text-warning"> * </span></label>
                                        <select class="selectpicker form-control" data-live-search="true" name="bl_kind" title="{{trans('forms.select')}}" required>
                                            <option value="Original" @isset($booking){{"Original" == $booking->bl_kind?? "selected"}} @endisset>Original</option>
                                            @permission('BlDraft-Seaway')
                                                <option value="Seaway BL" @isset($booking){{"Seaway BL" == $booking->bl_kind?? "selected"}} @endisset>Seaway BL</option>
                                            @endpermission
                                        </select>
                                    </div>
                                    @endif
                            <div class="form-group col-md-3">
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

                            <div class="form-group col-md-4">
                                <label for="terminal_id">Discharge Terminal <span class="text-warning"> * </span></label>
                                <select class="selectpicker form-control" id="terminal" data-live-search="true" name="terminal_id" data-size="10" title="{{trans('forms.select')}}" required>
                                              @foreach ($terminals as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('terminal_id') ? 'selected' : ''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('terminal_id')
                                    <div style="color: red;">{{$message}}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                                @php
                                    $isDraft = request()->input('quotation_id') == "0";
                                @endphp
                                <div class="form-group col-md-4">
                                    <label for="voyage_id">Load Vessel / Voyage <span class="text-warning"> * </span></label>
                                    <select class="selectpicker form-control" id="voyage_id" data-live-search="true" name="voyage_id" data-size="10" title="{{trans('forms.select')}}" required>
                                                  @foreach ($voyages as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('voyage_id') ? 'selected' : ''}}>{{$item->vessel->name}} / {{$item->voyage_no}} - {{ optional($item->leg)->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('voyage_id')
                                        <div style="color: red;">{{$message}}</div>
                                    @enderror
                                </div>
                                @if(request()->input('is_transhipment'))
                                    <div class="form-group col-md-4">
                                        <label for="transhipment_port">Transhipment Port</label>
                                        <select class="selectpicker form-control" id="transhipment_port" data-live-search="true" name="transhipment_port" data-size="10" title="{{trans('forms.select')}}">
                                            @foreach ($ports as $item)
                                                <option value="{{$item->id}}" {{$item->id == old('transhipment_port') ? 'selected' : ''}}>{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('transhipment_port')
                                        <div style="color: red;">{{$message}}</div>
                                        @enderror
                                    </div>
                                
                                    <div class="form-group col-md-4">
                                        <label for="final_destination">Final Destination</label>
                                        <select class="selectpicker form-control" id="final_destination" data-live-search="true" name="final_destination" data-size="10" title="Select...">
                                            <option value="add_new">Add New</option>
                                            @foreach ($voyages as $item)
                                                <option value="{{ $item->id }}" {{ $item->id == old('final_destination') ? 'selected' : '' }}>
                                                    {{ $item->vessel->name }} / {{ $item->voyage_no }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('final_destination')
                                        <div style="color: red;">{{ $message }}</div>
                                        @enderror
                                    </div>                                    
                                @endif
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
                            @if(!$isDraft)
                                <div class="form-group col-md-3">
                                    <label for="status">Bl Payment<span class="text-warning"> * </span></label>
                                    <select class="selectpicker form-control" data-live-search="true" name="payment_kind" title="{{trans('forms.select')}}" required>
                                        <option value="Prepaid">Prepaid</option>
                                        <option value="Collect">Collect</option>
                                    </select>
                                </div>
                                @else
                                <input type="hidden" name="payment_kind" class="form-control" autocomplete="off" value="{{optional($quotation)->payment_kind}}">
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
                        <div style="overflow-x: auto;">

                        <table id="containerDetails" class="table table-bordered">
                            <thead>
                            <tr>
                                @if(request()->input('quotation_id') == "0")
                                <th class="text-center">Request Type</th>
                                @endif
                                <th class="text-center">Container No</th>
                                <th class="text-center">Container Type</th>
                                <th class="text-center">QTY</th>
                                <th class="text-center">Return Location</th>
                                <th class="text-center">Seal No</th>
                                <th class="text-center">HAZ</th>
                                <th class="text-center">Packs</th>
                                <th class="text-center">Packs Type</th>
                                <th class="text-center">Commodity Des</th>
                                <th class="text-center">Gross.W Kgs</th>
                                <th class="text-center">Net.W Kgs</th>
                                @if(request()->input('quotation_id') == "0")
                                    <th class="text-center">Special Equipment</th>
                                @endif
                                <th class="text-center">Add Container</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr id="initialRow">
                                @if(request()->input('quotation_id') == "0")
                                <td id="request">
                                    <select class="selectpicker form-control" id="requesttype" data-live-search="true" name="quotationDis[0][request_type]" data-size="10" title="{{trans('forms.select')}}" required>
                                        <option value="Dry">Dry</option>
                                        <option value="Reefer">Reefer</option>
                                        <option value="Special Equipment">Special Equipment</option>
                                    </select>
                                </td>
                                @endif
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

                                @if(request()->input('quotation_id') == "0")
                                <td>
                                    <div class="checkbox-group d-flex flex-row">
                                        <div style="display: inline-block; width: 50%;" class="mr-3">
                                            <div style="margin-bottom: 5px;">
                                                <label style="margin-right: 10px; width: 25px; display: inline-block;">SOC</label>
                                                <input type="checkbox" id="soc" name="quotationDis[0][soc]" value="1">
                                            </div>
                                            <div style="margin-bottom: 5px;">
                                                <label style="margin-right: 10px; width: 25px; display: inline-block;">IMO</label>
                                                <input type="checkbox" value="1" name="quotationDis[0][imo]">
                                            </div>
                                            <div>
                                                <label style="margin-right: 10px; width: 25px; display: inline-block;">OOG</label>
                                                <input type="checkbox" value="1" name="quotationDis[0][oog]">
                                            </div>
                                        </div>
                                        <div style="display: inline-block; width: 50%;">
                                            <div style="margin-bottom: 5px;">
                                                <label style="margin-right: 10px; width: 25px; display: inline-block;">RF</label>
                                                <input type="checkbox" value="1" name="quotationDis[0][rf]">
                                            </div>
                                            <div>
                                                <label style="margin-right: 10px; width: 25px; display: inline-block;">NOR</label>
                                                <input type="checkbox" value="1" name="quotationDis[0][nor]">
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            @endif
                               <td><button type="button" id="addContainerRow" class="btn btn-primary"><i class="fas fa-plus"></i></button></td>
                            </tr>
                            </tbody>
                        </table>
                        </div>
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

<!-- Modal for adding new Vessel and Voyage -->
<div class="modal fade" id="addVoyageModal" tabindex="-1" role="dialog" aria-labelledby="voyageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="voyageModalLabel">Add New Vessel & Voyage</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Vessel Details -->
                <h5>Vessel Information</h5>
                <div class="d-flex flex-row col-12">
                    <div class="form-group col-3">
                        <label for="vessel_name">Vessel Name</label>
                        <input type="text" class="form-control" id="vessel_name" name="vessel_name" placeholder="e.g., Sea Queen">
                    </div>
                    <div class="form-group col-3">
                        <label for="vessel_code">Vessel Code</label>
                        <input type="text" class="form-control" id="vessel_code" name="vessel_code" placeholder="e.g., SQ123">
                    </div>
                    <div class="form-group col-3">
                        <label for="vessel_type">Vessel Type</label>
                        <select class="form-control selectpicker" id="vessel_type" name="vessel_type" data-live-search="true" data-size="5" title="Select Type">
                            @foreach ($vessel_types as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-3">
                        <label for="vessel_operator">Vessel Operator</label>
                        <select class="form-control selectpicker" id="vessel_operator" name="vessel_operator" data-live-search="true" data-size="5" title="Select Operator">
                            @foreach ($vessel_operators as $operator)
                                <option value="{{ $operator->id }}">{{ $operator->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Voyage Details -->
                <h5>Voyage Information</h5>
                <div class="d-flex flex-row col-12">
                    <div class="form-group col-6">
                        <label for="voyage_name">Voyage Name</label>
                        <input type="text" class="form-control" id="voyage_name" name="voyage_name" placeholder="e.g., Pacific Route">
                    </div>
                    <div class="form-group col-6">
                        <label for="voyage_no">Voyage Number</label>
                        <input type="text" class="form-control" id="voyage_no" name="voyage_no" placeholder="e.g., VR456">
                    </div>
                </div>

                <!-- Ports Table for Dynamic Port Entry -->
                <h5>Ports</h5>
                <table id="portsTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Port</th>
                            <th>Terminal</th>
                            <th>Road No</th>
                            <th>ETA</th>
                            <th>ETD</th>
                            <th class="text-center">
                                <button type="button" class="btn btn-info" id="addPort"><i class="fa fa-plus"></i></button>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- First row for adding ports -->
                        <tr>
                            <td>
                                <select class="form-control" name="port[0][port_id]" required>
                                    @foreach ($ports as $port)
                                        <option value="{{ $port->id }}">{{ $port->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="port[0][terminal]" placeholder="Terminal" required>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="port[0][road_no]" placeholder="Road No" required>
                            </td>
                            <td>
                                <input type="datetime-local" class="form-control" name="port[0][eta]" required>
                            </td>
                            <td>
                                <input type="datetime-local" class="form-control" name="port[0][etd]" required>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger remove-port"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="saveVoyageData">Save</button>
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

<script>
$(document).ready(function () {


    $('#final_destination').change(function() {
        if ($(this).val() == 'add_new') {
            $('#addVoyageModal').modal('show');
        }
    });

    let portCounter = 0;
    let savedVoyageData = null;

    // Add a new row to the Ports table
    $('#addPort').click(function() {
        let rowCount = $('#portsTable tbody tr').length;
        let newRow = `
            <tr>
                <td>
                    <select class="form-control" name="port[${rowCount}][port_id]" required>
                        @foreach ($ports as $port)
                            <option value="{{ $port->id }}">{{ $port->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="text" class="form-control terminal" name="port[${rowCount}][terminal]" required>
                </td>
                <td>
                    <input type="text" class="form-control road_no" name="port[${rowCount}][road_no]" required>
                </td>
                <td>
                    <input type="datetime-local" class="form-control eta" name="port[${rowCount}][eta]" required>
                </td>
                <td>
                    <input type="datetime-local" class="form-control etd" name="port[${rowCount}][etd]" required>
                </td>
                <td>
                    <button type="button" class="btn btn-danger remove-port">Remove</button>
                </td>
            </tr>
        `;
        $('#portsTable tbody').append(newRow);
        portCounter++;
    });

    // Remove a port row
    $('#portsTable').on('click', '.remove-port', function() {
        $(this).closest('tr').remove();
    });

    // Save voyage data when "Save" is clicked
    $('#saveVoyageData').click(function() {
        let vesselData = {
            vessel_name: $('#vessel_name').val(),
            vessel_code: $('#vessel_code').val(),
            vessel_type: $('#vessel_type').val(),
            vessel_operator: $('#vessel_operator').val(),
            voyage_name: $('#voyage_name').val(),
            voyage_no: $('#voyage_no').val(),
            ports: []
        };

        // Loop through each row in the Ports table
        $('#portsTable tbody tr').each(function() {
            let port_id = $(this).find('select[name^="port"]').val();
            let terminal = $(this).find('input.terminal').val(); // Fixed selector for terminal
            let road_no = $(this).find('input.road_no').val(); // Fixed selector for road_no
            let eta = $(this).find('input.eta').val(); // Fixed selector for ETA
            let etd = $(this).find('input.etd').val(); // Fixed selector for ETD

            if (port_id && terminal && road_no && eta && etd) {
                let portData = {
                    port_id: port_id,
                    terminal: terminal,
                    road_no: road_no,
                    eta: eta,
                    etd: etd
                };
                vesselData.ports.push(portData);
            } else {
                alert('Please ensure all fields for each port are filled.');
                return false; // Stop execution if validation fails
            }
        });

        if (vesselData.ports.length > 0) {
            savedVoyageData = vesselData; // Store the data temporarily
            console.log('Saved Voyage Data:', savedVoyageData);
            $('#addVoyageModal').modal('hide'); // Close the modal
        } else {
            alert('Please ensure at least one port is added with all required details.');
        }
    });

    // Reopen the modal with saved data
    $('#addVoyageModal').on('show.bs.modal', function () {
        if (savedVoyageData) {
            $('#vessel_name').val(savedVoyageData.vessel_name);
            $('#vessel_code').val(savedVoyageData.vessel_code);
            $('#vessel_type').selectpicker('val', savedVoyageData.vessel_type);
            $('#vessel_operator').selectpicker('val', savedVoyageData.vessel_operator);
            $('#voyage_name').val(savedVoyageData.voyage_name);
            $('#voyage_no').val(savedVoyageData.voyage_no);

            // Clear the ports table and repopulate it with saved ports
            $('#portsTable tbody').empty();
            savedVoyageData.ports.forEach((port, index) => {
                let newRow = `
                    <tr>
                        <td>
                            <select class="form-control" name="port[${index}][port_id]" required>
                                @foreach ($ports as $portOption)
                                    <option value="{{ $portOption->id }}" ${(port.port_id == {{ $portOption->id }}) ? 'selected' : ''}>{{ $portOption->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="text" class="form-control" name="port[${index}][terminal]" value="${port.terminal}" required>
                        </td>
                        <td>
                            <input type="text" class="form-control" name="port[${index}][road_no]" value="${port.road_no}" required>
                        </td>
                        <td>
                            <input type="datetime-local" class="form-control" name="port[${index}][eta]" value="${port.eta}" required>
                        </td>
                        <td>
                            <input type="datetime-local" class="form-control" name="port[${index}][etd]" value="${port.etd}" required>
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger remove-port">Remove</button>
                        </td>
                    </tr>
                `;
                $('#portsTable tbody').append(newRow);
            });
        } else {
            // Reset modal fields if no saved data
            $('#vessel_name').val('');
            $('#vessel_code').val('');
            $('#vessel_type').val('').selectpicker('refresh');
            $('#vessel_operator').val('').selectpicker('refresh');
            $('#voyage_name').val('');
            $('#voyage_no').val('');
            $('#portsTable tbody').empty();
        }
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
@endpush

