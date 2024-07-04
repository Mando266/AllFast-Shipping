@extends('layouts.app')
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a a href="{{route('invoice.index')}}">Invoice</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">New Invoice</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                    <form id="createForm" action="{{route('invoice.create_invoice')}}" method="get">
                            @csrf
                            <form>
                    <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="Bldraft">BlDraft Number <span class="text-warning"> * (Required.) </span></label>
                                <select class="selectpicker form-control" id="Bldraft" data-live-search="true" name="booking_ref" data-size="10"
                                 title="{{trans('forms.select')}}" required>
                                 <!-- <option value="0">Customized Invoice</option> -->
                                    @foreach ($results as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('booking_ref',request()->input('booking_ref')) ? 'selected':''}}>{{$item->ref_no}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                    <div style="padding: 30px;">
                                        <input class="form-check-input" type="radio" name="add_egp" id="add_egp"
                                               value="USD">
                                        <label class="form-check-label" for="add_egp">
                                            USD
                                        </label>
                                        <br>
                                        <input class="form-check-input" type="radio" name="add_egp" id="add_egp"
                                               value="EGP">
                                        <label class="form-check-label" for="EGP">
                                            EGP
                                        </label>
                                    </div>
                                </div>
                    </div>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary mt-3">Next</button>
                                <a href="{{route('invoice.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

