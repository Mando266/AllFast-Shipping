@extends('layouts.app')
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">

        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Triffs</a></li>
                            <li class="breadcrumb-item"><a href="{{route('chargesDesc.index')}}">Charges Description</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);"> Add New Charge</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                <form id="createForm" action="{{route('chargesDesc.store')}}" method="POST">
                        @csrf
                            @if(session('alert'))
                            <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ session('alert') }}</p>
                            @endif
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="nameInput">Name <span class="text-warning"> * </span></label>
                            <input type="text" class="form-control" id="nameInput" name="name" value="{{old('name')}}"
                                 placeholder="Name" autocomplete="off" autofocus>
                                @error('name')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="codeInput">Code <span class="text-warning"> * </span></label>
                                <input type="text" class="form-control" id="codeInput" name="code" value="{{old('code')}}"
                                    placeholder="Code" autocomplete="off">
                                @error('code')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="Type">Type<span class="text-warning"> * </span></label>
                                <select class="selectpicker form-control" title="{{trans('forms.select')}}" name="type">
                                    <option value="1" {{ old('type') == "1" ? 'selected':'' }}>Invoice</option>
                                    <option value="0" {{ old('type') == "0" ? 'selected':'' }}>Depit</option>
                                </select>
                                @error('type')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        
                       <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary mt-3">{{trans('forms.create')}}</button>
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
