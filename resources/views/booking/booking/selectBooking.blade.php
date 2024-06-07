@extends('layouts.app')
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a a href="{{route('booking.index')}}">Booking</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">Import Booking Containers</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                    @if(Session::has('message'))
                        <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('message') }}</p>
                        @endif
                    <form action="{{route('importBooking')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                        <div class="form-row">
                            {{-- <div class="form-group col-md-6">
                                <label for="booking_id">Select Booking</label>
                                <select class="selectpicker form-control" id="booking_id" name="booking_id" required data-live-search="true" data-size="10"
                                    title="{{trans('forms.select')}}">
                                    @foreach ($bookings as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('booking_id') ? 'selected':''}}>{{$item->ref_no}}</option>
                                    @endforeach
                                </select>
                                @error('booking_id')
                                <div style="color:red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div> --}}
                            <div class="col-md-12 text-center mb-6">
                                {{ csrf_field() }}
                                    <input type="file" name="file" >
                                    <button  id="buttonSubmit" class="btn btn-success  mt-3" >Import</button>
                            </div>
                               
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

