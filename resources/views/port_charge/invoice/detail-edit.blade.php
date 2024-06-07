@extends('layouts.app')

@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">

            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-one">
                    <div class="widget-heading">
                        <nav class="breadcrumb-two" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Port Charge</a></li>
                                <li class="breadcrumb-item active"><a href="{{route('port-charge-invoices.index')}}">Invoice</a>
                                </li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                    </div>
                    <div class="widget-content widget-content-area">
                        <form id="createForm" action="{{ route('port-charge-invoices.detail-update', $invoice->id) }}"
                              method="POST">
                            @method('patch')
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <div class="input-group">
                                        <div class="col-md-2">
                                            <div class="input-group-prepend">
                                                <label class="input-group-text bg-transparent border-0"
                                                       for="payment_type">
                                                    Payment Type
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6"> <!-- Adjust the width here -->
                                            <input type="text" class="form-control" id="payment_type"
                                                   name="payment_type"
                                                   value="{{old('payment_type', $invoice->payment_type)}}"
                                                   autocomplete="off">
                                        </div>
                                    </div>
                                    @error('payment_type')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-12">
                                    <div class="input-group">
                                        <div class="col-md-2">
                                            <div class="input-group-prepend">
                                                <label class="input-group-text bg-transparent border-0"
                                                       for="invoice_type">
                                                    Invoice Type
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6"> <!-- Adjust the width here -->
                                            <select class="selectpicker form-control rounded-0" id="invoice_type"
                                                    name="invoice_type"
                                                    data-live-search="true" data-size="10"
                                                    title="{{trans('forms.select')}}">
                                                @foreach ([
                                                    '02-VESSEL DISCHARGING AND LOADING OPERATIONS',
                                                    '03-VESSEL OUTBOUND CONTAINERS STORAGE',
                                                    '05-WITHDRAWAL AND STUFFING CONTAINERS',
                                                    '08-STORAGE OF FULL INBOUND CONTAINERS'
                                                ] as $option)
                                                    <option value="{{ $option }}" {{ $invoice->invoice_type === $option ? 'selected' : '' }}>
                                                        {{ $option }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    @error('invoice_type')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-12">
                                    <div class="input-group">
                                        <div class="col-md-2">
                                            <div class="input-group-prepend">
                                                <label class="input-group-text bg-transparent border-0"
                                                       for="invoice_no">
                                                    Invoice No
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" id="invoice_no"
                                                   name="invoice_no" value="{{old('invoice_no', $invoice->invoice_no)}}"
                                                   autocomplete="off">
                                        </div>
                                    </div>
                                    @error('invoice_no')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-12">
                                    <div class="input-group">
                                        <div class="col-md-2">
                                            <div class="input-group-prepend">
                                                <label class="input-group-text bg-transparent border-0"
                                                       for="invoice_date">
                                                    Invoice Date *
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="date" class="form-control" id="invoice_date"
                                                   name="invoice_date"
                                                   value="{{old('invoice_date', $invoice->invoice_date)}}"
                                                   autocomplete="off" required>
                                        </div>
                                    </div>
                                    @error('invoice_date')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-12">
                                    <div class="input-group">
                                        <div class="col-md-2">
                                            <div class="input-group-prepend">
                                                <label class="input-group-text bg-transparent border-0"
                                                       for="exchange_rate">
                                                    Exchange Rate
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="number" class="form-control" id="exchange_rate"
                                                   name="exchange_rate"
                                                   value="{{old('exchange_rate', $invoice->exchange_rate)}}"
                                                   min="0" max="1000" step="0.01">
                                        </div>
                                    </div>
                                    @error('exchange_rate')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-12">
                                    <div class="input-group">
                                        <div class="col-md-2">
                                            <div class="input-group-prepend">
                                                <label class="input-group-text bg-transparent border-0"
                                                       for="invoice_status">
                                                    Invoice Status
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" id="invoice_status"
                                                   name="invoice_status"
                                                   value="{{old('invoice_status', $invoice->invoice_status)}}"
                                                   autocomplete="off">
                                        </div>
                                    </div>
                                    @error('invoice_status')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-12">
                                    <div class="input-group">
                                        <div class="col-md-2">
                                            <div class="input-group-prepend">
                                                <label class="input-group-text bg-transparent border-0"
                                                       for="country">
                                                    Country
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <select class="selectpicker form-control rounded-0" id="country"
                                                    name="country_id"
                                                    data-live-search="true" data-size="10"
                                                    title="{{ trans('forms.select') }}">
                                                @foreach ($countries as $item)
                                                    <option value="{{ $item->id }}" {{ $invoice->country_id == $item->id ? 'selected' : '' }}>
                                                        {{ $item->name }}
                                                    </option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                    <div class="input-group">
                                        <div class="col-md-2">
                                            <div class="input-group-prepend">
                                                <label class="input-group-text bg-transparent border-0"
                                                       for="ports">
                                                    Port *
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <select class="selectpicker form-control rounded-0" id="ports"
                                                    name="port_id"
                                                    data-live-search="true" data-size="10"
                                                    title="{{ trans('forms.select') }}" required>
                                                @foreach ($ports as $item)
                                                    <option value="{{ $item->id }}" {{ $invoice->port_id == $item->id ? 'selected' : '' }}>
                                                        {{ $item->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                    <div class="input-group">
                                        <div class="col-md-2">
                                            <div class="input-group-prepend">
                                                <label class="input-group-text bg-transparent border-0"
                                                       for="shipping_line">
                                                    Shipping Line *
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <select class="selectpicker form-control rounded-0" id="shipping_line"
                                                    name="shipping_line_id"
                                                    data-live-search="true" data-size="10"
                                                    title="{{ trans('forms.select') }}" required>
                                                @foreach ($lines as $item)
                                                    <option value="{{ $item->id }}" {{ $invoice->shipping_line_id == $item->id ? 'selected' : '' }}>
                                                        {{ $item->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    @error('shipping_line')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-12">
                                    <div class="input-group">
                                        <div class="col-md-2">
                                            <div class="input-group-prepend">
                                                <label class="input-group-text bg-transparent border-0" for="vessel_id">
                                                    Vessel Name *
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6"> <!-- Adjust the width here -->
                                            <select class="selectpicker form-control rounded-0" id="vessel_id"
                                                    name="vessel_id[]"
                                                    data-live-search="true" data-size="10"
                                                    title="{{trans('forms.select')}}" required multiple>
                                                @foreach ($vessels as $item)
                                                    <option value="{{$item->id}}"
                                                            {{ in_array($item->id, $invoice->vessels->pluck('id')->unique()->toArray()) ? 'selected' : '' }}>
                                                        {{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    @error('vessel_id')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-12">
                                    <div class="input-group">
                                        <div class="col-md-2">
                                            <div class="input-group-prepend">
                                                <label class="input-group-text bg-transparent border-0" for="voyage_id">
                                                    Voyage No *
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <select class="selectpicker form-control rounded-0" id="voyage"
                                                    name="voyage_id[]"
                                                    data-live-search="true" data-size="10"
                                                    title="{{trans('forms.select')}}" required multiple>
                                                @foreach ($voyages as $item)
                                                    <option value="{{ $item->id }}"
                                                    >{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    @error('voyage_id')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-12 dynamic-fields-clone">
                                    <div class="input-group">
                                        <div class="col-md-2">
                                            <div class="input-group-prepend">
                                                <label class="input-group-text bg-transparent border-0"
                                                       for="dynamic_fields">
                                                    Default Applied Costs
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <select class="form-control" id="dynamic_fields" multiple
                                                    data-size="10" name="selected_costs[]" required>
                                                @foreach($costs as $field)
                                                    <option value="{{ $field }}"
                                                            {{ in_array($field, $selected) ? 'selected' : '' }}
                                                    >{{ $field }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                    <div class="input-group">
                                        <div class="col-md-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-transparent border-0">Invoice EGP</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" name="invoice_egp" class="form-control" id="invoice_egp"
                                                   value="{{ $invoice->invoice_egp }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                    <div class="input-group">
                                        <div class="col-md-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-transparent border-0">Invoice USD</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" name="invoice_usd" class="form-control" id="invoice_usd"
                                                   value="{{ $invoice->invoice_usd }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                    <div class="input-group">
                                        <div class="col-md-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-transparent border-0">Total USD</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" name="total_usd" class="form-control" id="total_usd"
                                                   value="{{ $invoice->total_usd }}"
                                                   readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                    <div class="input-group">
                                        <div class="col-md-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-transparent border-0">Old Total USD</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control"
                                                   value="{{ $invoice->total_usd }}"
                                                   disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="ml-5">
                                    <label class="switch">
                                        <input type="checkbox" id="checkAll">
                                        <span class="slider round"></span>
                                    </label>
                                    <h6>All In EGP</h6>
                                </div>
                                <div class="container">
                                    <div id="table2-selects" class="d-none">
                                        <div class="text-center">
                                            <label>Empty/Export</label>
                                            <label class="col-md-3">From
                                                <select name="empty_export_from_id" class="form-control">
                                                    <option hidden selected>Select</option>
                                                    @foreach ($possibleMovements as $movement)
                                                        <option value="{{ $movement->id }}" {{ $movement->id == $invoice->empty_export_from_id ? 'selected' : '' }}>{{ $movement->code }}</option>
                                                    @endforeach
                                                </select>
                                            </label>
                                            <label class="col-md-3">To
                                                <select name="empty_export_to_id" class="form-control">
                                                    <option hidden selected>Select</option>
                                                    @foreach ($possibleMovements as $movement)
                                                        <option value="{{ $movement->id }}" {{ $movement->id == $invoice->empty_export_to_id ? 'selected' : '' }}>{{ $movement->code }}</option>
                                                    @endforeach
                                                </select>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="container">
                                    <div id="table3-selects" class="d-none">
                                        <div class="text-center">
                                            <label>Empty/Import</label>
                                            <label class="col-md-3">From
                                                <select name="empty_import_from_id" class="form-control">
                                                    <option hidden selected>Select</option>
                                                    @foreach ($possibleMovements as $movement)
                                                        <option value="{{ $movement->id }}" {{ $movement->id == $invoice->empty_import_from_id ? 'selected' : '' }}>{{ $movement->code }}</option>
                                                    @endforeach
                                                </select>
                                            </label>
                                            <label class="col-md-3">To
                                                <select name="empty_import_to_id" class="form-control">
                                                    <option hidden selected>Select</option>
                                                    @foreach ($possibleMovements as $movement)
                                                        <option value="{{ $movement->id }}" {{ $movement->id == $invoice->empty_import_to_id ? 'selected' : '' }}>{{ $movement->code }}</option>
                                                    @endforeach
                                                </select>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <ul class="nav nav-tabs">
                                    <li class="nav-item">
                                        <button type="button" class="nav-link switch-table active" data-table="table1">
                                            Full
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button type="button" class="nav-link switch-table" data-table="table2">
                                            Empty/Export
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button type="button" class="nav-link switch-table" data-table="table3">
                                            Empty/Import
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button type="button" class="nav-link switch-table" data-table="table4">
                                            Transhipment
                                        </button>
                                    </li>
                                </ul>
                            </div>

                            <div>
                                <button type="button" id="add-many-containers" class="btn btn-info m-3">Add Many
                                    Containers
                                </button>
                                <button type="button" id="add-row" class="btn btn-info my-3 mx-1">Add Row</button>
                            </div>
                            <div class="col-md-12">
                                <div class="table-container">
                                    <x-soa-table id="table1"/>
                                    <x-soa-table id="table2" class="d-none"/>
                                    <x-soa-table id="table3" class="d-none"/>
                                    <x-soa-table id="table4" class="d-none"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="submit" id="submit"
                                            class="btn btn-primary mt-3">{{trans('forms.update')}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
@push('styles')
    <style>
        input {
            min-width: 100px;
        }

        input.ref-no-td {
            min-width: 233px !important;
        }

        .table-container {
            overflow-x: auto;
            max-width: 100%;
        }

        .dynamic-input:not(.included) {
            display: none;
        }

        td:not(:has(input.included)) {
            display: none;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #2196F3;
        }

        input:focus + .slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }
    </style>
@endpush
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    @include('port_charge.invoice._js_soa')
    <script>
        $(document).ready(function () {
            setupPage()
        })

        async function setupPage() {
            $('tbody tr').remove()
            await loadVoyages();
            await selectVoyageCosts()
            toggleAllEgpSwitch()
            addCurrentInvoiceRows()
        }

        async function addCurrentInvoiceRows() {
            let containers = JSON.parse('@json($rows->pluck('container_no')->toArray())');
            let selectedServices = JSON.parse('@json($rows->pluck('service')->toArray())');
            let selectedPtiTypes = JSON.parse('@json($rows->pluck('pti_type')->toArray())');
            let selectedPowerDays = JSON.parse('@json($rows->pluck('power_days')->toArray())');
            let selectedStorageDays = JSON.parse('@json($rows->pluck('storage_days')->toArray())');
            let selectedAddPlans = JSON.parse('@json($rows->pluck('add_plan')->map(fn($s) => (int) $s)->toArray())');
            let additionalFees = JSON.parse('@json($rows->pluck('additional_fees')->toArray())');
            let additionalFeesDescriptions = JSON.parse('@json($rows->pluck('additional_fees_description')->toArray())');


            await addEditContainers(containers, selectedServices, selectedPtiTypes, selectedPowerDays, selectedStorageDays, selectedAddPlans, additionalFees, additionalFeesDescriptions)
        }

        function toggleAllEgpSwitch() {
            let isEgp = +{{ $invoice->invoice_egp }};
            if (isEgp > 0) {
                $("#checkAll").prop('checked', true).trigger('change')
            }
        }

        async function selectVoyageCosts() {
            await selectVoyages()
            let voyageCosts = JSON.parse('@json($voyagesCosts)')
            voyageCosts.forEach(voyageCost => {
                const voyageId = voyageCost.id
                $(`.voyage-${voyageId}-full`).val(voyageCost.full).trigger('change')
                $(`.voyage-${voyageId}-empty`).val(voyageCost.empty).trigger('change')
                $('.selectpicker').selectpicker('refresh')
            })
        }

        async function selectVoyages() {
            return new Promise(resolve => {
                let selectedVoyages = JSON.parse('@json($invoice->voyages->pluck('id')->unique()->toArray())')
                $("#voyage").val(selectedVoyages).trigger('change')
                $("#dynamic_fields").trigger('change')
                $('.selectpicker').selectpicker('refresh')
                resolve()
            })
        }

        const addEditContainers = async (containers, selectedServices, selectedPtiTypes, selectedPowerDays, selectedStorageDays, selectedAddPlans, additionalFees, additionalFeesDescriptions) => {
            const vesselId = $('#vessel_id').val();
            const voyage = $('#voyage').val();

            if (containers.length > 0 && vesselId && voyage) {
                for (let i = 0; i < containers.length; i++) {
                    const container = containers[i];
                    const service = selectedServices[i] !== "" ? selectedServices[i] : null;
                    const ptiType = selectedPtiTypes[i];
                    const powerDay = selectedPowerDays[i];
                    const storageDay = selectedStorageDays[i];
                    const addPlan = selectedAddPlans[i];
                    const additionalFee = additionalFees[i];
                    const additionalFeesDescription = additionalFeesDescriptions[i];

                    await processContainer(container, vesselId, voyage, service, ptiType, powerDay, storageDay, addPlan, additionalFee, additionalFeesDescription);
                }
                $(".storage-days").trigger('change')
                $(".power-days").trigger('change')
                $(".pti-type").trigger('change')
                $(".add-plan-select").trigger('change')
                handleDynamicFieldsChange()
                updateChargeTypeOptions('table1')
                updateChargeTypeOptions('table2')
                updateChargeTypeOptions('table3')
                updateChargeTypeOptions('table4')
            }
        };
    </script>
@endpush
