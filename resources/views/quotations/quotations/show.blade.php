@extends('layouts.app')
@section('content')
<div class="layout-px-spacing" style="background-color: #fff;">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading hide">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('quotations.index')}}">Quotations </a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);"> Quotation Details</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                <div class="col-md-12 text-center">
                <img src="{{asset('assets/img/allfastLogo.png')}}" style="width: 350px;" alt="logo">
                </div>
                
                <table class="col-md-12 tableStyle">
                    <thead>
                        <tr>
                            <th class="text-center thstyle">Quotation</th>
                        </tr>
                    </thead>
                </table>
                <table class="col-md-12 tableStyle">
                    <tbody>
                        <tr>
                            <td class="tableStyle">Quotation Ref N : {{$quotation->ref_no}}</td>
                            <td class="tableStyle">Date : {{$quotation->created_at}}</td>
                        </tr>
                    </tbody>
                </table>
                <table class="col-md-12 tableStyle">
                    <tbody>
                        <tr>
                            <td class="tableStyle">Validity : </td>
                            <td class="tableStyle">From : {{$quotation->validity_from}}</td>
                            <td class="tableStyle">To : {{$quotation->validity_to}}</td>
                            <td class="tableStyle">Status : {{$quotation->status}}</td>
                        </tr>
                    </tbody>
                </table>
                <table class="col-md-12 tableStyle">
                    <thead>
                        <tr>
                            <th class="tableStyle thstyle">To</th>
                            <th class="tableStyle thstyle">SOC</th>
                            <th class="tableStyle thstyle">Contact Name</th>
                            <th class="tableStyle thstyle">Quoted By</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="tableStyle">{{optional($quotation->customer)->name}}</td>
                            @if($quotation->soc == 1)
                            <td class="tableStyle">Y</td>
                            @else
                            <td class="tableStyle">N</td>
                            @endif
                            <td class="tableStyle">{{optional($quotation->customer)->contact_person}}</td>
                            <td class="tableStyle">{{optional($quotation->user)->full_name}}</td>
                        </tr>
                    </tbody>
                </table>
                <table class="col-md-12 tableStyle">
                    <thead>
                        <tr>
                            <th class="tableStyle thstyle">Rate Applicable To</th>
                            <th class="text-center tableStyle thstyle">SH</th>
                            <th class="text-center tableStyle thstyle">CN</th>
                            <th class="text-center tableStyle thstyle">NT</th>
                            <th class="text-center tableStyle thstyle">FWD</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="tableStyle">{{optional($quotation->customer)->name}}</td>
                            <?php $sh = 0;$cn=0;$nt=0;$ffw=0;?>
                            @foreach($quotation->customer->CustomerRoles as $itemRole)
                                @if($itemRole->role_id == 1)
                                <?php $sh = 1;?>
                                @elseif($itemRole->role_id == 2)
                                <?php $cn = 1;?>
                                @elseif($itemRole->role_id == 3)
                                <?php $nt = 1;?>
                                @elseif($itemRole->role_id == 6)
                                <?php $ffw = 1;?>
                                @endif
                            @endforeach
                            @if($sh == 1)
                                <td class="text-center tableStyle">Y</td>
                            @else
                                <td class="text-center tableStyle">N</td>
                            @endif
                            @if($cn == 1)
                            <td class="text-center tableStyle ">y</td>
                            @else
                            <td class="text-center tableStyle">N</td>
                            @endif
                            @if($nt == 1)
                            <td class="text-center tableStyle">y</td>
                            @else
                            <td class="text-center tableStyle">N</td>
                            @endif
                            @if($ffw == 1)
                            <td class="text-center tableStyle">y</td>
                            @else
                            <td class="text-center tableStyle">N</td>
                            @endif
                        </tr>
                    </tbody>
                </table>
                <table class="col-md-12 tableStyle">
                    <tbody>
                        <tr>
                            <td class="tableStyle">Place of Acceptance : &nbsp; <span>{{optional($quotation->placeOfAcceptence)->name}}</span></td>
                            <td class="tableStyle">Place of Delivery : &nbsp; <span>{{optional($quotation->placeOfDelivery)->name}}</span></td>
                        </tr>
                        <tr>
                            <td class="tableStyle">Load Port : &nbsp; <span>{{optional($quotation->loadPort)->name}}</span></td>
                            <td class="tableStyle"> Discharge Port : &nbsp; <span>{{optional($quotation->dischargePort)->name}}</span></td>
                        </tr>
                        <tr>
                        <td class="tableStyle">Pick Up Location : &nbsp; <span>{{optional($quotation->pickUpLocation)->name}}</span></td>
                            <td class="tableStyle">Place of Return  : &nbsp; <span>{{optional($quotation->placeOfReturn)->name}}</span></span></td>
                        </tr>
                    </tbody>
                </table>
                <table class="col-md-12 tableStyle">
                    <thead>
                        <tr>
                            <th class="tableStyle thstyle"> Commodity Code</th>
                            <th class="tableStyle thstyle"> Commodity Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="tableStyle">{{$quotation->commodity_code}}</td>
                            <td class="tableStyle">{{$quotation->commodity_des}}</td>
                        </tr>
                    </tbody>
                </table>
                <table class="col-md-12 tableStyle">
                    <thead>
                        <tr>
                            <th class="tableStyle thstyle"> OOG Dimenesions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="tableStyle">{{$quotation->oog_dimensions}}</td>
                        </tr>
                    </tbody>
                </table>
                <table class="col-md-12 tableStyle">
                    <thead>
                        <tr>
                            <th style="padding: .75rem;" class="thstyle" colspan="4">Additional Free Days</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="tableStyle">Export Free Time</td>
                            <td class="tableStyle">{{$quotation->export_detention}}</td>
                            <!-- <td class="tableStyle">Import Free Time</td>
                            <td class="tableStyle">{{$quotation->import_detention}}</td> -->
                        </tr>
                        <tr>
                            <!-- <td class="tableStyle">Export Storage</td>
                            <td class="tableStyle">{{$quotation->export_storage}}</td> -->
                            <td class="tableStyle">Import Free Time</td>
                            <td class="tableStyle">{{$quotation->import_detention}}</td>
                        </tr>
                    </tbody>
                </table>

                <table class="col-md-12 tableStyle">
                    <thead class="tableStyle">
                        <tr>
                            <th class="col-md-2 thstyle">EQUIPMENT TYPE	</th>
                            <th class="col-md-2 thstyle">Currency</th>
                            <th class="col-md-2 thstyle">Customer Rate</th>
                        </tr>
                    </thead>
                    <tbody class="tableStyle">
                        @foreach($quotation->quotationDesc as $item)
                        <tr> 
                            <td class="tableStyle">{{optional($item->equipmentsType)->name}} </td>
                            <td class="tableStyle">{{ $item->currency }}</td>
                            <td class="tableStyle">{{ $item->ofr}}</td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
                    <div class="row">
                        <div class="col-md-12 text-center">
                        @if($quotation->status == 'pending')
                                    <a href="{{route('quotation.approve',['quotation'=>$quotation->id])}}"  class="btn btn-success hide mt-3">Approve</a>
                                    <a href="{{route('quotation.reject',['quotation'=>$quotation->id])}}" class="btn btn-danger hide mt-3">Reject</a>
                        @endif
                        <button onclick="window.print()" class="btn btn-primary hide mt-3">Print This Quotation</button>

                        </div>
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
    .tableStyle {
        font-size: 14px !important;
        font-weight: bolder !important;
        border: 1px solid #000 !important;
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
        font-size: 16px !important;
        font-weight: bolder !important;
    }
</style>
