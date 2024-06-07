@extends('layouts.app')
@section('content')
<div class="layout-px-spacing transform" style="padding: 20px 20px 0px 20px !important">
        <div class="row layout-top-spacing ">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing ">
                <div class="widget widget-one">
                    <div class="widget-heading hide">
                        <nav class="breadcrumb-two" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('bldraft.index')}}">Bl Draft </a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);"> Ship's Manifest</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol> 
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                    </br>
               
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <h6 style=" font-size:18px" >Ship's Manifest</h6>
                            </div>
                        </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <img src="{{asset('assets/img/msl-logo.jpeg')}}" style="width: 260px;" alt="logo">
                                </div>
                            </div>
                            </br>


                    <table class="col-md-12 tableStyle" >
                   
                        <tbody>
                        <tr style="border-bottom-style: 1px solid !important; margin-bottom: 2rem;">
                                <th class="col-md-2 tableStyle" style="border-left-style: hidden; border-top-style: hidden;"></th>
                                <th class="col-md-1 tableStyle" style="border-left-style: hidden; border-top-style: hidden;"></th>
                                <th class="col-md-4 tableStyle" style="font-size: 16px; font-weight: bolder" colspan="2">2 .VESSEL: <span style="font-size: 12px; font-weight: 100px;">{{ optional($booking->voyage->vessel)->name }} </span></th>
                                <th class="col-md-2 tableStyle" style="font-size: 16px; font-weight: bolder" rowspan="2">7 .PORT OF LOADING <br>
                                {{ optional($booking->loadPort)->name }}</th> 
                                <th class="col-md-2 tableStyle" style="font-size: 16px; font-weight: bolder" rowspan="2">8 .PORT OF DISCHARGE <br>
                                {{ optional($booking->dischargePort)->name }}</th>
                                <th class="col-md-2 tableStyle" style="font-size: 16px; font-weight: bolder" rowspan="2">9 .FINAL DESTINATION <br>
                                {{ optional($booking->dischargePort)->name }}</th>
                                
                        </tr>
                        <tr style="border-bottom-style: 1px solid !important; margin-bottom: 2rem;">
                                <th class="col-md-2 tableStyle" style="border-left-style: hidden; border-top-style: hidden;"></th>
                                <th class="col-md-1 tableStyle" style="border-left-style: hidden; border-top-style: hidden;"></th>
                                <th class="col-md-2 tableStyle" style="font-size: 16px; font-weight: bolder">3 .VOYAGE No: <span style="font-size: 12px; margin-left: 12px;">{{ optional($booking->voyage)->voyage_no }}</span></th>
                                <th class="col-md-2 tableStyle" style="font-size: 16px; font-weight: bolder">4 .SALLING DATE: <br><span style="font-size: 12px; margin-left: 12px;">{{ $firstVoyagePort->eta }}</span></th> 
                                
                        </tr>
                        <tr style="border-bottom-style: 1px solid !important; ">
                                <th class="col-md-2 tableStyle" style="font-size: 16px; font-weight: bolder">10 .Shipper (S)</br> 11 .Consignee (C)</br> 3 .Notify (N)</th>
                                <th class="col-md-1 tableStyle" style="font-size: 16px; font-weight: bolder">13 .B/L No. <br>
                                <textarea class="tableStyle" style="border: none; height:43px; overflow: hidden; resize: none; background-color: white;"></textarea></th>
                                <th class="col-md-2 tableStyle" style="font-size: 16px; font-weight: bolder">14 .Marks And Nos </br> 15 .CNTR NOS/TYPE </br> 16 .SEAL</br></th> 
                                <th class="col-md-2 tableStyle" style="font-size: 16px; font-weight: bolder">17 .NO OF CNTRS </br> 18 .DESCRIPION OF GOODS</br> 19 .SHIPPING TERMS</br></th> 
                                <th class="col-md-2 tableStyle" style="font-size: 16px; font-weight: bolder">20 .SET</br> 21 . TARE</br> 22 .GROSS</br></th> 
                                <th class="col-md-2 tableStyle" style="font-size: 16px; font-weight: bolder">23 . B .REF <br>
                                <textarea class="tableStyle" style="border: none; height:43px; overflow: hidden; resize: none; background-color: white;"></textarea></th>
                                <th class="col-md-2 tableStyle" style="font-size: 16px; font-weight: bolder">24 .REMARKS <br>
                                <textarea class="tableStyle" style="border: none; height:43px; overflow: hidden; resize: none; background-color: white;"></textarea></th>
                        </tr>
                        </tbody>
                    </table>
                    <table class="col-md-12 tableStyle" >
                   
                        <tbody>
                        <tr class="col-md-12 tableStyle" style="border-style: hidden;">
                            <th class="col-md-2 tableStyle" style="border-left-style: hidden; border-right-style: hidden;"></th>
                            <th class="col-md-2 tableStyle" style="border-left-style: hidden; border-right-style: hidden;" ></th>
                            <th class="col-md-1 tableStyle" style="border-left-style: hidden; border-right-style: hidden;">
                            <th class="col-md-3 tableStyle" style="border-left-style: hidden; border-right-style: hidden;" colspan="3">
                                {{$qty}} X {{ optional($booking->equipmentsType)->name }} &nbsp CONTAINER(S) S.T.C <br>
                            </th>
                            <th class="col-md-1 tableStyle" style="border-left-style: hidden; border-right-style: hidden;">
                            <th class="col-md-1 tableStyle" style="border-left-style: hidden; border-right-style: hidden;"></th>
                            <th class="col-md-1 tableStyle" style="border-left-style: hidden; border-right-style: hidden;"></th>
                            <th class="col-md-1 tableStyle" style="border-left-style: hidden; border-right-style: hidden;"></th>

                        </tr>
                        <tr class="col-md-12 tableStyle" style="border-style: hidden;">
                            <td class="col-md-4 tableStyle" style="border-left-style: hidden; border-right-style: hidden;" colspan="2">(S) <br>
                                <textarea class="tableStyle" style="overflow: hidden; border: none; height:150px; width: 425px; resize: none; background-color: white;"></textarea> <br>
                                (C) <br> 
                                <textarea class="tableStyle" style="overflow: hidden; border: none; height:150px; width: 425px; resize: none; background-color: white;"></textarea> <br>
                                (N) <br> 
                                <textarea class="tableStyle" style="overflow: hidden; border: none; height:150px; width: 425px; resize: none; background-color: white;"></textarea>
                            </td>
                            <td class="col-md-1 tableStyle" style="border-left-style: hidden; border-right-style: hidden;" ></td>
                            <td class="col-md-1 tableStyle" style="border-left-style: hidden; border-right-style: hidden;" ></td>
                            <td class="col-md-1 tableStyle" style="border-left-style: hidden; border-right-style: hidden;" ></td>

                            <td class="col-md-1 tableStyle" style="border-left-style: hidden; border-right-style: hidden;"></td>
                            <td class="col-md-1 tableStyle" style="border-left-style: hidden; border-right-style: hidden;"></td>
                            <th class="col-md-1 tableStyle" style="border-left-style: hidden; border-right-style: hidden;"></th>
                            <th class="col-md-1 tableStyle" style="border-left-style: hidden; border-right-style: hidden;"></th>
                            <th class="col-md-1 tableStyle" style="border-left-style: hidden; border-right-style: hidden;"></th>

                        </tr>
                        
                        
                        </tbody>
                    </table>
                   
                <div class="row">
                        <div class="col-md-12 text-center">
                <button onclick="window.print()" class="btn btn-primary hide mt-3">Print This Manifest</button>
                <a href="{{route('bldraft.index')}}" class="btn btn-danger hide mt-3">{{trans('forms.cancel')}}</a>
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
    .transform{
        transform: rotate(90deg);
    }
    .hide {
        display: none !important;
        }
    }
    .tableStyle {
        font-size: 14px ;
        /* font-weight: bolder !important; */
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
</style>