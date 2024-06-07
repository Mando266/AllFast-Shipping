@extends('layouts.app')
@section('content')
<div class="layout-px-spacing" style="background-color: #fff;">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading hide">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('bldraft.index')}}">Bl Draft </a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);"> Bl Draft Confirmation</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol> 
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                    <div class="col-md-12 text-center">
                    </div>
                    </br>
                    </br>

                    <!-- <table class="col-md-12 tableStyle">
                        <thead>
                            <tr>
                                <th class="text-center thstyle">Booking Confirmation</th>
                            </tr>
                        </thead>
                    </table> -->
                    <table class="col-md-12 tableStyle" style="margin-bottom: 0rem;">
                        <tbody>
                            <tr>
                                <td class="col-md-6 tableStyle" style="border-left-style: hidden; height: 150px;">1- Shipper
                                <textarea class="tableStyle" name="shipper"  style="border-style: hidden; height: 150px; width: 706px; resize: none; background-color: white;" cols="30" rows="10" readonly>
                                    {{ old('shipper',$blDraft->customer_shipper_details) }}
                                    </textarea>
                                </td>
                                <td class="col-md-6 tableStyle " style="border-right-style: hidden; border-top-style: hidden;">B/L No: <br>
                                Book No: {{ optional($blDraft->booking)->ref_no }} <br>
                                REFERENCE:</td>
                            </tr>
                            <tr>
                                <td class="col-md-6 tableStyle" style="border-left-style: hidden; height: 150px;">2- Consignee </br>
                                    <textarea class="tableStyle" name="consignee"  style="border-style: hidden; height: 150px; width: 706px; resize: none; background-color: white;" cols="30" rows="10" readonly>
                                    {!! $blDraft->customer_consignee_details !!}
                                    </textarea>
                                </td>
                                <td class="col-md-6 tableStyle" style="border-right-style: hidden;"></td>
                            </tr>
                            <tr>
                                <td class="col-md-6 tableStyle" style="border-left-style: hidden; height: 150px;">3- Notify Party </br>
                                    <textarea class="tableStyle" name="notify"  style="border-style: hidden; height: 150px; width: 706px; resize: none; background-color: white;" cols="30" rows="10" readonly>
                                    {!! $blDraft->customer_notifiy_details !!}
                                    </textarea>
                                </td>
                                <td class="col-md-6 tableStyle" style="border-right-style: hidden; border-top-style: hidden;"></td>
                            </tr>
                            <tr>
                                <td class="col-md-6 tableStyle" style="border-left-style: hidden;">4- Vessel and Voyage No. </br>
                                &nbsp &nbsp &nbsp{{ optional($blDraft->voyage->vessel)->name }} &nbsp {{ optional($blDraft->voyage)->voyage_no }}</td>
                                <td class="col-md-6 tableStyle" style="border-right-style: hidden;">
                                7- Place of Receipt </br>
                                &nbsp &nbsp &nbsp{{ optional($blDraft->placeOfAcceptence)->name }}</td>
                            </tr>
                            <tr>
                                <table class="col-md-12 tableStyle" style="border-top-style: hidden;">
                                    <tr>
                                        <td class="col-md-3 tableStyle" style="border-left-style: hidden;">5- Port Of Loading </br>
                                        &nbsp &nbsp &nbsp{{ optional($blDraft->loadPort)->name }}</td>
                                        <td class="col-md-3 tableStyle" style="border-left-style: hidden;">6- Port Of Discharge </br>
                                        &nbsp &nbsp &nbsp{{ optional($blDraft->dischargePort)->name }}</td>
                                        <td class="col-md-6 tableStyle" style="border-right-style: hidden;">8- Place of Delivery </br>
                                        &nbsp &nbsp &nbsp{{ optional($blDraft->placeOfDelivery)->name }}</td>
                                    </tr>
                                </table>
                            </tr>
                        </tbody>
                    </table>
                    <br>
                    <h6 style="font-weight: bolder; font-size: 0.9rem;">&nbsp PARTICULARS  FURNISHED  BY  THE SHIPPER  -  NOT  CHECKED  BY  CARRIER  -  CARRIER  NOT  RESPONSIBLE  <span style="font-size: 12px;">(see clause 14)</span></h6>
                    <table class="col-md-12 tableStyle" style="height: 300px; border-right-style: hidden; border-left-style: hidden; border-bottom-style: hidden;">
                        <thead>
                            <tr>
                                <th class="col-md-3.5 tableStyle" style="border-bottom-style: hidden; font-size: 9px;">9- Marks and Nos / Container Nos / Seal Nos </th>
                                <th class="col-md-5.5 tableStyle" style="border-bottom-style: hidden;font-size: 9px;">10- Number and kind of Packages / Description of Goods </th>
                                <th class="col-md-1.5 tableStyle" style="border-bottom-style: hidden;font-size: 9px;">11- Gross Weight (kg) </th>
                                <th class="col-md-1.5 tableStyle" style="border-bottom-style: hidden;font-size: 9px;">12- Measurement (cbm) </th>
                            </tr>
                        </thead>
                        <tbody>
                        @php
                            $net_weight = 0;
                            $gross_weight = 0;
                            $measurement = 0;
                            $packages = 0;
                        @endphp
                            @foreach($blDraft->blDetails as $blkey => $bldetails)
                                @php
                                    $packages = $packages + $bldetails->packs;
                                    $net_weight = $net_weight + $bldetails->net_weight;
                                    $gross_weight = $gross_weight + $bldetails->gross_weight;
                                    $measurement = $measurement + $bldetails->measurement;
                                @endphp
                            @endforeach
                            <tr>
                                <td class="col-md-3.5 tdstyle" ></td>
                                <td class="col-md-5.5 tdstyle word">&nbsp &nbsp NO. Of PKGS : {{$packages}}
                                <textarea class="tableStyle" name="maindesc"  style="border-style: hidden; height: 150px; width: 706px; resize: none; background-color: white;" cols="30" rows="10" readonly>
                                    {!!  $blDraft->descripions  !!}
                                    </textarea></td>
                                <td class="col-md-1.5 tdstyle">{{ $gross_weight }} <br>
                                KGS <br><br>
                                NET WT <br>
                                {{ $net_weight }}<br>
                                KGS </td>
                                <td class="col-md-1.5 tdstyle">{{ $measurement }}</td>
                            </tr>
                            
                            
                           
                        </tbody>
                    </table>
                    </br>
                    </br>
                    </br>
                    <table class="col-md-12 tableStyle" style="margin-bottom: 0rem;">
                        <tbody>
                            <tr>
                                <td class="col-md-8 tableStyle" style="border-left-style: hidden;">13- Freight Payable At Cargo shall not be delivered unless Freight & charges are paid </br>
                                &nbsp &nbsp &nbsp</td>
                                <td class="col-md-4 tableStyle" style="border-right-style: hidden;">(NOT NEGOTIATABLE UNLESS CONSIGNED TO ORDER)</td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="col-md-12 tableStyle" style="border-top-style: hidden; margin-bottom: 0rem;">
                        <tbody>
                            <tr>
                                <td class="col-md-3 tableStyle" style="border-left-style: hidden;">14- Date of Issue </br>
                                &nbsp &nbsp &nbsp{{ $blDraft->date_of_issue }}</td>
                                <td class="col-md-3 tableStyle" style="border-left-style: hidden;">15- Place of Issue </br>
                                &nbsp &nbsp &nbsp</td>
                                <td class="col-md-2 tableStyle" style="border-left-style: hidden;">16- Movement </br>
                                &nbsp &nbsp &nbsp{{ $blDraft->movement }}</td>
                                <td class="col-md-4 tableStyle" style="border-right-style: hidden; border-bottom-style: hidden;"></td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="col-md-12 tableStyle" style="border-top-style: hidden; border-bottom-style: hidden;">
                        <tbody>
                            <tr>
                                <td class="col-md-4 tableStyle" style="border-left-style: hidden;">17- Declerd Value (Only Applicable if Ad Valer)</br>
                                &nbsp &nbsp &nbsp{{ $blDraft->declerd_value }}</td>
                                <td class="col-md-4 tableStyle">18- Number of Original Bills of Lading</br>
                                &nbsp &nbsp &nbsp{{ $blDraft->number_of_original }}</td>
                                <td class="col-md-4 tableStyle" style="border-right-style: hidden;"></td>
                            </tr>
                        </tbody>
                    </table>
                    </br>
                    </br>
                    </br>
                    </br>
                    @if($blDraft->blDetails->count() > 0)
                    <h5 class="text-center"><u>ATTACHED SHEET</u></h5>
                    <table class="col-md-12 tableStyle" style="border-top-style: hidden; border-right-style: hidden; margin-bottom: 1rem;">
                        <thead>
                            <tr>
                                <th class="col-md-4 tableStyle text-center" style="border-left-style: hidden; font-size: 9px;">BL NUMBER </th>
                                <th class="col-md-4 tableStyle text-center" style="border-left-style: hidden;font-size: 9px;">VESSEL NAME </th>
                                <th class="col-md-4 tableStyle text-center" style="border-left-style: hidden;font-size: 9px;">VOYAGE </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="col-md-4 tableStyle text-center" style="border-left-style: hidden;"></td>
                                <td class="col-md-4 tableStyle text-center" style="border-left-style: hidden;">{{ optional($blDraft->voyage->vessel)->name }}</td>
                                <td class="col-md-4 tableStyle text-center" style="border-left-style: hidden;">{{ optional($blDraft->voyage)->voyage_no }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="col-md-12 tableStyle" style="border-style: hidden;">
                        <thead>
                            <tr>
                                <th class="col-md-2 tableStyle" style="border-style: hidden; font-size: 9px;">CONTAINER</th>
                                <th class="col-md-2 tableStyle" style="border-style: hidden;font-size: 9px;">TYPE</th>
                                <th class="col-md-2 tableStyle" style="border-style: hidden;font-size: 9px;">SEAL No</th>
                                <th class="col-md-2 tableStyle" style="border-style: hidden;font-size: 9px;">PACKAGES</th>
                                <th class="col-md-2 tableStyle" style="border-style: hidden;font-size: 9px;">NET WT(KGS)</th>
                                <th class="col-md-2 tableStyle" style="border-style: hidden;font-size: 9px;">GR WT(KGS)</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($blDraft->blDetails as  $bldetails)
                        <tr>
                            <td class="col-md-2 tdstyle" >{{ optional($bldetails->container)->code }}</td>
                            <td class="col-md-2 tdstyle">{{ optional($blDraft->equipmentsType)->name }}</td>
                            <td class="col-md-2 tdstyle">{{ $bldetails->seal_no }}</td>
                            <td class="col-md-2 tdstyle">{{ $bldetails->packs }}</td>
                            <td class="col-md-2 tdstyle">{{ $bldetails->net_weight }}</td>
                            <td class="col-md-2 tdstyle">{{ $bldetails->gross_weight }}</td>
                        </tr>
                        
                        @endforeach
                        
                        </tbody>
                    </table>
                @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    let a = document.getElementsByName("shipper")[0].value
    document.getElementsByName("shipper")[0].value = a.trim()
    let b = document.getElementsByName("consignee")[0].value
    document.getElementsByName("consignee")[0].value = b.trim()
    let c = document.getElementsByName("notify")[0].value
    document.getElementsByName("notify")[0].value = c.trim()
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
        font-size: 9px ;
        font-weight: bolder !important;
        border: 1px solid #000;
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
        font-size: 16px;
        font-weight: bolder !important;
    }
    .word {
            width: 350Px;
            overflow-wrap: break-word;
            word-wrap: break-word;
            word-break: break-word;
        }
</style>