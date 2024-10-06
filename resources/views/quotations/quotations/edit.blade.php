@extends('layouts.app')
@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-one">
                    <div class="widget-heading">
                        <nav class="breadcrumb-two" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('quotations.index')}}">Quotations</a></li>
                                <li class="breadcrumb-item active"><a href="javascript:void(0);">Edit Quotation</a></li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                    </div>
                    <div class="widget-content widget-content-area">
                        <form novalidate id="createForm" action="{{route('quotations.update',['quotation'=>$quotation])}}" method="POST">
                            @csrf
                            @method('put')
                            <div class="form-row">
                                <!-- <div class="form-group col-md-3">
                                    <label>Ref No<span class="text-warning"> * </span></label>
                                    <input type="text" class="form-control" id="ref_no" name="ref_no" placeholder="Ref No" value="{{old('ref_no',$quotation->ref_no)}}" autocomplete="off" required>
                                    @error('ref_no')
                                    <div style="color: red;">{{$message}}</div>
                                    @enderror
                                </div> -->
                                <input type="hidden" class="form-control" id="ref_no" name="ref_no" placeholder="Ref No" value="{{old('ref_no',$quotation->ref_no)}}" autocomplete="off" >
                                <div class="form-group col-md-4">
                                    <label for="quotation_type">Quotation Type <span class="text-warning"> * </span></label>
                                    <select class="selectpicker form-control" data-live-search="true" name="quotation_type" title="{{trans('forms.select')}}">
                                        <option value="full" {{$quotation->quotation_type == "full" ? 'selected':''}}>Full</option>
                                        <option value="empty" {{$quotation->quotation_type == "empty" ? 'selected':''}}>Empty</option>
                                    </select>
                                    @error('quotation_type')
                                    <div style="color:red;">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="validity_from">Validity From <span class="text-warning"> * </span></label>
                                    <input type="date" class="form-control" id="validity_from" name="validity_from" value="{{old('validity_from', $quotation->validity_from)}}" autocomplete="off" required>
                                    @error('validity_from')
                                    <div style="color:red;">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="validity_to">Validity To <span class="text-warning"> * </span></label>
                                    <input type="date" class="form-control" id="validity_to" name="validity_to" value="{{old('validity_to', $quotation->validity_to)}}" autocomplete="off" required>
                                    @error('validity_to')
                                    <div style="color:red;">{{$message}}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label for="countrydis">Export Country <span class="text-warning"> * </span></label>
                                    <select class="selectpicker form-control" id="countryDis" name="countrydis" data-live-search="true" data-size="10" title="{{trans('forms.select')}}" required>
                                        @foreach ($country as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('countrydis', $quotation->countrydis) ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('countrydis')
                                    <div style="color:red;">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="discharge_agent_id">Export Agent <span class="text-warning"> * </span></label>
                                    <select class="selectpicker form-control" id="agentDis" data-live-search="true" name="discharge_agent_id" data-size="10" required>
                                        @foreach ($agents as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('discharge_agent_id', $quotation->discharge_agent_id) ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('discharge_agent_id')
                                    <div style="color:red;">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="countryload">Import Country <span class="text-warning"> * </span></label>
                                    <select class="selectpicker form-control" id="country" name="countryload" data-live-search="true" data-size="10" title="{{trans('forms.select')}}" required>
                                        @foreach ($country as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('countryload', $quotation->countryload) ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('countryload')
                                    <div style="color:red;">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="agent_id">Import Agent </label>
                                    <select class="selectpicker form-control" id="agentload" data-live-search="true" name="agent_id" data-size="10">
                                        @foreach ($agents as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('agent_id', $quotation->agent_id) ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('agent_id')
                                    <div style="color:red;">{{$message}}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="principal_name">Principal Name</label>
                                    <select class="selectpicker form-control" id="Principal" data-live-search="true" name="principal_name" data-size="10" title="{{trans('forms.select')}}">
                                        @foreach ($principals as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('principal_name', $quotation->principal_name) ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('principal_name')
                                    <div style="color:red;">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="vessel_name">Operator</label>
                                    <select class="selectpicker form-control" id="vessel_name" data-live-search="true" name="vessel_name" data-size="10" title="{{trans('forms.select')}}">
                                        @foreach ($operators as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('vessel_name', $quotation->vessel_name) ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('vessel_name')
                                    <div style="color:red;">{{$message}}</div>
                                    @enderror
                                </div>
                            </div>
                            @if($quotation->operator_frieght_payment == null && $quotation->shipment_type == 'Export')
                            <div class="form-row" id="additionalSelect" style="display: none;">
                                <div class="form-group col-md-6">
                                    <label for="operator_frieght_payment">Vessel Operator Freight Payment <span class="text-warning"> * </span></label>
                                    <select class="selectpicker form-control" data-live-search="true" name="operator_frieght_payment" id="operator_frieght_payment">
                                        <option value="agency" {{$quotation->operator_frieght_payment == "agency" ? 'selected':''}}>Agency (prepaid)</option>
                                        <option value="liner" {{$quotation->operator_frieght_payment == "liner" ? 'selected':''}}>Liner (Collect)</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Slot Rate</label>
                                    <input type="text" class="form-control" id="slot_rate" name="slot_rate" value="{{old('slot_rate',$quotation->slot_rate)}}" placeholder="Slot Rate" autocomplete="off">
                                </div>
                            </div>
                            @elseif($quotation->shipment_type == 'Export')
                            <div class="form-row" id="additionalSelect">
                                <div class="form-group col-md-6">
                                    <label for="operator_frieght_payment">Vessel Operator Freight Payment <span class="text-warning"> * </span></label>
                                    <select class="selectpicker form-control" data-live-search="true" name="operator_frieght_payment" id="operator_frieght_payment">
                                        <option value="agency" {{$quotation->operator_frieght_payment == "agency" ? 'selected':''}}>Agency (prepaid)</option>
                                        <option value="liner" {{$quotation->operator_frieght_payment == "liner" ? 'selected':''}}>Liner (Collect)</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Slot Rate</label>
                                    <input type="text" class="form-control" id="slot_rate" name="slot_rate" value="{{old('slot_rate',$quotation->slot_rate)}}" placeholder="Slot Rate" autocomplete="off">
                                </div>
                            </div>
                            @endif
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="customer_id">Agreement Party <span class="text-warning"> * </span></label>
                                    <select class="selectpicker form-control" id="customer_id" data-live-search="true" name="customer_id" data-size="10" title="{{trans('forms.select')}}" required>
                                        @foreach ($customers as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('customer_id', $quotation->customer_id) ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('customer_id')
                                    <div style="color:red;">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="ffw_id">Forwarder</label>
                                    <select class="selectpicker form-control" id="ffw_id" data-live-search="true" name="ffw_id" data-size="10" title="{{trans('forms.select')}}">
                                        @foreach ($ffw as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('ffw_id', $quotation->ffw_id) ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('ffw_id')
                                    <div style="color:red;">{{$message}}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row">
                            @if($quotation->shipment_type == 'Import')
                                <div class="form-group col-md-4">
                                    <label for="place_of_delivery_id">Place Of Delivery</label>
                                    <select class="selectpicker form-control importPort" id="place_of_delivery_id" data-live-search="true" name="place_of_delivery_id" data-size="10" title="{{trans('forms.select')}}">
                                        @foreach ($ports as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('place_of_delivery_id', $quotation->place_of_delivery_id) ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('place_of_delivery_id')
                                    <div style="color:red;">{{$message}}</div>
                                    @enderror
                                </div>
                            @else
                                <div class="form-group col-md-4">
                                    <label for="place_of_acceptence_id">Place Of Acceptance</label>
                                    <select class="selectpicker form-control port" id="place_of_acceptence_id" data-live-search="true" name="place_of_acceptence_id" data-size="10" title="{{trans('forms.select')}}">
                                        @foreach ($ports as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('place_of_acceptence_id', $quotation->place_of_acceptence_id) ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('place_of_acceptence_id')
                                    <div style="color:red;">{{$message}}</div>
                                    @enderror
                                </div>
                            @endif
                                <div class="form-group col-md-4">
                                    <label for="load_port_id">Load Port <span class="text-warning"> * </span></label>
                                    <select class="selectpicker form-control port" id="load_port_id" data-live-search="true" name="load_port_id" data-size="10" title="{{trans('forms.select')}}" required>
                                        @foreach ($ports as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('load_port_id', $quotation->load_port_id) ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('load_port_id')
                                    <div style="color:red;">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="discharge_port_id">Discharge Port <span class="text-warning"> * </span></label>
                                    <select class="selectpicker form-control importPort" id="discharge_port_id" data-live-search="true" name="discharge_port_id" data-size="10" title="{{trans('forms.select')}}" required>
                                        @foreach ($ports as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('discharge_port_id', $quotation->discharge_port_id) ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('discharge_port_id')
                                    <div style="color:red;">{{$message}}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label for="oog_dimensions">HAZ / Reefer/ OOG Details</label>
                                    <input type="text" class="form-control" id="oog_dimensions" name="oog_dimensions" value="{{old('oog_dimensions', $quotation->oog_dimensions)}}" placeholder="HAZ / Reefer/ OOG Details / Haz Approval Ref" autocomplete="off">
                                    @error('oog_dimensions')
                                    <div style="color:red;">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="commodity_code">Commodity Code</label>
                                    <input type="text" class="form-control" id="commodity_code" name="commodity_code" value="{{old('commodity_code', $quotation->commodity_code)}}" placeholder="Commodity Code" autocomplete="off">
                                    @error('commodity_code')
                                    <div style="color:red;">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="commodity_des">Commodity Description <span class="text-warning"> * </span></label>
                                    <input type="text" class="form-control" id="commodity_des" name="commodity_des" value="{{old('commodity_des', $quotation->commodity_des)}}" placeholder="Commodity Description" autocomplete="off" required>
                                    @error('commodity_des')
                                    <div style="color:red;">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="payment_kind">Freight Payment <span class="text-warning"> * </span></label>
                                    <select class="selectpicker form-control" data-live-search="true" name="payment_kind" id="payment_kind" title="{{trans('forms.select')}}" required>
                                        <option value="Prepaid" {{$quotation->payment_kind == "Prepaid" ? 'selected':''}}>Prepaid</option>
                                        <option value="Collect" {{$quotation->payment_kind == "Collect" ? 'selected':''}}>Collect</option>
                                        <option value="else_where" {{$quotation->payment_kind == "else_where" ? 'selected':''}}>Elsewhere</option>
                                    </select>
                                    @error('payment_kind')
                                    <div style="color:red;">{{$message}}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row" id="elseWhereSelect" style="display: none;">
                                <div class="form-group col-md-3">
                                    <label>Triff kind<span class="text-warning"> * </span></label>
                                    <select class="selectpicker form-control" id="triff_kind" data-live-search="true" name="tariff_type" data-size="10" title="{{trans('forms.select')}}" autofocus>
                                        @foreach ($triffs as $item)
                                            <option value="{{$item->name}}" {{$item->name == old('tariff_type',$quotation->tariff_type) ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="payment_location">Payment Location</label>
                                    <select class="selectpicker form-control" id="payment_location" data-live-search="true" name="payment_location" data-size="10" title="{{trans('forms.select')}}">
                                        @foreach ($paymentLocation as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('payment_location', $quotation->payment_location) ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('payment_location')
                                    <div style="color:red;">{{$message}}</div>
                                    @enderror
                                </div>
                            </div>
                            <table id="ofr" class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Request Type</th>
                                    <th>Equipment Type</th>
                                    <th>Currency</th>
                                    <th>OFR</th>
                                    <th>Free Time</th>
                                    <th>THC Term</th>
                                    <th>Special Equipment</th>
                                    <th>
                                        <a id="adddis"> Add <i class="fas fa-plus"></i></a>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($quotation->quotationDesc as $key => $desc)
                                    <tr id="quotationTriffDischargeRow">
                                        <input type="hidden" value="{{ $desc->id }}" name="quotationDis[{{ $key }}][id]">
                                        <td>
                                            <select class="selectpicker form-control" data-live-search="true" name="quotationDis[{{$key}}][request_type]" title="{{trans('forms.select')}}" required>
                                                <option value="Dry" {{$desc->request_type == "Dry" ? 'selected':''}}>Dry</option>
                                                <option value="Reefer" {{$desc->request_type == "Reefer" ? 'selected':''}}>Reefer</option>
                                                <option value="Special Equipment" {{$desc->request_type == "Special Equipment" ? 'selected':''}}>Special Equipment</option>
                                            </select>
                                        </td>  
                                        <td>
                                            <select class="selectpicker form-control equipment-type" id="equipments_type" data-live-search="true" name="quotationDis[{{ $key }}][equipment_type_id]" data-size="10" title="{{trans('forms.select')}}" readonly>
                                                @foreach ($equipment_types as $item)
                                                    <option value="{{$item->id}}" {{$item->id == old('equipment_type_id', $desc->equipment_type_id) ? 'selected':''}}>{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                            @error('equipment_type_id')
                                            <div style="color:red;">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </td>
                                        <td>
                                            <select class="selectpicker form-control" id="currency" data-live-search="true" name="quotationDis[{{ $key }}][currency]" data-size="10" title="{{trans('forms.select')}}">
                                                @foreach ($currency as $item)
                                                    <option value="{{$item->name}}" {{$item->name == old('currency', $desc->currency) ? 'selected':''}}>{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" id="dayes" name="quotationDis[{{$key}}][ofr]" class="form-control" autocomplete="off" value="{{old('ofr',$desc->ofr)}}" readonly>
                                        </td>
                                        <td>
                                            <input type="text" id="dayes" name="quotationDis[{{$key}}][free_time]" placeholder="Free Time"  class="form-control" autocomplete="off" value="{{old('free_time',$desc->free_time)}}"  required>
                                        </td>
                                        <td>
                                        <select class="selectpicker form-control" data-live-search="true" name="quotationDis[{{$key}}][thc_payment]" id="payment_kind" title="{{trans('forms.select')}}" required>
                                            <option value="pod" {{$desc->thc_payment == "pod" ? 'selected':''}}>POD</option>
                                            <option value="pol" {{$desc->thc_payment == "pol" ? 'selected':''}}>POL</option>
                                        </select>
                                        </td>  
                                        <td>
                                            <div class="checkbox-group d-flex flex-row">
                                                <div style="display: inline-block; width: 50%;" class="mr-3">
                                                    <div style="margin-bottom: 5px;">
                                                        <label style="margin-right: 10px; width: 25px; display: inline-block;">SOC</label>
                                                        <input type="checkbox" id="soc" name="quotationDis[{{$key}}][soc]" value="1" {{$desc->soc == 1 ? 'checked="checked"' : '' }} disabled>
                                                    </div>
                                                    <div style="margin-bottom: 5px;">
                                                        <label style="margin-right: 10px; width: 25px; display: inline-block;">IMO</label>
                                                        <input type="checkbox" value="1" name="quotationDis[{{$key}}][imo]" {{$desc->imo == 1 ? 'checked="checked"' : '' }} disabled>
                                                    </div>
                                                    <div>
                                                        <label style="margin-right: 10px; width: 25px; display: inline-block;">OOG</label>
                                                        <input type="checkbox" value="1" name="quotationDis[{{$key}}][oog]" {{$desc->oog == 1 ? 'checked="checked"' : '' }} disabled>
                                                    </div>
                                                </div>
                                                <div style="display: inline-block; width: 50%;">
                                                    <div style="margin-bottom: 5px;">
                                                        <label style="margin-right: 10px; width: 25px; display: inline-block;">RF</label>
                                                        <input type="checkbox" value="1" name="quotationDis[{{$key}}][rf]" {{$desc->rf == 1 ? 'checked="checked"' : '' }} disabled>
                                                    </div>
                                                    <div>
                                                        <label style="margin-right: 10px; width: 25px; display: inline-block;">NOR</label>
                                                        <input type="checkbox" value="1" name="quotationDis[{{$key}}][nor]" {{$desc->nor == 1 ? 'checked="checked"' : '' }} disabled>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>                                         

                                        <td style="width:85px;">
                                            <button type="button" class="btn btn-danger remove"><i class="fa fa-trash"></i></button>
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
                            <input name="removedDesc" id="removedDesc" type="hidden" value="">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script>
    $('#vessel_name').change(function () {
        if ($('#Principal').val() !== $('#vessel_name').val()) {
            $('#additionalSelect').show();
            $('#operator_frieght_payment').attr('required', true); // Make the select required
        } else {
            $('#additionalSelect').hide();
        }
    });

    $('#operator_frieght_payment').change(function () {
        if ($(this).val() === 'agency') {
            $('#slot_rate').attr('required', true).closest('.form-group').show();
        } else if ($(this).val() === 'liner') {
            $('#slot_rate').attr('required', false).val('').closest('.form-group').hide();
        }
    });
</script>
<script>
  $(document).ready(function () {
    // Initialize selectpicker
    $('.selectpicker').selectpicker();

    // Handle changes for the payment_kind select element
    $('#payment_kind').change(function () {
        if ($(this).val() === 'else_where') {
            $('#elseWhereSelect').show();
        } else {
            $('#elseWhereSelect').hide();
        }
    });

    // Function to fetch and populate ports based on selected country
    function fetchAndPopulatePorts(countryId, targetSelectors) {
        $.get(`/api/master/ports/${countryId}`).then(function (data) {
            let ports = data.ports || '';
            let options = `<option value=''>Select...</option>`;
            for (let i = 0; i < ports.length; i++) {
                options += `<option value='${ports[i].id}'>${ports[i].name}</option>`;
            }
            targetSelectors.forEach(selector => {
                $(selector).html(options).selectpicker('refresh');
            });
        }).fail(function (xhr, status, error) {
            console.error('Error fetching ports:', status, error);
        });
    }

    let countryDis = $('#countryDis');
        countryDis.on('change', function (e) {
            fetchAndPopulatePorts(e.target.value, ['#place_of_acceptence_id', '#load_port_id', '#pick_up_location']);
        });

        let country = $('#country');
        country.on('change', function (e) {
            fetchAndPopulatePorts(e.target.value, ['#place_of_delivery_id', '#discharge_port_id', '#place_return_id']);
        });
        
    let exportCount = $('#ofr tbody tr').length;

    // Add new row to the table

        $("#adddis").click(function () {
        var tr = '<tr>' +
            '<td><select class="selectpicker form-control" id="requesttype" data-live-search="true" name="quotationDis[' + exportCount + '][request_type]" data-size="10" title="{{trans('forms.select')}}" required><option value="Dry">Dry</option><option value="Reefer">Reefer</option><option value="Special Equipment">Special Equipment</option></select></td>' +
            '<td id="equpmints"><select class="selectpicker form-control equipment-type" data-live-search="true" name="quotationDis[' + exportCount + '][equipment_type_id]" data-size="10" title="{{trans('forms.select')}}" required disabled>@foreach ($equipment_types as $item)<option value="{{$item->id}}" {{$item->id == old('equipment_type_id') ? 'selected':''}}>{{$item->name}}</option>@endforeach </select></td>' +
            '<td><select class="selectpicker form-control" data-live-search="true" name="quotationDis[' + exportCount + '][currency]" data-size="10" title="{{trans('forms.select')}}">@foreach ($currency as $item)<option value="{{$item->name}}" {{ (old('currency') == $item->id || $item->id == 1) ? 'selected' : '' }}>{{$item->name}}</option>@endforeach</select></td>' +
            '<td><input type="text" name="quotationDis[' + exportCount + '][ofr]" class="form-control" autocomplete="off" placeholder="OFR" required></td>' +
            '<td><input type="text" name="quotationDis[' + exportCount + '][free_time]" class="form-control" autocomplete="off" placeholder="Free Time" required></td>' +
            '<td><select class="selectpicker form-control" data-live-search="true" name="quotationDis[' + exportCount + '][thc_payment]" data-size="10" title="{{trans('forms.select')}}" required><option value="pod">POD</option><option value="pol">POL</option></select></td>' +
            '<td>' +
            '<div class="checkbox-group d-flex flex-row">' +
            '<div style="display: inline-block; width: 50%;" class="mr-3">' +
            '<div style="margin-bottom: 5px;">' +
            '<label style="margin-right: 10px; width: 25px; display: inline-block;">SOC</label>' +
            '<input type="checkbox" value="1" name="quotationDis[' + exportCount + '][soc]" disabled>' +
            '</div>' +
            '<div style="margin-bottom: 5px;">' +
            '<label style="margin-right: 10px; width: 25px; display: inline-block;">IMO</label>' +
            '<input type="checkbox" value="1" name="quotationDis[' + exportCount + '][imo]" disabled>' +
            '</div>' +
            '<div>' +
            '<label style="margin-right: 10px; width: 25px; display: inline-block;">OOG</label>' +
            '<input type="checkbox" value="1" name="quotationDis[' + exportCount + '][oog]" disabled>' +
            '</div>' +
            '</div>' +
            '<div style="display: inline-block; width: 50%;">' +
            '<div style="margin-bottom: 5px;">' +
            '<label style="margin-right: 10px; width: 25px; display: inline-block;">RF</label>' +
            '<input type="checkbox" value="1" name="quotationDis[' + exportCount + '][rf]" disabled>' +
            '</div>' +
            '<div>' +
            '<label style="margin-right: 10px; width: 25px; display: inline-block;">NOR</label>' +
            '<input type="checkbox" value="1" name="quotationDis[' + exportCount + '][nor]" disabled>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</td>' +
            '<td style="width:85px;"><button type="button" class="btn btn-danger remove"><i class="fa fa-trash"></i></button></td>' +
            '</tr>';
        $('#ofr tbody').append(tr);
        $('.selectpicker').selectpicker('refresh');
        exportCount++;
        updateEquipmentOptions();
    });


    // Delegate the remove button event to a static parent element
    $(document).on('click', '.remove', function () {
        let row = $(this).closest('tr');
        let rowId = row.find('input[type="hidden"]').val();

        if (rowId) {
            // Append the row ID to the removedDesc hidden input
            let removedDesc = $('#removedDesc').val();
            if (removedDesc) {
                removedDesc += ',' + rowId;
            } else {
                removedDesc = rowId;
            }
            $('#removedDesc').val(removedDesc);
        }

        row.remove();
        updateEquipmentOptions();
    });
    // Function to fetch and update equipment options based on request type
    function fetchAndPopulateEquipmentOptions(requestType, row) {
        $.get(`/api/master/requesttype/${requestType}`).then(function (data) {
            let equipmentTypes = data.request_Type || '';
            let options = `<option value=''>Select...</option>`;
            for (let i = 0; i < equipmentTypes.length; i++) {
                options += `<option value='${equipmentTypes[i].id}'>${equipmentTypes[i].name}</option>`;
            }
            row.find('select[name^="quotationDis"][name$="[equipment_type_id]"]').html(options).selectpicker('refresh');

            // Reapply equipment options filtering after updating options
            updateEquipmentOptions();
        }).fail(function (xhr, status, error) {
            console.error('Error fetching equipment types:', status, error);
        });
    }

    // Handle changes in request type for dynamically added rows
    $(document).on('change', 'select[name^="quotationDis"][name$="[request_type]"]', function () {
        const row = $(this).closest('tr');
        let selectedRequestType = $(this).val();

        // Enable the equipment type select
        row.find('select[name^="quotationDis"][name$="[equipment_type_id]"]').prop('disabled', false);
        row.find('input[type="checkbox"]').prop('disabled', false);
        fetchAndPopulateEquipmentOptions(selectedRequestType, row);

        handleCheckboxDisabling(row, selectedRequestType);
    });

    // Function to disable/enable checkboxes based on request type
    function handleCheckboxDisabling(row, requestType) {
        const checkboxes = {
            oog: row.find('input[name^="quotationDis"][name$="[oog]"]'),
            rf: row.find('input[name^="quotationDis"][name$="[rf]"]'),
            nor: row.find('input[name^="quotationDis"][name$="[nor]"]')
        };

        // Enable and uncheck all checkboxes
        Object.values(checkboxes).forEach(checkbox => checkbox.prop('disabled', false).prop('checked', false));

        // Disable specific checkboxes based on the request type
        const disableActions = {
            'Dry': ['oog', 'rf', 'nor'],
            'Reefer': ['oog'],
            'Special Equipment': ['nor', 'rf']
        };

        if (disableActions[requestType]) {
            disableActions[requestType].forEach(type => checkboxes[type].prop('disabled', true));
        }
    }

    // Update equipment options to avoid duplicates
    function updateEquipmentOptions() {
        let selectedOptions = [];
        $('.equipment-type').each(function () {
            selectedOptions.push($(this).val());
        });

        $('.equipment-type').each(function () {
            let currentSelect = $(this);
            currentSelect.find('option').each(function () {
                let optionValue = $(this).val();
                if (selectedOptions.includes(optionValue) && optionValue !== currentSelect.val()) {
                    $(this).hide();
                } else {
                    $(this).show();
                }
            });
            currentSelect.selectpicker('refresh');
        });
    }
    
    $(document).on('change', '.equipment-type', function () {
        updateEquipmentOptions();
    });

    // Fetch and update agents when country changes
    country.on('change', function (e) {
        let value = e.target.value;
        $.get(`/api/agent/agentCountry/${value}`).then(function (data) {
            let agents = data.agents || '';
            let options = [`<option value=''>Select...</option>`];
            for (let i = 0; i < agents.length; i++) {
                options.push(`<option value='${agents[i].id}'>${agents[i].name}</option>`);
            }
            $('#agentload').html(options.join('')).selectpicker('refresh');
        });
    });

    countryDis.on('change', function (e) {
        let value = e.target.value;
        $.get(`/api/agent/agentCountry/${value}`).then(function (data) {
            let agents = data.agents || '';
            let options = [`<option value=''>Select...</option>`];
            for (let i = 0; i < agents.length; i++) {
                options.push(`<option value='${agents[i].id}'>${agents[i].name}</option>`);
            }
            $('#agentDis').html(options.join('')).selectpicker('refresh');
        });
    });

    // Call the function on page load to handle the initial state
    updateEquipmentOptions();
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

