@extends('layouts.app')
@section('content')

<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('movements.index')}}">Movements</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">Movement Details </a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                        <div class="row">
                            <div class="col-md-12 text-right mb-6">

                                @permission('Movements-List')
                                @if (Auth::user()->id != 18)
                                <form class="export-form" action="{{ route('export') }}" method="post">
                                        @csrf
                                    <input type="hidden" name="items" value="">
                                        <button class="btn btn-warning" type="submit">Export</button>
                                </form>
                                @endif
                                @endpermission
                                @permission('Movements-Create')
                                    <a href="{{route('movements.create',['container_id'=>$containers->id])}}" class="btn btn-primary">Add New Movement</a>
                                @endpermission
                            </div>

                        </div>
                </div>
            </br>
            <h5><span style='color:#1b55e2';>Container No / Type:</span> {{$containers->code}} / {{{optional($containers->containersTypes)->name}}}</h5>
            </br>
                <?php

use App\Models\Containers\Movements;

if(request()->input('container_id') != null){

                    $container_id = request()->input('container_id');
                    if(is_array($container_id)){
                        $container_id = $container_id[0];
                    }
                }elseif(request()->input('bl_no') != null){
                    $container_id = Movements::where('bl_no',request()->input('bl_no'))->pluck('container_id')->first();

                }elseif($id != null){

                    $container_id = $id;
                    }?>

                <div class="widget-content widget-content-area">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-condensed mb-4">
                                <thead>
                                    <tr>
                                        <th>Movement</th>
                                        <th>Movement Date</th>
                                        <th>ACTIVITY LOCATION</th>
                                        <th>Pol</th>
                                        <th>Pod</th>
                                        <th>VSL/VOY</th>
                                        <th>BOOKING</th>
                                        <th>BL No</th>
                                        <th>free time destination</th>
                                        <th>import agent</th>
                                        <th>booking agent</th>
                                        <th>REMARKS</th>
                                        <th class='text-center' style='width:100px;'></th>
                                    </tr>
                                </thead>
                                <tbody>
                               

                                    @if($movementId == true)

                                        @if($movementsArray == false)
                                        <tr>
                                            <td>{{{optional($items->movementcode)->code}}}</td>
                                            <td>{{$items->movement_date}}</td>
                                            <td>{{optional($items->activitylocation)->code}}</td>
                                            <td>{{optional($items->pol)->code}}</td>
                                            <td>{{optional($items->pod)->code}}</td>
                                            <td>{{{optional($items->vessels)->name}}} {{optional($items->voyage)->voyage_no}}</td>
                                            <td>{{optional($items->booking)->ref_no}}</td>
                                            <td>{{$items->bl_no}}</td>
                                            <td>{{$items->free_time}}</td>
                                            <td>{{{optional($items->importAgent)->name}}}</td>
                                            <td>{{{optional($items->bookingAgent)->name}}}</td>
                                            <td>{{$items->remarkes}}</td>
                                            <td></td>

                                            <td class="text-center">
                                                <ul class="table-controls">
                                                    @permission('Movements-Edit')
                                                    <li>
                                                            <a href="{{route('movements.edit',['movement'=>$items->id])}}" data-toggle="tooltip" data-placement="top" title="" data-original-title="edit">
                                                                <i class="far fa-edit text-success"></i>
                                                            </a>
                                                    </li>
                                                    @endpermission
                                                    @permission('Movements-Delete')
                                                    <li>
                                                        <form action="{{route('movements.destroy',['movement'=>$items->id])}}" method="post">
                                                            @method('DELETE')
                                                            @csrf
                                                        <button style="border: none; background: none;" type="submit" class="fa fa-trash text-danger"></button>
                                                        </form>
                                                    </li>
                                                    @endpermission
                                                </ul>
                                            </td>
                                        </tr>
                                        @else
                                        <tr class="text-center">
                                            <td colspan="20">{{ trans('home.no_data_found')}}</td>
                                        </tr>
                                        @endif
                                    @else
                                        @forelse ($items as $item)
                                        <tr>
                                            <td>{{{optional($item->movementcode)->code}}}</td>
                                            <td>{{$item->movement_date}}</td>
                                            <td>{{optional($item->activitylocation)->code}}</td>
                                            <td>{{optional($item->pol)->code}}</td>
                                            <td>{{optional($item->pod)->code}}</td>
                                            <td>{{{optional($item->vessels)->name}}} {{optional($item->voyage)->voyage_no}}</td>
                                            <td>{{optional($item->booking)->ref_no}}</td>
                                            <td>{{$item->bl_no}}</td>
                                            <td>{{$item->free_time}}</td>
                                            <td>{{{optional($item->importAgent)->name}}}</td>
                                            <td>{{{optional($item->bookingAgent)->name}}}</td>
                                            <td>{{$item->remarkes}}</td>

                                            <td class="text-center">
                                                <ul class="table-controls">
                                                    @permission('Movements-Edit')
                                                    <li>
                                                            <a href="{{route('movements.edit',['movement'=>$item->id])}}" data-toggle="tooltip" data-placement="top" title="" data-original-title="edit">
                                                                <i class="far fa-edit text-success"></i>
                                                            </a>
                                                    </li>
                                                    @endpermission
                                                    @permission('Movements-Delete')
                                                    <li>
                                                        <form action="{{route('movements.destroy',['movement'=>$item->id])}}" method="post">
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

                                    @endif
                                </tbody>
                            </table>
                        </div>
                        @if($movementId == false)
                            <div class="paginating-container">
                                {{ $items->appends(request()->query())->links()}}
                            </div>
                        @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="{{asset('plugins/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css" >
<style>
        .export-form {
            display: inline; /* This ensures the form is displayed inline */
        }

        .export-form .btn-link {
            background: none; /* Remove the background color */
            border: none; /* Remove the border */
            color: #007bff; /* Set the link color */
            text-decoration: underline; /* Add underline to mimic link text */
            cursor: pointer; /* Show pointer cursor on hover */
        }
    </style>
@endpush
@push('scripts')
<script src="{{asset('plugins/bootstrap-select/bootstrap-select.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
<script type="text/javascript">
     $('.show_confirm').click(function(event) {
          var form =  $(this).closest("form");
          var name = $(this).data("name");
          event.preventDefault();
          swal({
              title: `Are you sure you want to delete this Movement?`,
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
