@extends('layouts.app')
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">

        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Operation </a></li>
                            <li class="breadcrumb-item"><a href="{{route('truckergate.index')}}">Trucker Gates</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);"> New Trucker Gate</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                <form novalidate id="createForm" action="{{route('truckergate.store')}}" method="POST">
                        @csrf
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="booking">Booking No <span class="text-warning"> * (Required.) </span> </label>
                            <select class="selectpicker form-control" id="booking" data-live-search="true" name="booking_id" data-size="10"
                                title="{{trans('forms.select')}}" required>
                                <option value="">Select...</option>
                                @foreach ($booking as $item)
                                    <option value="{{$item->id}}" {{$item->id == old('booking_id') ? 'selected':''}}>{{$item->ref_no}}</option>
                                @endforeach
                            </select>
                            @error('booking_id')
                            <div style="color: red;">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        
                        <div class="form-group col-md-6">
                            <label for="booking">Trucker <span class="text-warning"> * (Required.) </span></label>
                            <select class="selectpicker form-control" id="booking" data-live-search="true" name="trucker_id" data-size="10"
                                title="{{trans('forms.select')}}" required>
                                <option value="">Select...</option>
                                @foreach ($truckers as $item)
                                    <option value="{{$item->id}}" {{$item->id == old('trucker_id') ? 'selected':''}}>{{$item->company_name}}</option>
                                @endforeach
                            </select>
                            @error('trucker_id')
                            <div style="color: red;">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <input type="hidden" name="beneficiry_name" value="MSL">
                        <input type="hidden" name="operator" value="MSL">

                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="valid">Valid To</label>
                                <input type="date" class="form-control" id="valid" name="valid_to" value="{{old('valid_to',date('Y-m-d'))}}">
                                @error('valid_to')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                        </div>

                        <div class="form-group col-md-3">
                            <label for="issue_date">Issue Date</label>
                                <input type="date" class="form-control" id="issue_date" name="issue_date" value="{{old('issue_date',date('Y-m-d'))}}">
                                @error('issue_date')
                                    <div style="color: red;">
                                        {{$message}}
                                    </div>
                                @enderror
                        </div>

                        <div class="form-group col-md-3">
                            <label for="inception_date">Inception Date</label>
                                <input type="date" class="form-control" id="inception_date" name="inception_date" value="{{old('inception_date',date('Y-m-d'))}}">
                                @error('inception_date')
                                    <div style="color: red;">
                                        {{$message}}
                                    </div>
                                @enderror
                        </div>

                        <div class="form-group col-md-3">
                            <label for="payment_date">Payment Date</label>
                                <input type="date" class="form-control" id="payment_date" name="payment_date" value="{{old('payment_date',date('Y-m-d'))}}">
                                @error('payment_date')
                                    <div style="color: red;">
                                        {{$message}}
                                    </div>
                                @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="tax">QTY <span class="text-warning"> * (Required.) </span></label>
                        <input type="text" class="form-control" id="qty" name="qty" value="{{old('qty')}}"
                                placeholder="QTY" autocomplete="off" required>
                            @error('qty')
                            <div style="color: red;">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-md-3">
                            <label for="tax">Gross Premium</label>
                            <input type="text" class="form-control" id="gross_premium" name="gross_premium" value="650"
                                placeholder="Gross Premium" autocomplete="off">
                            @error('gross_premium')
                            <div style="color: red;">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-md-3">
                            <label for="tax">Net Contribution</label>
                            <input type="text" class="form-control" id="net_contribution" name="net_contribution" value="600"
                                placeholder="Net Contribution" autocomplete="off">
                            @error('net_contribution')
                            <div style="color: red;">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-md-3">
                            <label for="tax">Shipment</label>
                            <select class="form-control" id="shipment" data-live-search="true" name="shipment" data-size="10">
                                <option value="1">Export</option>
                                <option value="2">Import</option>
                                <option value="3">Empty Move</option>
                            </select>
                            @error('shipment')
                            <div style="color: red;">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-primary mt-3">{{trans('forms.create')}}</button>
                            <a href="{{route('truckergate.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                        </div>
                    </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection


