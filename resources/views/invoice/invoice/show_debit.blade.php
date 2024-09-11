@extends('layouts.bldraft')
@section('content')
<div class="layout-px-spacing" style="background-color: #fff;">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading hide">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('invoice.index')}}">Invoice </a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);"> Invoice Confirmation</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>

                <div class="widget-content widget-content-area">

                <div class="row">
                    <div class="col-md-6 text-left">
                        <img src="{{asset('assets/img/msl-logo.png')}}" style="width: 350px;" alt="logo">
                    </div>
                </div>

                </br>
                </br>

                <table class="col-md-12 tableStyle">
                    <thead>

                        <tr>
                        @if($invoice->invoice_status == "draft")
                            <th class="text-center thstyle underline" style="font-size: 22px;"> DRAFT DEBIT NOTE</th>
                            @else
                            <th class="text-center thstyle underline" style="font-size: 22px;">DEBIT NOTE</th>
                        @endif
                        </tr>

                    </thead>
                </table>
                <table class="col-md-12 tableStyle">
                    <tbody>
                        <tr>
                            <td class="col-md-3 tableStyle" >DEBIT NOTE NO. <span class="entry">{{ $invoice->invoice_no }}</span></td>
                            <td class="col-md-6 tableStyle" colspan="2">B/L No. <span class="entry">{{ $invoice->bldraft_id == 0 ? optional($invoice->booking)->ref_no : optional($invoice->bldraft)->ref_no }}</span></td>
                        
                            <td class="col-md-3 tableStyle" >Date: <span class="entry">{{Carbon\Carbon::parse($invoice->date)->format('Y-m-d')}}</span></td>
                        </tr>
                        <tr>
                            <td class="col-md-12 tableStyle" colspan="4">Customer: <span class="entry">{{ $invoice->customer }}</span></td>
                        </tr>
                        <tr>
                            <td class="col-md-3 tableStyle" >Booking Ref:  <span class="entry">{{$invoice->bldraft_id == 0 ? optional($invoice->booking)->ref_no :  optional(optional($invoice->bldraft)->booking)->ref_no }}</span></td>
                            <td class="col-md-3 tableStyle" >Vessel: <span class="entry">{{$invoice->bldraft_id == 0 ? optional(optional($invoice->voyage)->vessel)->name :  optional(optional(optional($invoice->bldraft)->voyage)->vessel)->name }}</span></td>
                            <td class="col-md-3 tableStyle" >Voyage: <span class="entry">{{$invoice->bldraft_id == 0 ? optional($invoice->voyage)->voyage_no :  optional(optional($invoice->bldraft)->voyage)->voyage_no }}</span></td>                            
                            <td class="col-md-2 tableStyle text-center" >ETD: <span class="entry">{{optional($firstVoyagePort)->etd}}</span></td>
                        </tr>
                        <tr>
                            <td class="col-md-3 tableStyle" >Place of Receipt:  <br> <br> <span class="entry" style="text-align: center;">{{optional(optional($invoice->bldraft)->placeOfAcceptence)->code }}</span></td>
                            <td class="col-md-3 tableStyle" >Loading Port: <br> <br> <span class="entry" style="text-align: center;">{{$invoice->bldraft_id == 0 ? optional($invoice->loadPort)->code : optional(optional($invoice->bldraft)->loadPort)->code }}</span></td>
                            <td class="col-md-3 tableStyle" >Discharging Port: <br> <br> <span class="entry" style="text-align: center;">{{$invoice->bldraft_id == 0 ? optional($invoice->dischargePort)->code : optional(optional($invoice->bldraft)->dischargePort)->code }}</span></td>
                            <td class="col-md-3 tableStyle" >Port of Delivery: <br> <br> <span class="entry" style="text-align: center;">{{$invoice->bldraft_id == 0 ? optional($invoice->placeOfDelivery)->code :  optional(optional($invoice->bldraft)->placeOfDelivery)->code }}</span></td>
                        </tr>
                    </tbody>
                </table>
                <br>
                @if($invoice->bldraft_id == "0")
                <table class="col-md-12 tableStyle">
                    <thead>
                        <tr>
                            <th class="col-md-3 tableStyle text-center">Quantity</th>
                            <th class="col-md-6 tableStyle text-center">Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="tableStyle text-center">{{ $qty }}</td>
                            <td class="tableStyle text-center"></td>
                        </tr>
                    </tbody>
                </table>
                <br>
                <h4 style="font-size: 24px !important; font-weight: bolder !important; color:black">Charges</h4>
                <table class="col-md-12 tableStyle">
                    <thead>
                        <tr>
                            <th class="col-md-4 tableStyle">Charge Description</th>
                            <th class="col-md-3 tableStyle text-center">Rate</th>
                            <th class="col-md-2 tableStyle text-center">Total US$</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->chargeDesc as $chargeDesc)
                        <tr>
                            <td class="col-md-4 tableStyle" style="font-size: 16px !important; font-weight: 100 !important;">{{ $chargeDesc->charge_description }}</td>
                            <td class="col-md-3 tableStyle text-center" style="font-size: 16px !important; font-weight: 100 !important;">{{ $chargeDesc->size_small }}</td>
                            <td class="col-md-2 tableStyle text-center" style="font-size: 16px !important; font-weight: 100 !important;">{{ $chargeDesc->total_amount }}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td class="col-md-10 tableStyle text-right" colspan="2">Total US$</td>
                            <td class="col-md-2 tableStyle text-center">{{ $total }}</td>
                        </tr>
                    </tbody>
                </table>
                <br>
                <br>
                @else
                <h4 style="font-size: 24px !important; font-weight: bolder !important; color:black">Container Details </h4>
                <table class="col-md-12 tableStyle">
                    <thead>
                        <tr>
                            <th class="col-md-3 tableStyle text-center">Container Size</th>
                            <th class="col-md-3 tableStyle text-center">Quantity</th>
                            <th class="col-md-6 tableStyle text-center">Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="tableStyle text-center">20 "</td>
                            @if($invoice->invoice_kind == "20")
                            <td class="tableStyle text-center">{{ $qty }}</td>
                            @else
                            <td class="tableStyle text-center">0</td>
                            @endif
                            <td class="tableStyle text-center"></td>
                        </tr>
                        <tr>
                            <td class="tableStyle text-center">40 "</td>
                            @if($invoice->invoice_kind == "40")
                            <td class="tableStyle text-center">{{ $qty }}</td>
                            @else
                            <td class="tableStyle text-center">0</td>
                            @endif
                            <td class="tableStyle text-center"></td>
                        </tr>
                    </tbody>
                </table>
                <br>
                <h4 style="font-size: 24px !important; font-weight: bolder !important; color:black">Charges</h4>
                <table class="col-md-12 tableStyle">
                    <thead>
                        <tr>
                            <th class="col-md-4 tableStyle">Charge Description</th>
                            <th class="col-md-3 tableStyle text-center">Rate 20/40</th>
                            <th class="col-md-3 tableStyle text-center">Amount 20/40</th>
                            <th class="col-md-2 tableStyle text-center">Total US$</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->chargeDesc as $chargeDesc)
                        <tr>
                            <td class="col-md-4 tableStyle" style="font-size: 16px !important; font-weight: 100 !important;">{{ $chargeDesc->charge_description }}</td>
                            <td class="col-md-3 tableStyle text-center" style="font-size: 16px !important; font-weight: 100 !important;">{{ $chargeDesc->size_small == 0? '0': $chargeDesc->size_small }}/{{ $chargeDesc->size_large == 0? '0': $chargeDesc->size_large }}</td>
                            <td class="col-md-3 tableStyle text-center" style="font-size: 16px !important; font-weight: 100 !important;">{{ $chargeDesc->size_small == 0? '0': $chargeDesc->size_small }}/{{ $chargeDesc->size_large == 0? '0': $chargeDesc->size_large }}</td>
                            <td class="col-md-2 tableStyle text-center" style="font-size: 16px !important; font-weight: 100 !important;">{{ $chargeDesc->total_amount }}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td class="col-md-10 tableStyle text-right" colspan="3">Total US$</td>
                            <td class="col-md-2 tableStyle text-center">{{ $total }}</td>
                        </tr>
                    </tbody>
                </table>
                <br>
                <br>
                @endif
                <table class="col-md-12 tableStyle">
                    <thead>
                        <tr>
                            <th class="tableStyle">Total amount in words in US $</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="entry" style="padding: .75rem;">{{ $USD }} Dollars <br><br></td>
                        </tr>
                    </tbody>
                </table>
                <table  class="col-md-12 tableStyle">
                    <tbody>
                        <tr>
                            <td class="col-md-3 tableStyle text-center">Notes</td>
                            <td class="col-md-9  tableStyle text-center" colspan="6">
                                <textarea style="width: 100%; border: none; height: 130px; font-size: 12px; font-weight: bolder !important; background-color: white; color: #000;" disabled>{!! $invoice->notes  !!}</textarea>

                            </td>
                        </tr>

                    </tbody>
                </table>
                @if(Auth::user()->company_id == 2)
                    <h4 style="font-size: 16px; color:#000;">Bank USD details: Ahli United Bank – AUB &nbsp; 0007169620002 &nbsp; IBAN:	EG020020000700000007169620002<h4>
                    <h4 style="font-size: 16px; color:#000;">Bank EGP &nbsp;details: Ahli United Bank – AUB &nbsp; 0007169620001 &nbsp; IBAN:	EG290020000700000007169620001<h4>
                    @else
                    <h4 style="font-size: 16px; color:#000;">Bank USD details: Commercial International Bank – CIB &nbsp; 100040346184 &nbsp; IBAN: EG130010020400000100061900866<h4>
                    <h4 style="font-size: 16px; color:#000;">Bank EGP &nbsp;details: Commercial International Bank – CIB &nbsp; 100040346157 &nbsp; IBAN: EG950010020400000100040346184<h4>
                @endif
                </div>
                <h4 style="font-size: 16px; color:#000; text-align: right;">الساده العملاء نود أن  نلفت انتباهكم إلى أهمية إجراء الإيداعات البنكية لكل عميل بشكل منفصل بما يتطابق مع العميل المصدره باسمه الفواتير. يرجى العلم بأنه لن يكون بإمكاننا قبول إيداعات مجمعة لعملاء مختلفين لضمان دقه معالجه المدفوعات . نقدر تعاونكم وفهمكم لهذه الضرورة <h4>

                <div class="row">
                        <div class="col-md-12 text-center">
                <button onclick="window.print()" class="btn btn-primary hide mt-3">Print This Invoice</button>
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
    .entry{
        font-size: 20px !important;
        font-weight: 100 !important;

    }
    .tableStyle {
        font-size: 20px !important;
        font-weight: bolder !important;
        border: 1px solid #000 !important;
        margin-bottom: 1rem;
        height: 50px;
        color: black;
        /* text-transform: uppercase; */
        padding: .75rem;
    }
    .underline{
        text-decoration: underline;
    }
    .thstyle {
        background-color: #80808061 !important;
        color: #000 !important;
        height: 50px;
        border: 1px solid #000 !important;
        font-size: 16px !important;
        font-weight: bolder !important;
    }
</style>
@endpush
