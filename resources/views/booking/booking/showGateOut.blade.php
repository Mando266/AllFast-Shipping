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

                    <div class="row">
                        <div class="col-md-6">
                            <img src="{{asset('assets/img/msl-logo.png')}}" style="width: 400px;" alt="logo">
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
                            @if(optional($booking->quotation)->shipment_type != "Import")
                                <tr>
                                    <th class="text-center thstyle underline">خطاب تحميل فوارغ صادر</th>
                                </tr>
                            @else
                                <tr>
                                    <th class="text-center thstyle underline">خطاب صرف حاويات وارد</th>
                                </tr>
                            @endif
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
                    $containerDetailsDisplay[] = " $count X $type";
                }

                @endphp

                <table class="col-md-12 tableStyle" >
                    <tbody>
                        <tr>
                            <td class="col-md-10 tableStyle" >&nbsp &nbsp &nbsp &nbsp{{ $mytime->format("D, F j, Y") }}</td>
                            <td class="col-md-2 tableStyle text-right underline" ></td>
                        </tr>
                        <tr>
                            @if(optional($booking->quotation)->shipment_type != "Import")
                            <td class="col-md-9 tableStyle text-right underline" >{{optional($booking->bookingContainerDetails->first()->activityLocation)->pick_up_location}}</td>
                            <td class="col-md-3 tableStyle text-right underline" >السادة</td>
                            @else
                            <td class="col-md-9 tableStyle text-right underline" >{{optional($booking->placeOfDelivery)->pick_up_location}}</td>
                            <td class="col-md-3 tableStyle text-right underline" >السادة</td>
                            @endif
                        </tr>
                        <tr>
                            <td class="col-md-9 tableStyle text-right underline" ></td>
                            <td class="col-md-3 tableStyle text-right underline" ></td>
                        </tr>

                    @if(optional($booking->quotation)->shipment_type == "Import")
                        <tr>
                            <td class="tableStyle text-right underline" colspan="2">
                                <textarea style="margin-bottom: -40px; text-align: right; border: none; resize: none; background-color: white;"></textarea>
                    يرجي التكرم بالسماح بصرف الحاويات ادناه من الدائره الجمركيه حتي يوم </td>
                        </tr>
                        <tr>
                            <!-- <td class="tableStyle text-right underline" colspan="2">
                                <textarea style="margin-bottom: -40px; text-align: right; border: none; resize: none; background-color: white;"></textarea>
                  تم تحصيل مصاريف الطاقة حتي يوم </td> -->
                        </tr>
                    @else
                        <tr>
                            <td class=" tableStyle text-right underline" colspan="2">برجاء من سيادتكم بالموافقة على تحميل وخروج عدد الحاويات التالية فارغ</td>
                        </tr>
                    @endif
                        <tr>
                            <td class=" tableStyle text-right underline" colspan="2"></td>
                        </tr>
                        <tr>
                            <td class="col-md-9 tableStyle text-right underline" ></td>
                            <td class="col-md-3 tableStyle text-right underline" ></td>
                        </tr>
                        @if(optional($booking->quotation)->shipment_type == "Import")
                        <tr>
                            <td class="col-md-9 tableStyle" >&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp{{optional($booking->consignee)->name}} <br>
                        </td>
                            <td class="col-md-3 tableStyle text-right underline" >العميل</td>
                        </tr>
                        @else
                        <tr>
                            <td class="col-md-9 tableStyle" >&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp{{optional($booking->customer)->name}} <br>
                        </td>
                            <td class="col-md-3 tableStyle text-right underline" >العميل</td>
                        </tr>
                        @endif
                        <tr>
                            <td class="col-md-9 tableStyle" >&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp{{ $booking->ref_no }}</td>
                        @if(optional($booking->quotation)->shipment_type == "Import")
                            <td class="col-md-3 tableStyle text-right underline" >رقم البوليصة </td>
                            @else
                            <td class="col-md-3 tableStyle text-right underline" >إذن شحن</td>
                        @endif
                        </tr>
                        @if(optional($booking->quotation)->shipment_type == "Import" && optional($booking)->transhipment_port == null)
                        <tr>
                            <td class="col-md-9 tableStyle" >&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp{{ $booking->voyage->vessel->name }} / {{ $booking->voyage->voyage_no}}</td>
                            @elseif(optional($booking->quotation)->shipment_type == "Import" && optional($booking)->transhipment_port != null)
                            <td class="col-md-9 tableStyle" >&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp{{ optional(optional($booking->secondvoyage)->vessel)->name }} / {{ optional($booking->secondvoyage)->voyage_no}}</td>
                            @else
                            <td class="col-md-9 tableStyle" >&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp{{ $booking->voyage->vessel->name }} / {{ $booking->voyage->voyage_no}}</td>
                            @endif
                            <td class="col-md-3 tableStyle text-right underline" >الباخرة / رحلة</td>
                        </tr>
                        @if(optional($booking->quotation)->shipment_type != "Import")
                        <tr>
                            <td class="col-md-9 tableStyle" >&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp{{optional($firstVoyagePort)->eta}}</td>
                            <td class="col-md-3 tableStyle text-right underline" >متوقع الوصول</td>
                        </tr>
                        @elseif(optional($booking->quotation)->shipment_type == "Import" && optional($booking)->transhipment_port == null)
                        <tr>
                            <td class="col-md-9 tableStyle" >&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp {{optional($secondVoyagePortImport)->eta}}</td>
                            <td class="col-md-3 tableStyle text-right underline" >تاريخ الرحلة</td>
                        </tr>
                        @else
                        <tr>
                            <td class="col-md-9 tableStyle" >&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp {{optional($firstVoyagePortImport)->eta}}</td>
                            <td class="col-md-3 tableStyle text-right underline" >تاريخ الرحلة</td>
                        </tr>
                        @endif
                        <tr>
                            <td class="col-md-9 tableStyle" style="padding-left: 80px;">
                            @foreach($containerDetailsDisplay as $containerType => $count)
                                {{ $count }}<br>
                            @endforeach
                        </td>
                            <td class="col-md-3 tableStyle text-right underline" >عدد الحاويات</td>
                        </tr>
                        @if(optional($booking->quotation)->shipment_type != "Import" || optional($booking)->shipment_type != "Import")
                        <tr>
                        @foreach($booking->truckerGates as $truckerGate)
                        <td class="col-md-9 tableStyle">&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp{{optional(optional($truckerGate)->trucker)->company_name}} </td>
                        @endforeach
                        <td class="col-md-3 tableStyle text-right underline">
                       مقاول النقل </td>
                        </tr>
                        @endif
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

         @if(optional($booking->quotation)->shipment_type != "Import")
            <td class="col-md-2 tableStyle underline" style="border: 1px solid #000; border-right-style: hidden; border-left-style: hidden; font-size: 14px; padding: .75rem;">
                Seal
            </td>
        @endif
        @if(optional($booking->quotation)->shipment_type != "Import")
            <td class="col-md-2 tableStyle underline" style="border: 1px solid #000; border-left-style: hidden; font-size: 14px; padding: .75rem;">
                Weight
            </td>
        @else
            <td class="col-md-2 tableStyle underline" style="border: 1px solid #000; border-left-style: hidden; font-size: 14px; padding: .75rem;">
                Notes
            </td>
        @endif
        </tr>
        @foreach($booking->bookingContainerDetails as $detail)
        <tr>
            <td class="col-md-2 tableStyle" style="border: 1px solid #000; border-right-style: hidden; font-size: 14px; padding: .75rem;">
                @if($detail->qty == 1)
                {{optional($detail->container)->code}}
                @else
                {{$detail->qty}}  Containers
                @endif
            </td>
            <td class="col-md-2 tableStyle" style="border: 1px solid #000; border-right-style: hidden; border-left-style: hidden; font-size: 14px; padding: .75rem;">
            {{substr(optional($detail->containerType)->name, 0, 2)}}
            </td>
            <td class="col-md-2 tableStyle" style="border: 1px solid #000; border-right-style: hidden; border-left-style: hidden; font-size: 14px; padding: .75rem;">
                {{optional($detail->containerType)->code}}
            </td>
        @if(optional($booking->quotation)->shipment_type != "Import")
            <td class="col-md-2 tableStyle" style="border: 1px solid #000; border-right-style: hidden; border-left-style: hidden; font-size: 14px; padding: .75rem;">
                {{$detail->seal_no}}
            </td>
        @endif

        @if(optional($booking->quotation)->shipment_type != "Import")
            <td class="col-md-2 tableStyle" style="border: 1px solid #000; border-left-style: hidden; font-size: 14px; padding: .75rem;">
            {{$detail->weight}}
            </td>
        @else
            <td class="col-md-2 tableStyle" style="border: 1px solid #000; border-left-style: hidden; font-size: 14px; padding: .75rem;">
            {{$detail->haz}}
            </td>
        @endif
        </tr>

        @endforeach
    </tbody>
</table>
            </div>
            <div class="form-row">
                                <div class="col-md-12 form-group">
                                    <label> Notes </label>
                                    <textarea class="form-control" name="notes">
                                    </textarea>
                                </div>
                            </div>
            @if(optional($booking->quotation)->shipment_type != "Import")
                <table class="col-md-12 tableStyle" >
                    <tbody>
                            <tr>
                                <td class="col-md-12 tableStyle text-right underline" >ملحوظه :- أي شطب او تعديل في الخطاب يعتبر الخطاب لاغي</td>
                            </tr>
                            <tr>
                                <td class="col-md-12 tableStyle text-right underline" >
                                    يتعهد الشاحن ووكيل الشاحن بالالتزام بأرقام السيول عن كل حاوية وفقا للبيان عالية وفي حالة الاختلاف أو عدم المطابقة يتم تطبيق غرامة
                                    مالية مائة دولار امريكي عن كل حاوية فيها الاختلاف مع تطبيق غرامات ميناء الوصول.
                                    رجاء ملاحظة ان مبالغ التأمين غير قابلة للاسترداد
                                </td>
                            </tr>
                    </tbody>
                </table>
            @else
                <table class="col-md-12 tableStyle" >
                    <tbody>
                        <tr>
                            <td class="col-md-4 tableStyle text-center">قسم الوارد </td>
                            <td class="col-md-8 tableStyle text-right underline"> <textarea style="margin-bottom: -40px; text-align: right; border: none; resize: none; background-color: white;"></textarea>  / مقاول النقل

                            </td>
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
            @endif

                </div>
                <div class="row">
                        <div class="col-md-12 text-center">
                <button onclick="window.print()" class="btn btn-primary hide mt-3">Print This Shipping Order</button>
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
