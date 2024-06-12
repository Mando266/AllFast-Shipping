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
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">Create New Import Booking</a></li>
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
                            <div class="form-group col-md-3">
                                <label for="customer_id">Shipper Customer <span class="text-warning"> * </span></label>
                                <select class="selectpicker form-control" id="customer_id" data-live-search="true" name="customer_id" data-size="10"
                                 title="{{trans('forms.select')}}" required>
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
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>

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
                            @php
                                $fields = ['soc' => 'SOC', 'imo' => 'IMO', 'oog' => 'OOG', 'rf' => 'RF'];
                                $isDraft = request('quotation_id') == 'draft';
                            @endphp

                            <div class="form-group col-md-3" style="padding-top: 30px;">
                                <div class="form-check">
                                    @foreach ($fields as $field => $label)
                                        <input type="checkbox" id="{{ $field }}" name="{{ $field }}" value="1"
                                            {{ $quotation->$field == 1 ? 'checked' : '' }}
                                            {{ $isDraft ? '' : 'disabled' }}>
                                        <a style="font-size: 15px; color: #3b3f5c; letter-spacing: 1px; margin-right: 10px;"> {{ $label }} </a>
                                    @endforeach
                                </div>
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
                                <label for="vessel_name">Vessel Operator <span class="text-warning"> * </span></label>
                                <select class="selectpicker form-control" id="vessel_name" data-live-search="true" name="vessel_name" data-size="10"
                                title="{{trans('forms.select')}}" required>
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
                            <div class="form-group col-md-6">
                                <label for="ffw_id">Forwarder Customer</label>
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
                                <label for="customer_consignee_id">Consignee Customer</label>
                                <select class="selectpicker form-control" id="customer_consignee_id" data-live-search="true" name="customer_consignee_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($consignee as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('customer_consignee_id') ? 'selected':''}}>{{$item->name}} @foreach($item->CustomerRoles as $itemRole) - {{optional($itemRole->role)->name}}@endforeach</option>
                                    @endforeach
                                </select>
                                @error('customer_consignee_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>

                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="place_of_acceptence_id">Place Of Acceptence <span class="text-warning"> * </span></label>
                                 <select class="selectpicker form-control" id="place_of_acceptence_id" data-live-search="true" name="place_of_acceptence_id" data-size="10"
                                 title="{{trans('forms.select')}}" required>
                                    @foreach ($ports as $item)
                                        @if($quotation->place_of_acceptence_id != null)
                                        <option value="{{$item->id}}" {{$item->id == old('place_of_acceptence_id',$quotation->place_of_acceptence_id) ? 'selected':'disabled'}}>{{$item->name}}</option>
                                        @else
                                        <option value="{{$item->id}}" {{$item->id == old('place_of_acceptence_id',$quotation->place_of_acceptence_id) ? 'selected':''}}>{{$item->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('place_of_acceptence_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
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
                                <label for="shipper_ref_no">Shipper Ref No</label>
                                <input type="text" class="form-control" id="shipper_ref_no" name="shipper_ref_no" value="{{old('shipper_ref_no')}}"
                                    placeholder="Shipper Ref No" autocomplete="off">
                                @error('shipper_ref_no')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
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
                            <div class="form-group col-md-4">
                                <label for="pick_up_location">Pick Up Location</label>
                                <select class="selectpicker form-control" id="pick_up_location" data-live-search="true" name="pick_up_location" data-size="10"
                                 title="{{trans('forms.select')}}">
                                 <option value="">Select...</option>
                                    @foreach ($ports as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('pick_up_location',$quotation->pick_up_location) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('pick_up_location')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="place_return_id">Place Of Return</label>
                                <select class="selectpicker form-control" id="place_return_id" data-live-search="true" name="place_return_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                 <option value="">Select...</option>
                                    @foreach ($ports as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('place_return_id',$quotation->place_return_id) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('place_return_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="bl_release">BL Release <span class="text-warning"> * </span></label>
                                 <select class="selectpicker form-control" id="bl_release" data-live-search="true" name="bl_release" data-size="10"
                                 title="{{trans('forms.select')}}" required>
                                    @foreach ($agents as $item)
                                        @if($quotation->discharge_bl_release != null)
                                        <option value="{{$item->id}}" {{$item->id == old('bl_release',$quotation->discharge_bl_release) ? 'selected':'disabled'}}>{{$item->name}}</option>
                                        @else
                                        <option value="{{$item->id}}" {{$item->id == old('bl_release',$quotation->discharge_bl_release) ? 'selected':''}}>{{$item->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('bl_release')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                                @php
                                    $isDraft = request()->input('quotation_id') == "draft";
                                @endphp

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

                                <div class="form-group col-md-4">
                                    <label for="Transhipment">Transhipment Port</label>
                                    <select class="selectpicker form-control" id="transhipment_port" data-live-search="true" name="transhipment_port" data-size="10" title="{{trans('forms.select')}}">
                                        <option value="">Select...</option>
                                        @foreach ($activityLocations as $item)
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

                            </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="load_terminal_id">Load Port Terminal <span class="text-warning"> * </span></label>
                                <select class="selectpicker form-control" id="terminal" data-live-search="true" name="load_terminal_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($terminals as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('load_terminal_id') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('load_terminal_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="discharge_etd">Discharge ETA</label>
                                <input type="date" class="form-control" id="discharge_etd" name="discharge_etd" value="{{old('discharge_etd')}}"
                                    placeholder="Discharge ETA" autocomplete="off">
                                @error('discharge_etd')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="load_port_cutoff">Load Port Cutoff</label>
                                <input type="date" class="form-control" id="load_port_cutoff" name="load_port_cutoff" value="{{old('load_port_cutoff')}}"
                                    placeholder="Load Port Cutoff" autocomplete="off">
                                @error('load_port_cutoff')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="load_port_dayes">Load Port Days</label>
                                <input type="text" class="form-control" id="load_port_dayes" name="load_port_dayes" value="{{old('load_port_dayes')}}"
                                    placeholder="Load Port Days" autocomplete="off">
                                @error('load_port_dayes')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
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
                            <div class="form-group col-md-4">
                                <label>Exporter Number <span class="text-warning"> * </span></label>
                                <input type="text" class="form-control"  style="background-color:#fff" name="exportal_id" placeholder="Exporter Number"   required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="acid">ACID</label>
                                <input type="text" class="form-control" id="acid" name="acid" value="{{old('acid')}}"
                                    placeholder="ACID" autocomplete="off">
                                @error('acid')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
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
                            <div class="form-group col-md-3">
                                <label for="commodity_description"> Commodity Description <span class="text-warning"> * </span></label>
                                <input type="text" class="form-control" id="commodity_description" name="commodity_description" value="{{old('commodity_description')}}"
                                    placeholder="Commodity Description" autocomplete="off" required>
                                @error('commodity_description')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                                <input type="hidden" class="form-control"  name ="shipment_type"  value="Import">
                            @if($quotation->id != 0)
                                <input type="hidden" class="form-control" name="booking_type" value="{{$quotation->quotation_type}}" readonly>
                            @else
                            <div class="form-group col-md-2">
                                <label>Booking Status <span class="text-warning"> * </span></label>
                                <select class="selectpicker form-control" data-live-search="true" name="booking_type" title="{{trans('forms.select')}}" required>
                                   <option value="Empty">Empty</option>
                                   <option value="Full">Full</option>
                                </select>
                            </div>
                            @endif
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
                                <th class="text-center">Seal No</th>
                                <th class="text-center">Container Type</th>
                                <th class="text-center">QTY</th>
                                <th class="text-center">Return Location</th>
                                <th class="text-center">Container No</th>
                                <th class="text-center">HAZ / Reefer/ OOG Details / Haz Approval Ref</th>
                                <th class="text-center">Weight</th>
                                <th class="text-center">VGM</th>
                                <th class="text-center">Add Container</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><input type="text" name="containerDetails[0][seal_no]" class="form-control" placeholder="Seal No"></td>
                                <td class="container_type">
                                    <select class="selectpicker form-control" name="containerDetails[0][container_type]" data-live-search="true" title="Select">
                                        @foreach ($equipmentTypes as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="text" name="containerDetails[0][qty]" class="form-control" placeholder="QTY" required></td>
                                <td class="ports">
                                    <select class="selectpicker form-control" name="containerDetails[0][activity_location_id]" data-live-search="true" title="Select">
                                        @foreach ($activityLocations as $location)
                                            <option value="{{ $location->id }}">{{ $location->code }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="containerDetails[0][container_number]" class="form-control container-number" placeholder="Container No">
                                    <input type="hidden" name="containerDetails[0][container_id]" class="container-id">
                                </td>
                                <td><input type="text" name="containerDetails[0][haz]" class="form-control" placeholder="HAZ / REEFER/ OOG DETAILS / HAZ APPROVAL REF"></td>
                                <td><input type="text" name="containerDetails[0][weight]" class="form-control" placeholder="Weight"></td>
                                <td><input type="text" name="containerDetails[0][vgm]" class="form-control" placeholder="VGM"></td>
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

<script>

        $(document).ready(function(){
        $("#containerDetails").on("click", ".remove", function () {
            $(this).closest("tr").remove();
    });
    var counter  = 1;
    $("#add").click(function(){

            var tr = '<tr>'+
                '<td><input type="text" name="containerDetails['+counter+'][seal_no]" class="form-control" autocomplete="off" placeholder="Seal No"></td>'+
                '<td class="container_type"><select class="selectpicker form-control" id="container_type" data-live-search="true" name="containerDetails[0][container_type]" data-size="10" title="{{ trans('forms.select') }}">@foreach ($equipmentTypes as $item)<option value="{{ $item->id }}" {{ $item->id == old('container_type') ? 'selected' : '' }}>{{ $item->name }}</option>@endforeach</select></td>'+
                '<td><input type="text" name="containerDetails['+counter+'][qty]" class="form-control input" id="number"  onchange="return check_value();" autocomplete="off" placeholder="QTY" required></td>'+
                '<td class="ports"><select class="selectpicker form-control" id="activity_location_id" data-live-search="true" name="containerDetails['+counter+'][activity_location_id]" data-size="10" title="{{trans('forms.select')}}">@foreach ($activityLocations as $activityLocation)<option value="{{$activityLocation->id}}" {{$activityLocation->id == old('activity_location_id') ? 'selected':''}}>{{$activityLocation->code}}</option> @endforeach </select></td>'+
                '<td class="containerDetailsID"><select id="selectpicker" class="selectpicker form-control" data-live-search="true" name="containerDetails['+counter+'][container_id]" data-size="10"><option value="000">Select</option>@foreach ($containers as $item)<option value="{{$item->id}}" {{$item->id == old('container_id') ? 'selected':''}}>{{$item->code}}</option> @endforeach </select></td>'+
                '<td><input type="text" value="{{$quotation->oog_dimensions}}" name="containerDetails['+counter+'][haz]" class="form-control" placeholder="HAZ / REEFER/ OOG DETAILS / HAZ APPROVAL REF" autocomplete="off"></td>'+
                '<td><input type="text" name="containerDetails['+counter+'][weight]" class="form-control" autocomplete="off" placeholder="Weight"></td>'+
                '<td><input type="text" name="containerDetails['+counter+'][vgm]" class="form-control" autocomplete="off" placeholder="VGM"></td>'+
                '<td style="width:85px;"><button type="button" class="btn btn-danger remove"><i class="fa fa-trash"></i></button></td>'
            '</tr>';
           $('#containerDetails').append(tr);
           $('.selectpicker').selectpicker("render");
           $('#selectpicker').selectpicker();
            counter++;
    });
});
</script>
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
            let equipment_id =  $('#container_type').val();
                $('#containerDetails').on('change','td.ports select' , function(e){
                  let self = $(this);
                  let parent = self.closest('tr');
                    let value = e.target.value;
                    let container = $('td.containerDetailsID select' , parent);
                    let response =    $.get(`/api/booking/activityContainers/${value}/${company_id}/${equipment_id}`).then(function(data){
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
<script>
    $(document).ready(function() {
        $('#voyage_id_second').change(function() {
            var selectedVoyageId = $(this).val();
            if (selectedVoyageId) {
                $('#transhipment_port').prop('required', true);
                alert('Please Fill Transhipment Port Item')
            } else {
                $('#transhipment_port').prop('required', false);
            }
        });
    });
</script>


<script>
    $(document).ready(function() {
        var containerIndex = 1; // Start from 1 since 0 is already used for the first row

        function addContainerRow() {
            var newRow = `<tr>
            <td><input type="text" name="containerDetails[${containerIndex}][seal_no]" class="form-control" placeholder="Seal No"></td>
            <td class="container_type">
                <select class="selectpicker form-control" name="containerDetails[${containerIndex}][container_type]" data-live-search="true" data-size="10" title="Select">
                    @foreach ($equipmentTypes as $item)
            <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
            </select>
        </td>
        <td><input type="text" name="containerDetails[${containerIndex}][qty]" class="form-control" placeholder="QTY" required></td>
            <td class="ports">
                <select class="selectpicker form-control" name="containerDetails[${containerIndex}][activity_location_id]" data-live-search="true" data-size="10" title="Select">
                    @foreach ($activityLocations as $location)
            <option value="{{ $location->id }}">{{ $location->code }}</option>
                    @endforeach
            </select>
        </td>
        <td>
            <input type="text" name="containerDetails[${containerIndex}][container_number]" class="form-control container-number" placeholder="Container No">
                <input type="hidden" name="containerDetails[${containerIndex}][container_id]" class="container-id">
            </td>
            <td><input type="text" name="containerDetails[${containerIndex}][haz]" class="form-control" placeholder="HAZ / REEFER/ OOG DETAILS / HAZ APPROVAL REF"></td>
            <td><input type="text" name="containerDetails[${containerIndex}][weight]" class="form-control" placeholder="Weight"></td>
            <td><input type="text" name="containerDetails[${containerIndex}][vgm]" class="form-control" placeholder="VGM"></td>
            <td><button type="button" class="btn btn-danger removeRow"><i class="fa fa-trash"></i></button></td>
        </tr>`;
            $('#containerDetails tbody').append(newRow);
            $('.selectpicker').selectpicker('refresh');
            containerIndex++;
        }

        $('#addContainerRow').click(function() {
            addContainerRow();
        });

        $(document).on('click', '.removeRow', function() {
            $(this).closest('tr').remove();
        });

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
                            if (name.includes('[haz]')) $(this).val(response.haz);
                            if (name.includes('[weight]')) $(this).val(response.weight);
                            if (name.includes('[vgm]')) $(this).val(response.vgm);
                        });
                        row.find('.container-id').val(response.id);
                    } else {
                        $('#errorModalMessage').text('Container not found! Please enter the container type manually.');
                        $('#containerErrorModal').modal('show');
                        row.find('[name*="[container_type]"]').val('').selectpicker('refresh');
                        row.find('[name*="[activity_location_id]"]').val('').selectpicker('refresh');
                        row.find('[name*="[haz]"]').val('');
                        row.find('[name*="[weight]"]').val('');
                        row.find('[name*="[vgm]"]').val('');
                        row.find('.container-id').val('');
                    }
                },
                error: function(xhr) {
                    console.error('An error occurred:', xhr);
                }
            });
        });

        $('#bookingForm').on('submit', function(e) {
            e.preventDefault();
            var formData = $(this).serializeArray();

            // Collect container data separately
            var containerDetails = [];
            $('#containerDetails tbody tr').each(function() {
                var containerNumber = $(this).find('.container-number').val();
                var containerId = $(this).find('.container-id').val();
                if (!containerId) { // If container_id is not set, create the container
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
                    data: JSON.stringify({ containers: containerDetails }),
                    contentType: 'application/json',
                    success: function(response) {
                        if (response.success) {
                            // Update form with new container IDs
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

