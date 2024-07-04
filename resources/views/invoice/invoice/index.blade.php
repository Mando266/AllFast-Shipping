@extends('layouts.app')
@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-one">
                    <div class="widget-heading">
                        <nav class="breadcrumb-two" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a a href="javascript:void(0);">Invoice</a></li>
                                <li class="breadcrumb-item  active"><a href="javascript:void(0);">Invoice List</a></li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                    </div>
                        <div class="row">
                            <div class="col-md-12 text-right mb-5">
                            @permission('Invoice-Create')
                            <a href="{{route('invoice.selectBL')}}" class="btn btn-primary">New Debit Note</a>
                            <a href="{{route('invoice.selectBLinvoice')}}" class="btn btn-info">New Invoice</a>
                            @endpermission
                            @permission('Invoice-List')
                            <a class="btn btn-warning" href="{{ route('export.invoice') }}">Export</a>
                            <a class="btn btn-success" href="{{ route('export.invoice.breakdown') }}">Export Invoice Breakdown</a>
                            @endpermission
                            </div>
                        </div>
                    </br>
                    <form id="createForm">
                        <div class="form-row">

                            <div class="form-group col-md-3">
                                <label for="invoice">Invoice No</label>
                                <select class="selectpicker form-control" id="invoice" data-live-search="true" name="invoice_no" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($invoiceRef as $item)
                                        <option value="{{$item->invoice_no}}" {{$item->invoice_no == old('invoice_no',request()->input('invoice_no')) ? 'selected':''}}>{{$item->invoice_no}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="Type">Invoice Type</label>
                                <select class="selectpicker form-control" id="Type" data-live-search="true" name="type" data-size="10"
                                 title="{{trans('forms.select')}}">
                                        <option value="debit" {{ request()->input('type') == "debit" ? 'selected':'' }}>Debit</option>
                                        <option value="invoice" {{ request()->input('type') == "invoice" ? 'selected':'' }}>Invoice</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="status">Invoice Status</label>
                                <select class="selectpicker form-control" data-live-search="true" name="invoice_status" title="{{trans('forms.select')}}">
                                    <option value="draft" {{ request()->input('invoice_status') == "draft" ? 'selected':'' }}>Draft</option>
                                    <option value="ready_confirm" {{ request()->input('invoice_status') == "confirm" ? 'selected':'' }}>Ready To Confirm</option>
                                    <option value="confirm" {{ request()->input('invoice_status') == "confirm" ? 'selected':'' }}>Confirm</option>
                               </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Payment Status</label>
                                <select class="selectpicker form-control" data-live-search="true" name="paymentstauts" title="{{trans('forms.select')}}">
                                    <option value="1" {{ request()->input('paymentstauts') == "1" ? 'selected':'' }}>Paid </option>
                                    <option value="0" {{ request()->input('paymentstauts') == "0" ? 'selected':'' }}>UnPaid</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="Bldraft">Bl Number</label>
                                <select class="selectpicker form-control" id="Bldraft" data-live-search="true" name="bldraft_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                 <option value="o">Customize</option>
                                    @foreach ($bldrafts as $item)
                                        <option value="{{$item->id}}" data-code="{{$item->booking->id}}" {{$item->id == old('bldraft_id',request()->input('bldraft_id')) ? 'selected':''}}>{{$item->ref_no}} </option>
                                    @endforeach
                                </select>
                            </div>

                            <input type="hidden" class="form-control" id="bookingIdInput" name="booking_ref" value="{{request()->input('booking_ref')}}">

                            <div class="form-group col-md-3">
                                <label for="Bldraft">Customer</label>
                                <select class="selectpicker form-control" id="customer_id" data-live-search="true" name="customer_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($customers as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('customer_id',request()->input('customer_id')) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="status">Bl Payment</label>
                                <select class="selectpicker form-control" data-live-search="true" name="payment_kind" title="{{trans('forms.select')}}">
                                    <option value="Prepaid" {{ request()->input('payment_kind') == "Prepaid" ? 'selected':'' }}>Prepaid </option>
                                    <option value="Collect" {{ request()->input('payment_kind') == "Collect" ? 'selected':'' }}>Collect</option>
                                </select>
                                @error('payment_kind')
                                <div style="color:red;">
                                    {{$message}}
                                </div>
                                @enderror
                        </div>
                        <div class="form-group col-md-3">
                            <label for="voyage_id">Voyages </label>
                            <select class="selectpicker form-control" id="voyage_id" name="voyage_id" data-live-search="true" data-size="10"
                                title="{{trans('forms.select')}}">
                                @foreach ($voyages as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('voyage_id',request()->input('voyage_id')) ? 'selected':''}}>{{$item->voyage_no}} {{optional($item->vessel)->name }} - {{ optional($item->leg)->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        </div>

                            <div class="form-row">
                                <div class="col-md-12 text-center">
                                    <button  type="submit" class="btn btn-success mt-3">Search</button>
                                    <button type="button" id="reset-select" class="btn btn-info mt-3">Reset</button>
                                    <a href="{{route('invoice.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                                </div>
                            </div>
                    </form>
                    @php
                    $totalusd = 0;
                    $totalegp = 0;
                    @endphp
                    <div class="widget-content widget-content-area">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-condensed mb-4">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Invoice No</th>
                                        <th>Customer</th>
                                        <th>Tax NO</th>
                                        <th>Bl No</th>
                                        <th>Voyage</th>
                                        <th>Vessel</th>
                                        <th>Date</th>
                                        <th>Invoice Type</th>
                                        <th>payment kind</th>
                                        <th>Total USD</th>
                                        <th>Total EGP</th>
                                        <th>Exchange Rate</th>
                                        <th>Created by</th>
                                        <th>Invoice Status</th>
                                        <th>Payment Status</th>
                                        <th>Receipts</th>
                                        <th class='text-center' style='width:100px;'></th>
                                        <!-- <th class='text-center' style='width:100px;'>Portal</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($invoices as $invoice)
                                    @php
                                    $vat = $invoice->vat;
                                    $vat = $vat / 100;
                                    $total = 0;
                                    $total_eg = 0;
                                    $total_after_vat = 0;
                                    $total_before_vat = 0;
                                    $total_eg_after_vat = 0;
                                    $total_eg_before_vat = 0;
                                    $totalAftereTax = 0;
                                    $totalAftereTax_eg = 0;

                                    foreach($invoice->chargeDesc as $chargeDesc){
                                        $total += $chargeDesc->total_amount;
                                        $total_eg += $chargeDesc->total_egy;

                                        $totalAftereTax = (($total * $invoice->tax_discount)/100);
                                        $totalAftereTax_eg = (($total_eg * $invoice->tax_discount)/100);

                                    if($chargeDesc->add_vat == 1){
                                            $total_after_vat += ($vat * $chargeDesc->total_amount);
                                            $total_eg_after_vat += ($vat * $chargeDesc->total_egy);
                                        }
                                    }
                                    $total_before_vat = $total;
                                    if($total_after_vat != 0){
                                        $total = $total + $total_after_vat;
                                    }
                                    if($total_eg_after_vat != 0){
                                        $total_eg = $total_eg + $total_eg_after_vat;
                                    }
                                    @endphp

                                        <tr>
                                            <td>{{ App\Helpers\Utils::rowNumber($invoices,$loop)}}</td>
                                            <td>{{optional($invoice)->invoice_no}}</td>
                                            <td>{{$invoice->customer}}</td>
                                            <td>{{optional($invoice->customerShipperOrFfw)->tax_card_no}}</td>
                                            <td>{{optional($invoice->booking)->ref_no}}</td>
                                            <td>{{optional(optional($invoice->booking)->voyage)->voyage_no}}</td>
                                            <td>{{optional(optional(optional($invoice->booking)->voyage)->vessel)->name}}</td>
                                            <td>{{optional($invoice)->date}}</td>
                                            <td>{{optional($invoice)->type}}</td>
                                            <td>{{optional($invoice->booking)->payment_kind}}</td>
                                            @if( $invoice->add_egp != 'onlyegp')
                                            <td>{{$total}}</td>
                                            @else
                                            <td></td>
                                            @endif
                                            @if($invoice->add_egp == 'true' || $invoice->add_egp == 'onlyegp')
                                            <td>{{$total_eg}}</td>
                                            @else
                                            <td></td> 
                                            @endif
                                            @if($invoice->add_egp == 'false')
                                            <td>{{optional($invoice)->customize_exchange_rate}}</td>
                                            @else 
                                            <td></td>
                                            @endif
                                            <td>{{optional($invoice->user)->name}}</td>

                                            <td class="text-center">
                                                @if($invoice->invoice_status == "confirm")
                                                    <span class="badge badge-success"> Confirm </span>
                                                @elseif($invoice->invoice_status == "ready_confirm")
                                                    <span class="badge badge-info"> Ready To Confirm</span>
                                                @else
                                                    <span class="badge badge-danger"> Draft </span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($invoice->paymentstauts == 1)
                                                    <span class="badge badge-info"> Paid </span>
                                                @elseif($invoice->receipts->count() != 0)
                                                    <span class="badge badge-success"> Partially Paid </span>
                                                @else
                                                    <span class="badge badge-danger"> UnPaid </span>
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                @if($invoice->receipts->count() != 0)
                                                    @foreach($invoice->receipts as $receipt)
                                                        {{$receipt->receipt_no}} <br>
                                                    @endforeach
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                 <ul class="table-controls">
                                                    @permission('Invoice-Edit')
                                                    <li>
                                                        <a href="{{route('invoice.edit',['invoice'=>$invoice->id,'booking_ref'=>$invoice->booking_refv])}}" data-toggle="tooltip" target="_blank" data-placement="top" title="" data-original-title="edit">
                                                            <i class="far fa-edit text-success"></i>
                                                        </a>
                                                    </li>
                                                    @endpermission

                                                    @permission('Invoice-Show')
                                                    <li>
                                                        <a href="{{route('invoice.show',['invoice'=>$invoice->id])}}" data-toggle="tooltip"  target="_blank"  data-placement="top" title="" data-original-title="show">
                                                            <i class="far fa-eye text-primary"></i>
                                                        </a>
                                                    </li>
                                                    @endpermission

                                                @if($invoice->paymentstauts == 0)
                                                    @permission('Invoice-Delete')
                                                    <li>
                                                        <form action="{{route('invoice.destroy',['invoice'=>$invoice->id,'bldraft_id'=>$invoice->bldraft_id])}}" method="post">
                                                            @method('DELETE')
                                                            @csrf
                                                        <button style="border: none; background: none;" type="submit" class="fa fa-trash text-danger show_confirm"></button>
                                                        </form>
                                                    </li>
                                                    @endpermission
                                                @endif
                                                </ul>
                                            </td>
                                            <!-- <td class="text-center">
                                                @if($invoice->invoice_status == "confirm"  && $invoice->created_at >= '2024-01-01' && $invoice->portal_status == null)
                                                    <a href="{{route('invoice.get_invoice_json',['invoice'=>$invoice->id])}}" data-toggle="tooltip"  target="_blank"  data-placement="top" title="" data-original-title="show">
                                                        <button type="submit" class="btn btn-primary mt-3">Json</button>
                                                    </a>
                                                @elseif($invoice->portal_status == 'Valid')
                                                        <button class="btn btn-success mt-3">Valid</button>
                                                @elseif($invoice->portal_status == 'Submitted')
                                                    <button class="btn btn-info mt-3">Submitted</button>
                                                @endif
                                            </td> -->
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
                            {{ $invoices->appends(request()->query())->links()}}

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
@push('scripts')

<script>
    const selectElement = document.getElementById('Bldraft');
    const bookingIdInput = document.getElementById('bookingIdInput');
    selectElement.addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const dataCodeValue = selectedOption.getAttribute('data-code');
    bookingIdInput.value = dataCodeValue;
    });
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
<script type="text/javascript">

     $('.show_confirm').click(function(event) {
          var form =  $(this).closest("form");
          var name = $(this).data("name");
          event.preventDefault();
          swal({
              title: `Are you sure you want to delete this Invoice?`,
              icon: "warning",
              buttons: true,
              dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) {
              form.submit();
            }
          });
      });

</script>
@endpush
