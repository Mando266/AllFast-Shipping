@extends('layouts.app')
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">

        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('demurrage.index')}}">Demurrages</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">Demurrage Details </a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                    <h5><span style='color:#1b55e2';>Tariff Ref No:</span> {{{optional($demurrages->tarriffType)->code}}} - {{{optional($demurrages->ports)->code}}} - {{$demurrages->tariff_id}}</h5>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="countryInput">{{trans('company.country')}}</label>
                            <select class="form-control" id="country" data-live-search="true"
                                   data-size="10"
                                    title="{{trans('forms.select')}}">
                                    <option value="{{$demurrages->id}}">{{optional($demurrages->country)->name}}</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="port">Port</label>
                            <select class="form-control" id="port" data-live-search="true"
                                 data-size="10">
                                    <option
                                            value="{{$demurrages->id}}">{{$demurrages->ports->name}}</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="currency">Currency</label>
                            <select class="form-control" id="currency" data-live-search="true"
                                    data-size="10"
                                    title="{{trans('forms.select')}}">
                                    <option
                                            value="{{$demurrages->id}}">{{$demurrages->currency}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="validity_from">Validity From </label>
                            <input type="text" class="form-control"
                                   value="{{$demurrages->validity_from}}">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="validity_from">Validity to </label>
                            <input type="text" class="form-control" id="currency"
                                   value="{{$demurrages->validity_to}}">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="Triffs">Triff</label>
                            <select class="form-control" id="triff_kind" data-live-search="true"
                                    data-size="10"
                                    title="{{trans('forms.select')}}">
                                    <option
                                            value="{{$demurrages->id}}">{{$demurrages->tariff_id}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="terminal">Terminal</label>
                            <select class="form-control" id="terminal" data-live-search="true"
                                    name="terminal_id" data-size="10">
                                    <option value="{{$demurrages->id}}">{{optional($demurrages->terminal)->name}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="tariff_type_id">Tariff Type</label>
                            <select class="form-control" id="tariff_type_id">
                                    <option value="{{ $demurrages->id }}">{{$demurrages->tarriffType->code }}
                                        - {{ $demurrages->tarriffType->description }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="container_status">Container Status </label>
                            <input class="form-control" id="container_status"  value="{{$demurrages->container_status}}">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="is_storage">Detention OR Storage</label>
                            <input class="form-control" id="is_storage" value="{{$demurrages->is_storge}}">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="bound_id">Bound</label>
                            <input class="form-control" id="bound_id" value="{{$demurrages->bound_id}}">
                        </div>
                    </div>

                <div class="widget-content widget-content-area">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-condensed mb-4">
                                <thead>
                                    <tr>
                                            <th>period</th>
                                            <th>calendar days</th>
                                            <th>rate per day</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($slabs as $slab)

                                    @forelse ($slab->periods as $period)
                                        <tr>
                                            <td>{{$period->period}}</td>
                                            <td>{{$period->number_off_dayes}}</td>
                                            <td>{{$period->rate}}</td>
                                        </tr>

                                        @empty
                                        <tr class="text-center">
                                            <td colspan="20">{{ trans('home.no_data_found')}}</td>
                                        </tr>
                                    @endforelse
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Slabs</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="slabs" class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <th>Equipment Type</th>
                                                <th>Status</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($slabs as $slab)
                                                    <tr>
                                                        <td>{{optional($slab->containersType)->name}}</td>
                                                        <td>Active</td>
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
                            </div>
                        </div>
                    </div>

                       <div class="row">
                            <div class="col-md-12 text-center">
                                <a href="{{route('demurrage.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
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
