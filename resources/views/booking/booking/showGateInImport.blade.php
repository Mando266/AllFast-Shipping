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
                            <li class="breadcrumb-item active"><a href="javascript:void(0);"> Gate In</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">

                    <div class="row">
                        <div class="col-md-6">
                            <img src="{{asset('assets/img/msl.png')}}" style="width: 400px;" alt="logo">
                        </div>
                        <div class="col-md-6 tableStyle text-right underline" style="font-size: 30px; font-weight:bold !important">
                            {{optional($booking->principal)->name}}
                        </div>
                        <div class="col-md-3">
                        </div>
                        <table class="col-md-6 " style="height: 20px;">

                            <tbody>
                            <tr>
                                <td style="height: 45px;"></td>
                            </tr>
                                <tr>
                                    <th class="text-center thstyle underline">خطاب تعتيق فوارغ وارد</th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </br>
                </br>
                @php
                $mytime = Carbon\Carbon::now();
                $containerTypes = []; 

                foreach ($booking->bookingContainerDetails as $detail) {
                    $containerType = optional($detail->containerType)->name;
                    $haz = $detail->haz;
                    
                    if (!isset($containerTypes[$containerType])) {
                        $containerTypes[$containerType] = 0;
                    }
                    
                    $containerTypes[$containerType] += $detail->qty;
                }

                $containerDetailsDisplay = [];
                foreach ($containerTypes as $type => $count) {
                    $containerDetailsDisplay[] = "$type * $count";
                }

                @endphp

                <table class="col-md-12 tableStyle" >
                    <tbody>
                        <tr>
                            <td class="col-md-10 tableStyle" >&nbsp &nbsp &nbsp &nbsp{{ $mytime->format("D, F j, Y") }}</td>
                            <td class="col-md-2 tableStyle text-right underline" ></td>
                        </tr>
                        <tr>
                            <td class="col-md-9 tableStyle text-right underline" >{{optional($booking->bookingContainerDetails->first()->activityLocation)->pick_up_location}}</td>
                            <td class="col-md-3 tableStyle text-right underline" >السادة</td> 
                        </tr>
                        <tr>
                            <td class="col-md-9 tableStyle text-right underline" ></td>
                            <td class="col-md-3 tableStyle text-right underline" ></td>
                        </tr>
                        <tr>
                        <td class="col-md-9 tableStyle text-right underline"></td>
                            @if(optional($booking->principal)->code == 'PLS')
                            <td class="col-md-3 tableStyle text-right underline" >{{optional($booking->principal)->code}} SOC</td>
                            @elseif(optional($booking->principal)->code == 'MAS')
                            <td class="col-md-3 tableStyle text-right underline" >{{optional($booking->principal)->code}} COC</td>
                            @else
                            <td class="col-md-3 tableStyle text-right underline" >{{optional($booking->principal)->code}} </td>
                            @endif
                        </tr>
                        <tr>
                        <td class=" tableStyle text-right underline" colspan="2">برجاء من سيادتكم بالموافقة على دخول وتعتيق الحاويات أدناه حتي يوم </td>
                        </tr>
                        <tr>
                            <td class=" tableStyle text-right underline" colspan="2"></td>
                        </tr>
                        <tr>
                            <td class="col-md-9 tableStyle text-right underline" ></td>
                            <td class="col-md-3 tableStyle text-right underline" ></td>
                        </tr>
                        <tr>
                            <td class="col-md-9 tableStyle" style="padding-left: 80px;">{{optional(optional($booking)->consignee)->name}} <br>
                            {{optional($booking->consignee)->address}} &nbsp {{optional(optional($booking->consignee)->country)->name}} &nbsp {{optional(optional($booking)->consignee)->landline}}</td>
                            <td class="col-md-3 tableStyle text-right underline" >العميل</td>
                        </tr>
                        <tr>
                            <td class="col-md-9 tableStyle" style="padding-left: 80px;">{{ $booking->ref_no }}</td>
                            <td class="col-md-3 tableStyle text-right underline" >B/L NO </td>
                        </tr>
                        <tr>
                            <td class="col-md-9 tableStyle" style="padding-left: 80px;">{{ optional(optional($booking)->voyage)->vessel->name }} / {{ optional(optional($booking)->voyage)->voyage_no}}</td>
                            
                            <td class="col-md-3 tableStyle text-right underline" >الباخرة / رحلة</td>
                        </tr>

                        <tr>
                        <td class="col-md-9 tableStyle" style="padding-left: 80px;">
                            @foreach($containerDetailsDisplay as $containerType => $count)
                                {{ $count }}<br>
                            @endforeach
                        </td>
                        <td class="col-md-3 tableStyle text-right underline" >عدد الحاويات</td>
                        </tr>
                    </tbody>
                </table>
                <div class="row">
                <div class="col-md-1">

</div>
<table class="col-md-10 tableStyle" >
    <tbody>
        <tr>
            <td class="col-md-2 tableStyle underline" style="border: 1px solid #000; border-right-style: hidden; font-size: 14px; padding: .75rem;">
                Container No.
            </td>
            <td class="col-md-2 tableStyle underline" style="border: 1px solid #000; border-right-style: hidden; border-left-style: hidden; font-size: 14px; padding: .75rem;">
                Size
            </td>
            <td class="col-md-2 tableStyle underline" style="border: 1px solid #000; border-right-style: hidden; border-left-style: hidden; font-size: 14px; padding: .75rem;">
                Type
            </td>

            <td class="col-md-2 tableStyle underline" style="border: 1px solid #000; border-left-style: hidden; font-size: 14px; padding: .75rem;">
                Notes
            </td>
        </tr>
        @foreach($booking->bookingContainerDetails as $detail)
        @if($detail->qty == 1)
        <tr>

            <td class="col-md-2 tableStyle" style="border: 1px solid #000; border-right-style: hidden; font-size: 14px; padding: .75rem;">
                {{optional($detail->container)->code}}
            </td>
            <td class="col-md-2 tableStyle" style="border: 1px solid #000; border-right-style: hidden; border-left-style: hidden; font-size: 14px; padding: .75rem;">
            {{substr(optional($detail->containerType)->name, 0, 2)}}
            </td>
            <td class="col-md-2 tableStyle" style="border: 1px solid #000; border-right-style: hidden; border-left-style: hidden; font-size: 14px; padding: .75rem;">
                {{optional($detail->containerType)->code}}
            </td>

            <td class="col-md-2 tableStyle" style="border: 1px solid #000; border-left-style: hidden; font-size: 14px; padding: .75rem;">
                {{$detail->haz}}
            </td>
            </tr>

            @elseif($detail->container == 000 )
                @for($i=0 ; $i <$detail->qty ; $i++)
                <tr>

                <td class="col-md-2 tableStyle" style="border: 1px solid #000; border-right-style: hidden; font-size: 14px; padding: .75rem;">
                {{optional($detail->container)->code}}
                </td>
                <td class="col-md-2 tableStyle" style="border: 1px solid #000; border-right-style: hidden; border-left-style: hidden; font-size: 14px; padding: .75rem;">
            {{substr(optional($detail->containerType)->name, 0, 2)}}
            </td>
            <td class="col-md-2 tableStyle" style="border: 1px solid #000; border-right-style: hidden; border-left-style: hidden; font-size: 14px; padding: .75rem;">
                {{optional($detail->containerType)->code}}
            </td>
           
            <td class="col-md-2 tableStyle" style="border: 1px solid #000; border-left-style: hidden; font-size: 14px; padding: .75rem;">
            {{$detail->haz}}
            </td>
                <!-- {{$detail->qty}}  Containers -->

        </tr>
        @endfor
        @endif
        @endforeach
    </tbody>
</table>
                </div>
                <table class="col-md-12 tableStyle" >
                    <tbody>
                        <tr>
                            <td class="col-md-4 tableStyle text-center">قسم الوارد </td>
                        </tr>
                    </tbody>
                </table>

                <table class="col-md-12 tableStyle" >
                    <tbody>
                        <tr>
                            <td class="col-md-12 tableStyle text-right underline" >ملحوظه :- أي شطب او تعديل في الخطاب يعتبر الخطاب لاغي</td>
                        </tr>
                    </tbody>
                </table>


                </div>
                <div class="row">
                        <div class="col-md-12 text-center">
                <button onclick="window.print()" class="btn btn-primary hide mt-3">Print This Gate In</button>
                <a href="{{route('booking.index')}}" class="btn btn-danger hide mt-3">{{trans('forms.cancel')}}</a>
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
        font-size: 22px;
        font-weight: 500 !important;
        border: none;
        margin-bottom: 1rem;
        height: 40px;
        color: black;
        padding: 0 .75rem;
    }
    .underline{
        text-decoration: underline;
    }
    .thstyle {
        background-color: #fff !important;
        color: black !important;
        height: 35px;
        border: 1px solid #000 !important;
        font-size: 25px !important;
        font-weight: bolder !important;
    }
</style>
