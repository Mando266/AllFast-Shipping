@extends('layouts.app')
@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-one">
                    <div class="widget-heading">
                        <nav class="breadcrumb-two" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Containers Movement </a></li>
                                <li class="breadcrumb-item active"><a href="javascript:void(0);">Containers</a></li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                        </br>

                        @if(Session::has('message'))
                            <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('message') }}</p>
                        @endif
                        <div class="row">
                            <div class="col-md-12 text-right mb-6">
                                @permission('Containers-List')
                                <button id="export-current" class="btn btn-warning" type="button">Export</button>
                                @endpermission
                                @permission('Containers-Create')
                                <a href="{{route('containers.create')}}" class="btn btn-primary">Add New Container</a>
                                @endpermission
                            </div>
                            </br>
                            </br>
                            <div class="col-md-12 text-right mb-6">
                                @permission('Containers-List')
                                <form action="{{ route('importContainers') }}" method="POST"
                                      enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    <input type="file" name="file" onchange="unlock();">
                                    <button id="buttonSubmit" class="btn btn-success  mt-3" disabled>Import</button>
                                </form>
                                @endpermission
                                @permission('Containers-List')
                                <form action="{{ route('overwritecont') }}" method="POST" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    <input type="file" name="file" onchange="unlockupdate();">
                                    <button id="updatebuttonSubmit" class="btn btn-danger  mt-3" disabled>Overwrite
                                    </button>
                                </form>
                                @endpermission
                            </div>
                        </div>
                        </br>
                        </br>
                        <form id="search-form">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label for="ContainerInput">Container Number </label>
                                    <select class="selectpicker form-control" id="ContainerInput"
                                            data-live-search="true" name="code" data-size="10"
                                            title="{{trans('forms.select')}}">
                                        @foreach ($containers as $item)
                                            <option
                                                value="{{$item->code}}" {{$item->code == old('code',request()->input('code')) ? 'selected':''}}>{{$item->code}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="countryInput">Container Type</label>
                                    <select class="selectpicker form-control" id="countryInput" data-live-search="true"
                                            name="container_type_id" data-size="10"
                                            title="{{trans('forms.select')}}">
                                        @foreach ($container_types as $item)
                                            <option
                                                value="{{$item->id}}" {{$item->id == old('container_type_id',request()->input('container_type_id')) ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('container_type_id')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="countryInput"> Container Ownership </label>
                                    <select class="selectpicker form-control" id="countryInput" data-live-search="true"
                                            name="container_ownership_id" data-size="10"
                                            title="{{trans('forms.select')}}">
                                        @foreach ($container_ownership as $item)
                                            <option
                                                value="{{$item->id}}" {{$item->id == old('container_ownership_id',request()->input('container_ownership_id')) ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('container_ownership_id')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="remarkes">Container Ownership</label>
                                    <select class="selectpicker form-control" id="countryInput" data-live-search="true"
                                            name="description" data-size="10"
                                            title="{{trans('forms.select')}}">
                                        @foreach ($sellers as $item)
                                            <option
                                                value="{{$item->id}}" {{$item->id == old('description',request()->input('description')) ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12 text-center">
                                    <button id="search-btn" type="submit" class="btn btn-success mt-3">Search</button>
                                    <a href="{{route('containers.index')}}"
                                       class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                                </div>
                            </div>
                        </form>
                        <div class="widget-content widget-content-area">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-condensed mb-4">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Number</th>
                                        <th>Type</th>
                                        <th>ISO</th>
                                        <th>Ownership Type</th>
                                        <th>tar weight</th>
                                        <th>max payload</th>
                                        <th>production year</th>
                                        <th>Container Ownership</th>
                                        <th>SOC_COC</th>
                                        <th class='text-center' style='width:100px;'></th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse ($items as $item)
                                        @php
                                            $container = $item->container;
                                        @endphp
                                        <tr>
                                            <td>{{ App\Helpers\Utils::rowNumber($items,$loop)}}</td>
                                            <td>{{$item->code}}</td>
                                            <td>{{{optional($item->containersTypes)->name}}}</td>
                                            <td>{{$item->iso}}</td>
                                            <td>{{{optional($item->containersOwner)->name}}}</td>
                                            <td>{{$item->tar_weight}}</td>
                                            <td>{{$item->max_payload}}</td>
                                            <td>{{$item->production_year}}</td>
                                            <td>{{$item->seller->name ?? $item->description }}</td>
                                            <td>{{$item->SOC_COC}}</td>

                                            <td class="text-center">
                                                <ul class="table-controls">
                                                    @permission('Containers-Edit')
                                                    <li>
                                                        <a href="{{route('containers.edit',['container'=>$item->id])}}"
                                                           data-toggle="tooltip" data-placement="top" title=""
                                                           data-original-title="edit">
                                                            <i class="far fa-edit text-success"></i>
                                                        </a>
                                                    </li>
                                                    @endpermission
                                                    @if ($container && $container->movement->count() > 0)
                                                        @permission('Containers-Delete')
                                                        <li>
                                                            <form
                                                                action="{{route('containers.destroy',['container'=>$item->id])}}"
                                                                method="post">
                                                                @method('DELETE')
                                                                @csrf
                                                                <button style="border: none; background: none;"
                                                                        type="submit"
                                                                        class="fa fa-trash text-danger show_confirm"></button>
                                                            </form>
                                                        </li>
                                                        @endpermission
                                                    @endif
                                                    @if($item->certificat == !null)
                                                        <li>
                                                            <a href='{{asset($item->certificat)}}' target="_blank">
                                                                <i class="fas fa-file-pdf text-primary"
                                                                   style='font-size:large;'></i>
                                                            </a>
                                                        </li>
                                                    @endif
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

                $('.show_confirm').click(function (event) {
                    var form = $(this).closest("form");
                    var name = $(this).data("name");
                    event.preventDefault();
                    swal({
                        title: `Are you sure you want to delete this record?`,
                        text: "If you delete this, it will delete all movements for this container.",
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
                const searchForm = $("#search-form");
                $('#export-current').click(() => {
                    searchForm.attr('method', 'post');
                    searchForm.attr('action', '{{ route('export.container') }}');
                    searchForm.find('input[name="_token"]').prop('disabled', false);

                    searchForm.submit();
                });
                $('#search-btn').click(() => {
                    searchForm.attr('method', 'get');
                    searchForm.attr('action', '{{ route('containers.index') }}');

                    searchForm.submit();
                });
            </script>
            <script>
                function unlock() {
                    document.getElementById('buttonSubmit').removeAttribute("disabled");
                }
            </script>
            <script>
                function unlockupdate() {
                    document.getElementById('updatebuttonSubmit').removeAttribute("disabled");
                }
            </script>
    @endpush
