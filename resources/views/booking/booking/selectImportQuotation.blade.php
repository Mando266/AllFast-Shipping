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
                        <div class="form-row align-items-center">
                            <div class="form-group col-md-7">
                                <label for="quotation">Quotation</label>
                                <select class="selectpicker form-control" id="quotation_id" name="quotation_id" data-live-search="true" data-size="10"
                                    title="{{trans('forms.select')}}">
                                        <option value='0'>No Quotation</option>
                                    @foreach ($quotation as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('quotation_id') ? 'selected':''}}>{{$item->ref_no}} - {{optional($item->customer)->name}}</option>
                                    @endforeach
                                </select>
                                @error('quotation_id')
                                <div style="color:red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="col-2"></div>
                        
                            <!-- Transhipment Checkbox -->
                            <div class="form-group col-md-3">
                                <label for="is_transhipment" class="mr-2 mb-0">Is Transhipment</label>
                                <input type="checkbox" id="is_transhipment" name="is_transhipment">
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

