@extends('layouts.app')

@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('receipt.index') }}">Receipt</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">New Receipt</a></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                    <form id="createForm" action="{{ route('receipt.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <h4 style="color:#1b55e2">Invoice Details</h4>

                        @foreach($invoices as $invoice)
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label>Invoice No</label>
                                    <input type="text" class="form-control" value="{{ $invoice->invoice_no }}" disabled>
                                </div>
                                {{-- Repeat for other invoice details you wish to display for each invoice --}}
                            </div>
                        @endforeach

                        <h4 style="color:#1b55e2">Payment Methods</h4>
                        {{-- Payment method fields --}}
                        {{-- Ensure you adapt this section based on how you want to handle payments for multiple invoices --}}

                        {{-- Add hidden fields to pass selected invoice IDs back to the server --}}
                        @foreach($invoices as $invoice)
                            <input type="hidden" name="invoice_ids[]" value="{{ $invoice->id }}">
                        @endforeach

                        <div class="form-row">
                            <div class="col-md-12 form-group">
                                <label>Notes</label>
                                <textarea class="form-control" name="notes"></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary mt-3">{{ trans('forms.create') }}</button>
                                <a href="{{ route('receipt.index') }}" class="btn btn-danger mt-3">{{ trans('forms.cancel') }}</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
