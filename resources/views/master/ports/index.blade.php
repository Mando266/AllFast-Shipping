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
                                <li class="breadcrumb-item active"><a href="javascript:void(0);">Ports</a></li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                        @permission('Ports-Create')
                        <div class="row">
                            <div class="col-md-12 text-right mb-5">
                            <a href="{{route('ports.create')}}" class="btn btn-primary">Add New Port</a>
                            </div>
                        </div>
                        @endpermission
                    </div> 
                    <form>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="vessel_port_idInput">Port </label>
                                <select class="selectpicker form-control" id="vessel_port_idInput" data-live-search="true" name="name" data-size="10"
                                    title="{{trans('forms.select')}}">
                                    @foreach ($port as $item)
                                        <option value="{{$item->name}}" {{$item->name == old('name',request()->input('name')) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12 text-center">
                                <button  type="submit" class="btn btn-success mt-3">Search</button>
                                <a href="{{route('ports.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                            </div>
                    </div>
                </form>

                    <div class="widget-content widget-content-area">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-condensed mb-4">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Code</th>
                                        <th>Pickup / Return Location</th>
                                        <th>via port</th>
                                        <th>Country</th>
                                        <th>Terminal</th>
                                        <!-- <th>Agent</th> -->
                                        <th>Port Type</th>

                                        <th class='text-center' style='width:100px;'></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($items as $item)
                                        <tr>
                                            <td>{{ App\Helpers\Utils::rowNumber($items,$loop)}}</td>
                                            <td>{{$item->name}}</td>
                                            <td>{{$item->code}}</td>
                                            <td>{{$item->pick_up_location}}</td>
                                            <td>{{$item->via_port}}</td>
                                            <td>{{optional($item->country)->name}}</td>
                                            <td>{{optional($item->Terminal)->name}}</td>
                                            <!-- <td>{{optional($item->Agent)->name}}</td> -->
                                            <td>{{{optional($item->PortTypes)->name}}}</td>

                                            <td class="text-center">
                                                <ul class="table-controls">
                                                    @permission('Ports-Edit')
                                                    <li>
                                                        <a href="{{route('ports.edit',['port'=>$item->id])}}" data-toggle="tooltip" data-placement="top" title="" data-original-title="edit">
                                                            <i class="far fa-edit text-success"></i>
                                                        </a>
                                                    </li>
                                                    @endpermission
                                                    @permission('Ports-Delete')
                                                    <li>
                                                        <form action="{{route('ports.destroy',['port'=>$item->id])}}" method="post">
                                                            @method('DELETE')
                                                            @csrf
                                                        <button style="border: none; background: none;" type="submit" class="fa fa-trash text-danger show_confirm"></button>
                                                        </form> 
                                                    </li>
                                                    @endpermission
                                                    <!-- @permission('Ports-Show')
                                                    <li>
                                                        <a href="{{route('ports.show',['port'=>$item->id])}}" data-toggle="tooltip" data-placement="top" title="" data-original-title="show">
                                                            <i class="far fa-eye text-primary"></i>
                                                        </a>
                                                    </li>
                                                    @endpermission -->
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
              title: `Are you sure you want to delete this Port?`,
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
