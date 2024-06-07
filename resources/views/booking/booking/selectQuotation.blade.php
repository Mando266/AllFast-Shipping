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
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">Create New Booking</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                    <form id="createForm" action="{{route('booking.create')}}" method="get">
                            @csrf
                        <div class="form-row">
                            <div class="form-group col-md-8">
                                <label for="quotation">Quotation</label>
                                <select class="selectpicker form-control" id="quotation_id" name="quotation_id" data-live-search="true" data-size="10"
                                    title="{{trans('forms.select')}}">
                                        <option value="draft" >Draft Or Transhipment</option>
                                    @foreach ($quotation as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('quotation_id') ? 'selected':''}}>{{$item->ref_no}} - {{optional($item->equipmentsType)->name}} - {{optional($item->customer)->name}} - {{$item->validity_from}}</option>
                                    @endforeach
                                </select>
                                @error('quotation_id')
                                <div style="color:red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                               
                        </div>

                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary mt-3">{{trans('Next')}}</button>
                                <a href="{{route('booking.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

