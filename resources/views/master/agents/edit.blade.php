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
                            <li class="breadcrumb-item active"><a href="javascript:void(0);"> {{trans('forms.edit')}}</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                <form id="createForm" action="{{route('agents.update',['agent'=>$agent])}}" method="POST">
                        @csrf
                        @method('put')
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="nameInput">Name <span class="text-warning"> *</span></label>
                            <input type="text" class="form-control" id="nameInput" name="name" value="{{old('name',$agent->name)}}"
                                 placeholder="Name" autocomplete="disabled" autofocus required>
                                @error('name')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="countryInput">{{trans('company.country')}} <span class="text-warning"> *</span></label>
                                <select class="selectpicker form-control" id="countryInput" data-live-search="true" data-size="10"
                                name="country_id" title="{{trans('forms.select')}}" required>
                                    @foreach ($countries as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('country_id',$agent->country_id) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('country_id')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="CityInput">City</label>
                                <input type="text" class="form-control" id="CityInput" name="city" value="{{old('city',$agent->city)}}"
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
                                    <option value="1" {{ old('status',$agent->is_active) == "1" ? 'selected':'' }}>Active</option>
                                    <option value="0" {{ old('status',$agent->is_active) == "0" ? 'selected':'' }}>Inactive</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="status">Intermediate payer</label>
                                <select class="selectpicker form-control"  name="intermediate_payer">
                                    <option value="1" {{ old('status',$agent->intermediate_payer) == "1" ? 'selected':'' }}>Yes</option>
                                    <option value="0" {{ old('status',$agent->intermediate_payer) == "0" ? 'selected':'' }}>No</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="status">Port Control</label>
                                <select class="selectpicker form-control"  name="port_control">
                                    <option value="1" {{ old('status',$agent->port_control) == "1" ? 'selected':'' }}>Yes</option>
                                    <option value="0" {{ old('status',$agent->port_control) == "0" ? 'selected':'' }}>No</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="status">Documentation Control</label>
                                <select class="selectpicker form-control"  name="documentation_control">
                                    <option value="1" {{ old('status',$agent->documentation_control) == "1" ? 'selected':'' }}>Yes</option>
                                    <option value="0" {{ old('status',$agent->documentation_control) == "0" ? 'selected':'' }}>No</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <!-- <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="details">Customer Details</label>
                                <textarea class="form-control" id="details" name="details" value="{{old('details',$agent->details)}}"
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
                                <button type="submit" class="btn btn-primary mt-3">{{trans('forms.update')}}</button>
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