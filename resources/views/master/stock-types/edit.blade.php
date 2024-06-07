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
                            <li class="breadcrumb-item"><a href="{{route('stock-types.index')}}">Stock Types</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">Edit</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                <form id="createForm" action="{{route('stock-types.update',['stock_type'=>$stock_type])}}" method="POST">
                        @csrf
                        @method('put')
                        <div class="form-row">
                        <div class="form-group col-md-6">
                                <label for="nameInput">STOCK STATUS DESCRIPTION *</label>
                            <input type="text" class="form-control" id="nameInput" name="name" value="{{old('name',$stock_type->name)}}"
                                 placeholder="STOCK STATUS DESCRIPTION" autocomplete="off" autofocus>
                                @error('name')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        <div class="form-group col-md-6">
                                <label for="codeInput">USAGE AVAILABILITY *</label>
                            <input type="text" class="form-control" id="codeInput" name="code" value="{{old('code',$stock_type->code)}}"
                                 placeholder="USAGE AVAILABILITY" autocomplete="off" autofocus>
                                @error('name')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>

                       <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary mt-3">Update</button>
                                <a href="{{route('stock-types.index')}}" class="btn btn-danger mt-3">Cancel</a>
                            </div>
                       </div>


                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
