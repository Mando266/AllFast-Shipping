@extends('layouts.app')
@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-one">
                    <div class="widget-heading">
                        <nav class="breadcrumb-two" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a a href="javascript:void(0);">Refunds</a></li>
                                <li class="breadcrumb-item  active"><a href="javascript:void(0);">Refund Gates</a></li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                    </div> 
                        <div class="row">
                            <div class="col-md-12 text-right mb-5">
                            {{-- @permission('Refund-List')
                            <a href="{{route('export.receipt')}}" class="btn btn-warning">Export</a>
                            @endpermission --}}
                            @permission('Refund-Create')
                            <a href="{{route('refund.create')}}" class="btn btn-primary">New Refund</a>
                            @endpermission
                            </div>
                        </div>
                    </br>

                    <form>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="Receipt_no">Refund NO</label>
                                <select class="selectpicker form-control" id="Receipt_no" data-live-search="true" name="receipt_no" data-size="10"
                                 title="{{trans('forms.select')}}">
                                 @foreach($refundno as $refund)
                                        <option value="{{$refund->receipt_no}}">{{$refund->receipt_no}}</option>
                                @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="Customer">Customer</label>
                                <select class="selectpicker form-control" id="Customer" data-live-search="true" name="customer_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                 @foreach($customers as $customer)
                                        <option value="{{$customer->id}}">{{$customer->name}}</option>
                                @endforeach
                                </select>
                            </div>      
                        </div>
                        

                            <div class="form-row">
                                <div class="col-md-12 text-center">
                                    <button  type="submit" class="btn btn-success mt-3">Search</button>
                                    <a href="{{route('refund.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                                </div>
                            </div>
                    </form>
                    <div class="widget-content widget-content-area">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-condensed mb-4">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Refund No</th>
                                        <th>Customer Name</th>
                                        <th>Payment Methods</th>
                                        <th>Total</th>
                                        <th>Paid</th>
                                        <th>Curency</th>
                                        <th>User</th>
                                        <th class='text-center' style='width:100px;'>Receipt</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($refunds as $refund)
                                        <tr>
                                            <td>{{ App\Helpers\Utils::rowNumber($refunds,$loop)}}</td>
                                            <td>{{$refund->receipt_no}}</td>
                                            <td>{{optional($refund->customer)->name}}</td>
                                            <td>
                                                @if($refund->bank_transfer != null)
                                                    Bank Transfer {{$refund->bank_transfer}}<br>
                                                @endif
                                                @if($refund->bank_deposit != null)
                                                    Bank Deposit {{$refund->bank_deposit}}<br>
                                                @endif
                                                @if($refund->bank_check != null)
                                                    Bank Cheque  {{$refund->bank_check}}<br>
                                                @endif
                                                @if($refund->bank_cash != null)
                                                    Cash {{$refund->bank_cash}}<br>
                                                @endif
                                                @if($refund->matching != null)
                                                    Matching {{$refund->matching}}<br>
                                                @endif
                                            </td>
                                            <td>{{$refund->total}}</td>
                                            <td>{{$refund->paid}}</td>
                                            @if(optional($refund->invoice)->add_egp == 'false')
                                            <td>USD</td>
                                            @else
                                            <td>EGP</td>
                                            @endif
                                            <td>{{optional($refund->user)->name}}</td>
                                            
                                            <td class="text-center">
                                                <ul class="table-controls">                                                
                                                @permission('Refund-Show')
                                                <li>
                                                    <a href="{{route('refund.show',['refund'=>$refund->id])}}" data-toggle="tooltip"  target="_blank"  data-placement="top" title="" data-original-title="show">
                                                        <i class="far fa-eye text-primary"></i>
                                                    </a>
                                                </li>
                                                @endpermission
                                                </ul>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr class="text-center">
                                            <td colspan="20">{{ trans('home.no_data_found')}}</td>
                                        </tr>
                                    @endforelse

                                </tbody>

                            </table>
                        </div>
                        <div class="paginating-container">
                            {{ $refunds->appends(request()->query())->links()}}

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
