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
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">Create New Quotation</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                    <form novalidate id="createForm" action="{{route('quotations.store')}}" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label> Ref No <span class="text-warning"> *</span></label>
                                <input type="text" class="form-control" name="ref_no" value="{{old('ref_no')}}" autocomplete="off" placeholder="Ref No" required>
                                @error('ref_no')
                                <div style="color:red;">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="quotation_type">Quotation Type<span class="text-warning"> *</span></label>
                                <select class="selectpicker form-control" data-live-search="true" name="quotation_type" title="{{trans('forms.select')}}" required>
                                    <option value="full">Full</option>
                                    <option value="empty">Empty</option>
                                </select>
                                @error('quotation_type')
                                <div style="color:red;">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="transportation_mode">Transportation Mode<span class="text-warning"> *</span></label>
                                <select class="selectpicker form-control" data-live-search="true" name="transportation_mode" title="{{trans('forms.select')}}" required>
                                    <option value="vessel">Vessel</option>
                                    <option value="trucker">Trucker</option>
                                    <option value="train">Train</option>
                                </select>
                                @error('transportation_mode')
                                <div style="color:red;">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="booking_agency">Booking Agency<span class="text-warning"> *</span></label>
                                <select class="selectpicker form-control" data-live-search="true" name="booking_agency" title="{{trans('forms.select')}}" required>
                                    @foreach ($booking_agency as $item)
                                    <option value="{{$item->id}}" {{ $item->id == old('booking_agency', Auth::user()->agent_id) ? 'selected':'' }}>
                                        {{$item->name}}
                                    </option>
                                    @endforeach
                                </select>
                                @error('booking_agency')
                                <div style="color:red;">{{$message}}</div>
                                @enderror
                            </div>
                            <!-- <div class="form-group col-md-4">
                                <label for="agency_bookingr_ref">Payment As Per Agreement <span class="text-warning"> *</span></label>
                                <select class="selectpicker form-control" data-live-search="true" name="agency_bookingr_ref" title="{{trans('forms.select')}}" required>
                                    <option value="EXW">EXW</option>
                                    <option value="FCA">FCA</option>
                                    <option value="FOB">FOB</option>
                                    <option value="CIF">CIF</option>
                                    <option value="CPT">CPT</option>
                                </select>
                            </div> -->
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="countrydis">Export Country <span class="text-warning"> *</span></label>
                                <select class="selectpicker form-control" id="countryDis" name="countrydis" data-live-search="true" data-size="10" title="{{trans('forms.select')}}" required>
                                    @foreach ($country as $item)
                                    <option value="{{$item->id}}" {{$item->id == old('countrydis') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('countrydis')
                                <div style="color:red;">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="discharge_agent_id">Export Agent <span class="text-warning"> *</span></label>
                                <select class="selectpicker form-control" id="agentDis" data-live-search="true" name="discharge_agent_id" data-size="10" required>
                                    <option value="">Select...</option>
                                    @foreach ($agents as $item)
                                    <option value="{{$item->id}}" {{$item->id == old('discharge_agent_id') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('discharge_agent_id')
                                <div style="color:red;">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="countryload">Import Country <span class="text-warning"> *</span></label>
                                <select class="selectpicker form-control" id="country" name="countryload" data-live-search="true" data-size="10" title="{{trans('forms.select')}}" required>
                                    @foreach ($country as $item)
                                    <option value="{{$item->id}}" {{$item->id == old('countryload') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('countryload')
                                <div style="color:red;">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="agent_id">Import Agent <span class="text-warning"> *</span></label>
                                <select class="selectpicker form-control" id="agentload" data-live-search="true" name="agent_id" data-size="10" required>
                                    <option value="">Select...</option>
                                    @foreach ($agents as $item)
                                    <option value="{{$item->id}}" {{$item->id == old('agent_id') ? 'selected':''}}>{{$item->name}}</option>
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
                                    <option value="{{$item->id}}" {{$item->id == old('principal_name') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('principal_name')
                                <div style="color:red;">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="vessel_name">Operator</label>
                                <select class="selectpicker form-control" id="vessel_name" data-live-search="true" name="vessel_name" data-size="10" title="{{trans('forms.select')}}">
                                    @foreach ($oprators as $item)
                                    <option value="{{$item->id}}" {{$item->id == old('vessel_name') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('vessel_name')
                                <div style="color:red;">{{$message}}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row" id="additionalSelect" style="display: none;">
                            <div class="form-group col-md-4">
                                <label for="operator_frieght_payment">Vessel Operator Freight Payment <span class="text-warning"> *</span></label>
                                <select class="selectpicker form-control" data-live-search="true" name="operator_frieght_payment">
                                    <option value="agency">Agency (prepaid)</option>
                                    <option value="liner">Liner (Collect)</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="customer_id">Shipper Name <span class="text-warning"> *</span></label>
                                <select class="selectpicker form-control" id="customer_id" data-live-search="true" name="customer_id" data-size="10" title="{{trans('forms.select')}}" required>
                                    @foreach ($customers as $item)
                                    <option value="{{$item->id}}" {{$item->id == old('customer_id') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('customer_id')
                                <div style="color:red;">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="ffw_id">Forwarder Name</label>
                                <select class="selectpicker form-control" id="ffw_id" data-live-search="true" name="ffw_id" data-size="10" title="{{trans('forms.select')}}">
                                    @foreach ($ffw as $item)
                                    <option value="{{$item->id}}" {{$item->id == old('ffw_id') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('ffw_id')
                                <div style="color:red;">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="customer_consignee_id">Consignee Name</label>
                                <select class="selectpicker form-control" id="customer_consignee_id" data-live-search="true" name="customer_consignee_id" data-size="10" title="{{trans('forms.select')}}">
                                    @foreach ($consignee as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('customer_consignee_id') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('customer_consignee_id')
                                <div style="color:red;">{{$message}}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="special_requirements">Special Requirements</label>
                                <div class="form-check">
                                    <input type="checkbox" id="soc" name="soc" value="1"> <label for="soc" class="form-check-label">SOC</label>
                                    <input type="checkbox" id="imo" name="imo" value="1"> <label for="imo" class="form-check-label">IMO</label>
                                    <input type="checkbox" id="oog" name="oog" value="1"> <label for="oog" class="form-check-label">OOG</label>
                                    <input type="checkbox" id="rf" name="rf" value="1"> <label for="rf" class="form-check-label">RF</label>
                                    <input type="checkbox" id="nor" name="nor" value="1"> <label for="nor" class="form-check-label">NOR</label>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="validity_from">Validity From <span class="text-warning"> *</span></label>
                                <input type="date" class="form-control" id="validity_from" name="validity_from" value="{{old('validity_from')}}" autocomplete="off" required>
                                @error('validity_from')
                                <div style="color:red;">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="validity_to">Validity To<span class="text-warning"> *</span></label>
                                <input type="date" class="form-control" id="validity_to" name="validity_to" value="{{old('validity_to')}}" autocomplete="off" required>
                                @error('validity_to')
                                <div style="color:red;">{{$message}}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="place_of_acceptence_id">Place Of Acceptance </label>
                                <select class="selectpicker form-control port" id="place_of_acceptence_id" data-live-search="true" name="place_of_acceptence_id" data-size="10" title="{{trans('forms.select')}}">
                                    @foreach ($ports as $item)
                                    <option value="{{$item->id}}" {{$item->id == old('place_of_acceptence_id') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('place_of_acceptence_id')
                                <div style="color:red;">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="load_port_id">Load Port <span class="text-warning"> *</span></label>
                                <select class="selectpicker form-control port" id="load_port_id" data-live-search="true" name="load_port_id" data-size="10" title="{{trans('forms.select')}}" required>
                                    @foreach ($ports as $item)
                                    <option value="{{$item->id}}" {{$item->id == old('load_port_id') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('load_port_id')
                                <div style="color:red;">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="pick_up_location">Pick Up Location</label>
                                <select class="selectpicker form-control port" id="pick_up_location" data-live-search="true" name="pick_up_location" data-size="10" title="{{trans('forms.select')}}">
                                    @foreach ($ports as $item)
                                    <option value="{{$item->id}}" {{$item->id == old('pick_up_location') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('pick_up_location')
                                <div style="color:red;">{{$message}}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="place_of_delivery_id">Place Of Delivery</label>
                                <select class="selectpicker form-control importPort" id="place_of_delivery_id" data-live-search="true" name="place_of_delivery_id" data-size="10" title="{{trans('forms.select')}}">
                                    @foreach ($ports as $item)
                                    <option value="{{$item->id}}" {{$item->id == old('place_of_delivery_id') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('place_of_delivery_id')
                                <div style="color:red;">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="discharge_port_id">Discharge Port <span class="text-warning"> *</span></label>
                                <select class="selectpicker form-control importPort" id="discharge_port_id" data-live-search="true" name="discharge_port_id" data-size="10" title="{{trans('forms.select')}}" required>
                                    @foreach ($ports as $item)
                                    <option value="{{$item->id}}" {{$item->id == old('discharge_port_id') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('discharge_port_id')
                                <div style="color:red;">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="place_return_id">Place Of Return</label>
                                <select class="selectpicker form-control importPort" id="place_return_id" data-live-search="true" name="place_return_id" data-size="10" title="{{trans('forms.select')}}">
                                    @foreach ($ports as $item)
                                    <option value="{{$item->id}}" {{$item->id == old('place_return_id') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('place_return_id')
                                <div style="color:red;">{{$message}}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="export_detention">Export Free Time</label>
                                <input type="text" class="form-control" id="export_detention" name="export_detention" value="{{old('export_detention')}}" placeholder="Additional Export Detention" autocomplete="off">
                                @error('export_detention')
                                <div style="color:red;">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="import_detention">Import Free Time</label>
                                <input type="text" class="form-control" id="import_detention" name="import_detention" value="{{old('import_detention')}}" placeholder="Additional Import Free Time" autocomplete="off">
                                @error('import_detention')
                                <div style="color:red;">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="power_charges">Power Charges Free Days</label>
                                <input type="text" class="form-control" id="power_charges" name="power_charges" value="{{old('power_charges')}}" placeholder="Power Charges Free Days" autocomplete="off">
                                @error('power_charges')
                                <div style="color:red;">{{$message}}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="oog_dimensions">HAZ / Reefer/ OOG Details</label>
                                <input type="text" class="form-control" id="oog_dimensions" name="oog_dimensions" value="{{old('oog_dimensions')}}" placeholder="HAZ / Reefer/ OOG Details / Haz Approval Ref." autocomplete="off">
                                @error('oog_dimensions')
                                <div style="color:red;">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="commodity_code">Commodity Code</label>
                                <input type="text" class="form-control" id="commodity_code" name="commodity_code" value="{{old('commodity_code')}}" placeholder="Commodity Code" autocomplete="off">
                                @error('commodity_code')
                                <div style="color:red;">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="commodity_des">Commodity Description <span class="text-warning"> *</span></label>
                                <input type="text" class="form-control" id="commodity_des" name="commodity_des" value="{{old('commodity_des')}}" placeholder="Commodity Description" autocomplete="off" required>
                                @error('commodity_des')
                                <div style="color:red;">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="payment_kind">Freight Payment<span class="text-warning"> *</span></label>
                                <select class="selectpicker form-control" data-live-search="true" name="payment_kind" id="payment_kind" title="{{trans('forms.select')}}" required>
                                    <option value="Prepaid">Prepaid</option>
                                    <option value="Collect">Collect</option>
                                    <option value="else_where">Elsewhere</option>
                                </select>
                                @error('payment_kind')
                                <div style="color:red;">{{$message}}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row" id="elseWhereSelect" style="display: none;">
                            <div class="form-group col-md-3">
                                <label for="payment_location">Payment Location</label>
                                <select class="selectpicker form-control" id="payment_location" data-live-search="true" name="payment_location" data-size="10" title="{{trans('forms.select')}}">
                                    @foreach ($paymentLocation as $item)
                                    <option value="{{$item->id}}" {{$item->id == old('payment_location') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('payment_location')
                                <div style="color:red;">{{$message}}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label> Request Type <span class="text-warning"> * </span></label>
                                <select class="selectpicker form-control" id="requesttype" data-live-search="true" name="request_type" data-size="10" title="{{trans('forms.select')}}" required>
                                    <option value="General">General</option>
                                    <option value="Reefer">Reefer</option>
                                    <option value="Special Equipment">Special Equipment</option>
                                </select>
                            </div>
                        </div>

                        <table id="ofr" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Equipment Type</th>
                                    <th>Currency</th>
                                    <th>OFR</th>
                                    <th>
                                        <a id="adddis"> Add Equ <i class="fas fa-plus"></i></a>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td id="equpmints">
                                        <select class="selectpicker form-control" id="equpmint" data-live-search="true" name="quotationDis[0][equipment_type_id]" data-size="10" title="{{trans('forms.select')}}">
                                            <!-- Options will be dynamically loaded here -->
                                        </select>
                                    </td>
                                    <td>
                                        <select class="selectpicker form-control" id="currency" data-live-search="true" name="quotationDis[0][currency]" data-size="10" title="{{trans('forms.select')}}" required>
                                            @foreach ($currency as $item)
                                            <option value="{{$item->name}}" {{$item->id == old('currency') ? 'selected':''}}>{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" id="dayes" name="quotationDis[0][ofr]" class="form-control" autocomplete="off">
                                    </td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary mt-3">{{trans('forms.create')}}</button>
                                <a href="{{route('quotations.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
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
        if (countryDis.val()) {
            fetchAndPopulatePorts(countryDis.val(), ['#place_of_acceptence_id', '#load_port_id', '#pick_up_location']);
        }

        countryDis.on('change', function (e) {
            fetchAndPopulatePorts(e.target.value, ['#place_of_acceptence_id', '#load_port_id', '#pick_up_location']);
        });

        let country = $('#country');
        if (country.val()) {
            fetchAndPopulatePorts(country.val(), ['#place_of_delivery_id', '#discharge_port_id', '#place_return_id']);
        }

        country.on('change', function (e) {
            fetchAndPopulatePorts(e.target.value, ['#place_of_delivery_id', '#discharge_port_id', '#place_return_id']);
        });

        // Function to fetch equipment types based on request type
        function fetchEquipmentTypes(requestType, targetSelect) {
            $.ajax({
                url: 'http://127.0.0.1:8000/api/master/requesttype/' + requestType,
                method: 'GET',
                success: function (data) {
                    targetSelect.empty();
                    targetSelect.append('<option value="">Select...</option>');


                    if (data.hasOwnProperty('request_Type')) {
                        var equipmentTypes = data.request_Type;


                        if (Array.isArray(equipmentTypes)) {
                            equipmentTypes.forEach(function (item) {
                                targetSelect.append('<option value="' + item.name + '">' + item.name + '</option>');
                            });
                        } else {
                            console.error('Unexpected data format for equipment types:', equipmentTypes);
                        }

                        // Refresh the selectpicker to show the new options
                        targetSelect.selectpicker('refresh');
                        updateOptions();
                    } else {
                        console.error('Unexpected data format:', data);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Failed to fetch equipment types:', error);
                }
            });
        }

        // Function to update options in all rows
        function updateOptions() {
            var selectedValues = [];
            $('select[name^="quotationDis"][name$="[equipment_type_id]"]').each(function() {
                var selectedValue = $(this).val();
                if (selectedValue) {
                    selectedValues.push(selectedValue);
                }
            });

            $('select[name^="quotationDis"][name$="[equipment_type_id]"]').each(function() {
                var currentSelect = $(this);
                var currentValue = currentSelect.val();
                currentSelect.find('option').each(function() {
                    var optionValue = $(this).val();
                    if (selectedValues.includes(optionValue) && optionValue !== currentValue) {
                        $(this).remove();
                    } else if (!selectedValues.includes(optionValue) && !currentSelect.find('option[value="' + optionValue + '"]').length) {
                        currentSelect.append(`<option value="${optionValue}">${$(this).text()}</option>`);
                    }
                });
                currentSelect.selectpicker('refresh');
            });
        }

        // Event listener for request type change
        $('#requesttype').on('change', function () {
            var selectedRequestType = $(this).val();
            // Apply changes to all equipment type select fields
            $('select[name^="quotationDis"][name$="[equipment_type_id]"]').each(function() {
                fetchEquipmentTypes(selectedRequestType, $(this));
            });
        });

        // Event listener for equipment type change
        $(document).on('change', 'select[name^="quotationDis"][name$="[equipment_type_id]"]', function () {
            updateOptions();
        });

        let exportCount = 1;
        $("#adddis").click(function () {
            var selectedRequestType = $('#requesttype').val();
            var tr = '<tr>' +
                '<td id="equpmints"><select id="equpmint_' + exportCount + '" class="selectpicker form-control equipment-type" data-live-search="true" name="quotationDis[' + exportCount + '][equipment_type_id]" data-size="10"><option value="">Select...</option></select></td>' +
                '<td><select class="selectpicker form-control" data-live-search="true" name="quotationDis[' + exportCount + '][currency]" data-size="10"><option value="">Select...</option>@foreach ($currency as $item)<option value="{{$item->name}}">{{$item->name}}</option>@endforeach</select></td>' +
                '<td><input type="text" name="quotationDis[' + exportCount + '][ofr]" class="form-control" autocomplete="off" required></td>' +
                '<td style="width:85px;"><button type="button" class="btn btn-danger remove"><i class="fa fa-trash"></i></button></td>' +
                '</tr>';
            $('#ofr tbody').append(tr);
            $('.selectpicker').selectpicker('refresh');

            // Fetch equipment types for the newly added row
            fetchEquipmentTypes(selectedRequestType, $('#equpmint_' + exportCount));
            exportCount++;
        });

        $("#ofr").on("click", ".remove", function () {
            var removedValue = $(this).closest("tr").find('select[name^="quotationDis"][name$="[equipment_type_id]"]').val();
            $(this).closest("tr").remove();
            updateOptions();

            // If an option was removed, add it back to the remaining dropdowns
            if (removedValue) {
                $('select[name^="quotationDis"][name$="[equipment_type_id]"]').each(function() {
                    if (!$(this).find('option[value="' + removedValue + '"]').length) {
                        $(this).append('<option value="' + removedValue + '">' + removedValue + '</option>');
                    }
                    $(this).selectpicker('refresh');
                });
            }
        });

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
    });
</script>
@endpush
