@extends('layouts.app')
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('localporttriff.index')}}">Local Port Triff</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">Local Port Triff Details </a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                    <div class="row">
                            <div class="col-md-12 text-right mb-6">
                                <a class="btn btn-warning" href="{{ route('export.Localportshow') }}">Export</a>
                            </div>
                        </div>
                </div>
                <div class="widget-content widget-content-area">
                    <h5><span style='color:#1b55e2';>Tariff No:</span> {{$TriffNo->triff_no}}</h5>

                <div class="widget-content widget-content-area">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-condensed mb-4">
                                <thead>
                                    <tr>
                                        <th>charge type</th>
                                        <th>Equipment Type</th>
                                        <th>unit</th>
                                        <th>currency</th>
                                        <th>selling price</th>
                                        <th>payer</th>
                                        <th>Import Or Export</th>
                                        <th>standard Or customise</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($triffPriceDetailes as $triffPriceDetailes)
                                        <tr>
                                            <td>{{optional($triffPriceDetailes->charge)->name}}</td>
                                            @if($triffPriceDetailes->equipment_type_id == 100)
                                            <td>All</td>
                                            @else
                                            <td>{{{optional($triffPriceDetailes->equipmentsType)->name}}}</td>
                                            @endif
                                            <td>{{$triffPriceDetailes->unit}}</td>
                                            <td>{{$triffPriceDetailes->currency}}</td>
                                            <td>{{$triffPriceDetailes->selling_price}}</td>
                                            <td>{{$triffPriceDetailes->payer}}</td>
                                            <td class="text-center">
                                                @if($triffPriceDetailes->is_import_or_export == 0)
                                                    <span class="badge badge-info"> Import </span>
                                                @elseif($triffPriceDetailes->is_import_or_export == 1)
                                                    <span class="badge badge-danger"> Export</span>
                                                @elseif($triffPriceDetailes->is_import_or_export == 2)
                                                    <span class="badge badge-success"> Empty</span>
                                                @elseif($triffPriceDetailes->is_import_or_export == 3)
                                                    <span class="badge badge-dark">Transshipment</span>
                                                @else
                                                    <span class="badge badge-success"> Empty</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($triffPriceDetailes->standard_or_customise == 1)
                                                    <span class="badge badge-info"> standard </span>
                                                @else
                                                    <span class="badge badge-danger"> customise</span>
                                                @endif
                                            </td>
                                        </tr>

                                        @empty
                                        <tr class="text-center">
                                            <td colspan="20">{{ trans('home.no_data_found')}}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                       <div class="row">
                            <div class="col-md-12 text-center">
                                <a href="{{route('localporttriff.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                            </div>
                       </div>


                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="{{asset('plugins/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css" >
@endpush
@push('scripts')
<script src="{{asset('plugins/bootstrap-select/bootstrap-select.min.js')}}"></script>
@endpush