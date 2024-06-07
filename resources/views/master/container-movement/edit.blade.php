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
                            <li class="breadcrumb-item active"><a href="javascript:void(0);"> {{trans('forms.edit')}}</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                <form id="createForm" action="{{route('container-movement.update',['container_movement'=>$container_movement])}}" method="POST">
                        @csrf
                        @method('put')
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="nameInput">Name *</label>
                            <input type="text" class="form-control" id="nameInput" name="name" value="{{old('name',$container_movement->name)}}"
                                 placeholder="Name" autocomplete="disabled" autofocus>
                                @error('name')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="codeInput">Code</label>
                                <input type="text" class="form-control" id="codeInput" name="code" value="{{old('code',$container_movement->code)}}"
                                    placeholder="Code" autocomplete="disabled">
                                @error('code')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="container_statusInput"> Container Status </label>
                                <select class="selectpicker form-control" id="container_statusInput" data-live-search="true" name="container_status_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($container_status as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('container_status_id',$container_movement->container_status_id) ? 'selected':''}}>{{$item->name}}</option>
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
                                        <option value="{{$item->id}}" {{$item->id == old('stock_type_id',$container_movement->stock_type_id) ? 'selected':''}}>{{$item->name}} - {{$item->code}}</option>
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
                                <select class="selectpicker form-control" id="ContainerInput" data-live-search="true" name="next_move[][code]" data-size="10"
                                 title="{{trans('forms.select')}}"  multiple="multiple">
                                    @foreach ($containersMovements as $item)
                                    <option value="{{$item->code}}" {{is_array($next_move) && $item->code == old('code',in_array($item->code, $next_move) ) ? 'selected':''}}>{{$item->code}}</option>
                                    @endforeach
                                </select>
                                @error('code')
                                <div class ="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        </div>
                        
    

                       <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary mt-3">{{trans('forms.update')}}</button>
                                <a href="{{route('container-movement.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                            </div>
                       </div>


                    </form>
                </div>
        </div>
    </div>
</div>
@endsection