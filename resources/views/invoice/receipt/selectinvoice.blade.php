@extends('layouts.app')
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a a href="{{route('receipt.index')}}">Receipt</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">New Receipt</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                    <form id="createForm" action="{{route('receipt.create')}}" method="get">
                            @csrf
                            <form>
                    <!-- <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="Bldraft">BlDraft Number <span class="text-warning"></span></label>
                                <select class="selectpicker form-control" id="Bldraft" data-live-search="true" name="bldraft_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($bldrafts as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('bldraft_id',request()->input('bldraft_id')) ? 'selected':''}}>{{$item->ref_no}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="customer">Customer</label>
                                <select class="selectpicker form-control" id="customer" data-live-search="true" name="customer_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                 <option value="">Select...</option>
                                    @foreach ($customers as $item)     
                                        <option value="{{$item->id}}" {{$item->id == old('customer_id',request()->input('customer_id')) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                    </div> -->
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="invoice">Invoice No</label>
                            <!-- <select class="selectpicker form-control" id="invoice" data-live-search="true" name="invoice_id" data-size="10"
                             title="{{trans('forms.select')}}" required>
                            <option value="">Select...</option>
                            @foreach ($invoiceRef as $item)     
                            <option value="{{$item->id}}" {{$item->id == old('invoice_id',request()->input('invoice_id')) ? 'selected':''}}>
                            InvoiceNo: {{$item->invoice_no}} &nbsp;&nbsp; BL No: {{optional($item->bldraft)->ref_no}}
                            &nbsp;&nbsp; Customer: {{$item->customer}}
                            @if($item->qty != 0) &nbsp;&nbsp; No Of Containers: {{$item->qty}} @endif
                            &nbsp;&nbsp; Total: {{$item->add_egp == "onlyegp" ? $item->chargeDesc->sum('total_egy')." EGP" : $item->chargeDesc->sum('total_amount')." USD"}}
                        </option>
                                @endforeach
                            </select> -->
                            <select class="selectpicker form-control" id="invoice" data-live-search="true" name="invoice_id[]" data-size="10" title="{{trans('forms.select')}}" required multiple>
                                <option value="">Select...</option>
                                @foreach ($invoiceRef as $item)
                                    <option value="{{$item->id}}" {{ in_array($item->id, old('invoice_ids', request()->input('invoice_ids', []))) ? 'selected' : '' }}>
                                        InvoiceNo: {{$item->invoice_no}} &nbsp;&nbsp; BL No: {{optional($item->bldraft)->ref_no}}
                                        &nbsp;&nbsp; Customer: {{$item->customer}}
                                        @if($item->qty != 0) &nbsp;&nbsp; No Of Containers: {{$item->qty}} @endif
                                        &nbsp;&nbsp; Total: {{$item->add_egp == "onlyegp" ? $item->chargeDesc->sum('total_egy')." EGP" : $item->chargeDesc->sum('total_amount')." USD"}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary mt-3">Next</button>
                                <a href="{{route('receipt.selectinvoice')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
        $(function(){
                let customer = $('#customer');
                $('#customer').on('change',function(e){
                    let value = e.target.value;
                    let response =    $.get(`/api/master/invoicesCustomers/${customer.val()}`).then(function(data){
                        let invoices = data.invoices || '';
                        let list2 = [`<option value=''>Select...</option>`];
                        for(let i = 0 ; i < invoices.length; i++){
                            list2.push(`<option value='${invoices[i].id}'>${invoices[i].invoice_no} - ${invoices[i].customer}</option>`); 
                        }
                let invoice = $('#invoice');
                invoice.html(list2.join(''));
                $(invoice).selectpicker('refresh');

                });
            });
        });
        $(function(){
                let bldraft = $('#Bldraft');
                $('#Bldraft').on('change',function(e){
                    let value = e.target.value;
                    let response =    $.get(`/api/master/invoices/${bldraft.val()}`).then(function(data){
                        let invoices = data.invoices || '';
                        let list2 = [`<option value=''>Select...</option>`];
                        for(let i = 0 ; i < invoices.length; i++){
                            list2.push(`<option value='${invoices[i].id}'>${invoices[i].invoice_no} - ${invoices[i].customer}</option>`);
                        }
                let invoice = $('#invoice');
                invoice.html(list2.join(''));
                $(invoice).selectpicker('refresh');

                });
            });
        });
</script>
@endpush