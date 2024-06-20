@extends('layouts.bldraft')
@section('content')
<div class="layout-px-spacing transform" style="padding: 20px 20px 0px 20px !important">
        <div class="row layout-top-spacing ">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing ">
                <div class="widget widget-one">
                    <div class="widget-heading hide">
                        <nav class="breadcrumb-two" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('bldraft.index')}}">Bl Draft </a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">EXPORT SERVICE MANIFEST</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                    <div class="col-md-12 text-center">
                    </div>
                    </br>
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
                    <div class="row">
                        <div class="col-md-2">
                            @if(optional(optional($blDraft->booking)->principal)->code == 'PLS')
                                <img src="{{asset('assets/img/cstar-logo.jpeg')}}" style="width: 260px;" alt="logo">
                                @elseif(optional(optional($blDraft->booking)->principal)->code == 'Cstar')
                                <img src="{{asset('assets/img/cstar-logo.jpeg')}}" style="width: 260px;" alt="logo">
                                @elseif(Auth::user()->company_id == 3)
                                <img src="{{asset('assets/img/winwin_maritime.png')}}" style="width: 260px;" alt="logo">
                                @else
                                <img src="{{asset('assets/img/msl-logo.png')}}" style="width: 260px;" alt="logo">
                            @endif
                        </div>
                        <table class="col-md-10 tableStyle" style="margin-bottom: 0rem; border-style: hidden;">
                            <tbody>
                                <tr>
                                    @if($blDraft->booking->is_transhipment == 1 && $blDraft->booking->quotation_id == 0)
                                        <td class="col-md-6 tableStyle text-center" style="height: 150px; font-size:18px" colspan="6">Transhipment MANIFEST</br></br>
                                    @elseif($blDraft->booking->quotation->shipment_type == "Import")
                                        <td class="col-md-6 tableStyle text-center" style="height: 150px; font-size:18px" colspan="6">{{$blDraft->booking->quotation->imo == 1 ? 'IMO' : ''}} IMPORT SERVICE MANIFEST</br></br>
                                    @else
                                        <td class="col-md-6 tableStyle text-center" style="height: 150px; font-size:18px" colspan="6">{{$blDraft->booking->quotation->imo == 1 ? 'IMO' : ''}} EXPORT SERVICE MANIFEST </br></br>
                                    @endif
                                    @if($blDraft->booking->is_transhipment == 1 && $blDraft->booking->quotation_id == 0)
                                        <span style="font-size: 14px; margin-left: 12px;">Discharge VESSEL / VOYAGE &nbsp &nbsp{{ optional($blDraft->voyage->vessel)->name }} &nbsp {{ optional($blDraft->voyage)->voyage_no }}</span>
                                    </br>
                                        <span style="font-size: 14px; margin-left: 12px;">Loading VESSEL / VOYAGE &nbsp &nbsp{{ optional(optional(optional($blDraft->booking)->secondvoyage)->vessel)->name}} &nbsp {{ optional(optional($blDraft->booking)->secondvoyage)->voyage_no }}</span>
                                    @elseif($blDraft->booking->is_transhipment == 1 && optional(optional($blDraft->booking)->quotation)->shipment_type == "Import")
                                    <span style="font-size: 14px; margin-left: 12px;">VESSEL / VOYAGE &nbsp &nbsp{{ optional(optional(optional($blDraft->booking)->secondvoyage)->vessel)->name}} &nbsp {{ optional(optional($blDraft->booking)->secondvoyage)->voyage_no }}</span>
                                    @else
                                        <span style="font-size: 14px; margin-left: 12px;">VESSEL / VOYAGE &nbsp &nbsp{{ optional($blDraft->voyage->vessel)->name }} &nbsp {{ optional($blDraft->voyage)->voyage_no }} </span>
                                    @endif
                                    </td>
                                    </tbody>
                        </table>
                    </div>
                    <table class="col-md-12 tableStyle" style="margin-bottom: 0rem; border-style: hidden;">
                        <tbody>

                                <td class="tableStyle" style="border-style: hidden;">Port of Loading </br>
                                {{ optional($blDraft->loadPort)->name }}
                                </td>
                                <td class="tableStyle" style="border-style: hidden;">Transhipment Port </br>
                                    {{ optional(optional($blDraft->booking)->transhipmentPort)->name }}
                                </td>
                                <td class="tableStyle" style="border-style: hidden;">Port of Discharge </br>
                                {{ optional($blDraft->dischargePort)->name }}
                                </td>
                                <td class="tableStyle" style="border-style: hidden;">Final Destination </br>
                                {{ optional($blDraft->dischargePort)->name }}
                                </td>
                                <td class="tableStyle" style="border-style: hidden;">Date of Sailing </br>
                                @if(optional($blDraft->booking)->transhipment_port != null && $blDraft->booking->shipment_type == "Import")
                                    {{ optional($etdfristvoayege)->etd }} 
                                    @else
                                    {{ optional($etdvoayege)->etd }}
                                @endif    
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                    <table class="col-md-12 tableStyle" style="border-top-style: hidden; border-right-style: hidden; margin-bottom: 1rem; height: 700px;">

                        <tbody>
                        <tr style="border-bottom-style: 1px solid !important; margin-bottom: 1rem;">
                                <td class="tableStyle" style="border-left-style: hidden; font-size: 16px;">Book No./ BL No.</td>
                                <td class="tableStyle" style="border-left-style: hidden; font-size: 16px;">Shipper / Consignee / Notify</td>
                                <td class="tableStyle" style="border-left-style: hidden; font-size: 16px;">No. and Kind of Packages - Description of Goods
                                </td>
                                <td class=" tableStyle" style="border-left-style: hidden; font-size: 16px;">Weight</td>
                                <td class=" tableStyle" style="border-left-style: hidden; font-size: 16px;">Measurement</td>
                            </tr>

                        <tr class="col-md-12 tableStyle">
                            <td class="tableStyle" style="border-left-style: hidden;">{{ optional($blDraft->booking)->ref_no }} <br>
                            {{ $blDraft->ref_no }}
                            </td>
                            <td class="tableStyle" style="border-left-style: hidden;">
                            <textarea class="tableStyle" style="border: none; height:290px; resize: none; background-color: white;"></textarea>

                            </td>
                            <td class="tableStyle" style="border-left-style: hidden; width: 300px !important;">No. of Containers: {{ $blDraft->blDetails->count() }} <br> <br>
                                <textarea style="width: 100%; height:400px; border: none; font-size: 12px; font-weight: bolder !important; resize: none; background-color: white; color: #000;" disabled>{!! $blDraft->descripions  !!}</textarea>
                            </td>
                            <td class="tableStyle" style="border-left-style: hidden;">{{ $gross_weight }}</td>
                            <td class="tableStyle" style="border-left-style: hidden;">{{ $bldetails->measurement }}</td>
                        </tr>

                        </tbody>
                    </table>
                    @php
                        $chunkedDetails = $blDraft->blDetails->chunk(15); // Divide the collection into chunks of 15 items
                    @endphp
                    @foreach($chunkedDetails as $chunkIndex => $chunk)
                    <div class="widget-content widget-content-area">
                        <div class="col-md-12 text-center">
                        </div>
                        </br>
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
                        <div class="row">
                            <div class="col-md-2">
                                @if(optional(optional($blDraft->booking)->principal)->code == 'PLS')
                                <img src="{{asset('assets/img/cstar-logo.jpeg')}}" style="width: 260px;" alt="logo">
                                @elseif(optional(optional($blDraft->booking)->principal)->code == 'Cstar')
                                <img src="{{asset('assets/img/cstar-logo.jpeg')}}" style="width: 260px;" alt="logo">
                                @elseif(Auth::user()->company_id == 3)
                                <img src="{{asset('assets/img/winwin_maritime.png')}}" style="width: 260px;" alt="logo">
                                @else
                                <img src="{{asset('assets/img/msl-logo.png')}}" style="width: 260px;" alt="logo">
                                @endif
                            </div>
                            <table class="col-md-10 tableStyle" style="margin-bottom: 0rem; border-style: hidden;">
                                <tbody>
                                    <tr>
                                        @if($blDraft->booking->is_transhipment == 1 && $blDraft->booking->quotation_id == 0)
                                                <td class="col-md-6 tableStyle text-center" style="height: 150px; font-size:18px" colspan="6">Transhipment MANIFEST</br></br>
                                            @elseif($blDraft->booking->quotation->shipment_type == "Import")
                                                <td class="col-md-6 tableStyle text-center" style="height: 150px; font-size:18px" colspan="6">{{$blDraft->booking->quotation->imo == 1 ? 'IMO' : ''}} IMPORT SERVICE MANIFEST</br></br>
                                            @else
                                                <td class="col-md-6 tableStyle text-center" style="height: 150px; font-size:18px" colspan="6">{{$blDraft->booking->quotation->imo == 1 ? 'IMO' : ''}} EXPORT SERVICE MANIFEST </br></br>
                                            @endif
                                            @if($blDraft->booking->is_transhipment == 1 && $blDraft->booking->quotation_id == 0)
                                                <span style="font-size: 14px; margin-left: 12px;">Discharge VESSEL / VOYAGE &nbsp &nbsp{{ optional($blDraft->voyage->vessel)->name }} &nbsp {{ optional($blDraft->voyage)->voyage_no }}</span>
                                            </br>
                                                <span style="font-size: 14px; margin-left: 12px;">Loading VESSEL / VOYAGE &nbsp &nbsp{{ optional(optional(optional($blDraft->booking)->secondvoyage)->vessel)->name}} &nbsp {{ optional(optional($blDraft->booking)->secondvoyage)->voyage_no }}</span>
                                                @elseif($blDraft->booking->is_transhipment == 1 && optional($blDraft->booking)->transhipment_port != null)
                                                <span style="font-size: 14px; margin-left: 12px;">VESSEL / VOYAGE &nbsp &nbsp{{ optional(optional(optional($blDraft->booking)->secondvoyage)->vessel)->name}} &nbsp {{ optional(optional($blDraft->booking)->secondvoyage)->voyage_no }}</span>
                                            @else
                                                <span style="font-size: 14px; margin-left: 12px;">VESSEL / VOYAGE &nbsp &nbsp{{ optional($blDraft->voyage->vessel)->name }} &nbsp {{ optional($blDraft->voyage)->voyage_no }} </span>
                                            @endif
                                        </td>
                                        </tbody>
                            </table>
                        </div>
                        <table class="col-md-12 tableStyle" style="margin-bottom: 0rem; border-style: hidden;">
                            <tbody>
                                    <td class="tableStyle" style="border-style: hidden;">BL NO </br>
                                        {{ $blDraft->ref_no }}
                                    </td>
                                    <td class="tableStyle" style="border-style: hidden;">Port of Loading </br>
                                    {{ optional($blDraft->loadPort)->name }}
                                    </td>
                                    <td class="tableStyle" style="border-style: hidden;">Transhipment Port </br>

                                        {{ optional(optional($blDraft->booking)->transhipmentPort)->name }}
                                    </td>
                                    <td class="tableStyle" style="border-style: hidden;">Port of Discharge </br>
                                    {{ optional($blDraft->dischargePort)->name }}
                                    </td>
                                    <td class="tableStyle" style="border-style: hidden;">Final Destination </br>
                                    {{ optional($blDraft->dischargePort)->name }}
                                    </td>
                                    <td class="tableStyle" style="border-style: hidden;">Date of Sailing </br>
                                    @if(optional($blDraft->booking)->transhipment_port != null && $blDraft->booking->shipment_type == "Import")
                                    {{ optional($etdfristvoayege)->etd }} 
                                    @else
                                    {{ optional($etdvoayege)->etd }}
                                    @endif  
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <table class="col-md-12 tableStyle" style="border-style: hidden;">
                        <tbody>
                            <tr style="border-bottom-style: 1px solid !important; margin-bottom: 1rem;">
                                <td class=" tableStyle" style="border-left-style: hidden;">Container No.</td>
                                <td class=" tableStyle" style="border-left-style: hidden;">Size</td>
                                <td class=" tableStyle" style="border-left-style: hidden;">Iso</td>
                                <td class=" tableStyle" style="border-left-style: hidden;">Seal</td>
                                <td class=" tableStyle" style="border-left-style: hidden;">Packages</td>
                                <td class=" tableStyle" style="border-left-style: hidden;">Type</td>
                                <td class=" tableStyle" style="border-left-style: hidden;">G. Weight</td>
                                <td class=" tableStyle" style="border-left-style: hidden;">Measurement</td>
                                <td class=" tableStyle" style="border-left-style: hidden;">Net Weight</td>
                                <td class=" tableStyle" style="border-left-style: hidden;">UNNO</td>
                            </tr>
                        @foreach($chunk as  $bldetails)
                        <tr class="col-md-12 tableStyle" >
                            <td class="tableStyle" style="border-right-style: hidden;">{{substr(optional(optional($bldetails->container)->containersTypes)->name, 0, 2)}} / {{optional($bldetails->container->containersTypes)->code}} </td>
                            <td class="tableStyle" style="border-right-style: hidden;">{{ optional($blDraft->equipmentsType)->name }}</td>
                            <td class="tableStyle" style="border-right-style: hidden;">{{ optional($bldetails->container)->iso}}</td>
                            <td class="tableStyle" style="border-right-style: hidden;">{{ $bldetails->seal_no }}</td>
                            <td class="tableStyle" style="border-right-style: hidden;">{{ $bldetails->packs }}</td>
                            <td class="tableStyle" style="border-right-style: hidden;">{{ $bldetails->pack_type }}</td>
                            <td class="tableStyle" style="border-right-style: hidden;">{{ $bldetails->gross_weight }}</td>
                            <td class="tableStyle" style="border-right-style: hidden;">{{ $bldetails->measurement }}</td>
                            <td class="tableStyle" style="border-right-style: hidden;">{{ $bldetails->net_weight }}</td>
                            <td class="tableStyle" style="border-right-style: hidden;">&nbsp</td>
                        </tr>
                        @endforeach
                        <tr style="border-top-style: 1px solid !important; margin-bottom: 1rem;">
                                <td class=" tableStyle" style="border-left-style: hidden;"></td>
                                <td class=" tableStyle" style="border-left-style: hidden;"></td>
                                <td class=" tableStyle" style="border-left-style: hidden;"></td>
                                <td class=" tableStyle text-right" style="border-left-style: hidden;">Total</td>
                                <td class=" tableStyle " style="border-left-style: hidden;">{{ $packages }}</td>
                                <td class=" tableStyle " style="border-left-style: hidden;"></td>
                                <td class=" tableStyle" style="border-left-style: hidden;">{{ $gross_weight }}</td>
                                <td class=" tableStyle" style="border-left-style: hidden;">{{ $measurement }}</td>
                                <td class=" tableStyle" style="border-left-style: hidden;">{{ $net_weight }}</td>
                                <td class=" tableStyle" style="border-left-style: hidden;"></td>
                            </tr>
                        </tbody>
                    </table>
                    @endforeach
                <div class="row">
                    <div class="col-md-12 text-center">
                        @if($xml)
                        <form id="createForm" action="{{route('xml.store')}}" method="POST">
                            @csrf
                            <input type="hidden" value="{{$voyage}}" name="voyage_id">
                            @isset($loadPort)
                            <input type="hidden" value="{{$loadPort}}" name="loadPort">
                            @endisset
                            @isset($dischargePort)
                            <input type="hidden" value="{{$dischargePort}}" name="dischargePort">
                            @endisset
                            <button type="submit" class="btn btn-primary hide mt-3">Download Manifest XML</button>
                        </form>
                        <a href="{{route('xml.index')}}" class="btn btn-danger hide mt-3">{{trans('forms.cancel')}}</a>
                        @else
                        <button onclick="window.print()" class="btn btn-primary hide mt-3">Print This Manifest</button>
                        <a href="{{route('bldraft.index')}}" class="btn btn-danger hide mt-3">{{trans('forms.cancel')}}</a>
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
    let d = document.getElementsByName("descripions")[0].value
    document.getElementsByName("descripions")[0].value = d.trim()
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
        font-size: 12px ;
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
    td{
        width: 50Px !important;
    }


		body {
          margin: 0 !important;
          margin-top: 0 !important;
		  padding: 0 !important;
        }

</style>
@endpush
