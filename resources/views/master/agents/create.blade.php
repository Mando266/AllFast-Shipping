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
                            <li class="breadcrumb-item"><a href="{{route('agents.index')}}">Agents</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);"> Add New Agent</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                <form id="createForm" action="{{route('agents.store')}}" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="nameInput"> Name <span class="text-warning"> *</span></label>
                            <input type="text" class="form-control" id="nameInput" name="name" value="{{old('name')}}"
                                 placeholder="Name" autocomplete="disabled" autofocus>
                                @error('name')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="countryInput">{{trans('company.country')}} <span class="text-warning"> *</span></label>
                                <select class="selectpicker form-control" id="countryInput" data-live-search="true" name="country_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($countries as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('country_id') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('country_id')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="cityInput">City</label>
                                <input type="text" class="form-control" id="cityInput" name="city" value="{{old('city')}}"
                                    placeholder="City" autocomplete="disabled">
                                @error('city')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div> 
  
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="status">{{trans('user.status')}}</label>
                                <select class="selectpicker form-control"  name="is_active">
                                    <option value="1" {{ old('status') == "1" ? 'selected':'' }}>Active</option>
                                    <option value="0" {{ old('status') == "0" ? 'selected':'' }}>Inactive</option>
                                </select>
                                @error('status')
                                <div style="color:red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <input type="checkbox" id="intermediate_payerInput" name="intermediate_payer" value="1"><a style="font-size: 15px; color: #3b3f5c; letter-spacing: 1px;"> Intermediate Payer </a>
                                </br>
                                <input type="checkbox" id="port_controlInput" name="port_control" value="1"><a style="font-size: 15px; color: #3b3f5c; letter-spacing: 1px;"> Port Control </a>
                                </br>
                                <input type="checkbox" id="documentation_controlInput" name="documentation_control" value="1"><a style="font-size: 15px; color: #3b3f5c; letter-spacing: 1px;"> Documentation Control</a>
                                </br>
                            </div>
                        </div>
                        <!-- <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="details">Customer Details</label>
                                <textarea class="form-control" id="details" name="details" value="{{old('details')}}"
                                 placeholder="Customer Details" autocomplete="off" autofocus></textarea>
                                @error('details')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div> -->

                       <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary mt-3">{{trans('forms.create')}}</button>
                                <a href="{{route('agents.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                            </div>
                       </div>


                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection