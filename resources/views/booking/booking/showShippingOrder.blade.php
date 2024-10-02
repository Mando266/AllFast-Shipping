@extends('layouts.bldraft') 
@section('content')
<div class="layout-px-spacing" style="background-color: #fff;">
    <div class="row layout-top-spacing">
        <div>
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
                        <div class="col-md-6 text-center">
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
                                <tr>
                                    <th class="text-center thstyle underline">  Shipping Order No {{ $booking->ref_no }}  إذن شحن رقم</th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </br>
                </br>
                @php
                $mytime = Carbon\Carbon::now();
                $containerCount = 0;
                $firstContainerDetail = $booking->bookingContainerDetails->first();
                $containerType = optional(optional($firstContainerDetail)->containerType)->name;
                $haz = optional($firstContainerDetail)->haz;
                foreach($booking->bookingContainerDetails as $detail){
                    $containerCount = $containerCount + $detail->qty;
                    $containerType = optional($detail->containerType)->name;
                }
                @endphp
                @php
                    $data = $haz;
                    $hazformat = implode(",<br>", explode(", ", $data));
                @endphp
                @php
                    $gross_weight = 0;
                @endphp
                @foreach($booking->bookingContainerDetails as $detail)
                    @php
                        $gross_weight = $gross_weight + (float)$detail->weight;
                    @endphp
                @endforeach
                <table class="col-md-12 tableStyle" >
                    <tbody>
                        <tr>
                            <td class="col-md-4 tableStyle underline" >IMO No.</td>
                            <td class="col-md-4 tableStyle text-center underline" >560-161-093</td>
                            <td class="col-md-4 tableStyle text-right underline letter-spacing: 0px;" > الرقم الضريبي</td>
                        </tr>
                        <tr>
                            <td class="col-md-4 tableStyle underline" >Booking Ref.</td>
                            <td class="col-md-4 tableStyle text-center" >{{ $booking->ref_no }}</td>
                            <td class="col-md-4 tableStyle text-right underline letter-spacing: 0px;" >رقم الحجز</td>
                        </tr>
                        <tr>
                            <td class="col-md-4 tableStyle underline" >ON</td>
                            <td class="col-md-4 tableStyle text-center" >{{ $mytime->format("D, F, j") }}</td>
                            <td class="col-md-4 tableStyle text-right underline letter-spacing: 0px;" >في</td>
                        </tr>
                        <tr>
                            <td class="col-md-4 tableStyle underline" >Please receive on M/V</td>
                            <td class="col-md-4 tableStyle text-center" >{{ $booking->voyage->vessel->name }} / {{ $booking->voyage->voyage_no}}</td>
                            <td class="col-md-4 tableStyle text-right underline letter-spacing: 0px;" >الرجاء الإستلام على الباخرة / رحلة</td>
                        </tr>
                        <tr>
                            <td class="col-md-4 tableStyle underline" >ETA</td>
                            <td class="col-md-4 tableStyle text-center" >{{optional($firstVoyagePort)->eta}}</td>
                            <td class="col-md-4 tableStyle text-right underline letter-spacing: 0px;">المتوقع وصولها في</td>
                        </tr>
                        <tr>
                            <td class="col-md-4 tableStyle underline" >Shipper</td>
                            <td class="col-md-4 tableStyle text-center" >{{optional($booking->customer)->name}} </td>
                            <td class="col-md-4 tableStyle text-right underline letter-spacing: 0px;" >الشاحن</td>
                        </tr>
                        <tr>
                            <td class="col-md-4 tableStyle underline" >POL</td>
                            <td class="col-md-4 tableStyle text-center" >{{optional($booking->loadPort)->name}}</td>
                            <td class="col-md-4 tableStyle text-right underline letter-spacing: 0px;" >ميناء الشحن</td>
                        </tr>
                        <tr>
                            <td class="col-md-4 tableStyle underline" >Transshipment Port</td>
                            <td class="col-md-4 tableStyle text-center" >{{optional($booking->transhipmentPort)->name}}</td>
                            <td class="col-md-4 tableStyle text-right underline letter-spacing: 0px;" >ميناء الترنزيت</td>
                        </tr>
                        <tr>
                            <td class="col-md-4 tableStyle underline" >POD</td>
                            <td class="col-md-4 tableStyle text-center" >{{optional($booking->dischargePort)->name}}</td>
                            <td class="col-md-4 tableStyle text-right underline letter-spacing: 0px;">ميناء الوصول</td>
                        </tr>
                        <tr>
                            <td class="col-md-4 tableStyle underline" >No Of Containers</td>
                            <td class="col-md-4 tableStyle text-center">  
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
                                {{ $totalQty }} X {{ optional($singleType->containerType)->name }}
                            @endif
                            </td>
                        <td class="col-md-4 tableStyle text-right underline letter-spacing: 0px;">عدد الحاويات</td>
                        </tr>
                        <tr>
                            <td class="col-md-4 tableStyle underline" >Cargo / Weight</td>
                            <td class="col-md-4 tableStyle text-center" >{{$booking->commodity_description}} - {{$gross_weight}}</td>
                            <td class="col-md-4 tableStyle text-right underline letter-spacing: 0px;">وصف البضاعة / وزن</td>
                        </tr>
                        <tr>
                            <td class="col-md-4 tableStyle underline" ></td>
                            <td class="col-md-4 tableStyle text-center" >{!! nl2br(e($haz)) !!}</td>
                            <td class="col-md-4 tableStyle text-right underline" ></td>
                        </tr>

                        {{-- <tr>
                            @if(optional($booking->principal)->code == 'PLS')
                            <td class="col-md-4 tableStyle">Container Operator : {{optional($booking->principal)->code}} SOC</td>
                            @elseif(optional($booking->principal)->code == 'MAS')
                            <td class="col-md-4 tableStyle">Container Operator : {{optional($booking->principal)->code}} COC</td>
                            @else
                            <td class="col-md-4 tableStyle">Container Operator : {{optional($booking->principal)->code}}</td>
                            @endif
                            <td class="col-md-4 tableStyle text-center">Pick Up Location : {{optional($booking->pickUpLocation)->code}}</td>
                            <td class="col-md-4 tableStyle text-right">Return Location : {{optional($booking->placeOfReturn)->code}}</td>
                        </tr> --}}
                    </tbody>
                </table>
                <table class="col-md-12 tableStyle" >
                    <tbody>
                        <tr>
                            <td class="col-md-6 tableStyle" style="border: 1px solid #000 !important; font-size: 12px;">
                                1-Shippers of the herein mentioned goods are wanted that any remarks appearing on the mate’s receipt concerning of the cargo will invariably be reproduced on the respective bill of lading and no guarantee in place hereof will be accepted.
                                2-The shippers are responsible for the stowing, lashing, weight and cargo distribution inside the container (s), provided that the weight of 20” container doesn’t exceed 18 tons and the 40” container doesn’t exceed 25 tons and incase cargo to be stuffed inside terminal, shipper has to advice the shipping agency 72 hours before getting cargo.
                                3-The shippers are obliged to pay double freight in case of increase of weight and/or volume as previously mentioned, the agent has the right not to deliver the B/L unless the double freight is collected. The carrier/shipping agency has the right to claim the settlement of the said increase in freight.
                                4-This shipping order is valid for 6 days for the allotment of containers from date mentioned above.
                                5-Container is a shipping agency’s equipment, demurrage of delay should be collected according to the following:
                                a) 7 calendar days free time from date of withdrawal of the empty container. 
                                b) from 8 to 15 days: USD 20 for 20’ container & USD 40 for 40’ container.      
                                c) more than 15 days: USD 20 for 20’ container & USD 40 for 40’ container.
                                6-the shipper has to specify quantity and weight stuffed in each container before issuing the bill of lading and order to comply with the customs regulations the following information must be supplied by the shippers within 24 hours after stuffing.
                                A. Customs certificate No.: ــــــــــــــــــ 
                                B. Customs Declaration No.: ــــــــــــــــــ 
                            </td>
                            <td class="col-md-6 tableStyle text-right" style="border: 1px solid #000 !important; font-size: 14px;  direction: rtl; letter-spacing: inherit;">
                                1-يوافق شاحنو البضائع المبينه في اذن الشحن ان يدرج في البوليصه الاصليه الملاحظات الوارده على ايصال كبير ضباط الباخره عن حالة الشحنه بدون اي تغيير ولا يقبل اي ضامن عوضا عن ذلك
                                2-العميل مسئول عن الوزن التستيف وتوزيع الاحمال وتربيط البضاعه بداخل الحاويه/ الحاويات بحيث لا يتعدى الوزن 18 طن للحاويه 20 قدم , 25 طن للحاويه 40 قدم وفي حالة التستيف داخل الميناء يشترط اخطار التوكيل قبل احضار البضاعه بـ72 ساعه.
                                3-يلتزم الشاحن بسداد نولونا مضاعفا في حالة وجود زياده في الوزن و/ أو الحجم عن تلك المبينه عاليه ويجوز للوكيل عدم تسليم سندات الشحن الا بعد سداد النولون المضاعف ويجوز للناقل / التوكيل ان يطالب المرسل اليه بسداد .تلك الزياده 
                                4-هذا الاذن يعتبر لاغيا اذا لم يتم تخصيص الحاويه خلال ستة ايام من التاريخ المدون اعلاه 
                                5-الحاوية تعتبر معده من معدات التوكيل الملاحي تحصل عليها غرامات في حالة التأخير طبقا للاتي: أ-لا تحصل غرامه على الحاويه في الخمس ايام الاولى من تاريخ سحبها. ب-بدءا من اليوم الثامن حتى الثاني عشر يحصل 20 دولار /20" قدم – و 40 دولار /40" قدم ج-فيما بعد اليوم الثالث عشر يحصل 40 دولار 20" قدم – و 80 دولار/ 40" قدم 
                                6- يتحتم على الشاحنين تحديد العدد والوزن للبضاعه المستفه داخل كل حاويه على حدى. 
                                7-قبل اصدار بوليصة الشحن والعمل باللوائح الجمركيه يجب على الشاحنين تقديم البيانات التاليه خلال 24 ساعه بعد التستيف. 
                                أ-قسيمة الجمرك رقم : ــــــــــــــ 
                                ب – الشهاده الجمركيه رقم : ــــــــــــــــ 
                                8-الخط الملاحي /الوكيل غير مسئول عن تحميل اي مصاريف تخزين ناجمه عن تأخر وصول/ سفر الباخره 9- يشترط وصول الباخره بسلام وبعد موافقة الربان كبير الضباط
                            </td>
                        </tr>
                        <tr>
                            <td class="col-md-6 tableStyle text-center" style="border: 1px solid #000 !important; font-size: 16px; height: 1px;" colspan="2"> للتوكيل الحق في عدم شحن الحاويه في حالة عدم تسليم إذن الشحن قبل وصول الباخره بـ 24 ساعه</td>
                        </tr>
                        <tr>
                            <td class="col-md-6 tableStyle"></td>
                            <td class="col-md-6 tableStyle text-right underline"></td>
                        </tr>
                        <tr>
                            <td class="col-md-6 tableStyle"></td>
                            <td class="col-md-6 tableStyle text-right underline">ملحوظة</td>
                        </tr>
                        <tr>
                            <td class="col-md-6 tableStyle" style="border-bottom-style: 1px solid #000 !important; font-size: 14px;">The shipping agency has the right not to ship the container in case
of non delivery of the shipping order before 24 hours from vessel
arrival.</td>
                            <td class="col-md-6 tableStyle text-right" style="font-size: 14px;">للتوكيل الحق في عدم شحن الحاويه في حالة عدم تسليم إذن الشحن قبل وصول الباخره بـ 24
                            ساعه</td>
                        </tr>
                        <tr>
                            <td class="col-md-6 tableStyle"></td>
                            <td class="col-md-6 tableStyle text-right"></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="border-bottom: solid;"></td>
                        </tr>
                    </tbody>
                </table>


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
        background-color: gray !important;
        color: #fff !important;
        height: 35px;
        border: 1px solid #000 !important;
        font-size: 25px !important;
        font-weight: bolder !important;
    }
</style>