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
                            <li class="breadcrumb-item"><a href="{{route('ports.index')}}">Ports</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);"> Add New Port</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                <form id="createForm" action="{{route('ports.store')}}" method="POST">
                        @csrf
                            @if(session('alert'))
                            <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ session('alert') }}</p>
                            @endif
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="nameInput">Name <span class="text-warning"> * </span></label>
                            <input type="text" class="form-control" id="nameInput" name="name" value="{{old('name')}}"
                                 placeholder="Name" autocomplete="off" autofocus required>
                                @error('name')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="codeInput">Code <span class="text-warning"> * </span></label>
                                <input type="text" class="form-control" id="codeInput" name="code" value="{{old('code')}}"
                                    placeholder="Code" autocomplete="off" required>
                                @error('code')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="countryInput">{{trans('company.country')}} <span class="text-warning"> * </span></label>
                                <select class="selectpicker form-control" id="countryInput" data-live-search="true" name="country_id" data-size="10"
                                 title="{{trans('forms.select')}}" required>
                                    @foreach ($countries as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('country_id') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('country_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
  
                    <div class="form-row">
                        <div class="form-group col-md-4">
                                <label for="via_portInput">Via Port</label>
                                <input type="text" class="form-control" id="via_portInput" name="via_port" value="{{old('via_port')}}"
                                    placeholder="Via Port" autocomplete="off">
                                @error('via_port')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        <div class="form-group col-md-4">
                                <label for="countryInput">Port Type <span class="text-warning"> * </span></label>
                                <select class="selectpicker form-control" id="countryInput" data-live-search="true" name="port_type_id" data-size="10"
                                 title="{{trans('forms.select')}}" required>
                                    @foreach ($port_types as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('port_type_id') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('port_type_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="terminalInput">Terminal</label>
                                <select class="selectpicker form-control" id="terminalInput" data-live-search="true" name="terminal_id" data-size="10"
                                    title="{{trans('forms.select')}}">
                                    @foreach ($terminals as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('terminal_id') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('terminal_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="pick_up_locationInput">Pickup / Return Location</label>
                            <input type="text" class="form-control" id="pick_up_locationInput" name="pick_up_location" value="{{old('pick_up_location')}}"
                                placeholder="Pickup / Return Location" autocomplete="off">
                            @error('pick_up_location')
                            <div style="color: red;">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-md-8">
                            <label for="Notes">Notes</label>
                            <textarea class="form-control" id="Notes" name="notes" value="{{old('notes')}}"
                             placeholder="Notes" autocomplete="off"></textarea>
                            @error('notes')
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
