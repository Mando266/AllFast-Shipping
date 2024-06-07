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
                            <li class="breadcrumb-item"><a href="{{route('lines.index')}}">Lines</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);"> {{trans('forms.edit')}}</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                <form id="createForm" action="{{route('lines.update',['line'=>$line])}}" method="POST">
                        @csrf
                        @method('put')
                        @if(session('alert'))
                                <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ session('alert') }}</p>
                        @endif
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="nameInput">Name <span class="text-warning"> * </span> </label>
                            <input type="text" class="form-control" id="nameInput" name="name" value="{{old('name',$line->name)}}"
                                 placeholder="Name" autocomplete="off" autofocus required>
                                @error('name')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="CodeInput">Code <span class="text-warning"> * </span></label>
                                <input type="text" class="form-control" id="CodeInput" name="code" value="{{old('code',$line->code)}}"
                                    placeholder="Code" autocomplete="off">
                                @error('Code')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="lineInput">Line Type <span class="text-warning"> * </span></label>
                                <select class="selectpicker form-control" id="lineInput" data-live-search="true" data-size="10"
                                name="line_type_id[][type_id]" title="{{trans('forms.select')}}" multiple="multiple" required>
                                    @foreach ($line_types as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('line_type_id',$line->line_type_id) || in_array($item->id, $types)? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('port_type_id')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="countryInput">Country <span class="text-warning"> * </span></label>
                                <select class="selectpicker form-control" id="countryInput" data-live-search="true"
                                        name="country_id" data-size="10"
                                        title="{{ trans('forms.select') }}" required>
                                    @foreach ($countries ?? [] as $item)
                                        <option value="{{ $item->id }}" {{ $item->id == old('country_id', $line->country_id ?? '') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('country_id')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                    <!-- <x-contact-people-table :contact-people="$contactPeople"></x-contact-people-table> -->

                       <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary mt-3">{{trans('forms.update')}}</button>
                                <a href="{{route('lines.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                            </div>
                       </div>


                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
