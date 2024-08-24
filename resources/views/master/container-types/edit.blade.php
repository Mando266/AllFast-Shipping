@extends('layouts.app')
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">

        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Containers Movement</a></li>
                            <li class="breadcrumb-item"><a href="{{route('container-types.index')}}">Container Types</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">Edit</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                <form id="createForm" action="{{route('container-types.update',['container_type'=>$container_type])}}" method="POST">
                        @csrf
                        @method('put')
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="nameInput">Description <span class="text-warning"> * </span></label>
                            <input type="text" class="form-control" id="nameInput" name="name" value="{{old('name',$container_type->name)}}"
                                 placeholder="Description" autocomplete="off" autofocus>
                                @error('name')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="codeInput">Code <span class="text-warning"> * </span></label>
                                <input type="text" class="form-control" id="codeInput" name="code" value="{{old('code',$container_type->code)}}"
                                    placeholder="Code" autocomplete="off">
                                @error('code')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>   
                            <div class="form-group col-md-4">
                                <label for="Category"> Category <span class="text-warning"> * </span></label>
                                <select class="selectpicker form-control" id="Category" data-live-search="true" name="category" data-size="10"
                                 title="{{trans('forms.select')}}" Required>
                                        <option value="Dry" {{$container_type->category == old('category') ||  $container_type->category == "Dry"? 'selected':''}}>Dry</option>
                                        <option value="Reefer" {{$container_type->category == old('category') ||  $container_type->category == "Reefer"? 'selected':''}}>Reefer</option>
                                        <option value="Special Equipment" {{$container_type->category == old('category') ||  $container_type->category == "Special Equipment"? 'selected':''}}>Special Equipment</option>
                                </select>
                            </div>
                
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="iso_noInput">Iso No</label>
                                <input type="text" class="form-control" id="iso_noInput" name="iso_no" value="{{old('iso_no',$container_type->iso_no)}}"
                                    placeholder="Iso No" autocomplete="off">
                                @error('iso_no')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="widthInput">Width</label>
                                <input type="text" class="form-control" id="widthInput" name="width" value="{{old('width',$container_type->width)}}"
                                    placeholder="Width" autocomplete="off">
                                @error('width')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="heightsInput">Height</label>
                                <input type="text" class="form-control" id="heightsInput" name="heights" value="{{old('heights',$container_type->heights)}}"
                                    placeholder="Height" autocomplete="off">
                                @error('heights')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="lenghtInput">Lenght</label>
                                <input type="text" class="form-control" id="lenghtInput" name="lenght" value="{{old('lenght',$container_type->lenght)}}"
                                    placeholder="Lenght" autocomplete="off">
                                @error('lenght')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>

                       <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary mt-3">Update</button>
                                <a href="{{route('container-types.index')}}" class="btn btn-danger mt-3">Cancel</a>
                            </div>
                       </div>


                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
