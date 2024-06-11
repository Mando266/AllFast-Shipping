@extends('layouts.app')
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a a href="{{route('quotations.index')}}">Quotations</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">Create New Quotation</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                    <form  novalidate id="createForm"  action="{{route('quotations.update',['quotation'=>$quotation])}}" method="POST">
                            @csrf
                            @method('put')
                            <h4> Quotation Ref N : {{$quotation->ref_no}} </h4>
                    </br>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="status">Quotation Type <span class="text-warning"> * </span></label>
                            <select class="selectpicker form-control" data-live-search="true" name="quotation_type" title="{{trans('forms.select')}}">
                                <option value="full" {{$quotation->id == old('quotation_type')  ||  $quotation->quotation_type == "full"? 'selected':''}}>Full</option>
                                <option value="empty" {{$quotation->id == old('quotation_type') ||  $quotation->quotation_type == "empty"? 'selected':''}}>Empty</option>
                            </select>
                            @error('quotation_type')
                            <div style="color:red;">
                                {{$message}}
                            </div>
                            @enderror
                        </div>

                        <div class="form-group col-md-3">
                                <label>Transportation Mode <span class="text-warning"> * </span></label>
                                <select class="selectpicker form-control" data-live-search="true" name="transportation_mode" title="{{trans('forms.select')}}" required>
                                    <option value="vessel" {{$quotation->id == old('transportation_mode') ||  $quotation->transportation_mode == "vessel"? 'selected':''}}>Vessel</option>
                                    <option value="trucker" {{$quotation->id == old('transportation_mode') ||  $quotation->transportation_mode == "trucker"? 'selected':''}}>Trucker</option>
                                    <option value="train" {{$quotation->id == old('transportation_mode') ||  $quotation->transportation_mode == "train"? 'selected':''}}>Train</option>
                                </select>
                                @error('transportation_mode')
                                <div style="color:red;">
                                    {{$message}}
                                </div>
                                @enderror
                        </div>
                        <div class="form-group col-md-3">
                                <label>Booking Agency<span class="text-warning"> * </span></label>
                                <select class="selectpicker form-control" id="" name="booking_agency" data-live-search="true" data-size="10"
                                    title="{{trans('forms.select')}}" required>
                                    @foreach ($booking_agency as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('booking_agency',$quotation->booking_agency) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('booking_agency')
                                <div style="color:red;">
                                    {{$message}}
                                </div>
                                @enderror
                        </div>
                        <div class="form-group col-md-3">
                            <label>Payment As Per Agreement <span class="text-warning"> * </span></label>
                            <select class="selectpicker form-control" data-live-search="true" name="agency_bookingr_ref" required  title="{{trans('forms.select')}}">
                                <option value="EXW" {{$quotation->id == old('agency_bookingr_ref') ||  $quotation->agency_bookingr_ref == "EXW"? 'selected':''}}>EXW</option>
                                <option value="FCA" {{$quotation->id == old('agency_bookingr_ref') ||  $quotation->agency_bookingr_ref == "FCA"? 'selected':''}}>FCA</option>
                                <option value="FOB" {{$quotation->id == old('agency_bookingr_ref') ||  $quotation->agency_bookingr_ref == "FOB"? 'selected':''}}>FOB</option>
                                <option value="CIF" {{$quotation->id == old('agency_bookingr_ref') ||  $quotation->agency_bookingr_ref == "CIF"? 'selected':''}}>CIF</option>
                                <option value="CPT" {{$quotation->id == old('agency_bookingr_ref') ||  $quotation->agency_bookingr_ref == "CPT"? 'selected':''}}>CPT</option>

                            </select>                
                        </div>
                    </div>

                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="countryInput">Export Country <span class="text-warning"> * </span></label>
                                <select class="selectpicker form-control" id="countryDis" name="countrydis" data-live-search="true" data-size="10"
                                    title="{{trans('forms.select')}}" required>
                                    @foreach ($country as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('countrydis',$quotation->countrydis) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('countrydis')
                                <div style="color:red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="agent_id">Export Agent <span class="text-warning"> * </span></label>
                                <select class="form-control" id="agentDis" data-live-search="true" name="discharge_agent_id" data-size="10" required>
                                 <option value="">Select...</option>
                                    @foreach ($agents as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('discharge_agent_id',$quotation->discharge_agent_id) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('discharge_agent_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="countryInput">Import Country <span class="text-warning"> * </span></label>
                                <select class="selectpicker form-control" id="country" name="countryload" data-live-search="true" data-size="10"
                                    title="{{trans('forms.select')}}" required>
                                    @foreach ($country as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('countryload',$quotation->countryload) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('countryload')
                                <div style="color:red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="agent_id">Import Agent <span class="text-warning"> * </span></label>
                                <select class="form-control" id="agentload" data-live-search="true" name="agent_id" data-size="10" required>
                                 <option value="">Select...</option>
                                    @foreach ($agents as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('agent_id',$quotation->agent_id) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('agent_id')
                                <div style="color: red;">
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
                                    @foreach ($principals as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('principal_name',$quotation->principal_name) ? 'selected':''}}>{{$item->name}}</option>
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
                                    @foreach ($oprators as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('vessel_name',$quotation->vessel_name) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('principal_name')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div id="additionalSelect" style="display: none;" class="form-group col-md-4">
                                <label>Vessel Operator Frieght Payment <span class="text-warning"> * </span></label>
                                <select class="selectpicker form-control" data-live-search="true" name="operator_frieght_payment" id="operator_frieght_payment">
                                    <option value="agency" {{$quotation->id == old('operator_frieght_payment') ||  $quotation->operator_frieght_payment == "Prepaid"? 'selected':''}}>Agency (prepaid)</option>
                                    <option value="liner" {{$quotation->id == old('operator_frieght_payment') ||  $quotation->operator_frieght_payment == "liner"? 'selected':''}}>Liner (Collect)</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="customer_id">Agreement Party <span class="text-warning"> * </span></label>
                                <select class="selectpicker form-control" id="customer_id" data-live-search="true" name="customer_id" data-size="10"
                                 title="{{trans('forms.select')}}" require>
                                    @foreach ($customers as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('customer_id',$quotation->customer_id) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('customer_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="ffw_id">Forwarder Customer</label>
                                <select class="selectpicker form-control" id="ffw_id" data-live-search="true" name="ffw_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($ffw as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('ffw_id',$quotation->ffw_id) ? 'selected':''}}>{{$item->name}} </option>
                                    @endforeach
                                </select>
                                @error('ffw_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-3" style="padding-top: 30px;">
                                <div class="form-check">
                                <input type="checkbox" id="soc" name="soc" value="1" {{$quotation->soc == 1 ? 'checked="checked"' : '' }}><a style="font-size: 15px; color: #3b3f5c; letter-spacing: 1px; margin-right: 10px;"> SOC </a>

                                <input type="checkbox" id="imo" name="imo" value="1" {{$quotation->imo == 1 ? 'checked="checked"' : '' }}><a style="font-size: 15px; color: #3b3f5c; letter-spacing: 1px; margin-right: 10px;"> IMO </a>

                                <input type="checkbox" id="oog" name="oog" value="1" {{$quotation->oog == 1 ? 'checked="checked"' : '' }}><a style="font-size: 15px; color: #3b3f5c; letter-spacing: 1px; margin-right: 10px;"> OOG </a>

                                <input type="checkbox" id="rf" name="rf" value="1" {{$quotation->rf == 1 ? 'checked="checked"' : '' }}><a style="font-size: 15px; color: #3b3f5c; letter-spacing: 1px; margin-right: 10px;"> RF </a>

                                <input type="checkbox" id="nor" name="nor" value="1" {{$quotation->nor == 1 ? 'checked="checked"' : '' }}><a style="font-size: 15px; color: #3b3f5c; letter-spacing: 1px; margin-right: 10px;"> NOR </a>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="validity_from">Validity From <span class="text-warning"> * </span></label>
                                <input type="date" class="form-control" id="validity_from" name="validity_from" value="{{old('validity_from',$quotation->validity_from)}}"
                                     autocomplete="off" required>
                                @error('validity_from')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="validity_to">Validity To <span class="text-warning"> * </span></label>
                                <input type="date" class="form-control" id="validity_to" name="validity_to" value="{{old('validity_to',$quotation->validity_to)}}"
                                     autocomplete="off" required>
                                @error('validity_to')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="place_of_acceptence_id">Place Of Acceptence <span class="text-warning"> * </span></label>
                                <select class="form-control port" id="place_of_acceptence_id" data-live-search="true" name="place_of_acceptence_id" data-size="10"
                                 title="{{trans('forms.select')}}" required>
                                    @foreach ($ports as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('place_of_acceptence_id',$quotation->place_of_acceptence_id) ? 'selected':''}}>{{$item->name}}</option>
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
                                <select class="form-control port" id="load_port_id" data-live-search="true" name="load_port_id" data-size="10"
                                 title="{{trans('forms.select')}}" required>
                                    @foreach ($ports as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('load_port_id',$quotation->load_port_id) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('load_port_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="pick_up_location">Pick Up Location</label>
                                <select class="form-control port" id="pick_up_location" data-live-search="true" name="pick_up_location" data-size="10"
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
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="place_of_delivery_id">Place Of Delivery <span class="text-warning"> * </span></label>
                                <select class="form-control importPort" id="place_of_delivery_id" data-live-search="true" name="place_of_delivery_id" data-size="10"
                                 title="{{trans('forms.select')}}" required>
                                    @foreach ($ports as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('place_of_delivery_id',$quotation->place_of_delivery_id) ? 'selected':''}}>{{$item->name}}</option>
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
                                <select class="form-control importPort" id="discharge_port_id" data-live-search="true" name="discharge_port_id" data-size="10"
                                 title="{{trans('forms.select')}}" required>
                                    @foreach ($ports as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('discharge_port_id',$quotation->discharge_port_id) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('discharge_port_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="place_return_id">Place Of Return</label>
                                <select class="form-control importPort" id="place_return_id" data-live-search="true" name="place_return_id" data-size="10"
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
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="export_detention">Export Free Time <span class="text-warning"> * </span></label>
                                <input type="text" class="form-control" id="export_detention" name="export_detention" value="{{old('export_detention',$quotation->export_detention)}}"
                                    placeholder="Export Detention" autocomplete="off" required>
                                @error('export_detention')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="import_detention">Import Free Time <span class="text-warning"> * </span></label>
                                <input type="text" class="form-control" id="import_detention" name="import_detention" value="{{old('import_detention',$quotation->import_detention)}}"
                                    placeholder="Import Free Time" autocomplete="off" required>
                                @error('import_detention')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="power_charges ">Power Charges Free Dayes</label>
                                <input type="text" class="form-control" id="power_charges" name="power_charges" value="{{old('power_charges',$quotation->power_charges)}}"
                                    placeholder="Power Charges Free Dayes" autocomplete="off">
                                @error('power_charges ')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                          
                            <div class="form-group col-md-3">
                                <label for="oog_dimensions">HAZ / Reefer/ OOG Details </label>
                                <input type="text" class="form-control" id="oog_dimensions" name="oog_dimensions" value="{{old('oog_dimensions',$quotation->oog_dimensions)}}"
                                    placeholder="HAZ / Reefer/ OOG Details / Haz Approval Ref" autocomplete="off">
                                @error('oog_dimensions')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="commodity_code">Commodity Code</label>
                                <input type="text" class="form-control" id="commodity_code" name="commodity_code" value="{{old('commodity_code',$quotation->commodity_code)}}"
                                    placeholder="Commodity Code" autocomplete="off">
                                @error('commodity_code')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="commodity_des">Commodity Description <span class="text-warning"> * </span></label>
                                <input type="text" class="form-control" id="commodity_des" name="commodity_des" value="{{old('commodity_des',$quotation->commodity_des)}}"
                                    placeholder="Commodity Description" autocomplete="off" required>
                                @error('commodity_des')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="status">Payment kind<span class="text-warning"> * </span></label>
                                <select class="selectpicker form-control" data-live-search="true" name="payment_kind" id="payment_kind" title="{{trans('forms.select')}}" required>
                                    <option value="Prepaid" {{$quotation->id == old('payment_kind') ||  $quotation->payment_kind == "Prepaid"? 'selected':''}}>Prepaid</option>
                                    <option value="Collect" {{$quotation->id == old('payment_kind') ||  $quotation->payment_kind == "Collect"? 'selected':''}}>Collect</option>
                                    <option value="else_where" {{$quotation->id == old('payment_kind') ||  $quotation->payment_kind == "else_where"? 'selected':''}}>Else Where</option>
                                </select>
                                @error('payment_kind')
                                <div style="color:red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div id="elseWhereSelect" style="display: none;"  class="form-group col-md-3">
                                <label>Payment Location<span class="text-warning"> * </span></label>
                                <select class="selectpicker form-control" id="payment_location" data-live-search="true" name="payment_location" data-size="10"
                                title="{{trans('forms.select')}}">
                                    @foreach ($paymentLocation as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('payment_location') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('payment_location')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <h4>Export Price</h4>
                            <table id="quotationTriffDischarge" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Equipment Type</th>
                                        <th>Currency</th>
                                        <th>OFR</th>
                                        <th>
                                            <a id="adddis"> Add CHARGE <i class="fas fa-plus"></i></a>
                                        </th>
                                    </tr>
                                </thead>

                                <tbody>
                                @foreach($quotation->quotationDesc as $key=>$desc)
                                    <tr id="quotationTriffDischargeRow">
                                            <input type="hidden" value ="{{ $desc->id }}" name="quotationDis[{{ $key }}][id]">                                      
                                        <td>
                                            <select class="selectpicker form-control" id="equipments_type" data-live-search="true" name="quotationDis[{{$key}}][equipment_type_id]"
                                            data-size="10" title="{{trans('forms.select')}}">
                                                @foreach ($equipment_types as $item)
                                                    <option value="{{$item->id}}" {{$item->id == old('equipments_type') || $item->id == $desc->equipment_type_id ? 'selected':'disabled'}}>{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                            @error('equipments_type')
                                            <div style="color:red;">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </td>
                                        <td>
                                            <select class="selectpicker form-control" id="currency" data-live-search="true" name="quotationDis[{{$key}}][currency]" data-size="10"
                                            title="{{trans('forms.select')}}" autofocus>
                                                @foreach ($currency as $item)
                                                    <option value="{{$item->name}}" {{$item->name == old('currency') || $item->name == $desc->currency? 'selected':'disabled'}}>{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" id="dayes" name="quotationDis[{{$key}}][ofr]" class="form-control" autocomplete="off" value="{{old('ofr',$desc->ofr)}}" readonly>
                                        </td>
                                        <td style="width:85px;">
                                            <button type="button" class="btn btn-danger remove" onclick="removeDesc({{$desc->id}})">
                                                <i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-primary mt-3">{{trans('forms.edit')}}</button>
                                    <a href="{{route('quotations.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                                </div>
                           </div>
                           <input name="removedDesc" id="removedDesc" type="hidden"  value="">
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    $(document).ready(function () {
        $('.selectpicker').selectpicker();

        $('#payment_kind').change(function () {
            if ($(this).val() === 'else_where') {
                $('#elseWhereSelect').show();
            } else {
                $('#elseWhereSelect').hide();
            }
        });

        $('#vessel_name').change(function () {
            if ($('#Principal').val() !== $('#vessel_name').val()) {
                $('#additionalSelect').show();
            } else {
                $('#additionalSelect').hide();
            }
        });

        function fetchAndPopulatePorts(countryId, targetSelectors, oldValues = {}) {
            $.get(`/api/master/ports/${countryId}`).then(function (data) {
                let ports = data.ports || '';
                let options = `<option value=''>Select...</option>`;
                for (let i = 0; i < ports.length; i++) {
                    let selected = ports[i].id == oldValues[targetSelectors[i]] ? 'selected' : '';
                    options += `<option value='${ports[i].id}' ${selected}>${ports[i].name}</option>`;
                }
                targetSelectors.forEach((selector, index) => {
                    $(selector).html(options).selectpicker('refresh');
                });
            }).fail(function (xhr, status, error) {
                console.error('Error fetching ports:', status, error);
            });
        }

        let oldValues = {
            '#place_of_acceptence_id': "{{ old('place_of_acceptence_id', $quotation->place_of_acceptence_id) }}",
            '#load_port_id': "{{ old('load_port_id', $quotation->load_port_id) }}",
            '#pick_up_location': "{{ old('pick_up_location', $quotation->pick_up_location) }}",
            '#place_of_delivery_id': "{{ old('place_of_delivery_id', $quotation->place_of_delivery_id) }}",
            '#discharge_port_id': "{{ old('discharge_port_id', $quotation->discharge_port_id) }}",
            '#place_return_id': "{{ old('place_return_id', $quotation->place_return_id) }}"
        };
        let countryDis = $('#countryDis');
            if (countryDis.val()) {
                fetchAndPopulatePorts(countryDis.val(), ['#place_of_acceptence_id', '#load_port_id', '#pick_up_location'], oldValues);
            }

            countryDis.on('change', function (e) {
                fetchAndPopulatePorts(e.target.value, ['#place_of_acceptence_id', '#load_port_id', '#pick_up_location']);
            });

            let country = $('#country');
            if (country.val()) {
                fetchAndPopulatePorts(country.val(), ['#place_of_delivery_id', '#discharge_port_id', '#place_return_id'], oldValues);
            }

            country.on('change', function (e) {
                fetchAndPopulatePorts(e.target.value, ['#place_of_delivery_id', '#discharge_port_id', '#place_return_id']);
            });

        let exportCount = 1;
        $("#adddis").click(function () {
            var tr = '<tr>' +
                '<td><select class="selectpicker form-control" data-live-search="true" name="quotationDis[' + exportCount + '][equipment_type_id]" data-size="10"><option>Select</option>@foreach ($equipment_types as $item)<option value="{{$item->id}}">{{$item->name}}</option>@endforeach</select></td>' +
                '<td><select class="selectpicker form-control" data-live-search="true" name="quotationDis[' + exportCount + '][currency]" data-size="10"><option>Select</option>@foreach ($currency as $item)<option value="{{$item->name}}">{{$item->name}}</option>@endforeach</select></td>' +
                '<td><input type="text" name="quotationDis[' + exportCount + '][ofr]" class="form-control" autocomplete="off" required></td>' +
                '<td style="width:85px;"><button type="button" class="btn btn-danger remove"><i class="fa fa-trash"></i></button></td>' +
                '</tr>';
            $('#quotationTriffDischarge tbody').append(tr);
            $('.selectpicker').selectpicker('refresh');
            exportCount++;
        });

        $("#quotationTriffDischarge").on("click", ".remove", function () {
            $(this).closest("tr").remove();
        });

        $(document).ready(function () {
        function fetchAndPopulateAgents(countryId, targetSelector, oldValue = '') {
            $.get(`/api/agent/agentCountry/${countryId}`).then(function (data) {
                let agents = data.agents || '';
                let options = [`<option value=''>Select...</option>`];
                for (let i = 0; i < agents.length; i++) {
                    let selected = agents[i].id == oldValue ? 'selected' : '';
                    options.push(`<option value='${agents[i].id}' ${selected}>${agents[i].name}</option>`);
                }
                $(targetSelector).html(options.join('')).selectpicker('refresh');
            }).fail(function (xhr, status, error) {
                console.error('Error fetching agents:', status, error);
            });
        }

        let exportCountry = $('#countryDis');  // Renamed from 'country'
        let importCountry = $('#country');     // Kept original name for clarity

        let oldAgentLoadValue = "{{ old('agent_id', $quotation->agent_id) }}";
        let oldAgentDisValue = "{{ old('discharge_agent_id', $quotation->discharge_agent_id) }}";

        // Load agents on page load if country value is present
        if (exportCountry.val()) {
            fetchAndPopulateAgents(exportCountry.val(), '#agentDis', oldAgentDisValue);
        }

        if (importCountry.val()) {
            fetchAndPopulateAgents(importCountry.val(), '#agentload', oldAgentLoadValue);
        }

        // Load agents on country change
        exportCountry.on('change', function (e) {
            fetchAndPopulateAgents(e.target.value, '#agentDis');
        });

        importCountry.on('change', function (e) {
            fetchAndPopulateAgents(e.target.value, '#agentload');
        });
    });

    });
</script>
@endpush

