@extends('layouts.bldraft')
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
                            <img src="{{asset('assets/img/allfastLogo.png')}}" style="width: 400px;" alt="logo">
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
                                    <th class="text-center thstyle underline">اذن تسليم {{$booking->deleviry_no}}</th>
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
                            <td class="col-md-9 tableStyle text-right underline" >مدير مصلحة الجمارك </td>
                            <td class="col-md-3 tableStyle text-right underline" >/إلي السيد</td>
                        </tr>
                        <tr>
                            <td class="col-md-9 tableStyle text-right underline" ></td>
                            <td class="col-md-3 tableStyle text-right underline" ></td>
                        </tr>
                        <tr> 

                            <td class="col-md-9 tableStyle" style="padding-left: 80px;"><br>{{ optional($booking->consignee)->name }}</td>

                            <td class="col-md-3 tableStyle text-right underline" >برجاء تسليم السادة</td>
                        </tr>
                        <tr>
                            <td class="col-md-9 tableStyle" style="padding-left: 80px;">{{ $booking->ref_no }}</td>
                            <td class="col-md-3 tableStyle text-right underline" > بوليصه رقم</td>
                        </tr>
                        <tr>
                            @if(optional($booking)->transhipment_port == null)
                            <td class="col-md-9 tableStyle" style="padding-left: 80px;">{{ optional($booking->voyage)->vessel->name }} / {{ optional($booking->voyage)->voyage_no}}</td>
                            @else
                            <td class="col-md-9 tableStyle" style="padding-left: 80px;">{{ optional(optional($booking->secondvoyage)->vessel)->name }} / {{ optional($booking->secondvoyage)->voyage_no}}</td>
                            @endif
                            <td class="col-md-3 tableStyle text-right underline"> البضاعة المذكوره أدناه والواردة علي الباخرة</td>
                        </tr>
                        <tr>
                            @if(optional($booking)->transhipment_port == null)
                            <td class="col-md-9 tableStyle" style="padding-left: 80px;">{{optional($firstVoyagePortImport)->eta}}</td>
                            @else
                            <td class="col-md-9 tableStyle" style="padding-left: 80px;">{{optional($secondVoyagePortImport)->eta}}</td>
                            @endif
                            <td class="col-md-3 tableStyle text-right underline" >تاريخ الوصول</td>
                        </tr>
                        <tr>
                        <td class="col-md-9 tableStyle" style="padding-left: 80px;">
                            @foreach($containerDetailsDisplay as $containerType => $count)
                                {{ $count }}<br>
                            @endforeach
                        </td>
                        <td class="col-md-3 tableStyle text-right underline" >عدد الحاويات</td>
                        </tr>
                        <!-- <tr>
                            <td class="col-md-9 tableStyle" style="padding-left: 80px;">{{optional($booking->quotation)->payment_kind}} </td>
                            <td class="col-md-3 tableStyle text-right underline" >النولون</td>
                        </tr> -->
                        <tr>
                            <td class="col-md-9 tableStyle" style="padding-left: 80px;">{{optional($booking->quotation)->import_detention}} &nbsp; Days</td>
                            <td class="col-md-3 tableStyle text-right underline" >السماح</td>
                        </tr>
                        <tr>
                            <td class="col-md-9 tableStyle" style="padding-left: 80px;">
                                {{optional(optional($booking->voyage->voyagePorts)->where('port_from_name',$booking->discharge_port_id)->first())->road_no}}</td>
                            <td class="col-md-3 tableStyle text-right underline" >رقم الطريق</td>
                        </tr>
                        <tr>
                            <td class="col-md-9 tableStyle" style="padding-left: 80px;">
                                {{optional($booking)->acid}}</td>
                            <td class="col-md-3 tableStyle text-right underline" >ACID</td>
                        </tr>
                    </tbody>
                </table>
                <div class="row">
                    <div class="col-md-1">
                    </div>



                @if(optional(optional($booking)->bookingContainerDetails)->count() > 4 && optional(optional($booking)->bookingContainerDetails)->count() != null)
                    @php
                        $chunkedDetails = optional($booking)->bookingContainerDetails->chunk(26); // Divide the collection into chunks of 15 items
                        $gross_weight = 0;
                    @endphp
                    @foreach(optional($booking)->bookingContainerDetails as $bldetails)
                        @php
                            $gross_weight = $gross_weight + (float)$bldetails->gross_weight;
                        @endphp
                    @endforeach
                    <table class="col-md-10 tableStyle" >
                        <tbody>
                            <tr>
                                <td class="col-md-2 tableStyle underline" style="border: 1px solid #000; border-right-style: hidden; font-size: 14px; padding: .75rem;">
                                    Container No.
                                </td>
                                <td class="col-md-2 tableStyle underline" style="border: 1px solid #000; border-right-style: hidden; border-left-style: hidden; font-size: 14px; padding: .75rem;">
                                    Size / Type
                                </td>
                                <td class="col-md-2 tableStyle underline" style="border: 1px solid #000; border-right-style: hidden; border-left-style: hidden; font-size: 14px; padding: .75rem;">
                                    Seal
                                </td>
                                <td class="col-md-4 tableStyle underline" style="border: 1px solid #000; border-right-style: hidden; border-left-style: hidden; font-size: 14px; padding: .75rem;">
                                    Description
                                </td>
                                <td class="col-md-2 tableStyle underline" style="border: 1px solid #000; border-left-style: hidden; font-size: 14px; padding: .75rem;">
                                    Weight KG
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-2 tableStyle" style="border: 1px solid #000; border-bottom-style: hidden;  border-right-style: hidden; font-size: 14px; padding: .75rem;">

                                </td>
                                <td class="col-md-2 tableStyle" style="border: 1px solid #000; border-bottom-style: hidden; border-right-style: hidden; border-left-style: hidden; font-size: 14px; padding: .75rem;">

                                </td>
                                <td class="col-md-2 tableStyle" style="border: 1px solid #000; border-bottom-style: hidden; border-right-style: 1px solid #000; border-left-style: 1px solid #000; font-size: 14px; padding: .75rem;">

                                </td>
                                <td class="col-md-4 tableStyle"  rowspan="5" style="border: 1px solid #000;  border-left-style: 1px solid #000; font-size: 14px; padding: .75rem;">
                                    <textarea class="tableStyle" name="maindesc"  style="overflow: hidden;font-size: 16px;border-style: hidden; height: 285px; width: 500px;resize: none; background-color: white;" cols="30" rows="10" readonly>
                                        {!!  optional($booking)->descripions  !!}
                                    </textarea>
                                </td>
                                <td class="col-md-2 tableStyle" style="border: 1px solid #000; border-bottom-style: hidden; border-left-style: 1px solid #000; font-size: 14px; padding: .75rem;">
                                    {{$gross_weight}}
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-2 tableStyle" style="border: 1px solid #000; border-bottom-style: hidden; border-right-style: hidden; font-size: 14px; padding: .75rem;">
                                </td>
                                <td class="col-md-2 tableStyle" style="border: 1px solid #000; border-bottom-style: hidden; border-right-style: hidden; border-left-style: hidden; font-size: 14px; padding: .75rem;">
                                </td>
                                <td class="col-md-2 tableStyle" style="border: 1px solid #000; border-bottom-style: hidden; border-right-style: 1px solid #000; border-left-style: 1px solid #000; font-size: 14px; padding: .75rem;">
                                </td>
                                <td class="col-md-2 tableStyle" style="border: 1px solid #000; border-bottom-style: hidden; border-left-style: 1px solid #000; font-size: 14px; padding: .75rem;">
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-2 tableStyle" style="border: 1px solid #000;border-bottom-style: hidden; border-right-style: hidden; font-size: 14px; padding: .75rem;">
                                    As per attached sheet
                                </td>
                                <td class="col-md-2 tableStyle" style="border: 1px solid #000;border-bottom-style: hidden; border-right-style: hidden; border-left-style: hidden; font-size: 14px; padding: .75rem;">
                                    As per attached sheet
                                </td>
                                <td class="col-md-2 tableStyle" style="border: 1px solid #000;border-bottom-style: hidden; border-right-style: 1px solid #000; border-left-style: 1px solid #000; font-size: 14px; padding: .75rem;">
                                    As per attached sheet
                                </td>
                                <td class="col-md-2 tableStyle" style="border: 1px solid #000;border-bottom-style: hidden; border-left-style: 1px solid #000; font-size: 14px; padding: .75rem;">
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-2 tableStyle" style="border: 1px solid #000;border-bottom-style: hidden; border-right-style: hidden; font-size: 14px; padding: .75rem;">
                                </td>
                                <td class="col-md-2 tableStyle" style="border: 1px solid #000;border-bottom-style: hidden; border-right-style: hidden; border-left-style: hidden; font-size: 14px; padding: .75rem;">
                                </td>
                                <td class="col-md-2 tableStyle" style="border: 1px solid #000;border-bottom-style: hidden; border-right-style: 1px solid #000; border-left-style: 1px solid #000; font-size: 14px; padding: .75rem;">
                                </td>
                                <td class="col-md-2 tableStyle" style="border: 1px solid #000;border-bottom-style: hidden; border-left-style: 1px solid #000; font-size: 14px; padding: .75rem;">
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-2 tableStyle" style="border: 1px solid #000; border-right-style: hidden; font-size: 14px; padding: .75rem;">
                                </td>
                                <td class="col-md-2 tableStyle" style="border: 1px solid #000; border-right-style: hidden; border-left-style: hidden; font-size: 14px; padding: .75rem;">
                                </td>
                                <td class="col-md-2 tableStyle" style="border: 1px solid #000; border-right-style: 1px solid #000; border-left-style: 1px solid #000; font-size: 14px; padding: .75rem;">
                                </td>
                                <td class="col-md-2 tableStyle" style="border: 1px solid #000; border-left-style: 1px solid #000; font-size: 14px; padding: .75rem;">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                                    </div>
                                    <table class="col-md-12 tableStyle" >
                                        <tbody>
                                            <tr>
                                                <td class="col-md-12 tableStyle text-right underline" >فترة السماح :- {{optional($booking->quotation)->import_detention}} أيام</td>
                                            </tr>
                                            <tr>
                                                <td class="col-md-12 tableStyle text-right underline" >ملاحظات اذن التسليم
                                                    شركة ميدل أيست غير مسئولة عن الوزن والمقاس المبين بعالية والبضاعة تم تعبئتها  وتفريغها تحت مسئولية الشاحن والمستلم
                                                    دون اجنى مسئولية علي الخط الملاحي أو الوكيل الملاحي وعلي الجهات الرقابية والجمركية أخذ كافة الأجراءات الجمركية والقانونية اللازمة
                                                    ومراجعة المشمول ومحتوباته ويعتبر الخط الملاحي ناقل للحاوية  فقط  بأعتباره مالك الحاوية</td>
                                            </tr>
                                        </tbody>
                                    </table>


                                    </div>
                    @foreach($chunkedDetails as $chunkIndex => $chunk)



                    <div class="row">
                        <div class="col-md-1">

                        </div>
                        <table class="col-md-10 tableStyle" >
                            <tbody>
                                <tr>
                                    <td class="col-md-3 tableStyle underline" style="border: 1px solid #000; border-right-style: hidden; font-size: 14px; padding: .75rem;">
                                        Container No.
                                    </td>
                                    <td class="col-md-3 tableStyle underline" style="border: 1px solid #000; border-right-style: hidden; border-left-style: hidden; font-size: 14px; padding: .75rem;">
                                        Size / Type
                                    </td>
                                    <td class="col-md-3 tableStyle underline" style="border: 1px solid #000; border-right-style: hidden; border-left-style: hidden; font-size: 14px; padding: .75rem;">
                                        Seal
                                    </td>
                                    <td class="col-md-3 tableStyle underline" style="border: 1px solid #000; border-left-style: hidden; font-size: 14px; padding: .75rem;">
                                        Weight KG
                                    </td>
                                </tr>
                                @foreach($chunk as $detail)
                                <tr>
                                    <td class="col-md-3 tableStyle" style="border: 1px solid #000; border-right-style: hidden; font-size: 14px; padding: .75rem;">
                                        {{optional($detail->container)->code}}
                                    </td>
                                    <td class="col-md-3 tableStyle" style="border: 1px solid #000; border-right-style: hidden; border-left-style: hidden; font-size: 14px; padding: .75rem;">
                                    {{substr(optional(optional($detail->container)->containersTypes)->name, 0, 2)}} / {{optional($detail->container->containersTypes)->code}}  
                                    </td>
                                    <td class="col-md-3 tableStyle" style="border: 1px solid #000; border-right-style: 1px solid #000; border-left-style: 1px solid #000; font-size: 14px; padding: .75rem;">
                                        {{$detail->seal_no}}
                                    </td>
                                    <td class="col-md-3 tableStyle" style="border: 1px solid #000; border-left-style: 1px solid #000; font-size: 14px; padding: .75rem;">
                                        {{$detail->gross_weight}}
                                    </td>
                                    </tr>


                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <br>

                    @endforeach
                @else
                    <table class="col-md-10 tableStyle" >
                        <tbody>
                            <tr>
                                <td class="col-md-2 tableStyle underline" style="border: 1px solid #000; border-right-style: hidden; font-size: 14px; padding: .75rem;">
                                    Container No.
                                </td>
                                <td class="col-md-2 tableStyle underline" style="border: 1px solid #000; border-right-style: hidden; border-left-style: hidden; font-size: 14px; padding: .75rem;">
                                    Size / Type
                                </td>
                                <td class="col-md-2 tableStyle underline" style="border: 1px solid #000; border-right-style: hidden; border-left-style: hidden; font-size: 14px; padding: .75rem;">
                                    Seal
                                </td>
                                <td class="col-md-4 tableStyle underline" style="border: 1px solid #000; border-right-style: hidden; border-left-style: hidden; font-size: 14px; padding: .75rem;">
                                    Description
                                </td>
                                <td class="col-md-2 tableStyle underline" style="border: 1px solid #000; border-left-style: hidden; font-size: 14px; padding: .75rem;">
                                    Weight KG
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-2 tableStyle" style="border: 1px solid #000; border-bottom-style: hidden;  border-right-style: hidden; font-size: 14px; padding: .75rem;">

                                </td>
                                <td class="col-md-2 tableStyle" style="border: 1px solid #000; border-bottom-style: hidden; border-right-style: hidden; border-left-style: hidden; font-size: 14px; padding: .75rem;">
                                <td class="col-md-2 tableStyle" style="border: 1px solid #000; border-bottom-style: hidden; border-right-style: 1px solid #000; border-left-style: 1px solid #000; font-size: 14px; padding: .75rem;"></td>
                                </td>
                                    <td class="col-md-4 tableStyle"  rowspan="{{ $booking->bookingContainerDetails ->count()+ 1 }}" style="border: 1px solid #000;  border-left-style: 1px solid #000; font-size: 14px; padding: .75rem;">
                                        <textarea class="tableStyle" name="maindesc"  style="overflow: hidden;font-size: 16px;border-style: hidden; height: 285px; width: 500px;resize: none; background-color: white;" cols="30" rows="10" readonly>
                                            {!!  optional($booking)->descripions  !!}
                                        </textarea>
                                    </td>

                                <td class="col-md-2 tableStyle" style="border: 1px solid #000; border-bottom-style: hidden; border-left-style: 1px solid #000; font-size: 14px; padding: .75rem;">

                                </td>
                            </tr>
                            @foreach(optional($booking)->bookingContainerDetails as $detail)
                            <tr>

                                <td class="col-md-2 tableStyle" style="border: 1px solid #000; border-right-style: hidden; font-size: 14px; padding: .75rem;">
                                    {{optional($detail->container)->code}}
                                    
                                </td>
                                <td class="col-md-2 tableStyle" style="border: 1px solid #000; border-right-style: hidden; border-left-style: hidden; font-size: 14px; padding: .75rem;">
                                {{substr(optional(optional($detail->container)->containersTypes)->name, 0, 2)}} / {{optional(optional($detail->container)->containersTypes)->code}}  
                                </td>
                                <td class="col-md-2 tableStyle" style="border: 1px solid #000; border-right-style: 1px solid #000; border-left-style: 1px solid #000; font-size: 14px; padding: .75rem;">
                                    {{$detail->seal_no}}
                                </td>
                                <td class="col-md-2 tableStyle" style="border: 1px solid #000; border-left-style: 1px solid #000; font-size: 14px; padding: .75rem;">
                                    {{$detail->gross_weight}}
                                </td>
                                </tr>


                            @endforeach
                        </tbody>
                    </table>
                                    </div>
                                    <table class="col-md-12 tableStyle" >
                                        <tbody>

                                            <tr>
                                                <td class="col-md-12 tableStyle text-right underline" >ملاحظات اذن التسليم
                                                    شركة ميدل أيست غير مسئولة عن الوزن والمقاس المبين بعالية والبضاعة تم تعبئتها  وتفريغها تحت مسئولية الشاحن والمستلم
                                                    دون اجنى مسئولية علي الخط الملاحي أو الوكيل الملاحي وعلي الجهات الرقابية والجمركية أخذ كافة الأجراءات الجمركية والقانونية اللازمة
                                                    ومراجعة المشمول ومحتوباته ويعتبر الخط الملاحي ناقل للحاوية  فقط  بأعتباره مالك الحاوية</td>
                                            </tr>
                                        </tbody>
                                    </table>


                                    </div>
                @endif




                <div class="row">
                        <div class="col-md-12 text-center">
                <button onclick="window.print()" class="btn btn-primary hide mt-3">Print This Delivery Order</button>
                <a href="{{route('booking.index')}}" class="btn btn-danger hide mt-3">{{trans('forms.cancel')}}</a>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    let d = document.getElementsByName("maindesc")[0].value
    document.getElementsByName("maindesc")[0].value = d.trim()
</script>
@endpush
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
        height: 100%;
        color: black;
        padding: 0 .75rem;
    }
    .underline{
        text-decoration: underline;
    }
    .thstyle {
        background-color: #fff !important;
        color: black !important;
        height: 100%;
        border: 1px solid #000 !important;
        font-size: 25px !important;
        font-weight: bolder !important;
    }
</style>
@endpush
