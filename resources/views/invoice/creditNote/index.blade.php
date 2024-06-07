@extends('layouts.app')
@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-one">
                    <div class="widget-heading">
                        <nav class="breadcrumb-two" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a a href="javascript:void(0);">Accounting</a></li>
                                <li class="breadcrumb-item  active"><a href="javascript:void(0);">Credit Notes</a></li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                    </div> 
                        <div class="row">
                            <div class="col-md-12 text-right mb-5">
                            @permission('Invoice-Create')
                            <a href="{{route('creditNote.create')}}" class="btn btn-primary">New Credit Note</a>
                            @endpermission
                            </div>
                        </div>
                    </br>

                    <form>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="Customer">Customer</label>
                                <select class="selectpicker form-control" id="Customer" data-live-search="true" name="customer_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                 @foreach($customers as $customer)
                                        <option value="{{$customer->id}} {{$customer->name == old('name',request()->input('name')) ? 'selected':''}}">{{$customer->name}}</option>
                                @endforeach
                                </select>
                            </div>      
                        </div>
                        

                            <div class="form-row">
                                <div class="col-md-12 text-center">
                                    <button  type="submit" class="btn btn-success mt-3">Search</button>
                                    <a href="{{route('creditNote.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                                </div>
                            </div>
                    </form>
                    <div class="widget-content widget-content-area">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-condensed mb-4">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Credit Note No</th>
                                        <th>Customer Name</th>
                                        <th>Total Amount</th>
                                        <th>Currency</th>
                                        <th></th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($creditNotes as $creditNote)
                                        <tr>
                                            <td>{{ App\Helpers\Utils::rowNumber($creditNotes,$loop)}}</td>
                                            <td>{{$creditNote->credit_no}}</td>
                                            <td>{{optional($creditNote->customer)->name}}</td>
                                            <td>{{$creditNote->total_amount}}</td>  
                                            @if($creditNote->currency == 'credit_egp')
                                            <td>EGP</td>    
                                            @else
                                            <td>USD</td>    
                                            @endif
                                            <td class="text-center">
                                                <ul class="table-controls">                                                
                                                @permission('Invoice-Show')
                                                <li>
                                                    <a href="{{route('creditNote.show',['creditNote'=>$creditNote->id])}}" data-toggle="tooltip"  target="_blank"  data-placement="top" title="" data-original-title="show">
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
                            {{ $creditNotes->appends(request()->query())->links()}}

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
