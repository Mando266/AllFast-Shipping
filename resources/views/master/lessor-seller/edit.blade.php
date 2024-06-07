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
                                <li class="breadcrumb-item"><a href="{{route('seller.index')}}">Container Ownerships</a></li>
                                <li class="breadcrumb-item active"><a href="javascript:void(0);"> Edit </a></li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                    </div>
                    <div class="widget-content widget-content-area">
                        <form id="createForm" action="{{route('seller.update',['seller'=>$seller])}}" method="POST">
                            @csrf
                            @method('put')
                            @if(session('alert'))
                                <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ session('alert') }}</p>
                            @endif
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="nameInput">Name *</label>
                                    <input type="text" class="form-control" id="nameInput" name="name"
                                           value="{{old('name',$seller->name)}}"
                                           placeholder="Name" autocomplete="disabled" autofocus>
                                    @error('name')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="countryInput">{{trans('company.country')}}</label>
                                    <select class="selectpicker form-control" id="countryInput" data-live-search="true"
                                            data-size="10"
                                            name="country_id" title="{{trans('forms.select')}}">
                                        @foreach ($countries as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('country_id',$seller->country_id) ? 'selected':''}}>{{$item->name}}</option>
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
                                    <input type="text" class="form-control" id="cityInput" name="city"
                                           value="{{old('city',$seller->city)}}"
                                           placeholder="City" autocomplete="disabled" autofocus>
                                    @error('city')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="phoneInput">Phone</label>
                                    <input type="text" class="form-control" id="phoneInput" name="phone"
                                           value="{{old('phone',$seller->phone)}}"
                                           placeholder="Phone" autocomplete="disabled" autofocus>
                                    @error('phone')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="emailInput">Email</label>
                                    <input type="text" class="form-control" id="emailInput" name="email"
                                           value="{{old('email',$seller->email)}}"
                                           placeholder="Email" autocomplete="disabled" autofocus>
                                    @error('email')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="taxInput">Tax</label>
                                    <input type="text" class="form-control" id="taxInput" name="tax"
                                           value="{{old('tax',$seller->tax)}}"
                                           placeholder="Tax" autocomplete="disabled" autofocus>
                                    @error('tax')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="submit"
                                            class="btn btn-primary mt-3">{{trans('forms.update')}}</button>
                                    <a href="{{route('suppliers.index')}}"
                                       class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                                </div>
                            </div>


                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection