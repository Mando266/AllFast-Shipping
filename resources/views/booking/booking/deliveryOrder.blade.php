@extends('layouts.app')
@section('content')
<div class="layout-px-spacing">
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
                                $gross_weight = $gross_weight + (float)$detail->gross_weight;
                                $measurement = $measurement + (float)$detail->measurement;
                            @endphp
                        @endforeach
                </div>
                <div class="container">
                    <div class="row">
                        <div class="col-md-3  text-left">
                            <img src="{{asset('assets/img/allfastLogo.png')}}" style="width: 250px;" alt="logo">
                        </div>
                        <div class="col-md-6 title text-center">
                        اذن تسليم رقم{{$booking->deleviry_no}}
                        </br>
                         رقم الطريق {{optional(optional($booking->voyage->voyagePorts)->where('port_from_name',$booking->discharge_port_id)->first())->road_no}}
                        </div>
                        <div class="col-md-3 title text-right">
                            {{optional($booking->principal)->name}}
                        </div>
                    </div>
                </div>

                <div class="container">
                    <table class="table">
                        <tr>
                            <th>The Following Goods Arrived</th>
                            <th></th>
                            <th>البضاعة المذكورة أدناه والواردة على</th>
                        </tr>
                        <tr>
                            <td>Arrival Date</td>
                            <td>{{optional($firstVoyagePortImport)->eta}}</td>
                            <td>تاريخ الوصول</td>
                        </tr>
                        <tr>
                            <td>Free</td>
                            <td>{{$booking->import_free_time}}</td>
                            <td>السماح</td>
                        </tr>
                        <tr>
                            <td>Stored</td>
                            <td>{{optional($booking->terminals)->name}}</td>   
                            <td>ساحة التخزين</td>
                        </tr>
                        <tr>
                            <td>Packag</td>
                            <td>{{$packages}}</td>
                            <td>عدد الطرود</td>
                        </tr>
                        <tr>
                            <td>Weight</td>
                            <td>{{$gross_weight}}</td>
                            <td>الوزن كجم</td>
                        </tr>
                        <tr>
                            <td>Freight</td>
                            <td>{{$booking->payment_kind}}</td>
                            <td>التولون</td>
                        </tr>
                    </table>
                    <h4 style="text-align: right;">وصف البضاعة</h4>

                    <div class="notes">
                        <textarea class="tableStyle" 
                            style="overflow: hidden; font-size: 16px; border-style: hidden; width: 100%; resize: none; background-color: white;" 
                            cols="30" rows="1" readonly 
                            id="commodityDesc">
                            {!! $booking->commodity_description !!}
                        </textarea>
                    </div>

                </div>
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
   
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            border: 1px solid #000;
            padding: 20px;
        }
        .header {
            text-align: right;
        }
        .header img {
            width: 100px;
        }
        .title {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            color: #000;
        }
        .delivery {
            margin-bottom: 20px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table, .table th, .table td {
            border: 1px solid #000;
        }
        .table th, .table td {
            padding: 10px;
            text-align: center;
        }
        .notes {
            font-size: 14px;
        }
</style>
@endpush
