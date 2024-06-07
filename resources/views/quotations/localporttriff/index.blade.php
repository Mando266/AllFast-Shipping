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
                                <li class="breadcrumb-item active"><a href="javascript:void(0);">Local Port Triff</a></li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                        @permission('LocalPortTriff-Create')
                        <div class="row">
                            <div class="col-md-12 text-right mb-5">
                            <a href="{{route('localporttriff.create')}}" class="btn btn-primary"> New Local Port Triff</a>
                            </div>
                        </div>
                        @endpermission
                    </div>
                    <form>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="Refrance">Triff Number </label>
                                <select class="selectpicker form-control" id="Refrance" data-live-search="true" name="triff_no" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($localport as $item)
                                        <option value="{{$item->triff_no}}" {{$item->triff_no == old('triff_no',request()->input('triff_no')) ? 'selected':''}}>{{$item->triff_no}}</option>
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
                            <div class="col-md-12 text-center">
                                <button  type="submit" class="btn btn-success mt-3">Search</button>
                                <a href="{{route('localporttriff.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                            </div>
                        </div>
                    </form>
                        <div class="widget-content widget-content-area">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-condensed mb-4">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Tariff No</th>
                                            <th>country</th>
                                            <th>port</th>
                                            <th>Agent</th>
                                            <th>validity from</th>
                                            <th>validity to</th>
                                            <th>Triff Details</th>
                                            <th></th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($items as $item)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$item->triff_no}}</td>
                                            <td>{{{optional($item->country)->name}}}</td>
                                            <td>{{{optional($item->port)->code}}}</td>
                                            <td>{{{optional($item->agent)->name}}}</td>
                                            <td>{{$item->validity_from}}</td>
                                            <td>{{$item->validity_to}}</td>

                                            <td class="text-center">
                                                <ul class="table-controls">
                                                    @permission('LocalPortTriff-Show')
                                                    <li>
                                                        <a href="{{route('localporttriff.show',['localporttriff'=>$item->id])}}" data-toggle="tooltip" data-placement="top" title="" data-original-title="show" target="blank">
                                                            <i class="far fa-eye text-primary"></i>
                                                        </a>
                                                    </li>
                                                    @endpermission
                                                </ul>
                                                
                                            </td>
                                            <td class="text-center">
                                                <ul class="table-controls">
                                                    @permission('LocalPortTriff-Edit')
                                                    <li>
                                                        <a href="{{route('localporttriff.edit',['localporttriff'=>$item->id])}}" data-toggle="tooltip" data-placement="top" title="" data-original-title="edit">
                                                            <i class="far fa-edit text-success"></i>
                                                        </a>
                                                    </li>
                                                    @endpermission
                                                    @permission('LocalPortTriff-Delete')
                                                    <li>
                                                        <form action="{{route('localporttriff.destroy',['localporttriff'=>$item->id])}}" method="post">
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
