@extends('layouts.app')

@section('content')
<div class="layout-px-spacing" style="background-color: #fff;">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-content widget-content-area">
                    <div class="container" style="border:none;">
                        <div class="row">
                            <div class="col-md-3 text-left">
                                <img src="{{asset('assets/img/allfastLogo.png')}}" style="width: 250px;" alt="logo">
                            </div>
                            <div class="col-md-6 text-center title">
                                اذن تسليم رقم &nbsp {{$booking->deleviry_no}}
                                <br>
                                رقم الطريق {{optional(optional($booking->voyage->voyagePorts)->where('port_from_name',$booking->discharge_port_id)->first())->road_no}}
                            </div>
                            <div class="col-md-3 text-right">
                                <div class="date">{{ \Carbon\Carbon::now()->format('d/m/Y') }}</div>
                            </div>
                        </div>
                    </div>
                    <br>
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
                @php
                    $net_weight = 0;
                    $gross_weight = 0;
                    $measurement = 0;
                    $packages = 0;
                @endphp
                @foreach($booking->bookingContainerDetails as $detail)
                    @php
                        $packages = $packages + (float)$detail->packs;
                        $net_weight = $net_weight + (float)$detail->net_weight;
                        $gross_weight = $gross_weight + (float)$detail->weight;
                        $measurement = $measurement + (float)$detail->measurement;
                    @endphp
                @endforeach
                    <div class="myDiv">
                        <table class="col-md-12 tableStyle">
                            <tbody>
                                <tr>
                                    <td class="col-md-4 text-left">Please deliver to</td>
                                    <td class="col-md-4 text-center">{{ optional($booking)->reciver_customer ?? optional($booking)->consignee->name }}</td>
                                    <td class="col-md-4 text-right">رجاء تسليم السادة</td>
                                </tr>
                                <tr>
                                    <td class="col-md-4 text-left">The following goods arrived</td>
                                    <td class="col-md-4 text-center">{{ $booking->voyage->vessel->name }} / {{ $booking->voyage->voyage_no }}</td>
                                    <td class="col-md-4 text-right">البضاعة المذكورة أدناه والواردة على</td>
                                </tr>
                                <tr>
                                    <td class="col-md-4 text-left">Arrival Date</td>
                                    <td class="col-md-4 text-center">{{ optional($voyagePort)->eta }}</td>
                                    <td class="col-md-4 text-right">تاريخ الوصول</td>
                                </tr>
                                <tr>
                                    <td class="col-md-4 text-left">BOL</td>
                                    <td class="col-md-4 text-center">{{ optional($booking)->ref_no }}</td>
                                    <td class="col-md-4 text-right">بوليصه رقم</td>
                                </tr>
                                <tr>
                                    <td class="col-md-4 text-left">POL</td>
                                    <td class="col-md-4 text-center">{{ optional($booking->loadPort)->name }}</td>
                                    <td class="col-md-4 text-right">ميناء الشحن</td>
                                </tr>
                                <tr>
                                    <td class="col-md-4 text-left">Storage yard</td>
                                    <td class="col-md-4 text-center">{{ optional($booking->terminals)->name }}</td>
                                    <td class="col-md-4 text-right">ساحة التخزين</td>
                                </tr>
                                <tr>
                                    <td class="col-md-4 text-left">Freight</td>
                                    <td class="col-md-4 text-center">{{ optional($booking)->payment_kind }}</td>
                                    <td class="col-md-4 text-right">النولون</td>
                                </tr>
                                <tr>
                                    <td class="col-md-4 text-left">No Of Containers</td>
                                    <td class="col-md-4 text-center">
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
                                    <td class="col-md-4 text-right">عدد الحاويات</td>
                                </tr>
                                <tr>
                                    <td class="col-md-4 text-left">Package</td>
                                    <td class="col-md-4 text-center">{{ $packages }}</td>
                                    <td class="col-md-4 text-right">عدد الطرود</td>
                                </tr>
                                <tr>
                                    <td class="col-md-4 text-left">Free Time</td>
                                    <td class="col-md-4 text-center">{{ optional($booking)->import_free_time }}</td>
                                    <td class="col-md-4 text-right">السماح</td>
                                </tr>
                                <tr>
                                    <td class="col-md-4 text-left">Weight</td>
                                    <td class="col-md-4 text-center">{{ $gross_weight }}</td>
                                    <td class="col-md-4 text-right">الوزن كجم</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <br>

                    <div class="myDiv">
                            <table class="col-md-12 tableStyle">
                                <tbody>
                                    <tr>
                                        <td class="col-md-12 text-right"> وصف البضاعة</td>
                                    </tr>

                                </tbody>
                            </table>
                        <div class="notes">
                            <textarea class="tableStyle" name="maindesc" style="overflow: hidden; font-size: 16px; border-style: hidden; width: 100%; resize: none; background-color: white;" cols="30" rows="1" readonly id="commodityDesc">
                                {!! $booking->commodity_description !!}
                            </textarea>
                        </div>
                    </div>
                    <br>


                    <br>

                    <!-- Container Details Section -->
                    <div class="myDiv">
                        <div class="row">
                            <div class="col-md-1"></div>
                            <table class="col-md-10 tableStyle">
                                <thead>
                                    <tr>
                                        <th class="col-md-3 text-left underline">Container No.</th>
                                        <th class="col-md-3 text-center underline">Size / Type</th>
                                        <th class="col-md-3 text-center underline">Seal</th>
                                        <th class="col-md-3 text-right underline">Weight KG</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $chunkedDetails = optional($booking->bookingContainerDetails)->chunk(15); // Divide the collection into chunks of 15 items
                                    @endphp

                                    @foreach($chunkedDetails as $chunk)
                                        @foreach($chunk as $detail)
                                            <tr>
                                                <td class="col-md-3 text-left" style="border: 1px solid #000; border-right-style: hidden;">{{ optional($detail->container)->code }}</td>
                                                <td class="col-md-3 text-center" style="border: 1px solid #000; border-right-style: hidden; border-left-style: hidden;">
                                                    {{ substr(optional(optional($detail->container)->containersTypes)->name, 0, 2) }} / {{ optional($detail->container->containersTypes)->code }}
                                                </td>
                                                <td class="col-md-3 text-center" style="border: 1px solid #000; border-right-style: hidden; border-left-style: hidden;">{{ $detail->seal_no }}</td>
                                                <td class="col-md-3 text-right" style="border: 1px solid #000; border-left-style: hidden;">{{ $detail->weight }}</td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <br>
                    <div class="container" style="border:none;">
                        <table class="col-md-12 tableStyle">
                            <tbody>
                                <tr>
                                    <td class="col-md-12 text-right">فترة السماح :- {{ optional($booking->quotation)->import_detention }} أيام</td>
                                </tr>
                                <tr>
                                    <td class="col-md-12 text-right">شركة أوول فاست شيبينج اجينسى غير مسؤلة عن الوزن و المقاس المبين بعاليه و البضاعة تم تعبئتها و تفريغها تحت مسؤلية الشاحن و المستلم دون ادنى مسؤلية على الخط الملاحى او الوكيل الملاحى وعلى الجهات الرقابية والجمركيه اخذ كافة الاجراءات الجمركية والقانونية اللازمة. ومراجعة المشمول ومحتوياته و الخط الملاحى باعتباره مالك الحاوية فقط</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <button onclick="window.print()" class="btn btn-primary hide mt-3">Print This Delivery Order</button>
                            <a href="{{ route('booking.index') }}" class="btn btn-danger hide mt-3">{{ trans('forms.cancel') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const textarea = document.getElementById('commodityDesc');
        textarea.style.height = 'auto'; // Reset height to calculate scrollHeight
        textarea.style.height = textarea.scrollHeight + 'px'; // Set height based on content
    });
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
    .title {
        font-size: 24px;
        font-weight: bold;
    }
    .notes textarea {
        font-size: 18px;
        color: black;
    }
    .underline {
        text-decoration: underline;
    }
    .date {
        font-size: 20px;
        font-weight: bold;
    }
    .myDiv {
    border: 2px outset black;
    }
</style>
@endpush
