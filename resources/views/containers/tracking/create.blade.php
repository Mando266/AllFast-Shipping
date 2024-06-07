@extends('layouts.app')
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            {{-- <li class="breadcrumb-item"><a href="javascript:void(0);">Containers </a></li> --}}
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Containers</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">Tracking</a></li>

                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                <form  action="{{route('tracking.index')}}" >
                        <div class="form-row">
                        <div class="form-group col-md-3">
                                <label for="ContainerInput">Container Number </label>
                                <select class="selectpicker form-control" id="ContainerInput" data-live-search="true" name="container_id[]" data-size="10"
                                 title="{{trans('forms.select')}}" multiple="multiple">
                                    @foreach ($containers as $item)
                                        <option value="{{$item->id}}" data-code="{{$item->container_type_id}}" {{$item->id == old('container_id') ||in_array($item->id, request()->container_id ?? []) ? 'selected':''}}>{{$item->code}}</option>
                                    @endforeach
                                </select>
                                @error('container_id')
                                <div class ="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="portlocationInput">Activity Location</label>
                                <select class="selectpicker form-control" id="portlocationInput" data-live-search="true" name="port_location_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($ports as $item)
                                        <option value="{{$item->code}}" {{$item->code == old('port_location_id', request()->input('port_location_id')) ? 'selected':''}}>{{$item->code}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                            <label for="">Voyage No</label>
                                <select class="selectpicker form-control" id="voyage" data-live-search="true" name="voyage_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($voyages as $item)
                                        <option value="{{$item->voyage_no}}" {{$item->voyage_no == old('voyage_id',request()->input('voyage_id')) ? 'selected':''}}>{{$item->voyage_no}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- <div class="form-group col-md-3">
                            <label for="Movement">Movement Date</label>
                                <input type="date" class="form-control" id="movement_dateInput" name="movement_date" value="{{request()->input('movement_date')}}">
                            </div> -->
                            <div class="form-group col-md-3">
                                <label for="containersMovementsInput">Movement </label>
                                <select class="selectpicker form-control" id="containersMovementsInput" data-live-search="true" name="movement_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($containersMovements as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('movement_id',request()->input('movement_id')) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="BLNo">BL No</label>
                                <select class="selectpicker form-control" id="BLNoInput" data-live-search="true" name="bl_no" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($movementsBlNo as $item)
                                        @if($item != null)
                                        <option value="{{$item}}" {{$item == old('bl_no',request()->input('bl_no')) ? 'selected':''}}>{{$item}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="BLNo">Booking No</label>
                                <input type="text" class="form-control" id="BookingNoInput" name="booking_no" value="{{request()->input('booking_no')}}"
                                placeholder="Booking No" autocomplete="off">
                            </div>
                        </div>
                            <div class="col-md-12 text-center">
                                <button  type="submit" class="btn btn-success mt-3">Search</button>
                                <a href="{{route('tracking.create')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
