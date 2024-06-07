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
                                <li class="breadcrumb-item active"><a href="{{route('movements.index')}}">Invoice</a>
                                </li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                    </div>
                    <div class="widget-content widget-content-area">
                        <a href="{{ route('port-charge-invoices.detail-edit', $invoice->id) }}"
                           class="btn btn-success m-3">Detailed Edit</a>
                        <form id="createForm" action="{{ route('port-charge-invoices.update', $invoice->id) }}"
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
                                                   name="invoice[payment_type]"
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
                                                    name="invoice[invoice_type]"
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
                                                   name="invoice[invoice_no]"
                                                   value="{{old('invoice_no', $invoice->invoice_no)}}"
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
                                                   name="invoice[invoice_date]"
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
                                                   name="invoice[exchange_rate]"
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
                                                   name="invoice[invoice_status]"
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
                                                    name="invoice[country_id]"
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
                                                    name="invoice[port_id]"
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
                                                    name="invoice[shipping_line_id]"
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
                                                <span class="input-group-text bg-transparent border-0">Invoice EGP</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" name="invoice[invoice_egp]" class="form-control"
                                                   id="invoice_egp"
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
                                            <input type="text" name="invoice[invoice_usd]" class="form-control"
                                                   id="invoice_usd"
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
                                            <input type="text" name="invoice[total_usd]" class="form-control"
                                                   id="total_usd"
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
                            </div>

                            <div class="col-md-12">
                                <div class="table-container">
                                    <input name="removed_ids" id="removed-ids" hidden>
                                    <table class='table table-bordered table-hover table-condensed mb-4'>
                                        <thead>
                                        <tr>
                                            <th>Remove</th>
                                            <th>#</th>
                                            <th style="min-width: 190px">Type</th>
                                            <th style="min-width: 170px">Service</th>
                                            <th style="min-width: 200px">Voyage</th>
                                            <th style="min-width: 200px">BL NO</th>
                                            <th style="min-width: 200px">CONTAINER NO</th>
                                            <th style="min-width: 80px">CONTAINER TYPE</th>
                                            <th style="min-width: 144px">TS</th>
                                            <th style="min-width: 142px">SHIPMENT TYPE</th>
                                            <th style="min-width: 142px">QUOTATION TYPE</th>
                                            @foreach(['thc', 'storage', 'storage_days', 'power', 
                                                      'power_days', 'shifting', 'disinf', 'hand_fes_em',
                                                       'gat_lift_off_inbnd_em_ft40', 'gat_lift_on_inbnd_em_ft40'
                                                       , 'pti', 'pti_type', 'add_plan'] as $field)
                                                @if(in_array($field, $selected))
                                                    <th data-field="{{ $field }}">{{ strtoupper($field) }}</th>
                                                @endif
                                            @endforeach
                                            <th colspan="2">Additional Fees</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($invoice->rows as $row)
                                            <tr>
                                                <td style="width:85px;">
                                                    <button type="button" class="btn btn-danger removeContact"><i
                                                                class="fa fa-trash"></i></button>
                                                </td>
                                                <td class="row-number">{{ $loop->iteration }}</td>
                                                <td>{{ $row->portCharge->name }}<input name="rows[id][]" class="row-id"
                                                                                       value="{{ $row->id }}" hidden>
                                                </td>
                                                <td>
                                                    <select name="rows[service][]" class="form-control service_type">
                                                        <option value="" hidden>Select</option>
                                                        @php
                                                            $options = [
                                                                "001-VSL-RE-STW-OPR",
                                                                "005-VSL-DIS-OPR",
                                                                "006-VSL-LOD-OPR",
                                                                "007-VSL-TRNSHP-OPR",
                                                                "011-VSL-HOL-WRK",
                                                                "018-YARD-SERV",
                                                                "019-LOG-SERV",
                                                                "020-HAND-FES",
                                                                "021-STRG-INBND-FL-CONTRS",
                                                                "024-STRG-OUTBND-CONTRS-FL",
                                                                "025-STRG-OUTBND-CONTRS-EM",
                                                                "031-STRG-PR-DR-CONTRS",
                                                                "033-REFR-CONTR-PWR-SUP",
                                                                "037-MISC-REV-GAT-SERV",
                                                                "038-MISC-REV-YARD-CRN-SHIFTING",
                                                                "039-MISC-REV-GAT-SERV-LIFT OFF",
                                                                "045-MISC-REV-ELEC-REP-SERV",
                                                                "051-VSL-OPR-ADD-PLAN",
                                                                "060-DISINFECTION OF CONTAINERS"
                                                            ];
                                                        @endphp

                                                        @foreach ($options as $option)
                                                            <option value="{{ $option }}" {{ $row->service === $option ? 'selected' : '' }}>{{ $option }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>{{ "{$row->vessel->name} - {$row->voyage->voyage_no} - {$row->voyage->leg->name}" }}</td>
                                                <td>{{ $row->bl_no }}</td>
                                                <td>{{ $row->container_no }}</td>
                                                <td>{{ optional($row->container)->containersTypes->name ?? '' }}</td>
                                                <td>{{ $row->ts }}</td>
                                                <td>{{ $row->shipment_type }}</td>
                                                <td>{{ $row->quotation_type }}</td>
                                                @foreach(['thc', 'storage', 'storage_days', 'power', 
                                                          'power_days', 'shifting', 'disinf', 'hand_fes_em',
                                                          'gat_lift_off_inbnd_em_ft40', 'gat_lift_on_inbnd_em_ft40',
                                                          'pti', 'pti_type', 'add_plan'] as $field)
                                                    @if(in_array($field, $selected))
                                                        <td><input type="number" step="0.01"
                                                                   class="form-control included"
                                                                   value="{{ $row->{$field} }}"
                                                                   name="rows[{{ $field }}][]"></td>
                                                    @endif
                                                @endforeach
                                                <td><input type="number" step="0.01" class="form-control included"
                                                           value="{{ $row->additional_fees }}"
                                                           name="rows[additional_fees][]"></td>
                                                <td style="min-width: 200px"><input type="text" class="form-control"
                                                                                    value="{{ $row->additional_fees_description }}"
                                                                                    name="rows[additional_fees_description][]">
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
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
    <script>
        let removedIds = []
        $(document).ready(function () {
            $(document).on('input', '.included', () => calculateTotals())
            $(document).on('click', '.removeContact', handleRemoveRow)
            $(document).on('click change keyup paste', () => calculateTotals());
            $('form').on('submit', () => setToZeroIfNull())
        })

        async function calculateTotals() {
            const exchangeRate = parseFloat($('#exchange_rate').val());
            let totalUSD = 0;
            let invoiceUSD = 0;
            let invoiceEGP = 0;
            let USD_to_EGP = 0;

            await new Promise((resolve) => {
                $('.included').each(function () {
                    totalUSD += parseFloat($(this).val()) || 0;
                });
                resolve();
            });

            await new Promise((resolve) => {
                $('.included').each(function () {
                    const table = $(this).closest('table');
                    const field = $(this).data('field');
                    let checkbox = table.find(`input[type="checkbox"].${field}`);
                    if (checkbox.length === 0) {
                        checkbox = $("#checkAll")
                    }

                    if (checkbox.prop('checked')) {
                        USD_to_EGP += parseFloat($(this).val()) || 0;
                    }
                });
                resolve();
            });

            invoiceUSD = totalUSD - USD_to_EGP;

            if (!isNaN(exchangeRate)) {
                invoiceEGP = USD_to_EGP * exchangeRate;
            }

            $("#total_usd").val(totalUSD);
            $("#invoice_usd").val(invoiceUSD);
            $("#invoice_egp").val(invoiceEGP);
        }

        function handleRemoveRow() {
            let row = $(this).closest("tr")
            let id = row.find(".row-id").val()
            removedIds.push(id)
            row.remove()
            calculateTotals()
            updateRowNumbers()
        }

        function updateRowNumbers() {
            $('table').each(function (tableIndex, tableElement) {
                $(tableElement).find('tr').each(function (rowIndex, rowElement) {
                    $(rowElement).find('td.row-number').text(rowIndex);
                });
            });
        }

        function setToZeroIfNull() {
            $('.dynamic-input').each(function () {
                if ($(this).val() === null || $(this).val() === '') {
                    $(this).val('0');
                }
            });
            $("#removed-ids").val(removedIds)
        }
    </script>
@endpush
