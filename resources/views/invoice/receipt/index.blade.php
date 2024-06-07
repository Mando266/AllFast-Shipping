@extends('layouts.app')
@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-one">
                    <div class="widget-heading">
                        <nav class="breadcrumb-two" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a a href="javascript:void(0);">Receipts</a></li>
                                <li class="breadcrumb-item  active"><a href="javascript:void(0);">Receipt Gates</a></li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                    </div> 
                        <div class="row">
                            <div class="col-md-12 text-right mb-5">
                            @permission('Invoice-List')
                            <a href="{{route('export.receipt')}}" class="btn btn-warning">Export</a>
                            @endpermission
                            <a href="{{route('receipt.selectinvoice')}}" class="btn btn-primary">New Receipt</a>
                            </div>
                        </div>
                    </br>

                    <form>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="Receipt_no">RECEIPT NO</label>
                                <select class="selectpicker form-control" id="Receipt_no" data-live-search="true" name="receipt_no" data-size="10"
                                 title="{{trans('forms.select')}}">
                                 @foreach($receiptno as $receipt)
                                        <option value="{{$receipt->receipt_no}}">{{$receipt->receipt_no}}</option>
                                @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="Customer">Customer</label>
                                <select class="selectpicker form-control" id="Customer" data-live-search="true" name="customer" data-size="10"
                                 title="{{trans('forms.select')}}">
                                 @foreach($customers as $customer)
                                        <option value="{{$customer->id}}">{{$customer->name}}</option>
                                @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="Invoice">Invoice</label>
                                <select class="selectpicker form-control" id="Invoice" data-live-search="true" name="invoice" data-size="10"
                                 title="{{trans('forms.select')}}">
                                 @foreach($invoices as $invoice)
                                        <option value="{{$invoice->id}}">{{$invoice->invoice_no}}</option>
                                @endforeach
                                 
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="Bldraft">BL NO</label>
                                <select class="selectpicker form-control" id="Bldraft" data-live-search="true" name="bldraft" data-size="10"
                                 title="{{trans('forms.select')}}">
                                 @foreach($bldrafts as $bldraft)
                                        <option value="{{$bldraft->id}}">{{$bldraft->ref_no}}</option>
                                @endforeach
                                 
                                </select>
                            </div>
                            
                        </div>
                        

                            <div class="form-row">
                                <div class="col-md-12 text-center">
                                    <button  type="submit" class="btn btn-success mt-3">Search</button>
                                    <a href="{{route('receipt.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                                </div>
                            </div>
                    </form>
                    <div class="widget-content widget-content-area">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-condensed mb-4">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Receipt No</th>
                                        <th>Invoice No</th>
                                        <th>Bl No</th>
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
                                    @forelse ($receipts as $receipt)
                                        <tr>
                                            <td>{{ App\Helpers\Utils::rowNumber($receipts,$loop)}}</td>
                                            <td>{{$receipt->receipt_no}}</td>
                                            <td>{{optional($receipt->invoice)->invoice_no}}</td>
                                            <td>{{optional($receipt->bldraft)->ref_no}}</td>
                                            <td>{{optional($receipt->invoice->customerShipperOrFfw)->name}}</td>
                                            <td>
                                                @if($receipt->bank_transfer != null)
                                                    Bank Transfer {{$receipt->bank_transfer}}<br>
                                                @endif
                                                @if($receipt->bank_deposit != null)
                                                    Bank Deposit {{$receipt->bank_deposit}}<br>
                                                @endif
                                                @if($receipt->bank_check != null)
                                                    Bank Cheque  {{$receipt->bank_check}}<br>
                                                @endif
                                                @if($receipt->bank_cash != null)
                                                    Cash {{$receipt->bank_cash}}<br>
                                                @endif
                                                @if($receipt->matching != null)
                                                    Matching {{$receipt->matching}}<br>
                                                @endif
                                            </td>
                                            <td>{{$receipt->total}}</td>
                                            <td>{{$receipt->paid}}</td>
                                            @if(optional($receipt->invoice)->add_egp == 'false')
                                            <td>USD</td>
                                            @else
                                            <td>EGP</td>
                                            @endif
                                            <td>{{optional($receipt->user)->name}}</td>
                                            
                                            <td class="text-center">
                                                <ul class="table-controls">                                                
                                                @permission('Invoice-Show')
                                                <li>
                                                    <a href="{{route('receipt.show',['receipt'=>$receipt->id])}}" data-toggle="tooltip"  target="_blank"  data-placement="top" title="" data-original-title="show">
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
                            {{ $receipts->appends(request()->query())->links()}}

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
@push('scripts')
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
<script>
    $(function(){
            let customer = $('#Customer');
            $('#Customer').on('change',function(e){
                let value = e.target.value;
                let response =    $.get(`/api/master/invoicesCustomers/${customer.val()}`).then(function(data){
                    let invoices = data.invoices || '';
                    let list2 = [`<option value=''>Select...</option>`];
                    for(let i = 0 ; i < invoices.length; i++){
                        list2.push(`<option value='${invoices[i].id}'>${invoices[i].invoice_no} </option>`);
                    }
            let invoice = $('#Invoice');
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
                        list2.push(`<option value='${invoices[i].id}'>${invoices[i].invoice_no} </option>`);
                    }
            let invoice = $('#Invoice');
            invoice.html(list2.join(''));
            $(invoice).selectpicker('refresh');

            });
        });
    });
</script>
@endpush
