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
                            <li class="breadcrumb-item active"><a href="javascript:void(0);"> Edit</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                <form id="createForm" action="{{route('truckergate.update',['truckergate'=>$truckergate])}}" method="POST">
                        @csrf
                        @method('put')
                        <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="booking">Booking No <span class="text-warning"> * (Required.) </span> </label>
                            <select class="selectpicker form-control" id="booking" data-live-search="true" name="booking_id" data-size="10"
                                title="{{trans('forms.select')}}" required>
                                <option value="">Select...</option>
                                @foreach ($booking as $item)
                                    <option value="{{$item->id}}" {{$item->id == old('booking_id',$truckergate->booking_id) ? 'selected':''}}>{{$item->ref_no}}</option>
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
                                    <option value="{{$item->id}}" {{$item->id == old('trucker_id',$truckergate->trucker_id) ? 'selected':''}}>{{$item->company_name}}</option>
                                @endforeach
                            </select>
                            @error('trucker_id')
                            <div style="color: red;">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="valid">Valid To</label>
                                <input type="date" class="form-control" id="valid" name="valid_to" value="{{old('valid_to',$truckergate->valid_to)}}">
                                @error('valid_to')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                        </div>

                        <div class="form-group col-md-3">
                            <label for="issue_date">Issue Date</label>
                                <input type="date" class="form-control" id="issue_date" name="issue_date" value="{{old('issue_date',$truckergate->issue_date)}}">
                                @error('issue_date')
                                    <div style="color: red;">
                                        {{$message}}
                                    </div>
                                @enderror
                        </div>

                        <div class="form-group col-md-3">
                            <label for="inception_date">Inception Date</label>
                                <input type="date" class="form-control" id="inception_date" name="inception_date" value="{{old('inception_date',$truckergate->inception_date)}}">
                                @error('inception_date')
                                    <div style="color: red;">
                                        {{$message}}
                                    </div>
                                @enderror
                        </div>

                        <div class="form-group col-md-3">
                            <label for="payment_date">Payment Date</label>
                                <input type="date" class="form-control" id="payment_date" name="payment_date" value="{{old('payment_date',$truckergate->payment_date)}}">
                                @error('payment_date')
                                    <div style="color: red;">
                                        {{$message}}
                                    </div>
                                @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="tax">QTY</label>
                        <input type="text" class="form-control" id="qty" name="qty" value="{{old('qty',$truckergate->qty)}}"
                                placeholder="QTY" autocomplete="off">
                            @error('qty')
                            <div style="color: red;">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-md-3">
                            <label for="tax">Gross Premium</label>
                            <input type="text" class="form-control" id="gross_premium" name="gross_premium" value="{{old('gross_premium',$truckergate->gross_premium)}}"
                                placeholder="Gross Premium" autocomplete="off">
                            @error('gross_premium')
                            <div style="color: red;">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-md-3">
                            <label for="tax">Net Contribution</label>
                            <input type="text" class="form-control" id="net_contribution" name="net_contribution" value="{{old('net_contribution',$truckergate->net_contribution)}}"
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
                                <option value="1" {{ old('shipment',$truckergate->shipment) == "1" ? 'selected':'' }}>Export</option>
                                <option value="2" {{ old('shipment',$truckergate->shipment) == "2" ? 'selected':'' }}>Import</option>
                                <option value="3" {{ old('shipment',$truckergate->shipment) == "3" ? 'selected':'' }}>Empty Move</option>
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
                            <button type="submit" class="btn btn-primary mt-3">{{trans('forms.update')}}</button>
                            <a href="{{route('trucker.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                        </div>
                    </div>
                    
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection