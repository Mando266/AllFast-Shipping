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
                            <li class="breadcrumb-item"><a a href="javascript:void(0);">Manifest</a></li>
                                <li class="breadcrumb-item  active"><a href="javascript:void(0);">XML Gates</a></li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                    </div> 
                        <div class="row">
                            <div class="col-md-12 text-right mb-5">
                            @permission('XML-Create')
                                <a href="{{route('xml.selectManifest')}}" class="btn btn-primary">New Manifest XML</a>
                            @endpermission

                            </div>
                        </div>
                    </br>
                    <form>
                        <div class="form-row">
                            {{-- <div class="form-group col-md-6">
                                <label for="Refrance">Bl Number </label>
                                <select class="selectpicker form-control" id="Refrance" data-live-search="true" name="ref_no" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($blDraftNo as $item)
                                        <option value="{{$item->ref_no}}" {{$item->ref_no == old('ref_no',request()->input('ref_no')) ? 'selected':''}}>{{$item->ref_no}}</option>
                                    @endforeach
                                </select>
                            </div> --}}
 
                            <div class="form-group col-md-6">
                                <label for="voyage_id">Vessel / Voyage </label>
                                <select class="selectpicker form-control" id="voyage_id" data-live-search="true" name="voyage_id" data-size="10"
                                    title="{{trans('forms.select')}}">
                                    @foreach ($voyages as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('voyage_id') ? 'selected':''}}>{{$item->vessel->name}} / {{$item->voyage_no}}</option>
                                    @endforeach
                                </select>
                                @error('voyage_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            
                        </div>

                        <div class="form-row">
                            <div class="col-md-12 text-center">
                                <button  type="submit" class="btn btn-success mt-3">Search</button>
                                <a href="{{route('xml.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                            </div>
                        </div>
                    </form> 
                    <div class="widget-content widget-content-area">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-condensed mb-4">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Ref No</th>
                                        <th>Voyage</th>
                                        <th>BL No</th>
                                        {{-- <th class='text-center' style='width:100px;'></th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($items as $item)
                                        <tr>
                                            <td>{{ App\Helpers\Utils::rowNumber($items,$loop)}}</td>
                                            <td>{{ $item->ref_no }}</td>
                                            <td>{{ $item->voyage->voyage_no }}</td>
                                            <td>
                                                @foreach($item->voyage->bldrafts as $bldraft)
                                                    {{$bldraft->ref_no}} <br>
                                                @endforeach
                                            </td>
                                            {{-- <td class="text-center">
                                                 <ul class="table-controls">
                                                    @permission('xml-Show')
                                                    <li>
                                                        <a href="{{route('xml.show',['xml'=>$item->id])}}" data-toggle="tooltip" data-placement="top" title="" data-original-title="show">
                                                            <i class="far fa-eye text-primary"></i>
                                                        </a>
                                                    </li>
                                                    @endpermission 
                                                </ul>
                                            </td> --}}
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
  
</script>
<script>
$(document).on('click', '.container-count', function() {
    var blDraftId = $(this).data('id');
    $.ajax({
        url: '/api/bldrafts/' + blDraftId + '/containers',
        method: 'GET',
        success: function(data) {
    console.log(data)
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
