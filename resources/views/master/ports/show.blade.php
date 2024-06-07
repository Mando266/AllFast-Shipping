@extends('layouts.app')
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">

        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Master Data </a></li>
                            <li class="breadcrumb-item"><a href="{{route('ports.index')}}">Ports</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);"> Ports Details</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                <form id="createForm" action="{{route('ports.edit',['port'=>$port])}}" method="get">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="nameInput">{{trans('company.name')}}</label>
                                <input type="text" class="form-control" id="nameInput"  value="{{$port->name}}" disabled>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="codeInput">Code</label>
                                <input type="text" class="form-control" id="codeInput"  value="{{$port->code}}" disabled>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="countryInput">{{trans('company.country')}}</label>
                                <input type="text" class="form-control" id="countryInput" value="{{optional($port->country)->name}}" disabled>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="viaInput">Via Port</label>
                                <input type="text" class="form-control" id="viaInput" value="{{$port->via_port}}" disabled>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="portInput">Port Type</label>
                            <input type="text" class="form-control" id="portInput" value="{{optional($port->PortTypes)->name}}" disabled>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="AgentInput">Agent</label>
                                <input type="text" class="form-control" id="AgentInput" value="{{optional($port->Agent)->name}}" disabled>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="cityInput">Terminal</label>
                                <input type="text" class="form-control" id="cityInput" value="{{optional($port->Terminal)->name}}" disabled>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="cityInput">Pickup / Return Location</label>
                                <input type="text" class="form-control" id="cityInput" value="{{$port->pick_up_location}}" disabled>
                            </div>
                        </div>
                        
                       <div class="row">
                            <div class="col-md-12 text-center">
                                @permission('Ports-Edit')
                                <button type="submit" class="btn btn-primary mt-3">{{trans('forms.edit')}}</button>
                                @endpermission
                                <a href="{{route('ports.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
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
    <link href="{{asset('plugins/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css" >
@endpush
@push('scripts')
<script src="{{asset('plugins/bootstrap-select/bootstrap-select.min.js')}}"></script>
@endpush
