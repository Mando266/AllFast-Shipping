@extends('layouts.app')
@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-one">
                    <div class="widget-heading">
                        <nav class="breadcrumb-two" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Operation</a></li>
                                <li class="breadcrumb-item active"><a href="javascript:void(0);">Trucker Gate</a></li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                            <br>
                        <div class="row">
                            <div class="col-md-12 text-right mb-12">
                                @permission('TruckerGates-Create')
                                    <a href="{{route('truckergate.create')}}" class="btn btn-primary">New Insurance Certificate</a>
                                @endpermission
                                @permission('TruckerGates-List')
                                    <a class="btn btn-warning" href="{{ route('export.TruckerGate') }}">Export</a>
                                @endpermission

                            </div>
                        </div>
                    </div>
                    </br>
                    <form>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="Refrance">Booking No</label>
                                <select class="selectpicker form-control" id="Refrance" data-live-search="true" name="booking_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($booking as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('booking_id',request()->input('booking_id')) ? 'selected':''}}>{{$item->ref_no}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label>Certificate Type</label>
                                <select class="selectpicker form-control" data-live-search="true" name="certificate_type" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($certificate as $item)
                                        <option value="{{$item->certificate_type}}" {{$item->certificate_type == old('certificate_type',request()->input('certificate_type')) ? 'selected':''}}>{{$item->certificate_type}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-12 text-center">
                                <button  type="submit" class="btn btn-success mt-3">Search</button>
                                <a href="{{route('truckergate.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                            </div>
                        </div>
                    </form>
                    <div class="widget-content widget-content-area">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-condensed mb-4">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Booking No</th>
                                        <th>Principal Line</th>
                                        <th>vessel / Voyage</th>
                                        <th>Certificate Type</th>
                                        <th>Shipper Name</th>
                                        <th>Trucker Name</th>
                                        <th>Valid To</th>
                                        <th>Issue Date</th>
                                        <th>Qty</th>
                                        <th>Gross Premium</th>
                                        <th>Net Contribution</th>

                                        <th class='text-center' style='width:100px;'></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($items as $item)
                                    <?php 
                                        $qty = 0;
                                    ?>
                                        @foreach($item->booking->bookingContainerDetails as $bookingContainerDetail)
                                            <?php 
                                            $qty += $bookingContainerDetail->qty;
                                            ?>
                                        @endforeach
                                        <tr>
                                            <td>{{ App\Helpers\Utils::rowNumber($items,$loop)}}</td>
                                            <td>{{optional($item->booking)->ref_no}}</td>
                                            <td>{{optional($item->booking->principal)->name}}</td>
                                            <td>{{optional($item->booking->voyage->vessel)->name}} / {{optional($item->booking->voyage)->voyage_no}} </td>
                                            <td>{{$item->certificate_type}}</td>
                                            <td>{{optional($item->booking->customer)->name}}</td>
                                            <td>{{optional($item->trucker)->company_name}}</td>
                                            <td>{{$item->valid_to}}</td>
                                            <td>{{$item->issue_date}}</td>
                                            <td>{{ $item->qty }}</td>
                                            <td>{{$item->gross_premium * $item->qty}}</td>
                                            <td>{{$item->net_contribution * $item->qty}}</td>

                                            <td class="text-center">
                                                <ul class="table-controls">
                                                    @permission('TruckerGates-Edit')
                                                    <li>
                                                        <a href="{{route('truckergate.edit',['truckergate'=>$item->id])}}" data-toggle="tooltip" data-placement="top" title="" data-original-title="edit">
                                                            <i class="far fa-edit text-success"></i>
                                                        </a> 
                                                    </li>
                                                    @endpermission

                                                    @permission('TruckerGates-Delete')
                                                    <li>
                                                        <form action="{{route('truckergate.destroy',['truckergate'=>$item->id])}}" method="post">
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
              title: `Are you sure you want to delete this Trucker Gate?`,
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
