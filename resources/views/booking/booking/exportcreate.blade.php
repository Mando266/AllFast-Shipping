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
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">Add Booking</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                    <form novalidate id="createForm" action="{{route('booking.store')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                        <div class="form-row">
                            <input type="hidden" value="{{$quotation->id}}" name="quotation_id">
                            <input type="hidden" value="{{$quotation->export_detention}}" name="free_time">

                            <div class="form-group col-md-4">
                                <label for="customer_id">Shipper Name <span class="text-warning"> * </span></label>
                                <select class="selectpicker form-control" id="customer_id" data-live-search="true" name="customer_id" data-size="10"
                                 title="{{trans('forms.select')}}" required>
                                    @foreach ($customers as $item)
                                        @if($quotation->customer_id != null)
                                            @if(in_array(1, optional($quotation->customer)->CustomerRoles->pluck('role_id')->toarray()))
                                            <option value="{{$item->id}}" {{$item->id == old('customer_id',$quotation->customer_id) ? 'selected':'disabled'}}>{{$item->name}}</option>
                                            @else
                                            <option value="{{$item->id}}" {{$item->id == old('customer_id',$quotation->customer_id) ? 'selected':''}}>{{$item->name}}</option>
                                            @endif
                                        @else
                                        <option value="{{$item->id}}" {{$item->id == old('customer_id',$quotation->customer_id) ? 'selected':''}}>{{$item->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('customer_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <input type="hidden" class="form-control"  name ="shipment_type" value="{{$quotation->shipment_type}}" readonly>
                            <div class="form-group col-md-2">
                                <label>Booking Status </label>
                                <input type="text" class="form-control" name="booking_type" value="{{$quotation->quotation_type}}" readonly>
                            </div>   
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

                            <div class="form-group col-md-4" style="padding-top: 30px;">
                                <div class="form-check">
                                <input type="checkbox" id="soc" name="soc" value="1"  onclick="return false;" readonly {{$quotation->soc == 1 ? 'checked' : ''}}><a style="font-size: 15px; color: #3b3f5c; letter-spacing: 1px; margin-right: 10px;"> SOC </a>

                                <input type="checkbox" id="imo" name="imo" value="1"  onclick="return false;" readonly {{$quotation->imo == 1 ? 'checked' : ''}}><a style="font-size: 15px; color: #3b3f5c; letter-spacing: 1px; margin-right: 10px;"> IMO </a>

                                <input type="checkbox" id="oog" name="oog" value="1"  onclick="return false;" readonly {{$quotation->oog == 1 ? 'checked' : ''}}><a style="font-size: 15px; color: #3b3f5c; letter-spacing: 1px; margin-right: 10px;"> OOG </a>

                                <input type="checkbox" id="rf" name="rf" value="1"  onclick="return false;" readonly {{$quotation->rf == 1 ? 'checked' : ''}}><a style="font-size: 15px; color: #3b3f5c; letter-spacing: 1px; margin-right: 10px;"> RF </a>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="Principal">Principal Name <span class="text-warning"> *</span></label>
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
                                <label for="vessel_name">Vessel Operator <span class="text-warning"> *</span></label>
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
                                <label for="ffw_id">Forwarder Name</label>
                                <select class="selectpicker form-control" id="ffw_id" data-live-search="true" name="ffw_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($ffw as $item)
                                        @if($quotation->customer_id != null)
                                            @if(in_array(6, optional($quotation->customer)->CustomerRoles->pluck('role_id')->toarray()))
                                            <option value="{{$item->id}}" {{$item->id == old('ffw_id',$quotation->customer_id) ? 'selected':'disabled'}}>{{$item->name}}</option>
                                            @else
                                            <option value="{{$item->id}}" {{$item->id == old('ffw_id',$quotation->customer_id) ? 'selected':''}}>{{$item->name}}</option>
                                            @endif
                                        @else
                                        <option value="{{$item->id}}" {{$item->id == old('ffw_id') ? 'selected':''}}>{{$item->name}}</option>
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
                                        <option value="{{$item->id}}" {{$item->id == old('customer_consignee_id') ? 'selected':''}}>{{$item->name}}</option>
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
                        <div class="form-group col-md-3">
                                <label for="voyage_id">Vessel / Voyage <span class="text-warning"> *</span></label>
                                <select class="selectpicker form-control" id="voyage_id" data-live-search="true" name="voyage_id" data-size="10"
                                 title="{{trans('forms.select')}}" required>
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

                            <div class="form-group col-md-3">
                                <label for="Transhipment">Transhipment Port</label>
                                <select class="selectpicker form-control" id="transhipment_port" data-live-search="true" name="transhipment_port" data-size="10"
                                 title="{{trans('forms.select')}}">
                                                                      @foreach ($ports as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('transhipment_port') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('transhipment_port')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
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
                            <!-- <div class="form-group col-md-4">
                                <label for="pick_up_location">Pick Up Location</label>
                                <select class="selectpicker form-control" id="pick_up_location" data-live-search="true" name="pick_up_location" data-size="10"
                                 title="{{trans('forms.select')}}">
                                                                      @foreach ($ports as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('pick_up_location',$quotation->pick_up_location) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('pick_up_location')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div> -->
                            <!-- <div class="form-group col-md-4">
                                <label for="place_return_id">Place Of Return</label>
                                <select class="selectpicker form-control" id="place_return_id" data-live-search="true" name="place_return_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                                                      @foreach ($ports as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('place_return_id',$quotation->place_return_id) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('place_return_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div> -->
                            <div class="form-group col-md-3">
                                <label for="bl_release">BL Release <span class="text-warning"> *</span> </label>
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

                        <!-- <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="terminal_id">Discharge Terminal <span class="text-warning"> * </span></label>
                                <select class="form-control" id="terminal" data-live-search="true" name="terminal_id" data-size="10"
                                 title="{{trans('forms.select')}}" required>
                                    @foreach ($terminals as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('terminal_id') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('terminal_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                     -->

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
                                <label>Importer ID <span class="text-warning"></label>
                                <input type="text" class="form-control"  style="background-color:#fff" name="importer_id" placeholder="Exporter Number"  autocomplete="off">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Exporter ID <span class="text-warning"></label>
                                <input type="text" class="form-control"  style="background-color:#fff" name="exportal_id" placeholder="Exporter Number"  autocomplete="off">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
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
                            <div class="form-group col-md-4">
                                <label for="commodity_code">Commodity Code</label>
                                <input type="text" class="form-control" id="commodity_code" name="commodity_code" value="{{old('commodity_code')}}"
                                    placeholder="Commodity Code" autocomplete="off">
                                @error('commodity_code')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
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
                            <div class="form-group col-md-8">
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
                                        <th class="text-center">Container Type</th>
                                        <th class="text-center">Container No</th>
                                        <th class="text-center">QTY</th>
                                        <th class="text-center">Pick Up Location</th>
                                        <th class="text-center">Seal No</th>
                                        <th class="text-center">Packs</th>
                                        <th class="text-center">Packs Type</th>
                                        <th class="text-center">HAZ / Reefer/ OOG Details / Haz Approval Ref</th>
                                        <th class="text-center">Net weight</th>
                                        <th class="text-center">Gross weight</th>
                                        <!-- <th class="text-center">vgm</th> -->
                                        <th class="text-center">
                                            <a id="add"> Add Container <i class="fas fa-plus"></i></a>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                            <tr>
                                <td class="container_type">
                                    <select class="selectpicker form-control" id="container_type" data-live-search="true" name="containerDetails[0][container_type]" data-size="10"
                                            title="{{trans('forms.select')}}">
                                            @foreach ($equipmentTypes as $item)
                                            @if($quotation->equipment_type_id != null)
                                                <option value="{{$item->id}}" {{$item->id == old('container_type',$quotation->equipment_type_id) ? 'selected':''}}>{{$item->name}}</option>
                                                @else
                                                <option value="{{$item->id}}" {{$item->id == old('equipment_type_id',$quotation->equipment_type_id) ? 'selected':''}}>{{$item->name}}</option>
                                            @endif
                                            @endforeach
                                    </select>
                                    @error('container_type')
                                    <div style="color: red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </td>
                                <td class="containerDetailsID">
                                  <select class="selectpicker form-control" id="containerDetailsID" data-live-search="true" name="containerDetails[0][container_id]" data-size="10"
                                          title="{{trans('forms.select')}}">
                                          <option value="" selected>Select</option>
                                            @foreach ($containers as $item)
                                                <option value="{{$item->id}}" {{$item->id == old('container_id') ? 'selected':''}}>{{$item->code}}</option>
                                            @endforeach
                                  </select>
                                </td>
                                <td>
                                    <input type="text" id="qyt" onchange="return check();" name="containerDetails[0][qty]" class="form-control input"  autocomplete="off" placeholder="QTY" required>
                                    @error('qty')
                                    <div style="color: red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </td>

                                <td class="ports">
                                    <select class="selectpicker form-control" id="activity_location_id" data-live-search="true" name="containerDetails[0][activity_location_id]" data-size="10"
                                    title="{{trans('forms.select')}}">
                                        @foreach ($activityLocations as $activityLocation)
                                            <option value="{{$activityLocation->id}}" {{$activityLocation->id == old('activity_location_id') ? 'selected':''}}>{{$activityLocation->code}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="text" id="seal_no" name="containerDetails[0][seal_no]" class="form-control" autocomplete="off" placeholder="Seal No">
                                </td>
                                <td>
                                    <input type="text" id="Packs" name="containerDetails[0][packs]" class="form-control"  autocomplete="off" placeholder="Packs">
                                </td>

                                <td>
                                    <input type="text" id="Packs" name="containerDetails[0][pack_type]" class="form-control"  autocomplete="off" placeholder="Packs Type">
                                </td>
                                <td>
                                @if($quotation->oog_dimensions != null)
                                <input type="text" class="form-control" id="haz" name="containerDetails[0][haz]" value="{{old('haz',$quotation->oog_dimensions)}}"
                                    placeholder="" autocomplete="off">
                                @else
                                <input type="text" class="form-control" id="haz" name="containerDetails[0][haz]" value="{{old('haz')}}"
                                    placeholder="HAZ / REEFER/ OOG DETAILS / HAZ APPROVAL REF" autocomplete="off">
                                @endif
                                </td>
                                <td>
                                    <input type="text" id="net_weight" name="containerDetails[0][net_weight]" class="form-control"  autocomplete="off" placeholder="Net Weight">
                                </td>
                                <td><input type="text" class="form-control" id="weight" name="containerDetails[0][weight]" value="{{old('weight')}}"
                                    placeholder="Gross Weight" autocomplete="off">
                                </td>
                                <!-- <td><input type="text" class="form-control" id="vgm" name="containerDetails[0][vgm]" value="{{old('vgm')}}"
                                    placeholder="VGM" autocomplete="off">
                                </td> -->
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
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
                '<td class="container_type"><select id="selectpicker" class="selectpicker form-control" data-live-search="true" name="containerDetails['+counter+'][container_type]" data-size="10" title="{{trans('forms.select')}}">@foreach ($equipmentTypes as $item)@if($quotation->equipment_type_id != null)<option value="{{$item->id}}" {{$item->id == old('container_type',$quotation->equipment_type_id) ? 'selected':''}}>{{$item->name}}</option>@else<option value="{{$item->id}}" {{$item->id == old('equipment_type_id',$quotation->equipment_type_id) ? 'selected':''}}>{{$item->name}}</option> @endif @endforeach</select></td>'+
                '<td class="containerDetailsID"><select id="selectpicker" class="selectpicker form-control" data-live-search="true" name="containerDetails['+counter+'][container_id]" data-size="10"><option value="000">Select</option> @if($quotation->id == 0) @foreach ($transhipmentContainers as $item)<option value="{{$item->id}}" {{$item->id == old('container_id') ? 'selected':''}}>{{$item->code}}</option> @endforeach @else @foreach ($containers as $item)<option value="{{$item->id}}" {{$item->id == old('container_id') ? 'selected':''}}>{{$item->code}}</option> @endforeach @endif</select></td>'+
                '<td><input type="text" name="containerDetails['+counter+'][qty]" class="form-control input" id="number"  onchange="return check_value();" autocomplete="off" placeholder="QTY" required></td>'+
                '<td class="ports"><select class="selectpicker form-control" id="activity_location_id" data-live-search="true" name="containerDetails['+counter+'][activity_location_id]" data-size="10" title="{{trans('forms.select')}}">@foreach ($activityLocations as $activityLocation)<option value="{{$activityLocation->id}}" {{$activityLocation->id == old('activity_location_id') ? 'selected':''}}>{{$activityLocation->code}}</option> @endforeach </select></td>'+
                '<td><input type="text" name="containerDetails['+counter+'][seal_no]" class="form-control" autocomplete="off" placeholder="Seal No"></td>'+
                '<td><input type="text" name="containerDetails['+counter+'][packs]" class="form-control" autocomplete="off" placeholder="Packs"></td>'+
                '<td><input type="text" name="containerDetails['+counter+'][pack_type]" class="form-control" autocomplete="off" placeholder="Packs Type"></td>'+
                '<td><input type="text" value="{{$quotation->oog_dimensions}}" name="containerDetails['+counter+'][haz]" class="form-control" placeholder="HAZ / REEFER/ OOG DETAILS / HAZ APPROVAL REF" autocomplete="off"></td>'+
                '<td><input type="text" name="containerDetails['+counter+'][net_weight]" class="form-control" autocomplete="off" placeholder="Net Weight"></td>'+
                '<td><input type="text" name="containerDetails['+counter+'][weight]" class="form-control" autocomplete="off" placeholder="Weight"></td>'+
                // '<td><input type="text" name="containerDetails['+counter+'][vgm]" class="form-control" autocomplete="off" placeholder="VGM"></td>'+
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
                if(value == ""){
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

