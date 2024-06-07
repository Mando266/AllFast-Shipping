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
                                <li class="breadcrumb-item active"><a href="javascript:void(0);">Agents</a></li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                        @permission('Agents-Create')
                        <div class="row">
                            <div class="col-md-12 text-right mb-5">
                            <a href="{{route('agents.create')}}" class="btn btn-primary">Add New Agent</a>
                            </div>
                        </div>
                        @endpermission
                    </div>
                    <div class="widget-content widget-content-area">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-condensed mb-4">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Country</th>
                                        <th>City</th>
                                        <th class="text-center">{{trans('user.status')}}</th>
                                        <th>Intermediate Payer</th>
                                        <th>port control</th>
                                        <th>Documentation Control</th>
                                        <th class='text-center' style='width:100px;'></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($items as $item)
                                        <tr>
                                            <td>{{ App\Helpers\Utils::rowNumber($items,$loop)}}</td>
                                            <td>{{$item->name}}</td>
                                            <td>{{optional($item->country)->name}}</td>
                                            <td>{{$item->city}}</td>
                                            <td class="text-center">
                                                        @if($item->is_active )
                                                            <span class="badge badge-info"> Active </span>
                                                        @else
                                                            <span class="badge badge-danger"> Inactive </span>
                                                        @endif
                                                    </td>
                                            <td class="text-center">
                                                @if($item->intermediate_payer)
                                                <input type="checkbox" checked="checked" readonly>
                                                    @else
                                                    <input type="checkbox" readonly>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($item->port_control)
                                                <input type="checkbox" checked="checked" readonly>
                                                    @else
                                                    <input type="checkbox" readonly>
                                                @endif
                                            </td>  
                                            <td class="text-center">
                                                @if($item->documentation_control)
                                                <input type="checkbox" checked="checked" readonly>
                                                    @else
                                                    <input type="checkbox" readonly>
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                <ul class="table-controls">
                                                    @permission('Agents-Edit')
                                                    <li>
                                                        <a href="{{route('agents.edit',['agent'=>$item->id])}}" data-toggle="tooltip" data-placement="top" title="" data-original-title="edit">
                                                            <i class="far fa-edit text-success"></i>
                                                        </a>
                                                    </li>
                                                    @endpermission
                                                    @permission('Agents-Delete')
                                                    <li>
                                                        <form action="{{route('agents.destroy',['agent'=>$item->id])}}" method="post">
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
              title: `Are you sure you want to delete this Agent?`,
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
