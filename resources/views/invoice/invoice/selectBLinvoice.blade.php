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
                                <label for="Bldraft">BlDraft Number <span class="text-warning"> * </span></label>
                                <select class="selectpicker form-control" id="Bldraft" data-live-search="true" name="booking_ref" data-size="10"
                                        title="{{trans('forms.select')}}" required>
                                    <optgroup label="Import BL">
                                        @foreach ($booking as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('booking_ref',request()->input('booking_ref')) ? 'selected':''}}>{{$item->ref_no}}</option>
                                        @endforeach
                                    </optgroup>
                                    <optgroup label="Export BL">
                                        @foreach ($bldrafts as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('bldraft_id',request()->input('bldraft_id')) ? 'selected':''}}>{{$item->ref_no}}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                    <div style="padding: 30px;">
                                        <input class="form-check-input" type="radio" name="add_egp" id="add_egp"
                                               value="USD" checked>
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectElement = document.getElementById('Bldraft');
        selectElement.addEventListener('change', function () {
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const isBooking = selectedOption.parentElement.label === 'Import BL';
            const inputName = isBooking ? 'booking_ref' : 'bldraft_id';

            // Update the name attribute based on the selection
            selectElement.name = inputName;
        });
    });
</script>
@endpush