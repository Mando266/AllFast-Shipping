@extends('layouts.app')

@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-one">
                    <div class="widget-heading">
                        <nav class="breadcrumb-two" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('port-charges.index') }}">Port Charge</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('port-charge-invoices.index') }}">Invoice</a></li>
                                <li class="breadcrumb-item active"><a href="#">{{ 1 }}</a></li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                        <br>
{{--                        <div class="row">--}}
{{--                            <form action="{{ route('port-charge-invoices.show.export', $invoice->id) }}" method="post">--}}
{{--                                @csrf--}}
{{--                                <input name="id" value="{{ $invoice->id }}" hidden>--}}
{{--                                <button type="submit" class="btn btn-success" id="export-date">Export</button>--}}
{{--                            </form>--}}
{{--                        </div>--}}
                    </div>
                    <div class="col-md-12 mt-4">
                        <h1>Booking: {{ $booking }}</h1>
                        <h3 class="mt-5">Invoices :</h3>
                        <ul class="list-unstyled">
                            @foreach ($invoices as $invoice)
                                <li class="mb-3" style="display: inline-block; margin-right: 10px;">
                                    <a href="{{ route('port-charge-invoices.show', $invoice->id) }}" class="text-decoration-none text-white bg-info border border-primary p-2 d-inline-block rounded">
                                        <strong>{{ $invoice->invoice_no }}</strong>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        <ul>
                        </ul>
                        <div class="table-container">
                            <table class='table table-bordered table-hover table-condensed mb-4'>
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th style="min-width: 222px">Type</th>
                                    <th style="min-width: 222px">Service</th>
                                    <th style="min-width: 222px">Voyage</th>
                                    <th style="min-width: 222px">ETA</th>
                                    <th style="min-width: 222px">Invoice No</th>
                                    <th style="min-width: 222px">CONTAINER NO</th>
                                    <th style="min-width: 100px">CONTAINER TYPE</th>
                                    <th style="min-width: 222px">TS</th>
                                    <th style="min-width: 222px">SHIPMENT TYPE</th>
                                    <th style="min-width: 222px">QUOTATION TYPE</th>
                                    @foreach(['thc', 'storage', 'storage_days', 'power', 
                                              'power_days', 'shifting', 'disinf', 'hand_fes_em',
                                               'gat_lift_off_inbnd_em_ft40', 'gat_lift_on_inbnd_em_ft40'
                                               ,'pti', 'pti_type', 'add_plan'] as $field)
                                        @if(in_array($field, $selected))
                                            <th data-field="{{ $field }}">{{ strtoupper($field) }}</th>
                                        @endif
                                    @endforeach
                                    @if(! $rows->pluck('additional_fees')->filter()->isEmpty())
                                        <th colspan="2">Additional Fees</th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($rows as $row)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $row->portCharge->name }}</td>
                                        <td>{{ $row->service }}</td>
                                        <td>{{ "{$row->vessel->name} - {$row->voyage->voyage_no} - {$row->voyage->leg->name}" }}</td>
                                        <td>{{ $row->eta }}</td>
                                        <td>{{ $row->invoice->invoice_no }}</td>
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
                                                <td>{{ $row->{$field} }}</td>
                                            @endif
                                        @endforeach
                                        @isset($row->additional_fees)
                                            <td>{{ $row->additional_fees }}</td>
                                            <td style="min-width: 200px">{{ $row->additional_fees_description }}</td>
                                        @endif
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="col-md-12 text-center">
                            <a href="{{ route('port-charge-invoices.index') }}" class="btn btn-primary mt-3">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('styles')
    <style>
        /*input {*/
        /*    min-width: 100px;*/
        /*}*/

        .table-container {
            overflow-x: auto;
            max-width: 100%;
        }
    </style>
@endpush
