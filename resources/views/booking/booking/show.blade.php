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
                
<div class="container">
        <table>
            <tr>
                <td colspan="2">From: </td>
                <td colspan="2">All Fast Shipping Line</td>
                <td colspan="2">To:</td>
            </tr>
            <tr>
                <td colspan="2">Address:</td>
                <td colspan="2"></td>
                <td colspan="2">Address:</td>
            </tr>
        </table>
        <p class="section-title">ARRIVAL NOTIFICATION<br><span class="arabic">اخطار وصول شحنه</span></p>
        <table>
            <tr>
                <td>Vessel:</td>
                <td style = "text-align: center;">@if($booking->voyage != null){{ $booking->voyage->vessel->name }} @endif</td>
                <td style = "text-align: right;">:الباخرة</td>
            </tr>
            <tr>
                <td>Voyage:</td>
                <td style = "text-align: center;">@if($booking->voyage != null) {{ $booking->voyage->voyage_no}} @endif</td>
                <td style = "text-align: right;"> :رقم الرحلة</td>
            </tr>
            <tr>
                <td>E.T.A:</td>
                <td style = "text-align: center;">{{optional($firstVoyagePort)->eta}}</td>
                <td style = "text-align: right;">:تاريخ الوصول المتوقع</td>
            </tr>
            <tr>
                <td>Bill of Lading:</td>
                <td style = "text-align: center;">{{$booking->ref_no}}</td>
                <td style = "text-align: right;">رقم البوليصه</td>
            </tr>
            <tr>
                <td>Container(s):</td>
                <td style = "text-align: center;">

                @php
                    $containerTypes = $booking->bookingContainerDetails->pluck('containerType.name')->unique();
                @endphp
                @if($containerTypes->count() > 1)
                    @foreach($booking->bookingContainerDetails as $detail)
                        {{ $detail->qty }} X {{ optional($detail->containerType)->name }} <br>
                    @endforeach
                @else
                    @php
                        $totalQty = $booking->bookingContainerDetails->sum('qty');
                        $singleType = $booking->bookingContainerDetails->first();
                    @endphp
                    {{ $totalQty }}
                @endif
                </td>
                <td style = "text-align: right;">عدد الحاويات</td>
            </tr>
            <tr>
                <td>Port of Loading:</td>
                <td style = "text-align: center;"> {{optional($booking->loadPort)->name}}</td>
                <td style = "text-align: right;">ميناء الشحن:</td>
            </tr>
            <tr>
                <td>Port of Discharge:</td>
                <td style = "text-align: center;">{{optional(optional($booking)->dischargePort)->name}}</td>
                <td style = "text-align: right;">ميناء التفريغ:</td>
            </tr>
            <tr>
                <td>Shipper:</td>
                <td style = "text-align: center;">{{optional(optional($booking)->customer)->name}}</td>
                <td style = "text-align: right;">الشاحن:</td>
            </tr>
            <tr>
                <td>Consignee:</td>
                <td style = "text-align: center;">{{optional(optional($booking)->consignee)->name}}</td>
                <td style = "text-align: right;">المستلم:</td>
            </tr>
            <tr>
                <td>Notify:</td>
                <td></td>
                <td style = "text-align: right;">نائب المستلم:</td>
            </tr>
        </table>
        <div class="footer">
            الرجاء الحضور لمقر الشركة لبدء إجراءات صرف رسالتكم بعد تفريغ الحاوية<br>
            لتفادي احتساب أي مصروفات زائدة او غرامات الرجاء سرعة الحضور لانهاء الإجراءات الخاصة بكم
        </div>
    </div>


                <div class="row">

                        <div class="col-md-12 text-center">

                <button onclick="window.print()" class="btn btn-primary hide mt-3">Print This Arrival Notification 
        
                </button>

                </div>

        </div>

    </div>

</div>

@endsection

@push('styles')

   <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            width: 80%;
            margin: auto;
            border: 1px solid #000;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            font-size: 18px;
            color: black;
        }
        th {
            background-color: #f2f2f2;
        }
        .section-title {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
        }
        .arabic {
            font-family: 'Amiri', serif;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 16px;
            color: black;
        }
    </style>

@endpush