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
                            <li class="breadcrumb-item"><a href="{{route('company.index')}}">Companies</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);"> {{$company->name}}</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                <form id="createForm" action="{{route('company.edit',['company'=>$company])}}" method="get">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="nameInput">{{trans('company.name')}}</label>
                                <input type="text" class="form-control" id="nameInput"  value="{{$company->name}}" disabled>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="addressInput">{{trans('company.address')}}</label>
                                <input type="text" class="form-control" id="addressInput"  value="{{$company->address}}" disabled>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="phoneInput">{{trans('company.phone')}}</label>
                                <input type="text" class="form-control" id="phoneInput" value="{{$company->phone}}" disabled>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="emailInput">{{trans('company.email')}}</label>
                            <input type="text" class="form-control" id="emailInput" value="{{$company->email}}" disabled>
                            </div>
                        </div>
                        <div class="form-row">
                        <div class="form-group col-md-6">
                                <label for="countryInput">{{trans('company.country')}}</label>
                                <input type="text" class="form-control" id="countryInput" value="{{optional($company->country)->name}}" disabled>

                            </div>
                            <div class="form-group col-md-6">
                                <label for="cityInput">{{trans('company.city')}}</label>
                                <input type="text" class="form-control" id="cityInput" value="{{$company->city}}" disabled>
                            </div>
                        </div>
                       <div class="row">
                            <div class="col-md-12 text-center">
                                @permission('Company-Edit')
                                <button type="submit" class="btn btn-primary mt-3">{{trans('forms.edit')}}</button>
                                @endpermission
                                <a href="{{route('company.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
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
