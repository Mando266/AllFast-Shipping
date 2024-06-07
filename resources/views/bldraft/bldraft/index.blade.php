@extends('layouts.app')
@section('content')
@include('bldraft.bldraft._modal_show_containers')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-one">
                    <div class="widget-heading">
                        <nav class="breadcrumb-two" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a a href="javascript:void(0);">Bl Draft</a></li>
                                <li class="breadcrumb-item  active"><a href="javascript:void(0);">BL Gates</a></li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                    </div> 
                        <div class="row">
                            <div class="col-md-12 text-right mb-5">
                            @permission('BlDraft-Create')
                                <a href="{{route('bldraft.selectbooking')}}" class="btn btn-primary">New Bl Draft</a>
                                <a href="{{route('bookingContainersRefresh')}}" class="btn btn-warning">Refresh Booking Containers</a>
                            @endpermission
                            @permission('BlDraft-List')
                                <a class="btn btn-info" href="{{ route('export.BLExport') }}">BL Export</a> 
                                <a class="btn btn-success" href="{{ route('export.BLloadList') }}">BL Loadlist</a> 
                            @endpermission

                            </div>
                        </div>
                    </br>
                    <form>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="Refrance">Bl Number </label>
                                <select class="selectpicker form-control" id="Refrance" data-live-search="true" name="ref_no" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($blDraftNo as $item)
                                        <option value="{{$item->ref_no}}" {{$item->ref_no == old('ref_no',request()->input('ref_no')) ? 'selected':''}}>{{$item->ref_no}}</option>
                                    @endforeach
                                </select>
                            </div>
 
                            <div class="form-group col-md-4">
                                    <label for="customer_id">Agreement Party</label>
                                    <select class="selectpicker form-control" id="customer_id" data-live-search="true" name="customer_id" data-size="10"
                                        title="{{trans('forms.select')}}">
                                        @foreach ($customers as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('customer_id',request()->input('customer_id')) ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="ffw_id">Forwarder Customer</label>
                                <select class="selectpicker form-control" id="ffw_id" data-live-search="true" name="ffw_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($ffw as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('ffw_id',request()->input('ffw_id')) ? 'selected':''}}>{{$item->name}} </option>
                                    @endforeach
                                </select>
                                @error('ffw_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="POL">POL</label>
                            <select class="selectpicker form-control" id="POL" data-live-search="true" name="load_port_id" data-size="10"
                             title="{{trans('forms.select')}}">
                                @foreach ($ports as $item)
                                    <option value="{{$item->id}}" {{$item->id == old('load_port_id',request()->input('load_port_id')) ? 'selected':''}}>{{$item->code}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="place_of_delivery_id">POD</label>
                            <select class="selectpicker form-control" id="discharge_port_id" data-live-search="true" name="discharge_port_id" data-size="10"
                             title="{{trans('forms.select')}}">
                                @foreach ($ports as $item) 
                                    <option value="{{$item->id}}" {{$item->id == old('discharge_port_id',request()->input('discharge_port_id')) ? 'selected':''}}>{{$item->code}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="voyage_id">Vessel / Voyage </label>
                            <select class="selectpicker form-control" id="voyage_id_both" data-live-search="true" name="voyage_id_both" data-size="10"
                                title="{{trans('forms.select')}}">
                                @foreach ($voyages as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('voyage_id_both',request()->input('voyage_id_both')) ? 'selected':''}}>{{$item->vessel->name}} / {{$item->voyage_no}} - {{ optional($item->leg)->name }}</option>
                                @endforeach
                            </select>
                            @error('voyage_id_both')
                            <div style="color: red;">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                    </div>

                        <div class="form-row">
                            <div class="col-md-12 text-center">
                                <button  type="submit" class="btn btn-success mt-3">Search</button>
                                <button type="button" id="reset-select" class="btn btn-info mt-3">Reset</button>
                                <a href="{{route('bldraft.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                            </div>
                        </div>
                    </form> 
                    <div class="widget-content widget-content-area">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-condensed mb-4">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Booking no</th>
                                        <th>Bl Draft ref no</th>
                                        <th>Agreement Party</th>
                                        <th>forwarder</th>
                                        <th>consignee</th>
                                        <th>load port</th>
                                        <th>discharge port</th>
                                        <th>Equipment Type</th>
                                        <th>containers</th>
                                        <th>voyage vessel</th>
                                        <th>leg</th>
                                        <th>Bl Draft Creation</th>
                                        <th>Invoices</th>
                                        <th>BL Status</th>
                                        <th>BL Type</th>
                                        <th>BL Manafest</th>
                                        <th>BL Service Manafest</th>
                                        <th>ADD BL</th>

                                        <th class='text-center' style='width:100px;'></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($items as $item)
                                        @if ($item->invoice)
                                            @php
                                            $invoiceStatus = $item->invoice->paymentstauts;
                                            @endphp
                                        @else
                                            @php
                                            $invoiceStatus = '';
                                            @endphp
                                        @endif
                                        <tr>
                                            <td>{{ App\Helpers\Utils::rowNumber($items,$loop)}}</td>
                                            <td>{{optional($item->booking)->ref_no}}</td>
                                            <td>{{$item->ref_no}}</td>
                                            <td>{{optional($item->customer)->name}}</td>
                                            <td>{{optional($item->booking->forwarder)->name}}</td>
                                            <td>{{optional($item->customerConsignee)->name}}</td>
                                            <td>{{optional($item->loadPort)->code}}</td>
                                            <td>{{optional($item->dischargePort)->code}}</td>
                                            <td>{{optional($item->equipmentsType)->name}}</td> 
                                            @if($item->blDetails->count()  == 1 )
                                            <td> 
                                                @foreach($item->blDetails as $blDetail)
                                                <table style="border: hidden;">
                                                    <td>{{ optional($blDetail->container)->code }}</td>
                                                </table>
                                                @endforeach
                                            </td>
                                            @else
                                            <td class="container-count" data-id="{{ $item->id }}">
                                                {{ $item->blDetails->count() }} Containers
                                             </td>  
                                             @endif                                           
                                            <td>{{optional(optional($item->voyage)->vessel)->name}}  {{optional($item->voyage)->voyage_no}}</td>
                                            <td>{{optional(optional($item->voyage)->leg)->name}}</td>
                                            <td>{{{$item->created_at}}}</td>
                                            <td>
                                                @php
                                                $user = Auth::user();
                                                $usersToCheck = [1, 7, 3, 20];
                                                    $paymentstautsPaid =  0;
                                                    $paymentstautsUnPaid = 0;
                                                    $draft_invoice=0;
                                                    $confirm_invoice=0;
                                                    foreach ($item->invoices as $invoice) {
                                                        if($invoice->invoice_status == "draft"){
                                                            $draft_invoice ++;
                                                        }elseif($invoice->invoice_status == "confirm"){
                                                            $confirm_invoice ++;
                                                        }

                                                        if($invoice->paymentstauts == 1){
                                                            $paymentstautsPaid ++;
                                                        }else{
                                                            $paymentstautsUnPaid ++;
                                                        }
                                                    }

                                                @endphp
                                                {{$draft_invoice}} Draft <br>
                                                {{$confirm_invoice}} Confirm
                                            </td>
                                            <td class="text-center">
                                                @if($item->bl_status == 1)
                                                    <span class="badge badge-info"> Confirm </span>
                                                @else
                                                    <span class="badge badge-danger"> Draft </span>
                                                @endif
                                            </td>
                                            <td>{{$item->bl_kind}}</td>
                                            <td class="text-center">
                                                @permission('BlDraft-List')
                                                    <ul class="table-controls">
                                                        <li>
                                                            <a href="{{route('bldraft.manifest',['bldraft'=>$item->id])}}" target="_blank">
                                                                <i class="fas fa-file-pdf text-primary" style='font-size:large;'></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                @endpermission
                                            </td>
                                            <td class="text-center">
                                                @permission('BlDraft-List')
                                                    <ul class="table-controls">
                                                        <li>
                                                            <a href="{{route('bldraft.serviceManifest',['bldraft'=>$item->id])}}" target="_blank">
                                                                <i class="fas fa-file-pdf text-primary" style='font-size:large;'></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                @endpermission
                                            </td>
                                            <td class="text-center">
                                                @permission('BlDraft-Create')
                                                @if(optional($item->booking)->movement != 'FCL/FCL')
                                                <a href="{{route('bldraft.create',['bldraft'=>$item->id,'booking_id'=>$item->booking_id])}}" data-toggle="tooltip" data-placement="top" title="" data-original-title="edit">
                                                        <i class="fas fa-plus text-primary"></i>
                                                    </a>
                                                @elseif($item->has_child)
                                                    <a href="{{route('bldraft.create',['bldraft'=>$item->id,'booking_id'=>$item->booking_id])}}" data-toggle="tooltip" data-placement="top" title="" data-original-title="edit">
                                                        <i class="fas fa-plus text-primary"></i>
                                                    </a>
                                                @endif
                                                @endpermission
                                            </td>

                                            <td class="text-center">
                                                 <ul class="table-controls">
                                                    @permission('Booking-Edit')
                                                    <li>
                                                        <a href="{{route('bldraft.edit',['bldraft'=>$item->id,'booking_id'=>$item->booking_id])}}" data-toggle="tooltip" data-placement="top" title="" data-original-title="edit">
                                                            <i class="far fa-edit text-success"></i>
                                                        </a>
                                                    </li>
                                                    @endpermission
                                                    
                                                    @permission('Booking-Show')
                                                    <li>
                                                    @if(optional(optional($item->booking)->principal)->code == 'Cstar' || optional(optional($item->booking)->principal)->code  == 'Winwin' )
                                                            @if((in_array($user->id, $usersToCheck)))
                                                                <a href="{{ route('bldraft.showCstar', ['bldraft' => $item->id]) }}" data-toggle="tooltip" data-placement="top" title="" data-original-title="show">
                                                                    <i class="far fa-eye text-primary"></i>
                                                                </a>
                                                            @elseif(($paymentstautsPaid > 0) && ($paymentstautsUnPaid == 0) || ($paymentstautsPaid == 0) && ($paymentstautsUnPaid > 0) || ($paymentstautsPaid == 0) && ($paymentstautsUnPaid == 0))  
                                                                <a href="{{ route('bldraft.showCstar', ['bldraft' => $item->id]) }}" data-toggle="tooltip" data-placement="top" title="" data-original-title="show">
                                                                    <i class="far fa-eye text-primary"></i>
                                                                </a>
                                                            @elseif(($paymentstautsPaid > 0) && ($paymentstautsUnPaid > 0) )
                                                            <a href="{{ route('bldraft.showCstar', ['bldraft' => $item->id]) }}" data-toggle="tooltip" data-placement="top" title="" data-original-title="show">
                                                                    <i class="far fa-eye text-primary"></i>
                                                                </a>
                                                        @endif
                                                    @else
                                                        @if(($paymentstautsPaid > 0) && ($paymentstautsUnPaid == 0) || ($paymentstautsPaid == 0) && ($paymentstautsUnPaid > 0) || ($paymentstautsPaid == 0) && ($paymentstautsUnPaid == 0))  
                                                            <a href="{{ route('bldraft.show', ['bldraft' => $item->id]) }}" data-toggle="tooltip" data-placement="top" title="" data-original-title="show">
                                                                <i class="far fa-eye text-primary"></i>
                                                            </a>
                                                        @elseif(($paymentstautsPaid > 0) && ($paymentstautsUnPaid > 0))
                                                            <a href="{{ route('bldraft.showCstar', ['bldraft' => $item->id]) }}" data-toggle="tooltip" data-placement="top" title="" data-original-title="show">
                                                                        <i class="far fa-eye text-primary"></i>
                                                            </a>
                                                        @endif
                                                    @endif
                                                    </li>
                                                    @endpermission 
                                                    @permission('BlDraft-Delete')
                                                    @if($item->has_bl == 0)
                                                    <li>
                                                        <form action="{{route('bldraft.destroy',['bldraft'=>$item->id])}}" method="post">
                                                            @method('DELETE')
                                                            @csrf
                                                        <button style="border: none; background: none;" type="submit" class="fa fa-trash text-danger show_confirm"></button>
                                                        </form> 
                                                    </li>
                                                    @endif
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
                            {{ $items->appends(request()->query())->links()}}
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
              title: `Are you sure you want to delete this BL?`,
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

      $('.show_alert').click(function(event) {
          var form =  $(this).closest("form");
          var name = $(this).data("name");
          event.preventDefault();
          swal({
              title: `"The bill of lading cannot be printed due to outstanding unpaid invoices. Please ensure that all invoices are settled or request higher permission authority for resolution.".`,
              icon: "warning",
              buttons: true,
              dangerMode: true,
          })
        
      });
</script>
<script>
$(document).on('click', '.container-count', function() {
    var blDraftId = $(this).data('id');
    $.ajax({
        url: '/api/bldrafts/' + blDraftId + '/containers',
        method: 'GET',
        success: function(data) {
            var blNo = data.bl_no;
            var containers = data.containers;
            var containerList = '';

            // Create the HTML for the container list
            for (var i = 0; i < containers.length; i++) {
                containerList += '<h6>' + containers[i] + '</h6>';
            }

            // Create the HTML for the modal body
            var modalBody = '<h5 style="color:#1b55e2;">BLNO: ' + blNo + '</h5><ul>' + containerList + '</ul>';

            // Set the modal body and show the modal
            $('#container-modal .modal-body').html(modalBody);
            $('#container-modal').modal('show');
        },
        error: function() {
            alert('Error fetching containers');
        }
    });
});
</script>
<style>
.container-count:hover {
  cursor: pointer;
  background-color: #dddddd96;
}
</style>
@endpush
