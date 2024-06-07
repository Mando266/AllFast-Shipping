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
                    <div class="row">
                        <div class="col-md-12 text-right mb-6">
                            <a class="btn btn-danger" href="{{ route('temperature-discrepancy',$booking->id) }}"
                            >Temperature Discrepancy</a>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                    @if($booking->quotation_id != null)
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
                @endif
                    <form novalidate id="createForm" action="{{route('booking.update',['booking'=>$booking])}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('put')
                    <input type="hidden" name="quotation_id" value="{{old('quotation_id',$quotation->id)}}">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="ref_no">Booking Ref No</label>
                                        <input type="text" class="form-control" id="ref_no" name="ref_no" value="{{old('ref_no',$booking->ref_no)}}"
                                            placeholder="Booking Ref No" autocomplete="off">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>OFR</label>
                                    @if($booking->quotation_id == null)
                                        <input type="text" class="form-control" value="{{$quotation->ofr}}"
                                            placeholder="OFR" autocomplete="off">
                                    @else
                                    <input type="text" class="form-control" value="{{$quotation->ofr}}"
                                    placeholder="OFR" autocomplete="off" disabled>
                                    @endif
                                </div>
                            </div>
                            @php
                            $is_shipper = 0;
                            $is_ffw = 0;
                            $is_consignee = 0 ;
                            if($booking->quotation_id != null){
                                foreach($quotation->customer->CustomerRoles ?? []  as $customerRole){
                                    if($customerRole->role->name == "Fright Forwarder"){
                                        $is_ffw = 1;
                                    }elseif($customerRole->role->name == "Shipper"){
                                        $is_shipper = 1;
                                    }
                                    elseif(optional($customerRole)->role->name == "Consignee"){
                                        $is_consignee = 1;
                                    }
                                }
                            }
                            @endphp
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                <label for="customer_id">Shipper <span class="text-warning"> * (Required.) </span></label>
                                <select class="selectpicker form-control" id="customer_id" data-live-search="true" name="customer_id" data-size="10"
                                 title="{{trans('forms.select')}}" @if($is_shipper) disabled @endif>
                                    @foreach ($customers as $item)
                                        @if($quotation->id != $booking->quotation_id)
                                            @if($quotation->customer_id != null)
                                                @if(in_array(1, optional($quotation->customer)->CustomerRoles->pluck('role_id')->toarray()))
                                                    <option value="{{$item->id}}" {{$item->id == old('customer_id',$quotation->customer_id) ? 'selected':'disabled'}}>{{$item->name}} @foreach($item->CustomerRoles as $itemRole) - {{optional($itemRole->role)->name}}@endforeach</option>
                                                @else
                                                    <option value="{{$item->id}}" {{$item->id == old('customer_id',$quotation->customer_id) ? 'selected':''}}>{{$item->name}} @foreach($item->CustomerRoles as $itemRole) - {{optional($itemRole->role)->name}}@endforeach</option>
                                                @endif
                                            @else
                                                <option value="{{$item->id}}" {{$item->id == old('customer_id') ? 'selected':''}}>{{$item->name}} @foreach($item->CustomerRoles as $itemRole) - {{optional($itemRole->role)->name}}@endforeach</option>
                                            @endif
                                        @else
                                            @if($booking->customer_id != null)
                                                <option value="{{$item->id}}" {{$item->id == old('customer_id',$booking->customer_id) ? 'selected':''}}>{{$item->name}} @foreach($item->CustomerRoles as $itemRole) - {{optional($itemRole->role)->name}}@endforeach</option>
                                            @elseif($quotation->customer_id != null)
                                                @if(in_array(1, optional($quotation->customer)->CustomerRoles->pluck('role_id')->toarray()))
                                                <option value="{{$item->id}}" {{$item->id == old('customer_id',$quotation->customer_id) ? 'selected':'disabled'}}>{{$item->name}} @foreach($item->CustomerRoles as $itemRole) - {{optional($itemRole->role)->name}}@endforeach</option>
                                                @else
                                                <option value="{{$item->id}}" {{$item->id == old('customer_id',$quotation->customer_id) ? 'selected':''}}>{{$item->name}} @foreach($item->CustomerRoles as $itemRole) - {{optional($itemRole->role)->name}}@endforeach</option>
                                                @endif
                                            @else
                                                <option value="{{$item->id}}" {{$item->id == old('customer_id') ? 'selected':''}}>{{$item->name}} @foreach($item->CustomerRoles as $itemRole) - {{optional($itemRole->role)->name}}@endforeach</option>
                                            @endif
                                        @endif
                                    @endforeach
                                    </select>
                                        @error('customer_id')
                                        <div style="color: red;">
                                            {{$message}}
                                        </div>
                                        @enderror
                                </div>

                                <div class="form-group col-md-2">
                                    <label for="equipment_type_id">Equipment Type <span class="text-warning"> *</span></label>
                                    <select class="selectpicker form-control" id="equipment_type_id" data-live-search="true" name="equipment_type_id" data-size="10"
                                    title="{{trans('forms.select')}}">
                                        @foreach ($equipmentTypes as $item)
                                        @if($booking->quotation_id == null)
                                            <option value="{{$item->id}}" {{$item->id == old('equipment_type_id',$booking->equipment_type_id) ? 'selected':''}}>{{$item->name}}</option>
                                        @else
                                            <option value="{{$item->id}}" {{$item->id == old('equipment_type_id',$quotation->equipment_type_id) ? 'selected':'disabled'}}>{{$item->name}}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                        @error('equipment_type_id')
                                        <div style="color: red;">
                                            {{$message}}
                                        </div>
                                        @enderror
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="status">Booking Status<span class="text-warning"> *</span></label>
                                    <select class="selectpicker form-control" data-live-search="true" name="booking_confirm" data-live-search="true">
                                        <option value="1" {{ old('booking_confirm',$booking->booking_confirm) == "1" ? 'selected':'' }}>Confirm</option>
                                        <option value="3" {{ old('booking_confirm',$booking->booking_confirm) == "3" ? 'selected':'' }}>Draft</option>
                                        <option value="2" {{ old('booking_confirm',$booking->booking_confirm) == "2" ? 'selected':'' }}>Cancelled</option>
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
                                <label for="Principal">Principal Name <span class="text-warning"> * (Required.) </span></label>
                                <select class="selectpicker form-control" id="Principal" data-live-search="true" name="principal_name" data-size="10"
                                title="{{trans('forms.select')}}" required>
                                    @foreach ($line as $item)
                                    @if($quotation->principal_name != null)
                                        <option value="{{$item->id}}" {{$item->id == old('principal_name',$quotation->principal_name) ? 'selected':'disabled'}}>{{$item->name}}</option>
                                    @elseif($booking->quotation_id == null)
                                    <option value="{{$item->id}}" {{$item->id == old('principal_name',$booking->principal_name) ? 'selected':''}}>{{$item->name}}</option>
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
                                <label for="vessel_name">Vessel Operator <span class="text-warning"> * (Required.) </span></label>
                                <select class="selectpicker form-control" id="vessel_name" data-live-search="true" name="vessel_name" data-size="10"
                                title="{{trans('forms.select')}}" required>
                                    @foreach ($line as $item)
                                    @if($quotation->vessel_name != null)
                                        <option value="{{$item->id}}" {{$item->id == old('vessel_name',$quotation->vessel_name) ? 'selected':'disabled'}}>{{$item->name}}</option>
                                    @elseif($booking->quotation_id == null)
                                    <option value="{{$item->id}}" {{$item->id == old('vessel_name',$booking->vessel_name) ? 'selected':''}}>{{$item->name}}</option>
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
                                    title="{{trans('forms.select')}}" @if($is_ffw) disabled @endif>
                                        <option value="">Select...</option>
                                        @foreach ($ffw as $item)
                                            @if($quotation->id != $booking->quotation_id)
                                                @if($quotation->customer_id != null)
                                                    @if(in_array(6, optional($quotation->customer)->CustomerRoles->pluck('role_id')->toarray()))
                                                        <option value="{{$item->id}}" {{$item->id == old('ffw_id',$quotation->customer_id) ? 'selected':'disabled'}}>{{$item->name}} @foreach($item->CustomerRoles as $itemRole) - {{optional($itemRole->role)->name}}@endforeach</option>
                                                    @else
                                                        <option value="{{$item->id}}" {{$item->id == old('ffw_id',$quotation->customer_id) ? 'selected':''}}>{{$item->name}} @foreach($item->CustomerRoles as $itemRole) - {{optional($itemRole->role)->name}}@endforeach</option>
                                                    @endif
                                                @else
                                                    <option value="{{$item->id}}" {{$item->id == old('ffw_id') ? 'selected':''}}>{{$item->name}} @foreach($item->CustomerRoles as $itemRole) - {{optional($itemRole->role)->name}}@endforeach</option>
                                                @endif
                                            @else
                                                @if($booking->ffw_id != null)
                                                    <option value="{{$item->id}}" {{$item->id == old('ffw_id',$booking->ffw_id) ? 'selected':''}}>{{$item->name}} @foreach($item->CustomerRoles as $itemRole) - {{optional($itemRole->role)->name}}@endforeach</option>
                                                @elseif($quotation->customer_id != null)
                                                    @if(in_array(6, optional($quotation->customer)->CustomerRoles->pluck('role_id')->toarray()))
                                                        <option value="{{$item->id}}" {{$item->id == old('ffw_id',$quotation->customer_id) ? 'selected':'disabled'}}>{{$item->name}} @foreach($item->CustomerRoles as $itemRole) - {{optional($itemRole->role)->name}}@endforeach</option>
                                                    @else
                                                        <option value="{{$item->id}}" {{$item->id == old('ffw_id',$quotation->customer_id) ? 'selected':''}}>{{$item->name}} @foreach($item->CustomerRoles as $itemRole) - {{optional($itemRole->role)->name}}@endforeach</option>
                                                    @endif
                                                @else
                                                    <option value="{{$item->id}}" {{$item->id == old('ffw_id') ? 'selected':''}}>{{$item->name}} @foreach($item->CustomerRoles as $itemRole) - {{optional($itemRole->role)->name}}@endforeach</option>
                                                @endif
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
                                            <option value="{{$item->id}}" {{$item->id == old('customer_consignee_id',$booking->customer_consignee_id) ? 'selected':''}}>{{$item->name}} @foreach($item->CustomerRoles as $itemRole) - {{optional($itemRole->role)->name}}@endforeach</option>
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
                                <label for="place_of_acceptence_id">Place Of Acceptence <span class="text-warning"> * (Required.) </span></label>
                                 <select class="selectpicker form-control" id="place_of_acceptence_id" data-live-search="true" name="place_of_acceptence_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($ports as $item)
                                    @if($booking->quotation_id == null)
                                        <option value="{{$item->id}}" {{$item->id == old('place_of_acceptence_id',$booking->place_of_acceptence_id) ? 'selected':''}}>{{$item->name}}</option>
                                    @else
                                        <option value="{{$item->id}}" {{$item->id == old('place_of_acceptence_id',$quotation->place_of_acceptence_id) ? 'selected':'disabled'}}>{{$item->name}}</option>
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
                                <label for="load_port_id">Load Port <span class="text-warning"> * (Required.) </span></label>
                                 <select class="selectpicker form-control" id="load_port_id" data-live-search="true" name="load_port_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($ports as $item)
                                    @if($booking->quotation_id == null)
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
                                <label for="shipper_ref_no">Shipper Ref No</label>
                                <input type="text" class="form-control" id="shipper_ref_no" name="shipper_ref_no" value="{{old('shipper_ref_no',$booking->shipper_ref_no)}}"
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
                                <label for="place_of_delivery_id">Place Of Delivery <span class="text-warning"> * (Required.) </span></label>
                                <select class="selectpicker form-control" id="place_of_delivery_id" data-live-search="true" name="place_of_delivery_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($ports as $item)
                                    @if($booking->quotation_id == null)
                                    <option value="{{$item->id}}" {{$item->id == old('place_of_delivery_id',$booking->place_of_delivery_id) ? 'selected':''}}>{{$item->name}}</option>
                                    @else
                                        <option value="{{$item->id}}" {{$item->id == old('place_of_delivery_id',$quotation->place_of_delivery_id) ? 'selected':'disabled'}}>{{$item->name}}</option>
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
                                <label for="discharge_port_id">Discharge Port <span class="text-warning"> * (Required.) </span></label>
                                <select class="selectpicker form-control" id="discharge_port_id" data-live-search="true" name="discharge_port_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($ports as $item)
                                    @if($booking->quotation_id == null)
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
                            <div class="form-group col-md-4">
                                <label for="pick_up_location">Pick Up Location</label>
                                <select class="selectpicker form-control" id="pick_up_location" data-live-search="true" name="pick_up_location" data-size="10"
                                 title="{{trans('forms.select')}}">
                                 <option value="">Select...</option>
                                    @foreach ($ports as $item)
                                    @if($booking->quotation_id == null)
                                        <option value="{{$item->id}}" {{$item->id == old('pick_up_location',$booking->pick_up_location) ? 'selected':''}}>{{$item->name}}</option>
                                    @else
                                        <option value="{{$item->id}}" {{$item->id == old('pick_up_location',$booking->pick_up_location) ? 'selected':''}}>{{$item->name}}</option>
                                    @endif
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
                                    @if($booking->quotation_id == null)
                                        <option value="{{$item->id}}" {{$item->id == old('place_return_id',$booking->place_return_id) ? 'selected':''}}>{{$item->name}}</option>
                                    @else
                                        <option value="{{$item->id}}" {{$item->id == old('place_return_id',$booking->place_return_id) ? 'selected':''}}>{{$item->name}}</option>
                                    @endif
                                    @endforeach
                                </select>
                                @error('place_return_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="bl_release">BL Release <span class="text-warning"> * (Required.) </span></label>
                                <select class="selectpicker form-control" id="bl_release" data-live-search="true" name="bl_release" data-size="10"
                                title="{{trans('forms.select')}}">
                                    @foreach ($agents as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('bl_release',$booking->bl_release) ? 'selected':''}}>{{$item->name}}</option>
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
                        @if($quotation->id != 0)
                            <div class="form-group col-md-3">
                                <label for="voyage_id">First Vessel / Voyage <span class="text-warning"> * (Required.) </span></label>
                                <select class="selectpicker form-control" id="voyage_id" data-live-search="true" name="voyage_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                 <option value="">Select..</option>
                                    @foreach ($voyages as $item)
                            <option value="{{$item->id}}" {{$item->id == old('voyage_id',$booking->voyage_id) ? 'selected':''}}>{{$item->vessel->name}} / {{$item->voyage_no}} - {{ optional($item->leg)->name }}</option>
                                    @endforeach
                                </select>
                                @error('voyage_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="voyage_id_second">Second Vessel / Voyage</label>
                                <select class="selectpicker form-control" id="voyage_id_second" data-live-search="true" name="voyage_id_second" data-size="10"
                                 title="{{trans('forms.select')}}">
                                 <option value="">Select..</option>
                                    @foreach ($voyages as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('voyage_id_second',$booking->voyage_id_second) ? 'selected':''}}>{{$item->vessel->name}} / {{$item->voyage_no}} - {{ optional($item->leg)->name }}</option>
                                    @endforeach
                                </select>
                                @error('voyage_id_second')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="Transhipment">Transhipment Port</label>
                                <select class="selectpicker form-control" id="transhipment_port" data-live-search="true" name="transhipment_port" data-size="10"
                                 title="{{trans('forms.select')}}">
                                 <option value="">Select...</option>
                                    @foreach ($ports as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('transhipment_port',$booking->transhipment_port) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('transhipment_port')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="terminal_id">Discharge Terminal <span class="text-warning"> * (Required.) </span></label>
                                <select class="form-control" id="terminal" data-live-search="true" name="terminal_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($terminals as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('terminal_id',$booking->terminal_id) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('terminal_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            @else
                            <div class="form-group col-md-3">
                                <label for="voyage_id">First Vessel / Voyage <span class="text-warning"> * (Required.) </span></label>
                                <select class="selectpicker form-control" id="voyage_id" data-live-search="true" name="voyage_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                 <option value="">Select..</option>
                                    @foreach ($voyages as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('voyage_id',$booking->voyage_id) ? 'selected':''}}>{{$item->vessel->name}} / {{$item->voyage_no}} - {{ optional($item->leg)->name }}</option>
                                    @endforeach
                                </select>
                                @error('voyage_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="voyage_id_second">Second Vessel / Voyage</label>
                                <select class="selectpicker form-control" id="voyage_id_second" data-live-search="true" name="voyage_id_second" data-size="10"
                                 title="{{trans('forms.select')}}">
                                 <option value="">Select..</option>
                                    @foreach ($voyages as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('voyage_id_second',$booking->voyage_id_second) ? 'selected':''}}>{{$item->vessel->name}} / {{$item->voyage_no}} - {{ optional($item->leg)->name }}</option>
                                    @endforeach
                                </select>
                                @error('voyage_id_second')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="Transhipment">Transhipment Port</label>
                                <select class="selectpicker form-control" id="transhipment_port" data-live-search="true" name="transhipment_port" data-size="10"
                                 title="{{trans('forms.select')}}">
                                 <option value="">Select...</option>
                                    @foreach ($ports as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('transhipment_port',$booking->transhipment_port) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('transhipment_port')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="terminal_id">Discharge Terminal <span class="text-warning"> * (Required.) </span></label>
                                <select class="form-control" id="terminal" data-live-search="true" name="terminal_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($terminals as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('terminal_id',$booking->terminal_id) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('terminal_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            @endif
                        </div>

                        <div class="form-row">
                        <div class="form-group col-md-3">
                                <label for="load_terminal_id">Load Port Terminal <span class="text-warning"> * (Required.) </span></label>
                                <select class="selectpicker form-control" id="terminal" data-live-search="true" name="load_terminal_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($terminals as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('load_terminal_id',$booking->load_terminal_id) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('load_terminal_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>

                            <div class="form-group col-md-3">
                                <label for="discharge_etd">Discharge ETA</label>
                                @if($booking->quotation_id == null)
                                <input type="date" class="form-control" id="discharge_etd" name="discharge_etd" value="{{old('discharge_etd',$booking->discharge_etd)}}"
                                    placeholder="Discharge ETA" autocomplete="off">
                                @else
                                <input type="date" class="form-control" id="discharge_etd" name="discharge_etd" value="{{old('discharge_etd',$quotation->discharge_etd)}}"
                                    placeholder="Discharge ETA" autocomplete="off">
                                @endif
                                @error('discharge_etd')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="load_port_cutoff">Load Port Cutoff</label>
                                @if($booking->quotation_id == null)
                                <input type="date" class="form-control" id="load_port_cutoff" name="load_port_cutoff" value="{{old('load_port_cutoff',$booking->load_port_cutoff)}}"
                                placeholder="Load Port Cutoff" autocomplete="off">
                                @else
                                <input type="date" class="form-control" id="load_port_cutoff" name="load_port_cutoff" value="{{old('load_port_cutoff',$quotation->load_port_cutoff)}}"
                                    placeholder="Load Port Cutoff" autocomplete="off">
                                @endif
                                @error('load_port_cutoff')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="load_port_dayes">Load Port Days</label>
                                @if($booking->quotation_id == null)
                                <input type="text" class="form-control" id="load_port_dayes" name="load_port_dayes" value="{{old('load_port_dayes',$booking->load_port_dayes)}}"
                                    placeholder="Load Port Days" autocomplete="off">
                                @else
                                <input type="text" class="form-control" id="load_port_dayes" name="load_port_dayes" value="{{old('load_port_dayes',$quotation->load_port_dayes)}}"
                                placeholder="Load Port Days" autocomplete="off">
                                @endif
                                @error('load_port_dayes')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
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
                                <label for="tariff_service">Tariff Service</label>
                                @if($booking->quotation_id == null)
                                <input type="text" class="form-control" id="tariff_service" name="tariff_service" value="{{old('tariff_service',$booking->tariff_service)}}"
                                placeholder="Tariff Service" autocomplete="off">
                                @else
                                <input type="text" class="form-control" id="tariff_service" name="tariff_service" value="{{old('tariff_service',$quotation->ref_no)}}"
                                    placeholder="Tariff Service" autocomplete="off" disabled>
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
                            <div class="form-group col-md-3">
                                <label for="commodity_description">Commodity Description <span class="text-warning"> * (Required.) </span></label>
                                <input type="text" class="form-control" id="commodity_description" name="commodity_description" value="{{old('commodity_description',$booking->commodity_description)}}"
                                    placeholder="Commodity Description" autocomplete="off" required>
                                @error('commodity_description')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            @if($quotation->id != 0)
                            <div class="form-group col-md-3">
                                <label>Shipment Status</label>
                                <input type="text" class="form-control" name="shipment_type" value="{{$quotation->shipment_type}}" readonly>
                            </div>
                            @else
                            <div class="form-group col-md-3">
                                <label>Shipment Status</label>
                                <select class="selectpicker form-control" data-live-search="true" name="shipment_type" title="{{trans('forms.select')}}" required>
                                   <option value="Import" {{$booking->id == old('shipment_type') ||  $booking->shipment_type == "Import"? 'selected':''}}>Import</option>
                                   <option value="Export" {{$booking->id == old('shipment_type') ||  $booking->shipment_type == "Export"? 'selected':''}}>Export</option>
                                </select>
                            </div>
                            @endif

                            @if($quotation->id != 0)
                            <div class="form-group col-md-3">
                                <label>Booking Status</label>
                                <input type="text" class="form-control" name="booking_type" value="{{$quotation->quotation_type}}" readonly>
                            </div>
                            @else
                            <div class="form-group col-md-3">
                                <label>Booking Status</label>
                                <select class="selectpicker form-control" data-live-search="true" name="booking_type" title="{{trans('forms.select')}}" required>
                                   <option value="Empty" {{$booking->id == old('booking_type') ||  $booking->booking_type == "Empty"? 'selected':''}}>Empty</option>
                                   <option value="Full" {{$booking->id == old('booking_type') ||  $booking->booking_type == "Full"? 'selected':''}}>Full</option>
                                </select>
                            </div>
                            @endif
                            <div class="form-group col-md-3">
                                <label>Exporter Number</label>
                                <input type="text" class="form-control"  style="background-color:#fff" name="exportal_id" placeholder="Exporter Number" value="{{old('exportal_id',$booking->exportal_id)}}">
                            </div>
                            <div class="form-group col-md-3">
                                    <label for="status">Movement</label>
                                    <select class="selectpicker form-control" data-live-search="true" name="movement" title="{{trans('forms.select')}}">
                                        <option value="FCL/FCL" {{$booking->movement == old('movement') ||  $booking->movement == "FCL/FCL"? 'selected':''}}>FCL/FCL</option>
                                        <option value="LCL/LCL" {{$booking->movement == old('movement') ||  $booking->movement == "LCL/LCL"? 'selected':''}}>LCL/LCL</option>
                                    </select>
                                    @error('movement')
                                    <div style="color:red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                            </div>
                            <div class="form-group col-md-5">
                                <label for="details">Notes</label>
                                <textarea class="form-control" id="notes" name="notes"
                                 placeholder="Notes" autocomplete="off">{{ old('notes',$booking->notes) }}</textarea>
                                @error('notes')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>

                            <div class="form-group col-md-3">
                                <div class="custom-file-container" data-upload-id="certificat">
                                    <label> <span style="color:#3b3f5c";> Certificat </span><a href="javascript:void(0)" class="custom-file-container__image-clear" title="Clear Image"></a></label>
                                    <label class="custom-file-container__custom-file" >
                                        <input type="file" class="custom-file-container__custom-file__custom-file-input" name="certificat" value="{{old('certificat',$booking->certificat)}}" accept="pdf">
                                        <input type="hidden" name="MAX_FILE_SIZE" disabled value="10485760" />
                                        <span class="custom-file-container__custom-file__custom-file-control"></span>
                                    </label>
                                    <div class="custom-file-container__image-preview"></div>
                                </div>
                            </div>
                    </div>
                        <h4>Container Details</h4>
                            @error('containerDetails')
                                <div style="color: red; font-size: 30px; text-align: center;">
                                    {{$message}}
                                </div>
                            @enderror
                            <table id="containerDetails" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">Seal No</th>
                                        <th class="text-center">Container Type</th>
                                        <th class="text-center">QTY</th>
                                        @if(optional($quotation)->shipment_type == "Import")
                                        <th class="text-center">Return Location</th>
                                        @else
                                        <th class="text-center">Pick Up Location</th>
                                        @endif                                        <th class="text-center">Container No</th>
                                        <th class="text-center">HAZ / Reefer/ OOG Details / Haz Approval Ref</th>
                                        <th class="text-center">weight</th>
                                        <th class="text-center">vgm</th>
                                        <th class="text-center">
                                            <a id="add"> Add Container <i class="fas fa-plus"></i></a>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($booking_details as $key => $item)
                                <tr>
                                        <input type="hidden" value ="{{ $item->id }}" name="containerDetails[{{ $key }}][id]">
                                    <td>
                                        <input type="text" id="seal_no" name="containerDetails[{{ $key }}][seal_no]" class="form-control" autocomplete="off" placeholder="Seal No" value="{{old('seal_no',$item->seal_no)}}">
                                    </td>

                                    <td class="containertype">
                                        <select class="selectpicker form-control" id="container_type" data-live-search="true" name="containerDetails[{{ $key }}][container_type]" data-size="10"
                                                title="{{trans('forms.select')}}">
                                                @foreach ($equipmentTypes as $equipmentType)
                                                
                                                <option value="{{$equipmentType->id}}" {{$equipmentType->id == old('container_type',$item->container_type) ? 'selected':''}}>{{$equipmentType->name}}</option>

                                            @endforeach
                                        </select>
                                    </td>

                                    <td>
                                        <input type="text" id="qyt" onchange="return check();" name="containerDetails[{{ $key }}][qty]" class="form-control input"  autocomplete="off" placeholder="QTY" value="{{old('qty',$item->qty)}}" required>
                                    </td>
                                    @if($item->container_id == 000)
                                    @if($quotation->id == 0)
                                        <td class="ports">
                                            <select class="selectpicker form-control" name="containerDetails[{{ $key }}][activity_location_id]" id="activity_location_id" data-live-search="true"  data-size="10"
                                            title="{{trans('forms.select')}}">
                                                <option value="">Select....</option>
                                                @foreach ($activityLocations as $activityLocation)
                                                    <option value="{{$activityLocation->id}}" {{$activityLocation->id == old('activity_location_id',$item->activity_location_id) ? 'selected':''}}>{{$activityLocation->code}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    @else
                                        <td class="ports">
                                            <select class="selectpicker form-control" name="containerDetails[{{ $key }}][activity_location_id]" id="activity_location_id" data-live-search="true"  data-size="10"
                                            title="{{trans('forms.select')}}" required>
                                            <option value="">Select....</option>
                                                @foreach ($activityLocations as $activityLocation)
                                                    <option value="{{$activityLocation->id}}" {{$activityLocation->id == old('activity_location_id',$item->activity_location_id) ? 'selected':''}}>{{$activityLocation->code}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    @endif
                                    <td class="containerDetailsID">
                                        <select class="selectpicker form-control" id="containerDetailsID" name="containerDetails[{{ $key }}][container_id]" data-live-search="true"  data-size="10"
                                                title="{{trans('forms.select')}}">
                                                <option value="000" selected>Select</option>

                                            @if($booking->is_transhipment === 1)
                                                @foreach ($transhipmentContainers as $transhipmentContainer)
                                                    <option value="{{$transhipmentContainer->id}}" {{$transhipmentContainer->id == old('container_id',$transhipmentContainer->container_id) ? 'selected':''}}>{{$transhipmentContainer->code}}</option>
                                                @endforeach
                                            @else
                                                @foreach ($oldContainers as $container)
                                                    <option value="{{$container->id}}" {{$container->id == old('container_id',$item->container_id) ? 'selected':''}}>{{$container->code}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </td>
                                    @else
                                    @if($quotation->shipment_type == 'Import')
                                        <td>
                                    @else
                                        <td class="ports">
                                    @endif
                                        <select class="selectpicker form-control" id="activity_location_id" name="containerDetails[{{ $key }}][activity_location_id]" data-live-search="true"  data-size="10"
                                        title="{{trans('forms.select')}}" disabled>
                                            @foreach ($activityLocations as $activityLocation)
                                                <option value="{{$activityLocation->id}}" {{$activityLocation->id == old('activity_location_id',$item->activity_location_id) ? 'selected':''}}>{{$activityLocation->code}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="containerDetailsID">
                                        <select class="selectpicker form-control" id="containerDetailsID" name="containerDetails[{{ $key }}][container_id]" data-live-search="true"  data-size="10"
                                                title="{{trans('forms.select')}}" disabled>
                                                @if($booking->is_transhipment === 1)
                                                @foreach ($transhipmentContainers as $container)
                                                    <option value="{{$container->id}}" {{$container->id == old('container_id',$item->container_id) ? 'selected':''}}>{{$container->code}}</option>
                                                @endforeach
                                                @else
                                                @foreach ($oldContainers as $container)
                                                    <option value="{{$container->id}}" {{$container->id == old('container_id',$item->container_id) ? 'selected':''}}>{{$container->code}}</option>
                                                @endforeach
                                                @endif
                                        </select>
                                    </td>
                                    @endif
                                    <td>
                                        <input type="text" id="haz" name="containerDetails[{{ $key }}][haz]" class="form-control" autocomplete="off" placeholder="HAZ / REEFER/ OOG DETAILS / HAZ APPROVAL REF" value="{{old('haz',$item->haz)}}">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" id="weight" name="containerDetails[{{ $key }}][weight]" value="{{old('weight',$item->weight)}}"
                                        placeholder="Weight" autocomplete="off">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" id="vgm" name="containerDetails[{{ $key }}][vgm]" value="{{old('vgm',$item->vgm)}}"
                                        placeholder="VGM" autocomplete="off">
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
<script type="text/javascript">

     $('.show_confirm').click(function(event) {
          var form =  $(this).closest("form");
          var name = $(this).data("name");
          event.preventDefault();
          swal({
              title: `Are you sure you want to Change The Booking Rate?`,
              icon: "warning",
              buttons: true,
              dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) {
              form.submit();
            }
          });
      });
</script>
<script>
var removed = [];
function removeItem( item )
{
    removed.push(item);
    console.log(removed);
    document.getElementById("removed").value = removed;
}
$(document).ready(function(){
    $("#containerDetails").on("click", ".remove", function () {
    $(this).closest("tr").remove();
    });
    var counter  = <?= isset($key)? ++$key : 0 ?>;

    $("#add").click(function(){
            var tr = '<tr>'+
                '<td><input type="text" name="containerDetails['+counter+'][seal_no]" class="form-control" autocomplete="off" placeholder="Seal No"></td>'+
                '<td><select class="selectpicker form-control" id="selectpicker" data-live-search="true" name="containerDetails['+counter+'][container_type]" data-size="10">@foreach ($equipmentTypes as $item)@if($quotation->equipment_type_id != null)<option value="{{$item->id}}" {{$item->id == old('container_type',$quotation->equipment_type_id) ? 'selected':'disabled'}}>{{$item->name}}</option>@else<option value="{{$item->id}}" {{$item->id == old('equipment_type_id',$quotation->equipment_type_id) ? 'selected':''}}>{{$item->name}}</option> @endif @endforeach</select></td>'+
                '<td><input type="text" name="containerDetails['+counter+'][qty]" class="form-control input" autocomplete="off" id="number"  onchange="return check_value();" placeholder="QTY" required></td>'+
                '@if($quotation->shipment_type == 'Import')<td>@else<td class="ports">@endif<select class="selectpicker form-control" id="selectpicker" data-live-search="true" required name="containerDetails['+counter+'][activity_location_id]" data-size="10" title="{{trans('forms.select')}}">@foreach ($activityLocations as $activityLocation)<option value="{{$activityLocation->id}}" {{$activityLocation->id == old('activity_location_id') ? 'selected':''}}>{{$activityLocation->code}}</option> @endforeach </select></td>'+
                '<td class="containerDetailsID"><select id="selectpicker" class="selectpicker form-control" data-live-search="true" name="containerDetails['+counter+'][container_id]" data-size="10"><option value="000">Select</option> @if($quotation->id == 0) @foreach ($transhipmentContainers as $item)<option value="{{$item->id}}" {{$item->id == old('container_id') ? 'selected':''}}>{{$item->code}}</option> @endforeach @else @foreach ($containers as $item)<option value="{{$item->id}}" {{$item->id == old('container_id') ? 'selected':''}}>{{$item->code}}</option> @endforeach @endif</select></td>'+
                '<td><input type="text" value="" name="containerDetails['+counter+'][haz]" class="form-control" autocomplete="off" placeholder="HAZ / REEFER/ OOG DETAILS / HAZ APPROVAL REF"></td>'+
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
            let equipment_id = "{{$quotation->equipment_type_id}}";
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
    $('#createForm').submit(function() {
        $('select').removeAttr('disabled');
    });
</script>
@endpush

