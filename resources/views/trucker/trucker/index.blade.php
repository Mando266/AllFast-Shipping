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
                                <li class="breadcrumb-item active"><a href="javascript:void(0);">Trucker</a></li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                            <br>
                        <div class="row">
                            <div class="col-md-12 text-right mb-12">
                                @permission('Trucker-Create')
                                    <a href="{{route('trucker.create')}}" class="btn btn-primary">Add New Trucker</a>
                                @endpermission
                            </div>
                        </div>
                    </div>
            <form>
                <div class="form-row">
                    <div class="form-group col-md-12">
                            <label for="name">Truckers</label>
                            <select class="selectpicker form-control" id="company_name" data-live-search="true" name="company_name[]" data-size="10"
                                title="{{trans('forms.select')}}"  multiple="multiple">
                                @foreach ($items as $item)
                                    <option value="{{$item->company_name}}" {{$item->company_name == old('company_name',request()->input('company_name')) ? 'selected':''}}>{{$item->company_name}}</option>
                                @endforeach
                            </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-12 text-center">
                        <button  type="submit" class="btn btn-success mt-3">Search</button>
                        <a href="{{route('trucker.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                    </div>
                </div>
            </form>
                    <div class="widget-content widget-content-area">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-condensed mb-4">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>company name</th>
                                        <th>Contact Person</th>
                                        <th>Mobile</th>
                                        <th>phone</th>
                                        <th>delegated Persons</th>
                                        <th>Pdf</th>
                                        <th class='text-center' style='width:100px;'></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($items as $item)
                                        <tr>
                                            <td>{{ App\Helpers\Utils::rowNumber($items,$loop)}}</td>
                                            <td>{{$item->company_name}}</td>
                                            <td>{{$item->contact_person}}</td>
                                            <td>{{$item->mobile}}</td>
                                            <td>{{$item->phone}}</td>
                                            <td>
                                                    @foreach($item->delegatedPersons as $delegatedPerson)
                                                    <li>{{optional($delegatedPerson)->name}} </li>
                                                    @endforeach
                                            </td>

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
                                                    @permission('Trucker-Edit')
                                                    <li>
                                                        <a href="{{route('trucker.edit',['trucker'=>$item->id])}}" data-toggle="tooltip" data-placement="top" title="" data-original-title="edit">
                                                            <i class="far fa-edit text-success"></i>
                                                        </a>
                                                    </li>
                                                    @endpermission

                                                    @permission('Trucker-Delete')
                                                    <li>
                                                        <form action="{{route('trucker.destroy',['trucker'=>$item->id])}}" method="post">
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
              title: `Are you sure you want to delete this Trucker?`,
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
