@extends('layouts.bldraft')
@section('content')
<div class="layout-px-spacing" style="background-color: #fff;">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
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

                        @php
                            $net_weight = 0;
                            $gross_weight = 0;
                            $measurement = 0;
                            $packages = 0;
                        @endphp
                        @foreach($blDraft->blDetails as $blkey => $bldetails)
                            @php
                                $packages = $packages + (float)$bldetails->packs;
                                $net_weight = $net_weight + (float)$bldetails->net_weight;
                                $gross_weight = $gross_weight + (float)$bldetails->gross_weight;
                                $measurement = $measurement + (float)$bldetails->measurement;
                            @endphp
                        @endforeach
                    <table class="col-md-12 tableStyle" style="margin-bottom: 0rem;">
                        <tbody>
                            <tr>
                                <th rowspan="2" class="col-md-6 tableStyle" style="height: 150px;">Shipper (full style and address) </br></br>
                                    <span style="font-size: 14px; margin-left: 12px;">{{ optional($blDraft->customer)->name }}</span>
                                    <textarea class="tableStyle" name="shipper"  style="border-style: hidden; overflow: hidden; height: 120px; width: 100%; resize: none; background-color: white; padding-top: unset;" cols="30" rows="10" readonly>
                                        {{ old('shipper',$blDraft->customer_shipper_details) }}
                                        </textarea>
                                </th>
                                <td class="col-md-3 tableStyle" >B/L No. </br>
                                    &nbsp{{ $blDraft->ref_no }}</td>
                                <td class="col-md-3 tableStyle" >Reference No. </br>
                                    &nbsp{{ optional($blDraft->booking)->forwarder_ref_no }}</td>
                            </tr>

                                        @php
                                            $draft_invoice=0;
                                            $confirm_invoice=0;
                                            $paymentstautsPaid =  0;
                                            $paymentstautsUnPaid = 0;
                                            foreach ($blDraft->invoices as $invoice) {
                                                if($invoice->invoice_status == "draft"){
                                                    $draft_invoice ++;
                                                }elseif($invoice->invoice_status == "confirm"){
                                                    $confirm_invoice ++;
                                                }
                                                if($invoice->paymentstauts == 1){
                                                    $paymentstautsPaid ++;
                                                }else{
                                                    $paymentstautsUnPaid ++;
                                                }
                                            }
                                        @endphp
                            <tr>

                                @if(($paymentstautsPaid > 0) && ($paymentstautsUnPaid == 0) && ($blDraft->bl_status == 1) && ($blDraft->bl_kind != "Seaway BL"))  
                                <td class="col-md-6 tableStyle" style="font-size: 23px; text-align: center;" colspan="2" >Bill OF Lading <h3 style="font-weight: 900;"></h3><br>
                                @elseif(($blDraft->bl_kind != "Seaway BL") && Auth::user()->id == 3)
                                <td class="col-md-6 tableStyle" style="font-size: 23px; text-align: center;" colspan="2" >Non Negotiable Bill OF Lading <h3 style="font-weight: 900;"></h3><br>
                                @elseif(($paymentstautsPaid > 0) && ($paymentstautsUnPaid > 0) &&  ($blDraft->bl_kind != "Seaway BL"))
                                    <td class="col-md-6 tableStyle" style="font-size: 23px; text-align: center;" colspan="2" >Draft Bill OF Lading <h3 style="font-weight: 900;"></h3><br>
                                @elseif(($blDraft->bl_kind == "Seaway BL") && ($blDraft->bl_status == 1) || ($blDraft->bl_kind == "Seaway BL") && ($blDraft->bl_status == 0))
                                    <td class="col-md-6 tableStyle" style="font-size: 23px; text-align: center;" colspan="2">Seaway Bill <h3 style="font-weight: 900;"></h3><br>
                                @elseif(($paymentstautsPaid == 0) && ($paymentstautsUnPaid > 0) || ($paymentstautsPaid == 0) && ($paymentstautsUnPaid == 0) || ($blDraft->bl_status == 0))
                                    <td class="col-md-6 tableStyle" style="font-size: 23px; text-align: center;" colspan="2">Draft Bill OF Lading <h3 style="font-weight: 900;"></h3><br>
                                @elseif(($blDraft->bl_status == 1) && ($paymentstautsPaid == 0) && ($paymentstautsUnPaid == 0))
                                    <td class="col-md-6 tableStyle" style="font-size: 23px; text-align: center;" colspan="2">Non Bill OF Lading <h3 style="font-weight: 900;"></h3><br>
                                @endif
                                <div class="col-md-12 text-center">

                                @if(Auth::user()->company_id == 2)
                                    <img src="{{asset('assets/img/cstar-logo.jpeg')}}" style="width: 260px;" alt="logo">
                                    @else
                                    <img src="{{asset('assets/img/winwin_maritime.png')}}"  alt="logo"> 
                                @endif
                                </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                            <table class="col-md-12 tableStyle" style="border-top-style: hidden; margin-bottom: 0rem;">
                                <tr>
                                    <th rowspan="2" class="col-md-6 tableStyle" style=" height: 52px;">Consignee (full style and address) or Order </br></br>
                                        <span style="font-size: 14px; margin-left: 12px;">{{ optional($blDraft->customerConsignee)->name }}</span>
                                        <textarea class="tableStyle" name="consignee"  style="border-style: hidden; overflow: hidden; height:110px; width: 100%; resize: none; background-color: white; padding-top: unset;" cols="30" rows="10" readonly>
                                         {!! $blDraft->customer_consignee_details !!}
                                        </textarea>
                                    </th>
                                    <td class=" col-md-6 tableStyle" colspan="2">Vessel Voyage No </br>
                                        &nbsp{{ optional($blDraft->voyage->vessel)->name }} &nbsp {{ optional($blDraft->voyage)->voyage_no }}
                                        </td>
                                </tr>
                                <tr>
                                    <td class="col-md-3 tableStyle" >Port of loading </br>
                                        &nbsp{{ optional($blDraft->loadPort)->name }}
                                    </td>
                                    <td class=" col-md-3 tableStyle" colspan="2">Port of discharge </br>
                                        &nbsp{{ optional($blDraft->dischargePort)->name }}
                                    </td>
                                </tr>
                            </table>
                            </tr>
                            <tr>
                            <table class="col-md-12 tableStyle" style="border-top-style: hidden; margin-bottom: 0rem;">
                                <tr>
                                    <th rowspan="2" class="col-md-6 tableStyle" style=" height: 52px;">Notify Party (full style and address) </br></br>
                                    <span style="font-size: 14px; margin-left: 12px;">{{ optional($blDraft->customerNotify)->name }}</span>
                                    <textarea class="tableStyle" name="notify"  style="border-style: hidden; overflow: hidden; height: 110px; width: 100%; resize: none; background-color: white; padding-top: unset;" cols="30" rows="10" readonly>
                                    {!! $blDraft->customer_notifiy_details !!}
                                    </textarea>
                                    </th>
                                    <td class="col-md-3 tableStyle" >Place of Receipt </br>
                                    &nbsp{{ optional(optional(optional($blDraft->booking)->quotation)->placeOfAcceptence)->name }}</td>
                                    <td class="col-md-3 tableStyle" >Place of Delivery </br>
                                        &nbsp{{ optional(optional(optional($blDraft->booking)->quotation)->placeOfDelivery)->name }}</td>
                                </tr>
                                <tr>
                                    <td class=" col-md-6 tableStyle" colspan="2">Additional Notify Party </br>
                                        &nbsp
                                        <span style="font-size: 14px; margin-left: 12px;">{{ optional($blDraft->additionalNotify)->name }}</span>
                                        <textarea class="tableStyle" name="additional_notify_details"  style="border-style: hidden; overflow: hidden; height: 80px; width: 100%; resize: none; background-color: white; padding-top: unset;" cols="30" rows="10" readonly>
                                            {{ old('additional_notify_details',$blDraft->additional_notify_details) }}
                                        </textarea>
                                    </td>
                                </tr>
                            </table>
                    <table class="col-md-12 tableStyle" style="margin-bottom: 0rem;">
                        <thead>
                            <tr>
                                <th class="col-md-12 tableStyle text-center" style="height: 34px;" colspan="5">PARTICULARS DECLARED BY THE SHIPPER BUT NOT ACKNOWLEDGED BY THE CARRIER</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="col-md-4 tableStyle text-center" style="border-bottom-style: hidden; font-size: 13px;" colspan="2">Container No./Seal No./Marks and Numbers</td>
                                <td class="col-md-4 tableStyle text-center" style="border-bottom-style: hidden; font-size: 13px;">Number and kind of packages; description of cargo</td>
                                <td class="col-md-2 tableStyle text-center" style="border-bottom-style: hidden; font-size: 13px;">Gross weight, kg </td>
                                <td class="col-md-2 tableStyle text-center" style="border-bottom-style: hidden; font-size: 13px;">Measurement, m3</td>
                            </tr>
                            <tr>
                                <td class="col-md-4 tableStyle text-center" style="border-top-style: hidden; border-bottom-style: hidden;" colspan="2"></td>
                                <td class="col-md-4 tableStyle text-center" style="border-top-style: hidden; border-bottom-style: hidden;">
                                    <textarea class="tableStyle" name="maindesc"  style="border-style: hidden; overflow: hidden; height: 100%; width: 455px; padding-bottom: unset; resize: none; background-color: white;" cols="30" rows="18" readonly>
                                        {!!  $blDraft->descripions  !!}
                                    </textarea> 
                                    </br>
                                    @if($blDraft->id == 131)
                                    polar star booking Freight collect
                                    @elseif(optional($blDraft->booking->principal)->code == 'PLS')
                                    {{optional($blDraft->booking->principal)->name}} Soc
                                    @elseif(optional($blDraft->booking->principal)->code == 'FLW')
                                    {{optional($blDraft->booking->principal)->name}} SOC
                                    @elseif(optional($blDraft->booking->principal)->code == 'MAS')
                                    {{optional($blDraft->booking->principal)->name}} COC
                                    @elseif(optional($blDraft->booking->principal)->code == 'Cstar')
                                    {{optional($blDraft->booking->principal)->name}} COC
                                    @else
                                    {{optional($blDraft->booking->principal)->name}}
                                    @endif
                                </td>
                                <td class="col-md-2 tableStyle text-center" style="border-top-style: hidden; border-bottom-style: hidden;"></td>
                                <td class="col-md-2 tableStyle text-center" style="border-top-style: hidden; border-bottom-style: hidden;"></td>
                            </tr>
                            @if($blDraft->blDetails->count() <= 4)
                            <tr>
                                <td class="col-md-2 tableStyle text-center" style="font-size: 16px; height: 90px; border-top-style: hidden; padding-top: unset; padding-bottom: unset; border-bottom-style: hidden; border-right-style: hidden;">
                                @foreach($blDraft->blDetails as  $bldetails)
                                {{ optional($bldetails->container)->code }} <br>
                                @endforeach
                                </td>
                                <td class="col-md-2 tableStyle text-center" style="font-size: 16px; height: 90px; border-top-style: hidden; padding-top: unset; padding-bottom: unset; border-bottom-style: hidden;">
                                @foreach($blDraft->blDetails as  $bldetails)
                                {{ $bldetails->seal_no }} <br>
                                @endforeach
                                </td>
                                <td class="col-md-4 tableStyle" style="font-size: 16px; height: 90px; border-top-style: hidden; padding-top: unset; padding-bottom: unset; border-bottom-style: hidden;">
                                @foreach($blDraft->blDetails as  $bldetails)
                                &nbsp &nbsp &nbsp{{ $bldetails->packs }} - {{ $bldetails->pack_type }}<br>
                                @endforeach
                                </td>
                                <td class="col-md-2 tableStyle text-center" style="font-size: 16px; height: 90px; border-top-style: hidden; padding-top: unset; padding-bottom: unset; border-bottom-style: hidden;">
                                @foreach($blDraft->blDetails as  $bldetails)
                                {{ $bldetails->gross_weight }} <br>
                                @endforeach
                                </td>
                                <td class="col-md-2 tableStyle text-center" style="font-size: 16px; height: 90px; border-top-style: hidden; padding-top: unset; padding-bottom: unset; border-bottom-style: hidden;">
                                @foreach($blDraft->blDetails as  $bldetails)
                                @if($bldetails->measurement != 0)
                                {{ $bldetails->measurement }} <br>
                                @endif
                                @endforeach
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <td class="col-md-2 tableStyle text-center" style="border-right-style: hidden;"></td>
                                <td class="col-md-2 tableStyle text-center" ></td>
                                <td class="col-md-4 tableStyle " >Total No. Of packs {{$packages}}</td>
                                <td class="col-md-2 tableStyle text-center" >Total GW {{$gross_weight}}</td>
                                <td class="col-md-2 tableStyle text-center" >@if($measurement != 0)Total  {{$measurement}} @endif</td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="col-md-12 tableStyle" style="border-top-style: hidden;">
                        <tbody>
                            <tr>
                                <td class="col-md-6 tableStyle" style="text-transform: initial; font-size: 14px;" rowspan="3">SHIPPED on board in apparent good order and condition (unless otherwise stated
                                        herein) the total number of Containers/Packages or Units indicated in the Box
                                        opposite entitled “Total number of Containers/Packages or Units received by the
                                        Carrier” and the cargo as specified above, weight, measure, marks, numbers,
                                        quality, contents and value unknown, for carriage to the Port of discharge or so
                                        near thereunto as the vessel may safely get and lie always afloat, to be delivered
                                        in the like good order and condition at the Port of discharge unto the lawful holder
                                        of the Bill of Lading, on payment of freight as indicated to the right plus other
                                        charges incurred in accordance with the provisions contained in this Bill of Lading.
                                        In accepting this Bill of Lading the Merchant* expressly accepts and agrees to all
                                        its stipulations on both Page 1 and Page 2, whether written, printed, stamped or
                                        otherwise incorporated, as fully as if they were all signed by the Merchant.
                                        One original Bill of Lading must be surrendered duly endorsed in exchange for the
                                        cargo or delivery order, whereupon all other Bills of Lading to be void.
                                        IN WITNESS whereof the Carrier, Master or their Agent has signed the number of
                                        original Bills of Lading stated below right, all of this tenor and date.</td>
                                <td class="col-md-6 tableStyle" colspan="2">Total number of Containers/Packages or Units received by the Carrier <br>
                                        &nbsp{{$blDraft->blDetails->count()}} X {{ optional($blDraft->equipmentsType)->name }}
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-3 tableStyle" >Shipper’s declared value <br>
                                &nbsp
                                </td>
                                <td class="col-md-3 tableStyle" >Declared value charge <br>
                                &nbsp
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-6 tableStyle" colspan="2">Freight details and charges <br>
                                &nbsp {{$blDraft->payment_kind}}<br>
                                &nbsp <br>
                                &nbsp <br>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-6 tableStyle" rowspan="3">Carrier’s name/principal place of business <br>
                                &nbsp <br>
                                &nbsp <br>
                                &nbsp <br>
                                &nbsp <br>
                                &nbsp <br>
                                &nbsp <br>
                                &nbsp <br>
                                &nbsp <br>
                                </td>
                                @if($blDraft->received_shipment == 0)
                                <td class="col-md-3 tableStyle" >Date shipped on board <br>
                                &nbsp {{ optional($etdvoayege)->etd }}
                                </td>
                                @else
                                <td class="col-md-3 tableStyle"> Received From Shipment <br>
                                &nbsp {{ optional($blDraft)->shipment_date }}
                                </td>
                                @endif
                                <td class="col-md-3 tableStyle" >Place and date of issue <br>
                                &nbsp {{optional($blDraft->booking->agent)->city}} &nbsp {{optional($blDraft->booking->agent->country)->name}} &nbsp {{ optional($etdvoayege)->etd }}
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-3 tableStyle" colspan="2">Number of original Bills of Lading <br>
                                &nbsp {{$blDraft->number_of_original}}
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-6 tableStyle" colspan="2">Pre-carriage by** <br>
                                &nbsp
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    @if($blDraft->blDetails->count() > 4)
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
                                <th class="col-md-2 tableStyle" style="border-style: hidden;font-size: 9px;">Measurement, m3</th>
                                <th class="col-md-2 tableStyle" style="border-style: hidden;font-size: 9px;">GR WT(KGS)</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($blDraft->blDetails as  $bldetails)
                        <tr>
                            <td class="col-md-2 tdstyle">{{ optional($bldetails->container)->code }}</td>
                            <td class="col-md-2 tdstyle">{{ optional($blDraft->equipmentsType)->name }}</td>
                            <td class="col-md-2 tdstyle">{{ $bldetails->seal_no }}</td>
                            <td class="col-md-2 tdstyle">{{ $bldetails->packs }} - {{ $bldetails->pack_type }}</td>
                            <td class="col-md-2 tdstyle">{{ $bldetails->measurement }}</td>
                            <td class="col-md-2 tdstyle">{{ $bldetails->gross_weight }}</td>
                        </tr>
                        @endforeach
                        
                        </tbody>
                    </table>
                @endif
                <div class="row">
                        <div class="col-md-12 text-center">
                <button onclick="window.print()" class="btn btn-primary hide mt-3">Print This Bl</button>
                @if(Auth::user()->company_id == 3)
                <a href="{{route('bldraft.showWinPDF',['bldraft'=>$blDraft->id])}}" class="btn btn-success hide mt-3">Print Original</a>
                @endif
                <a href="{{route('bldraft.index')}}" class="btn btn-danger hide mt-3">{{trans('forms.cancel')}}</a>
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
    let e = document.getElementsByName("additional_notify_details")[0].value
    document.getElementsByName("additional_notify_details")[0].value = e.trim()
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
        font-size: 14px ;
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