@extends('layouts.app')
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">

        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('container-movement.index')}}">Movement Activity Codes</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);"> Add Activity Code</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                <form id="createForm" action="{{route('container-movement.store')}}" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="nameInput"> Name *</label>
                            <input type="text" class="form-control" id="nameInput" name="name" value="{{old('name')}}"
                                 placeholder="Name" autocomplete="off" autofocus>
                                @error('name')
                                <div style="color:red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label for="codeInput">Code</label>
                                <input type="text" class="form-control" id="codeInput" name="code" value="{{old('code')}}"
                                    placeholder="Code" autocomplete="off">
                                @error('code')
                                <div style="color:red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="container_statusInput"> Container Status </label>
                                <select class="selectpicker form-control" id="container_statusInput" data-live-search="true" name="container_status_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($container_status as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('container_status_id') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('container_status_id')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                        <div class="form-group col-md-4">
                                <label for="stock_type_idInput"> Stock Type </label>
                                <select class="selectpicker form-control" id="stock_type_idInput" data-live-search="true" name="stock_type_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($container_stock as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('stock_type_id') ? 'selected':''}}>{{$item->name}} - {{$item->code}}</option>
                                    @endforeach
                                </select>
                                @error('stock_type_id')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                        </div>
                        <div class="form-group col-md-8">
                                <label for="ContainerInput">Allowed Next Possible Activities</label>
                                <select class="selectpicker form-control" id="ContainerInput" data-live-search="true" name="movement[][code]" data-size="10"
                                 title="{{trans('forms.select')}}"  multiple="multiple">
                                    @foreach ($containersMovements as $item)
                                    <option value="{{$item->code}}" {{$item->id == old('code') ? 'selected':''}}>{{$item->code}}</option>
                                    @endforeach
                                </select>
                                @error('code')
                                <div class ="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>

                       <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary mt-3">{{trans('forms.create')}}</button>
                                <a href="{{route('container-movement.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                            </div>
                       </div>


                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection