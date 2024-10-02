@extends('layouts.app') 
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                    <div class="container" style="border:none;">
                        <div class="row">
                            <div class="col-md-3 text-left">
                                <img src="{{asset('assets/img/msl-logo.png')}}" style="width: 250px;" alt="logo">
                            </div>
                            <div class="col-md-6 text-center title" style="color:black">
                            {{$booking->win_delivery_no}} &nbsp  اذن تسليم رقم 
                                <br>
                                رقم الطريق {{optional(optional($booking->voyage->voyagePorts)->where('port_from_name',$booking->discharge_port_id)->first())->road_no}}
                            </div>
                            <div class="col-md-3 text-right" style="color:black">
                                <div class="date">{{ \Carbon\Carbon::now()->format('d/m/Y') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                            <div class="col-md-12 text-right title" style="color:black">
                                إلي السيد \ مدير مصلحة الجمارك
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
                @php
                    use Carbon\Carbon;
                    $importFreeTime = optional($booking)->free_time ?? 0;
                    $eta = optional($voyagePort)->etd;
                    $resultDate = $eta ? Carbon::parse($eta)->addDays($importFreeTime)->subDay()->toDateString() : 'No ETA available';
                @endphp
                @php
                    $containerTypes = $booking->bookingContainerDetails->pluck('containerType.name')->unique();
                    $qoutationcontainerTypes = $booking->quotation->quotationDesc->pluck('equipmentsType.name')->unique();
                @endphp
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
                                    <td class="col-md-4 text-center">{{ optional($voyagePort)->etd }}</td>
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
                                <td class="col-md-4 text-left">POD</td>
                                    <td class="col-md-4 text-center">{{ optional($booking->dischargePort)->name }}</td>
                                    <td class="col-md-4 text-right">ميناء الوصول</td>
                                </tr>
                                <tr>
                                    <td class="col-md-4 text-left">Storage yard</td>
                                    <td class="col-md-4 text-center">{{ optional($booking->terminals)->name }}</td>
                                    <td class="col-md-4 text-right">ساحة التخزين</td>
                                </tr>
                                <tr>
                                    <td class="col-md-4 text-left">Freight</td>
                                    <td class="col-md-4 text-center">
                                        @foreach($booking->quotation->quotationDesc as $detail)
                                        @if($qoutationcontainerTypes->count() > 1)
                                            {{ optional($detail->equipmentsType)->name }} - {{ $detail->ofr }}
                                        @else
                                            {{ $detail->ofr }} 
                                        @endif
                                    @endforeach
                                    </td>
                                    <td class="col-md-4 text-right">النولون</td>
                                </tr>
                                <tr>
                                    <td class="col-md-4 text-left">No Of Containers</td>
                                    <td class="col-md-4 text-center">
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
                                    <td class="col-md-4 text-center">
                                    @foreach($booking->quotation->quotationDesc as $detail)
                                        @if($qoutationcontainerTypes->count() > 1)
                                            {{ optional($detail->equipmentsType)->name }} - {{ $detail->free_time }} Day
                                        @else
                                            {{ $detail->free_time }} Day
                                        @endif
                                    @endforeach
                                    <td class="col-md-4 text-right">السماح</td>
                                </tr>
                                <tr>
                                    <td class="col-md-4 text-left">Weight</td>
                                    <td class="col-md-4 text-center">{{ $gross_weight }}</td>
                                    <td class="col-md-4 text-right">الوزن كجم</td>
                                </tr>
                                <tr>
                                    <td class="col-md-4 text-left">ACID</td>
                                    <td class="col-md-4 text-center">{{ $booking->acid}}</td>
                                    <td class="col-md-4 text-right">ACID</td>
                                </tr>
                                <tr>
                                    <td class="col-md-4 text-left">Importer ID</td>
                                    <td class="col-md-4 text-center">{{ $booking->importer_id}}</td>
                                    <td class="col-md-4 text-right">Importer ID</td>
                                </tr>
                                <tr>
                                    <td class="col-md-4 text-left">Exporter ID</td>
                                    <td class="col-md-4 text-center">{{ $booking->exportal_id}}</td>
                                    <td class="col-md-4 text-right">Exporter ID</td>
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
                    <div class="myDiv">
                        <table class="col-md-12 tableStyle">
                            <tbody>
                                <tr>
                                    <td class="col-md-12 text-right">فترة السماح : {{ optional($booking)->free_time }} أيام</td>
                                </tr>
                                <tr>
                                    <td class="col-md-12 text-right"> الحاويات فى فتره السماح من الغرامة حتى {{$resultDate}}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </br>
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

                    <div class="notes">
                        <table class="col-md-12 tableStyle">
                            <tbody>
                                <tr>
                                    <td class="col-md-12 text-right">
                                        شركة ميدل أيست غير مسئولة عن الوزن والمقاس المبين بعالية والبضاعة تم تعبئتها  وتفريغها تحت مسئولية الشاحن والمستلم
                                        دون اجنى مسئولية علي الخط الملاحي أو الوكيل الملاحي وعلي الجهات الرقابية والجمركية أخذ كافة الأجراءات الجمركية والقانونية اللازمة
                                        ومراجعة المشمول ومحتوباته ويعتبر الخط الملاحي ناقل للحاوية  فقط  بأعتباره مالك الحاوية</td>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12 text-center">
                        <input type="hidden" id="printCount" value="{{ $booking->print_count }}">
                        <button id="printButton" onclick="handlePrint()" class="btn btn-primary mt-3" {{ $booking->print_count >= $booking->max_print ? 'disabled' : '' }}>Print</button>                        
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

    document.addEventListener("DOMContentLoaded", function() {
    const printButton = document.getElementById('printButton');
    let printCount = {{ $booking->print_count }};
    const maxPrint = {{ $booking->max_print }};

    // Disable the print button if the print count is at max
    if (printCount >= maxPrint) {
        printButton.disabled = true;
    }

    // Disable printing using Ctrl+P or Cmd+P only if print count is max
    document.addEventListener('keydown', function(event) {
        if ((event.ctrlKey || event.metaKey) && event.key === 'p') {
            if (printCount >= maxPrint) {
                event.preventDefault();
                alert('Print limit reached.');
            }
        }
    });

    // Disable right-click context menu for printing only if print count is max
    document.addEventListener('contextmenu', function(event) {
        if (printCount >= maxPrint) {
            event.preventDefault();
            alert('Print limit reached.');
        }
    });

    // Handle after print event
    window.onafterprint = function() {
        if (!printButton.disabled) {
            // Update the print count in the database only if print is successful
            fetch('/booking/incrementPrintCount/{{ $booking->id }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    printCount += 1;
                    if (printCount >= maxPrint) {
                        printButton.disabled = true;
                    }
                }
            })
            .catch(error => console.error('Error:', error));
        }
    };
    });

    function handlePrint() {
        if ({{ $booking->print_count }} < {{ $booking->max_print }}) {
            window.print();
        } else {
            alert('Print limit reached.');
        }
    }

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
