@extends('layouts.app')
@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-one">
                    <div class="widget-heading">
                        <nav class="breadcrumb-two" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Triffs</a></li>
                                <li class="breadcrumb-item active"><a href="javascript:void(0);">Slot Rates Triffs</a></li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                        @permission('SupplierPrice-Create')
                        <div class="row">
                            <div class="col-md-12 text-right mb-5">
                            <a href="{{route('supplierPrice.create')}}" class="btn btn-primary">Add New Rate</a>
                            </div>
                        </div>
                        @endpermission
                    </div>
                    <form>
                        <div class="form-row">
                        <div class="form-group col-md-4">
                                    <label for="supplier_id">Supplier</label>
                                    <select class="selectpicker form-control" id="supplier_id" data-live-search="true" name="supplier_id" data-size="10"
                                        title="{{trans('forms.select')}}">
                                        @foreach ($line as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('supplier_id',request()->input('supplier_id')) ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="validity_from">Validity From</label>
                                <input type="date" class="form-control" id="validity_from" name="validity_from" value="{{request()->input('validity_from')}}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="validity_to">Validity To</label>
                                <input type="date" class="form-control" id="validity_to" name="validity_to" value="{{request()->input('validity_to')}}">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="place_of_acceptence_id">POL</label>
                                <select class="selectpicker form-control" id="pol_id" data-live-search="true" name="pol_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($ports as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('pol_id',request()->input('pol_id')) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="pod_id">POD</label>
                                <select class="selectpicker form-control" id="pod_id" data-live-search="true" name="pod_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($ports as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('pod_id',request()->input('pod_id')) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-12 text-center">
                                <button  type="submit" class="btn btn-success mt-3">Search</button>
                                <a href="{{route('supplierPrice.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                            </div>
                        </div>
                    </form>

                    <div class="widget-content widget-content-area">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-condensed mb-4">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>supplier</th>
                                        <th>Rate Ref</th>
                                        <th>pol</th>
                                        <th>pod</th>
                                        <th>Equipment Type</th>
                                        <th>valid from</th>
                                        <th>valid to</th>
                                        <th>terms of Shipment</th>
                                        <th>slot rate mty</th>
                                        <th>Baaf for mty</th>
                                        <th>EWRI MTY</th>
                                        <th>slot rate laden</th>
                                        <th>Baaf for laden</th>
                                        <th>EWRI LADEN</th>

                                        <th class='text-center' style='width:100px;'></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($items as $item)
                                        <tr>
                                            <td>{{ App\Helpers\Utils::rowNumber($items,$loop)}}</td>
                                            <td>{{optional($item->line)->name}}</td>
                                            <td>{{$item->ref_rate}}</td>
                                            <td>{{optional($item->pol)->code}}</td>
                                            <td>{{optional($item->pod)->code}}</td>
                                            <td>{{optional($item->equipmentsType)->name}}</td>
                                            <td>{{$item->validity_from}}</td>
                                            <td>{{$item->validity_to}}</td>
                                            <td>{{$item->term_of_shipment}}</td>
                                            <td>{{$item->slot_rate_mty}}</td>
                                            <td>{{$item->baf_for_mty}}</td>
                                            <td>{{$item->ewri_mty}}</td>
                                            <td>{{$item->slot_rate_leden}}</td>
                                            <td>{{$item->baf_for_leden}}</td>
                                            <td>{{$item->ewri_leden}}</td>

                                            <td class="text-center">
                                                <ul class="table-controls">
                                                    @permission('SupplierPrice-Edit')
                                                    <li>
                                                        <a href="{{route('supplierPrice.edit',['supplierPrice'=>$item->id])}}" data-toggle="tooltip" data-placement="top" title="" data-original-title="edit">
                                                            <i class="far fa-edit text-success"></i>
                                                        </a>
                                                    </li>
                                                    @endpermission
                                                    @permission('SupplierPrice-Delete')
                                                    <li>
                                                        <form action="{{route('supplierPrice.destroy',['supplierPrice'=>$item->id])}}" method="post">
                                                            @method('DELETE')
                                                            @csrf
                                                        <button style="border: none; background: none;" type="submit" class="fa fa-trash text-danger show_confirm"></button>
                                                        </form> 
                                                    </li>

                                                    @endpermission
                                                </ul>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr class="text-center">
                                            <td colspan="25">{{ trans('home.no_data_found')}}</td>
                                        </tr>
                                    @endforelse

                                </tbody>

                            </table>
                        </div>
                        <div class="paginating-container">
                            {{ $items->links() }}
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
              title: `Are you sure you want to delete this Triff?`,
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
