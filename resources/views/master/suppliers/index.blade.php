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
                                <li class="breadcrumb-item active"><a href="javascript:void(0);">Suppliers</a></li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                        @permission('Suppliers-Create')
                        <div class="row">
                            <div class="col-md-12 text-right mb-5">
                            <a href="{{route('suppliers.create')}}" class="btn btn-primary">Add New Supplier</a>
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
                                        <th>Container Depot</th>
                                        <th>services provider</th>
                                        <th>container seller</th>
                                        <th>container trucker</th>
                                        <th>container lessor</th>
                                        <th>container haulage</th>
                                        <th>container terminal</th>

                                        <th class='text-center' style='width:100px;'></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($items as $item)
                                        <tr>
                                            <td>{{ App\Helpers\Utils::rowNumber($items,$loop)}}</td>
                                            <td>{{$item->name}}</td>
                                            <td>{{optional($item->country)->name}}</td>
                                            <td class="text-center">
                                                @if($item->is_container_depot)
                                                <input type="checkbox" checked="checked">
                                                    @else
                                                    <input type="checkbox">
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($item->is_container_services_provider)
                                                <input type="checkbox" checked="checked">
                                                    @else
                                                    <input type="checkbox">
                                                @endif
                                            </td>  
                                            <td class="text-center">
                                                @if($item->is_container_seller)
                                                <input type="checkbox" checked="checked">
                                                    @else
                                                    <input type="checkbox">
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($item->is_container_trucker)
                                                <input type="checkbox" checked="checked">
                                                    @else
                                                    <input type="checkbox">
                                                @endif
                                            </td>                                            
                                            <td class="text-center">
                                                @if($item->is_container_lessor)
                                                <input type="checkbox" checked="checked">
                                                    @else
                                                    <input type="checkbox">
                                                @endif
                                            </td>                                            
                                            <td class="text-center">
                                                @if($item->is_container_haulage)
                                                <input type="checkbox" checked="checked">
                                                    @else
                                                    <input type="checkbox">
                                                @endif
                                            </td>                                            
                                            <td class="text-center">
                                                @if($item->is_container_terminal)
                                                <input type="checkbox" checked="checked">
                                                    @else
                                                    <input type="checkbox">
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <ul class="table-controls">
                                                    @permission('Suppliers-Edit')
                                                    <li>
                                                        <a href="{{route('suppliers.edit',['supplier'=>$item->id])}}" data-toggle="tooltip" data-placement="top" title="" data-original-title="edit">
                                                            <i class="far fa-edit text-success"></i>
                                                        </a>
                                                    </li>
                                                    @endpermission
                                                    @permission('Suppliers-Delete')
                                                    <li>
                                                        <form action="{{route('suppliers.destroy',['supplier'=>$item->id])}}" method="post">
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

    const checkboxes = document.querySelectorAll('input[type="checkbox"]');

    checkboxes.forEach((checkbox) => {
        checkbox.addEventListener('click', function(event) {
            event.preventDefault();
            return false;
        });
    });
 
     $('.show_confirm').click(function(event) {
          var form =  $(this).closest("form");
          var name = $(this).data("name");
          event.preventDefault();
          swal({
              title: `Are you sure you want to delete this Supplier?`,
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