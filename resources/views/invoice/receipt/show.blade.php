@extends('layouts.app')
@section('content')
<div class="layout-px-spacing" style="background-color: #fff;">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading hide">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('invoice.index')}}">Receipt </a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                    <div class="row">
                        <div class="col-md-6 text-left">
                            <img src="{{asset('assets/img/msl.png')}}" style="width: 350px; height: 97.6px;" alt="logo">
                        </div>
                        <div class="col-md-6 text-right">
                            {{-- <img src="{{asset('assets/img/mas logo.png')}}" style="width: 300px; height: 143.6px;" alt="logo"> --}}
                        </div>
                    </div>
                <br>
                <br>
                <table class="col-md-12 tableStyle" style="border-style: hidden !important;">
                    <thead>
                        <tr>
                            <th class="text-center  underline" style="font-size: 24px !important;"> إيصال إستلام نقدية / إيداع بنكي  </th>
                        </tr>
                    </thead>
                </table>
                <div class="form-row">
                    <div class="form-group col-md-6  text-right">
                        @if(optional($receipt->invoice)->add_egp == 'onlyegp')
                            <h3><span style="font-size:22px;">{{ $receipt->paid }} EGP</span> &nbsp;&nbsp;: المبلغ<h3>
                        @else
                            <h3><span style="font-size:22px;">{{ $receipt->paid }} USD</span> &nbsp;&nbsp;: المبلغ<h3>
                        @endif
                    </div>
                    <div class="form-group col-md-6  text-right">
                        <h3> <span style="font-size:22px;">{{$receipt->receipt_no }} </span> &nbsp; &nbsp;&nbsp; : رقم<h3>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12  text-right">
                        <h3> <span style="font-size:22px;">{{ optional($receipt->invoice)->customer }} </span>&nbsp;&nbsp; : استلمت من<h3>
                    </div> 
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6  text-right">
                        <h3> <span style="font-size:22px;">{{ $now->format('Y-m-d') }} </span> : تاريخ الطباعه<h3>
                    </div>
                    <div class="form-group col-md-6  text-right">
                        <h3> <span style="font-size:22px;">{{ $receipt->created_at->format('Y-m-d') }} </span> : تاريخ الانشاء<h3>
                    </div>
                </div>
                @if(optional($receipt->invoice)->add_egp == 'onlyegp')
                <div class="form-row">
                    <div class="form-group col-md-12  text-right">
                        <h3> <span style="font-size:22px;">{{ $total }} Egyptian Pound</span>&nbsp;&nbsp;: مبلغ وقدره<h3>
                    </div>
                </div>
                @else
                <div class="form-row">
                    <div class="form-group col-md-12  text-right">
                        <h3> <span style="font-size:22px;">{{ $total }} Dollar</span>&nbsp;&nbsp;: مبلغ وقدره<h3>
                    </div>
                </div>
                @endif
                <div class="form-row">
                    <div class="form-group col-md-12  text-right">
                        <h3> <span style="font-size:22px;">{{optional($receipt->invoice)->invoice_no}}</span>&nbsp;&nbsp; : وذلك عن فواتير<h3>
                    </div>
                </div>
                <div class="form-row">

                    <div class="form-group col-md-10  text-right">
                        <h3> <span style="font-size:22px;">                                     
                        @if($receipt->bank_transfer != null) 
                            {{$receipt->bank_transfer}} Bank Transfer {{optional($receipt->bank)->name}}<br> 
                        @endif
                        @if($receipt->bank_deposit != null)
                            {{$receipt->bank_deposit}}  Bank Deposit {{optional($receipt->bank)->name}}<br> 
                        @endif 
                        @if($receipt->bank_check != null)
                            {{$receipt->bank_check}}  Bank Cheque  {{optional($receipt)->cheak_no}}<br> 
                        @endif
                        @if($receipt->bank_cash != null)
                            {{$receipt->bank_cash}}  Cash <br>
                        @endif
                        @if($receipt->matching != null)
                        {{$receipt->matching}}  Matching 
                        @endif
                    </span>&nbsp;&nbsp;<h3>
                    </div>
                    <div class="form-group col-md-2  text-right">
                        <h3>: طريقة الدفع </h3>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6  text-right">
                        <h3> <span style="font-size:22px;"> {{ optional($receipt->invoice)->bldraft_id == 0 ? optional($receipt->invoice->booking)->ref_no : optional($receipt->invoice->bldraft)->ref_no }}</span>&nbsp;&nbsp;: بوليصة<h3>
                    </div>
                    <div class="form-group col-md-6  text-right"> 
                    @if(optional(optional(optional($receipt->invoice->bldraft)->booking)->quotation)->shipment_type == "Import")    
                    <h3> <span style="font-size:22px;">{{ $receipt->invoice->bldraft_id == 0 ? optional(optional(optional($receipt->invoice)->voyage)->vessel)->name : optional(optional(optional(optional($receipt->bldraft)->booking)->secondvoyage)->vessel)->name }} &nbsp; {{ $receipt->invoice->bldraft_id == 0 ? optional($receipt->voyage)->voyage_no : optional(optional(optional($receipt->bldraft)->booking)->secondvoyage)->voyage_no }}</span>&nbsp;&nbsp; : الباخرة / رحلة <h3>
                    @else
                        <h3> <span style="font-size:22px;">{{ $receipt->invoice->bldraft_id == 0 ? optional(optional(optional($receipt->invoice)->voyage)->vessel)->name : optional(optional(optional($receipt->bldraft)->voyage)->vessel)->name }} &nbsp; {{ $receipt->invoice->bldraft_id == 0 ? optional($receipt->voyage)->voyage_no : optional(optional($receipt->bldraft)->voyage)->voyage_no }}</span>&nbsp;&nbsp; : الباخرة / رحلة <h3>
                    @endif        
                    </div>       
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12  text-right">
                        <h3> <span style="font-size:22px;">{{optional($receipt)->notes}}</span>&nbsp;&nbsp; :  ملاحظات <h3>
                    </div>
                </div>    
                <div class="form-row">
                    <div class="form-group col-md-12  text-right">
                        <h3> <span style="font-size:22px;">{{optional($receipt->user)->name}}</span>&nbsp;&nbsp; :  المستخدم <h3>
                    </div>
                </div>   
                
                <div class="form-row">
                    <div class="form-group col-md-3  text-right">
                    <h3> <span></span>يعتمد<h3>
                    </div>
                    <div class="form-group col-md-3  text-right">
                    </div>
                    <div class="form-group col-md-3  text-right">
                    <h3> <span></span>التوقيع<h3>
                    </div>
                    <div class="form-group col-md-3  text-right">
                    </div>
                </div>
            </br>
            </br>
            </br>

                <h4 style="font-size: 18px; color:#000; text-align: right;">لا يحق للعميل القيام بأي استرداد متعلق بالايصال بعد مرور 90 يوم من تاريخ اصدار الايصال<h4>

                <div class="row">
                    <div class="col-md-12 text-center">
                        <button onclick="window.print()" class="btn btn-primary hide mt-3">Print This Receipt</button>
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
        font-size: 14px !important;
    }
    .tableStyle {
        font-size: 16px !important;
        font-weight: bolder !important;
        border: 1px solid #000 !important;
        margin-bottom: 1rem;
        height: 50px;
        color: black;
        text-transform: uppercase;
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