@extends('layouts.app')
@section('content')
<div class="layout-px-spacing" style="background-color: #fff;">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading hide">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('creditNote.index')}}">Credit Note</a></li>
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
                        </div>
                    </div>
                <br>
                <br>
                <table class="col-md-12 tableStyle" style="border-style: hidden !important;">
                    <thead>
                        <tr>
                            <th class="text-center  underline" style="font-size: 24px !important;">Credit Note</th>
                        </tr>
                    </thead>
                </table>
                <div class="form-row">
                    <div class="form-group col-md-6  text-right">
                        @if(optional($creditNote)->currency == 'credit_egp')
                            <h3><span style="font-size:22px;">{{ $creditNote->total_amount }} EGP</span> &nbsp;&nbsp;: المبلغ<h3>
                        @else
                            <h3><span style="font-size:22px;">{{ $creditNote->total_amount }} USD</span> &nbsp;&nbsp;: المبلغ<h3>
                        @endif
                    </div>
                    <div class="form-group col-md-6  text-right">
                        <h3> <span style="font-size:22px;">{{$creditNote->credit_no }} </span> &nbsp; &nbsp;&nbsp; : رقم<h3>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12  text-right">
                        <h3> <span style="font-size:22px;">{{ optional($creditNote->customer)->name }} </span>&nbsp;&nbsp; : اسم العميل<h3>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6  text-right">
                        <h3> <span style="font-size:22px;">{{ $now->format('Y-m-d') }} </span> : تاريخ الطباعه<h3>
                    </div>
                    <div class="form-group col-md-6  text-right">
                        <h3> <span style="font-size:22px;">{{ $creditNote->created_at->format('Y-m-d') }} </span> : تاريخ الانشاء<h3>
                    </div>
                </div>
                @if(optional($creditNote)->currency == 'credit_egp')
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
                    <div class="form-group col-md-12  text-left">
                        <h3> Notes : <span style="font-size:22px;">{{ $creditNote->notes }}</span><h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <button onclick="window.print()" class="btn btn-primary hide mt-3">Print This Credit Note</button>
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