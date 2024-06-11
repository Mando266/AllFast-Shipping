@extends('layouts.app')
@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-one">
                    <div class="widget-heading">
                        <nav class="breadcrumb-two" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Containers</a></li>
                                <li class="breadcrumb-item active"><a href="javascript:void(0);">Movements</a></li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                        </br>
                        @if(Session::has('stauts'))
                            <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('stauts') }}</p>
                        @endif
                        @if(Session::has('message'))
                            <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('message') }}</p>
                        @endif
                        @permission('Movements-List')
                        <div class="row">
                            <div class="col-md-12 text-right mb-6">
                                @permission('Booking-Create')
                                <a href="{{route('containerRefresh')}}" class="btn btn-success">Refresh Container
                                    Status</a>
                                @endpermission
                                @if(!$items->isEmpty())
                                    <a class="btn btn-info" href="{{ route('export.search',['container_id'=>request()->input('container_id'),'port_location_id'=>request()->input('port_location_id'),'voyage_id'=>request()->input('voyage_id'),
                                    'movement_id'=>request()->input('movement_id'),'bl_no'=>request()->input('bl_no'),'booking_no'=>request()->input('booking_no')]) }}">Export Last Movements</a>
                                @endif
                                @endpermission
                                @permission('Movements-List')
                                <a class="btn btn-warning" href="{{ route('export.all') }}">Export All Data</a>
                                @endpermission
                                @permission('Movements-Create')

                                <a href="{{route('movements.create')}}" class="btn btn-primary">Add New Movement</a>
                                @endpermission

                            </div>
                        </div>
                        @permission('Movements-Create')
                        <div class="row">
                            <div class="col-md-12 text-right mb-6">
                                @if($movementerrors->count() == 0)
                                    <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
                                        {{ csrf_field() }}
                                        <input type="file" name="file" onchange="unlock();">
                                        <button id="buttonSubmit" class="btn btn-success  mt-3" disabled>Import</button>
                                    </form>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-md-12 text-right mb-6">

                                <form action="{{ route('overwrite') }}" method="POST" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    <input type="file" name="file" onchange="unlockupdate();">
                                    <button id="updatebuttonSubmit" class="btn btn-danger  mt-3" disabled>Overwrite
                                    </button>
                                </form>
                                @else
                                    </br>
                                <a href="{{route('movementerrors.index')}}" class="btn btn-danger"> Show Errors</a>
                                @endif
                            </div>
                        </div>
                        @endpermission
                        </br>

                        <form>

                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="ContainerInput">Container Number</label>
                                    <select class="selectpicker form-control" id="ContainerInput"
                                            data-live-search="true" name="container_id[]" data-size="10"
                                            title="{{trans('forms.select')}}" multiple="multiple">
                                        @if(isset($containers))
                                            @foreach ($containers as $item)
                                                <option value="{{$item->id}}"
                                                        data-code="{{$item->container_type_id}}" {{$item->id == old('container_id') ||in_array($item->id, request()->container_id ?? []) ? 'selected':''}}>{{$item->code}}</option>
                                            @endforeach
                                            <input type="hidden" id="containersTypesInput" class="form-control"
                                                   name="container_type_id" placeholder="Container Type"
                                                   autocomplete="off" value="{{request()->input('container_type_id')}}">
                                        @else
                                            <option value="{{$container->id}}" selected
                                                    data-code="{{$container->container_type_id}}" {{$container->id == old('container_id') ? 'selected':''}}>{{$container->code}}</option>
                                            <input type="hidden" id="containersTypesInput" class="form-control"
                                                   name="container_type_id" placeholder="Container Type"
                                                   autocomplete="off" value="1">
                                        @endif
                                    </select>
                                    @error('container_type_id')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label for="portlocationInput">Activity Location</label>
                                    <select class="selectpicker form-control" id="portlocationInput"
                                            data-live-search="true" name="port_location_id[]" data-size="10"
                                            title="{{trans('forms.select')}}" multiple>
                                        @foreach ($ports as $item)
                                            <option value="{{ $item->id }}"
                                                {{ in_array($item->id, (array)request()->input('port_location_id')) ? 'selected' : '' }}>
                                                {{ $item->code }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="vessel_port_idInput">Vessel Name</label>
                                    <select class="selectpicker form-control" id="vessel_id" data-live-search="true"
                                            name="vessel_id" data-size="10"
                                            title="{{trans('forms.select')}}">
                                        @foreach ($vessels as $item)
                                            <option
                                                value="{{$item->id}}" {{$item->id == old('vessel_id',request()->input('vessel_id')) ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="">Voyage No</label>
                                    <select class="selectpicker form-control" id="voyage" data-live-search="true"
                                            name="voyage_id" data-size="10"
                                            title="{{trans('forms.select')}}">
                                        @foreach ($voyages as $item)
                                            <option
                                                value="{{$item->id}}" {{$item->id == old('voyage_id',request()->input('voyage_id')) ? 'selected':''}}>{{$item->voyage_no}}
                                                - {{ optional($item->leg)->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- <div class="form-group col-md-3">
                            <label for="Movement">Movement Date</label>
                                <input type="date" class="form-control" id="movement_dateInput" name="movement_date" value="{{request()->input('movement_date')}}">
                            </div> -->
                                <div class="form-group col-md-3">
                                    <label for="containersMovementsInput">Movement </label>
                                    <select class="selectpicker form-control" id="containersMovementsInput"
                                            data-live-search="true" name="movement_id[]" data-size="10"
                                            title="{{trans('forms.select')}}" multiple>
                                        @foreach ($containersMovements as $item)
                                            <option value="{{ $item->id }}"
                                                {{ in_array($item->id, (array)request()->input('movement_id')) ? 'selected' : '' }}>{{ $item->code }}
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label for="BLNo">BL No</label>
                                    <select class="selectpicker form-control" id="BLNoInput" data-live-search="true"
                                            name="bl_no" data-size="10"
                                            title="{{trans('forms.select')}}">
                                        @foreach ($movementsBlNo as $item)
                                            @if($item != null)
                                                <option
                                                    value="{{$item}}" {{$item == old('bl_no',request()->input('bl_no')) ? 'selected':''}}>{{$item}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="BLNo">Booking No</label>
                                    <select class="selectpicker form-control" id="BLNoInput" data-live-search="true"
                                            name="booking_no" data-size="10"
                                            title="{{trans('forms.select')}}">
                                        @foreach ($bookings as $item)
                                            <option
                                                value="{{$item->id}}" {{$item->id == old('booking_no',request()->input('booking_no')) ? 'selected':''}}>{{$item->ref_no}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="remarkes">Remarkes</label>
                                    <input type="text" class="form-control" id="remarkes" name="remarkes"
                                           value="{{request()->input('remarkes')}}"
                                           placeholder="Remarkes" autocomplete="off">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="countryInput"> Container Ownership Type</label>
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
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label for="countryInput"> Container Ownership </label>
                                    <select class="selectpicker form-control" id="countryInput" data-live-search="true"
                                            name="description" data-size="10"
                                            title="{{trans('forms.select')}}">
                                        @foreach ($lessor as $item)
                                            <option
                                                value="{{optional($item->seller)->id}}" {{$item->description == old('description',request()->input('description')) ? 'selected':''}}>{{optional($item->seller)->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('description')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-success mt-3">Search</button>
                                <button type="button" id="resetSearch" class="btn btn-info mt-3">Reset</button>
                                <a href="{{route('movements.index')}}"
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
                                    <th>Container No</th>
                                    <th>Container Type</th>
                                    <th>movement code</th>
                                    <th>Container Ownership Type</th>
                                    <th>Container Ownership</th>
                                    <th>movement date</th>
                                    <th>movement status</th>
                                    <th>bl no</th>
                                    <th>VSL/VOY</th>
                                    <th>ACTIVITY LOCATION</th>
                                    <th>Pol</th>
                                    <th>Pod</th>
                                    <th>free time destination</th>
                                    {{-- <th>import agent</th>
                                    <th>booking agent</th>    --}}
                                    <th>remarkes</th>
                                    <th class='text-center' style='width:100px;'>Container Movements</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($items as $item)
                                    <tr>
                                        <td>{{ App\Helpers\Utils::rowNumber($items,$loop)}}</td>
                                        <td>{{{optional($item->container)->code}}}</td>
                                        <td>{{{optional($item->containersType)->name}}}</td>
                                        <td>{{{optional($item->movementcode)->code}}}</td>
                                        <td>{{{optional($item->container->containersOwner)->name}}}</td>
                                        <td>{{{optional($item->container)->seller->name ?? optional($item->container)->description}}}</td>
                                        <td>{{$item->movement_date}}</td>
                                        <td>{{optional($item->movementcode->containerstatus)->name}}</td>
                                        <td>{{$item->bl_no}}</td>
                                        <td>{{{optional($item->vessels)->name}}} {{optional($item->voyage)->voyage_no}}</td>
                                        <td>{{optional($item->activitylocation)->code}}</td>
                                        <td>{{optional($item->pol)->code}}</td>
                                        <td>{{optional($item->pod)->code}}</td>
                                        <td>{{$item->free_time}}</td>
                                        {{-- <td>{{{optional($item->importAgent)->name}}}</td>
                                        <td>{{{optional($item->bookingAgent)->name}}}</td> --}}
                                        <td>{{$item->remarkes}}</td>

                                        <td class="text-center">
                                            <ul class="table-controls">
                                                @permission('Movements-Show')
                                                <li>
                                                    <a href="{{route('movements.show',['movement'=>$item->container_id])}}"
                                                       data-toggle="tooltip" data-placement="top" title=""
                                                       data-original-title="show" target="blank">
                                                        <i class="far fa-eye text-primary"></i>
                                                    </a>

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
                        @if($items->count() > 0)
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
    <script>
        $(function () {
            let vessel = $('#vessel_id');
            $('#vessel_id').on('change', function (e) {
                let value = e.target.value;
                let response = $.get(`/api/vessel/voyages/${vessel.val()}`).then(function (data) {
                    let voyages = data.voyages || '';
                    let list2 = [];
                    for (let i = 0; i < voyages.length; i++) {
                        list2.push(`<option value='${voyages[i].id}'>${voyages[i].voyage_no} - ${voyages[i].leg}</option>`);
                    }
                    let voyageno = $('#voyage');
                    voyageno.html(list2.join(''));
                    $('.selectpicker').selectpicker('refresh');
                });
            });
        });
    </script>

    <script>
        $(document).on('click', "#resetSearch", () => {
            $("select").val([])
            $('.selectpicker').selectpicker('refresh')
        })
        $(setTimeout(handlePasteContainers, 900))

        function handlePasteContainers() {
            $("#ContainerInput").closest('div').find('.bs-searchbox input').off('paste').on('paste', function (e) {
                const clipboardData = getClipboardData(e)
                const containerNumbers = getPastedContainerNumbers(clipboardData)

                $('#ContainerInput option').each(function () {
                    let optionValue = $(this).text();
                    if (containerNumbers.includes(optionValue)) {
                        $(this).prop('selected', true);
                    } else {
                        $(this).prop('selected', false);
                    }
                });

                $('.selectpicker').selectpicker('refresh');
            });
        }

        const getClipboardData = event => (event.originalEvent || event).clipboardData || window.clipboardData

        function getPastedContainerNumbers(clipboardData) {
            const pastedContent = clipboardData.getData('text/plain')
            return pastedContent.split(/[\s,]+/).map(containerNumber => containerNumber.trim())
        }

        // var containerSelect = document.getElementById('ContainerInput');
        // var pastedContainersInput = document.getElementById('pastedContainers');
        //
        // pastedContainersInput.addEventListener('input', function(e) {
        //     var pastedText = e.target.value;
        //     var numbersArray = pastedText.split(',');
        //
        //     for (var i = 0; i < containerSelect.options.length; i++) {
        //         containerSelect.options[i].selected = false;
        //     }
        //
        //     for (var j = 0; j < numbersArray.length; j++) {
        //         var numberToSelect = numbersArray[j].trim();
        //         for (var k = 0; k < containerSelect.options.length; k++) {
        //             if (containerSelect.options[k].text === numberToSelect) {
        //                 containerSelect.options[k].selected = true;
        //             }
        //         }
        //     }
        // });
    </script>
@endpush
