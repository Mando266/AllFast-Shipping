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
                            <li class="breadcrumb-item active"><a href="javascript:void(0);"> {{trans('company.create_new')}}</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                <form id="createForm" action="{{route('company.store')}}" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="nameInput">{{trans('company.name')}} *</label>
                            <input type="text" class="form-control" id="nameInput" name="name" value="{{old('name')}}"
                                 placeholder="{{trans('company.name')}}" autocomplete="disabled" autofocus>
                                @error('name')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="addressInput">{{trans('company.address')}}</label>
                                <input type="text" class="form-control" id="addressInput" name="address" value="{{old('address')}}"
                                    placeholder="{{trans('company.address')}}" autocomplete="disabled">
                                @error('address')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
  
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="phoneInput">{{trans('company.phone')}}</label>
                            <input type="text" class="form-control" id="phoneInput" name="phone" value="{{old('phone')}}"
                                 placeholder="{{trans('company.phone')}}" autocomplete="disabled" maxlength="30">
                                @error('phone')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="emailInput">{{trans('company.email')}}</label>
                                <input type="text" class="form-control" id="emailInput" name="email" value="{{old('email')}}"
                                 placeholder="{{trans('company.email')}}" autocomplete="disabled">
                                @error('email')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="countryInput">{{trans('company.country')}}</label>
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
                            <div class="form-group col-md-6">
                                <label for="cityInput">{{trans('company.city')}}</label>
                                <input type="text" class="form-control" id="cityInput" name="city" value="{{old('city')}}"
                                    placeholder="{{trans('company.city')}}" autocomplete="disabled">
                                @error('city')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>

                       <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary mt-3">{{trans('forms.create')}}</button>
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
