@extends('layouts.app')
@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-one">
                    <div class="widget-heading">
                        <nav class="breadcrumb-two" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Master Data</a></li>
                                <li class="breadcrumb-item active"><a href="javascript:void(0);">Vessels</a></li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                        @permission('Vessels-Create')
                        <div class="row">
                            <div class="col-md-12 text-right mb-5">
                            <a href="{{route('vessels.create')}}" class="btn btn-primary">Add New Vessel</a>
                            </div>
                        </div>
                        @endpermission
                    </div>
            <form>
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="vessel_port_idInput">Vessel Name</label>
                        <select class="selectpicker form-control" id="vessel_port_idInput" data-live-search="true" name="id" data-size="10"
                            title="{{trans('forms.select')}}">
                            @foreach ($vessel as $item)
                                <option value="{{$item->id}}" {{$item->id == old('id',request()->input('id')) ? 'selected':''}}>{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12 text-center">
                        <button  type="submit" class="btn btn-success mt-3">Search</button>
                        <a href="{{route('vessels.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                    </div>
                </div>
            </form>
                    <div class="widget-content widget-content-area">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-condensed mb-4">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th>Call Sign</th>
                                        <th>Imo Number</th>
                                        <th>Year Built</th>
                                        <th>MMSI</th>
                                        <th>flag</th>
                                        <th>G.W</th>
                                        <th>DWT</th>
                                        <!-- <th>Vessel Type</th> -->
                                        <!-- <th>Vessel Operator</th> -->
                                        <th class='text-center' style='width:100px;'></th>

                                        <th class='text-center' style='width:100px;'></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($items as $item)
                                        <tr>
                                            <td>{{ App\Helpers\Utils::rowNumber($items,$loop)}}</td>
                                            <td>{{$item->code}}</td>
                                            <td>{{$item->name}}</td>
                                            <td>{{$item->call_sign}}</td>
                                            <td>{{$item->imo_number}}</td>
                                            <td>{{$item->production_year}}</td>
                                            <td>{{$item->mmsi}}</td>
                                            <td>{{optional($item->country)->name}}</td>
                                            <td>{{$item->gw_no}}</td>
                                            <td>{{$item->dwt_no}}</td>
                                            <!-- <td>{{optional($item->VesselType)->name}}</td> -->
                                            <!-- <td>{{{optional($item->VesselOperators)->name}}}</td> -->
                                            <td class="text-center">
                                                <ul class="table-controls">
                                                    @if($item->certificat == !null)
                                                    <li>
                                                        <a href='{{asset($item->certificat)}}' target="_blank">
                                                            <i class="fas fa-file-pdf text-primary" style='font-size:large;'></i>
                                                        </a>
                                                    </li>
                                                    @endif
                                                    </ul>
                                            </td>
                                            <td class="text-center">
                                                <ul class="table-controls">
                                                    @permission('Vessels-Edit')
                                                    <li>
                                                        <a href="{{route('vessels.edit',['vessel'=>$item->id])}}" data-toggle="tooltip" data-placement="top" title="" data-original-title="edit">
                                                            <i class="far fa-edit text-success"></i>
                                                        </a>
                                                    </li>
                                                    @endpermission
                                                    @permission('Vessels-Delete')
                                                    <li>
                                                        <form action="{{route('vessels.destroy',['vessel'=>$item->id])}}" method="post">
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
              title: `Are you sure you want to delete this Vessel?`,
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