@extends('layouts.app')

@section('content')

<div class="layout-px-spacing" style="background-color: #fff;">

    <div class="row layout-top-spacing">

        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">

            <div class="widget widget-one">

                <div class="widget-heading hide">

                    <nav class="breadcrumb-two" aria-label="breadcrumb">

                        <ol class="breadcrumb">

                            <li class="breadcrumb-item"><a href="{{route('booking.index')}}">Booking </a></li>

                            <li class="breadcrumb-item active"><a href="javascript:void(0);"> Booking Confirmation</a></li>

                            <li class="breadcrumb-item"></li>

                        </ol>

                    </nav>

                </div>

                <div class="widget-content widget-content-area">

                <div class="col-md-12 text-center">

                <img src="{{asset('assets/img/allfastLogo.png')}}" style="width: 350px;" alt="logo">

                </div>
</br>
</br>
                <table class="col-md-12 tableStyle">

                    <thead>

                        <tr>

                        @if($booking->booking_confirm == 1)

                            <th class="text-center thstyle">Booking Confirmation</th>

                        @else

                            <th class="text-center thstyle">Booking Aknoledgment</th>

                        @endif

                        </tr>

                    </thead>

                </table>

                <table class="col-md-12 tableStyle">

                    <tbody>

                        <tr>

                            <td class="tableStyle" style="width: 50%;" style="width: 50%;">Booking Ref N : {{$booking->ref_no}}</td>

                            <td class="tableStyle" style="width: 50%;">Issue Date : {{Carbon\Carbon::parse($booking->created_at)->format('Y-m-d')}}</td>

                        </tr>

                    </tbody>

                </table>



                <table class="col-md-12 tableStyle">

                    <thead>

                        <tr>

                            <th class="tableStyle thstyle">Name And Address</th>

                            <th class="tableStyle thstyle">Forwarder</th>

                        </tr>

                    </thead>

                    <tbody>

                        <tr>

                            <td class="tableStyle" style="width: 50%;">{{optional($booking->customer)->name}}</br>{{optional($booking->customer)->address}}

                            </br>phone : {{optional($booking->customer)->phone}}</br>Email : {{optional($booking->customer)->email}}</td>

                            <td class="tableStyle" style="width: 50%;">{{optional($booking->forwarder)->name}}</br>{{optional($booking->forwarder)->address}}

                            </br>phone : {{optional($booking->forwarder)->phone}}</br>Email : {{optional($booking->forwarder)->email}}</td>

                        </tr>

                    </tbody>

                </table>

                <table class="col-md-12 tableStyle">

                    <tbody>

                        <tr>

                            <td class="tableStyle" style="width: 50%;">Shipper Ref No : {{$booking->shipper_ref_no}}</td>

                            <td class="tableStyle" style="width: 50%;">Forwarder Ref No : {{$booking->forwarder_ref_no}}</td>

                        </tr>

                    </tbody>

                </table>

                <table class="col-md-12 tableStyle">

                    <tbody>

                        <tr>

                            <td class="tableStyle" style="width: 50%;">Vessel / Voyage : @if($booking->voyage != null){{ $booking->voyage->vessel->name }} / {{ $booking->voyage->voyage_no}} @endif</td>

                            <td class="tableStyle" style="width: 50%;">ETA: {{optional($firstVoyagePort)->eta}}</br></br>ETD: {{optional($firstVoyagePort)->etd}}</td>

                        </tr>

              

                    </tbody>

                </table>

                <table class="col-md-12 tableStyle">

                    <tbody>

                        <tr>

                            <td class="tableStyle" style="width: 50%;">Place of Acceptance : &nbsp; <span>{{optional($booking->placeOfAcceptence)->name}}</span></td>

                            <td class="tableStyle" style="width: 50%;">Place of Delivery : &nbsp; <span>{{optional($booking->placeOfDelivery)->name}}</span></td>

                        </tr>

                        <tr>

                            <td class="tableStyle" style="width: 50%;">Port of Loading: &nbsp; <span>{{optional($booking->loadPort)->name}}</span></td>

                            <td class="tableStyle" style="width: 50%;">Port of Discharge : &nbsp; <span>{{optional($booking->dischargePort)->name}}</span></td>

                        </tr>

                    </tbody>

                </table>

                <table class="col-md-12 tableStyle">

                    <tbody>

                        <tr>

                            <td class="tableStyle"  style="width: 50%;" >Pick Up Location : @foreach($booking->bookingContainerDetails->unique('activity_location_id') as $bookingContainerDetail)

                               - {{ optional($bookingContainerDetail->activityLocation)->pick_up_location }}

                            @endforeach

                            </td>
                            <td class="tableStyle"  style="width: 50%;" >Container Operator : {{optional($booking->principal)->name}}</td>



                        </tr>

                    </tbody>

                </table>


                <table class="col-md-12 tableStyle">

                    <tbody>

                        <tr>

                            <td class="tableStyle" style="width: 50%;">BL Release : {{optional($booking->agent)->name}}</td>

                            <td class="tableStyle" style="width: 50%;"></td>

                        </tr>

                    </tbody>

                </table>

                <table class="col-md-12 tableStyle">

                    <tbody>

                        <tr>

                            <td class="tableStyle" style="width: 50%;">Booked By : {{optional($booking->bookedby)->full_name}}</td>

                            <td class="tableStyle" style="width: 50%;">Tarrif/Service Contract : {{optional($booking->quotation)->ref_no}}</td>

                        </tr>

                    </tbody>

                </table>

                <table class="col-md-12 tableStyle">

                    <tbody>

                        <tr>

                            <td class="tableStyle" style="width: 50%;">Commodity Description : {{$booking->commodity_description}}</td>

                            <td class="tableStyle" style="width: 50%;">Notes  : {{$booking->notes}}</td>



                        </tr>

                    </tbody>

                </table>



                <h4>Container Details :</h4>

                <table class="col-md-12 tableStyle">

                    <tbody>

                        <tr>

                            <td class="tableStyle thstyle">Items #</td>

                            <td class="tableStyle thstyle">Size Type</td>
                            <td class="tableStyle thstyle">Container No</td>
                            <td class="tableStyle thstyle">QTY</td>
                            <td class="tableStyle thstyle">Seal No</td>
                            <td class="tableStyle thstyle">Weight</td>
                            <td class="tableStyle thstyle">Haz / Reefer / OOG Details / Haz Approval Ref.</td>
                        </tr>

                        @foreach($booking->bookingContainerDetails as $details)

                        <tr>

                            <td class="tableStyle">{{ $loop->iteration }}</td>

                            <td class="tableStyle">{{ optional($details->containerType)->name }}</td>
                            <td class="tableStyle">{{ optional($details->container)->code }}</td>
                            <td class="tableStyle">{{ $details->qty }}</td>
                            <td class="tableStyle">{{ $details->seal_no }}</td>
                            <td class="tableStyle">{{ $details->weight}}</td>
                            <td class="tableStyle">{{ $details->haz}}</td>
                        </tr>

                        @endforeach

                    </tbody>

                </table>



                </div>

                <div class="row">

                        <div class="col-md-12 text-center">

                <button onclick="window.print()" class="btn btn-primary hide mt-3">Print This Booking</button>

                </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection

@push('styles')

<style>

    @media print {

    .search_row,

    .hide {

        display: none !important;

        }

    }

    .tableStyle {

        font-size: 14px !important;

        font-weight: bolder !important;

        border: 1px solid #000 !important;

        margin-bottom: 1rem;

        height: 50px;

        color: black;

        text-transform: uppercase;

        padding: .75rem;

    }

    .thstyle {

        background-color: #80808061 !important;

        color: #000 !important;

        height: 50px;

        border: 1px solid #000 !important;

        font-size: 16px !important;

        font-weight: bolder !important;

    }

</style>

@endpush