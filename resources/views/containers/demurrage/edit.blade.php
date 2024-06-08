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
                                <li class="breadcrumb-item"><a href="{{route('demurrage.index')}}">Demurrage & Dentention</a></li>
                                <li class="breadcrumb-item active"><a href="javascript:void(0);">Edit Demurrage & Dentention</a></li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                    </div>
                    <div class="widget-content widget-content-area">
                        <form id="createForm" action="{{route('demurrage.update',['demurrage'=>$demurrage->id])}}" method="POST">
                            @csrf
                            @method('put')
                            <!-- Existing form fields -->
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="countryInput">{{trans('company.country')}} <span class="text-warning"> * </span></label>
                                    <select class="selectpicker form-control" id="country" data-live-search="true" name="country_id" data-size="10" title="{{trans('forms.select')}}" required>
                                        @foreach ($countries as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('country_id',$demurrage->country_id) ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('country_id')
                                    <div style="color:red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="port">Port <span class="text-warning"> * </span></label>
                                    <select class="selectpicker form-control" id="port" data-live-search="true" name="port_id" data-size="10" required>
                                        <option value="">Select...</option>
                                        @foreach ($ports as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('port_id',$demurrage->port_id) ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('port_id')
                                    <div style="color:red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="currency">Currency <span class="text-warning"> * </span></label>
                                    <select class="selectpicker form-control" id="currency" data-live-search="true" name="currency" data-size="10" title="{{trans('forms.select')}}" required>
                                        @foreach ($currency as $item)
                                            <option value="{{$item->name}}" {{$item->name == old('currency',$demurrage->currency) ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('currency')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="validity_from">Validity From <span class="text-warning"> * </span></label>
                                    <input type="date" class="form-control" id="validity_from" name="validity_from" value="{{old('validity_from',$demurrage->validity_from)}}" placeholder="Validity From" autocomplete="off" required>
                                    @error('validity_from')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="validity_to">Validity to <span class="text-warning"> * </span></label>
                                    <input type="date" class="form-control" id="validity_to" name="validity_to" value="{{old('validity_to',$demurrage->validity_to)}}" placeholder="Validity To" autocomplete="off" required>
                                    @error('validity_to')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="Triffs">Triff</label>
                                    <select class="selectpicker form-control" id="triff_kind" data-live-search="true" name="tariff_id" data-size="10" title="{{trans('forms.select')}}" autofocus>
                                        @foreach ($triffs as $item)
                                            <option value="{{$item->name}}" {{$item->name == old('tariff_id',$demurrage->tariff_id) ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('tariff_id')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="currency">Terminal <span class="text-warning"> * </span></label>
                                    <select class="selectpicker form-control" id="terminal" data-live-search="true" name="terminal_id" data-size="10" required>
                                        @foreach ($terminals as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('terminal_id',$demurrage->terminal_id) ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('terminal_id')
                                    <div style="color:red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-8">
                                    <label for="tariff_type_id">Tariff Type</label>
                                    <select class="selectpicker form-control" id="tariff_type_id" name="tariff_type_id" required>
                                        <option value="" selected hidden>Select Tariff Type...</option>
                                        @foreach ($tariffTypes as $tariffType)
                                            <option value="{{ $tariffType->id }}" {{ $tariffType->id == old('tariff_type_id',$demurrage->tariff_type_id) ? 'selected':'' }}>{{ $tariffType->code }} - {{ $tariffType->description }}</option>
                                        @endforeach
                                    </select>
                                    @error('tariff_type_ide')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="container_status">Container Status </label>
                                    <input class="form-control" id="container_status" name="container_status" value="{{old('container_status',$demurrage->container_status)}}" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="is_storage">Detention OR Storage</label>
                                    <input class="form-control" id="is_storage" name="is_storge" value="{{old('is_storge',$demurrage->is_storge)}}" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="bound_id">Bound</label>
                                    <input class="form-control" id="bound_id" name="bound_id" value="{{old('bound_id',$demurrage->bound_id)}}" readonly>
                                </div>
                            </div>
                            <!-- Container Types and Periods -->
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="containersTypesInput">Container Types</label>
                                    <select class="selectpicker form-control" id="containersTypesInput" data-live-search="true" data-size="10">
                                        <option value="">Select...</option>
                                        @foreach ($containersTypes as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="containerTypesTable" class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th class="col-8">Equipment Type</th>
                                        <th class="col-4">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($slabs as $slab)
                                        <tr>
                                            <td>
                                                <select name="container_types[{{ $slab->container_type_id }}][id]" class="form-control">
                                                    <option value="{{ $slab->container_type_id }}" selected>{{ optional($slab->containersType)->name }}</option>
                                                    @foreach ($containersTypes as $type)
                                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-primary open-modal" data-toggle="modal" data-target="#periodModal" data-container-type-id="{{ $slab->container_type_id }}" data-container-type-name="{{ optional($slab->containersType)->name }}">
                                                    <i class="fa fa-plus"></i> Edit Periods
                                                </button>
                                                <button type="button" class="btn btn-danger delete-row"><i class="fa fa-trash"></i> Delete</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-primary mt-3">{{trans('forms.update')}}</button>
                                    <a href="{{route('demurrage.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="periodModal" tabindex="-1" role="dialog" aria-labelledby="periodModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="periodModalLabel">Edit Periods</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="periodForm">
                        <table class="table table-bordered" id="periodTable">
                            <thead>
                            <tr>
                                <th>Period</th>
                                <th>Rate</th>
                                <th>Calendar Days</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><input type="text" name="period[0][period]" class="form-control" required></td>
                                <td><input type="text" name="period[0][rate]" class="form-control" required></td>
                                <td><input type="text" name="period[0][days]" class="form-control" required></td>
                                <td class="text-center"><button type="button" class="btn btn-danger remove-row"><i class="fa fa-trash"></i></button></td>
                            </tr>
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-success" id="addPeriodRow"><i class="fa fa-plus"></i> Add Slab</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="savePeriods">Save changes</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('styles')
    <style>
        .modal-lg {
            max-width: 80% !important;
        }
    </style>
@endpush
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
{{--    <script>--}}
{{--        $("#createForm").on('submit', e => functionName(e))--}}
{{--        const functionName = async e => {--}}
{{--            e.preventDefault();--}}
{{--            const companyId = "{{optional(auth()->user())->company->id}}";--}}
{{--            const portId = $('#port').val();--}}
{{--            const from = $('#validity_from').val();--}}
{{--            const to = $('#validity_to').val();--}}
{{--            const triffType = $('#tariff_type_id').val();--}}
{{--            let { data: { valid } }  = await axios.get(`/api/validate/demurrage/${companyId}/${portId}/${from}/${to}/${triffType}`)--}}
{{--            if ( valid === true){--}}
{{--                swal({--}}
{{--                    title: `There is overlap`,--}}
{{--                    icon: 'error'--}}
{{--                });--}}
{{--            }else{--}}
{{--                $("#createForm").off('submit').submit();--}}
{{--            }--}}
{{--        }--}}
{{--    </script>--}}
    <script>
        $(document).ready(function () {
            // Function to update inputs based on the selected tariff type
            function updateInputs(selectedOption) {
                let bound_id = $('#bound_id');
                let is_storage = $('#is_storage');
                let container_status = $('#container_status');

                if (selectedOption.includes('EDET')) {
                    setValues('EXPORT', 'EXPORT/DETENTION', 'FULL');
                } else if (selectedOption.includes('ESTO')) {
                    setValues('EXPORT', 'EXPORT/STORAGE', 'FULL');
                } else if (selectedOption.includes('IDET')) {
                    setValues('IMPORT', 'IMPORT/DETENTION', 'FULL');
                } else if (selectedOption.includes('ISTO')) {
                    setValues('IMPORT', 'IMPORT/STORAGE', 'FULL');
                } else if (selectedOption.includes('EEST')) {
                    setValues('EXPORT', 'EXPORT/STORAGE', 'EMPTY');
                } else if (selectedOption.includes('IEST')) {
                    setValues('IMPORT', 'IMPORT/STORAGE', 'EMPTY');
                } else if (selectedOption.includes('PCEX')) {
                    setValues('EXPORT', 'EXPORT/POWER', 'FULL');
                } else if (selectedOption.includes('PCIM')) {
                    setValues('IMPORT', 'IMPORT/POWER', 'FULL');
                } else {
                    setValues('', '', '');
                }

                function setValues(bound, storage, status) {
                    bound_id.val(bound);
                    is_storage.val(storage);
                    container_status.val(status);
                }
            }

            // Event listener for tariff type change
            $('#tariff_type_id').on('change', function () {
                let selectedOption = $(this).find('option:selected').text();
                updateInputs(selectedOption);
            });

            // Trigger updateInputs on page load
            let initialSelectedOption = $('#tariff_type_id').find('option:selected').text();
            updateInputs(initialSelectedOption);

            $('#triff_kind').on('change', function (e) {
                let selectedOption = $(this).val();

                if (selectedOption.includes('Customer')) {
                    $("#tariff_type_id option[value='5'], #tariff_type_id option[value='6']").hide();

                    if ($("#tariff_type_id option:selected").is(':hidden')) {
                        $("#tariff_type_id").val($("#tariff_type_id option:visible:first").val());
                        $("#bound_id").val('');
                        $("#is_storage").val('');
                        $("#container_status").val('');
                    }
                } else {
                    $("#tariff_type_id option[value='5'], #tariff_type_id option[value='6']").show();
                }

                $('.selectpicker').selectpicker('refresh');
            });
        });

        $(function () {
            let country = $('#country');
            $('#country').on('change', function (e) {
                let value = e.target.value;
                let response = $.get(`/api/master/ports/${country.val()}`).then(function (data) {
                    let ports = data.ports || '';
                    let list2 = [`<option value=''>Select...</option>`];
                    for (let i = 0; i < ports.length; i++) {
                        list2.push(`<option value='${ports[i].id}'>${ports[i].name} </option>`);
                    }
                    let port = $('#port');
                    port.html(list2.join(''));
                    $('.selectpicker').selectpicker('refresh');
                });
            });
        });
    </script>
    <script>
        $(function () {
            let port = $('#port');
            $('#port').on('change', function (e) {
                let value = e.target.value;
                let response = $.get(`/api/master/terminals/${port.val()}`).then(function (data) {
                    let terminals = data.terminals || '';
                    let list2 = [`<option value=''>Select...</option>`];
                    for (let i = 0; terminals.length > i; i++) {
                        list2.push(`<option value='${terminals[i].id}'>${terminals[i].name} </option>`);
                    }
                    let terminal = $('#terminal');
                    terminal.html(list2.join(''));
                    $('.selectpicker').selectpicker('refresh');
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            let periodDataStore = {};
            let deletedContainerTypes = [];

            // Add Container Type
            $('#containersTypesInput').on('change', function() {
                let containerTypeId = $(this).val();
                if (containerTypeId) {
                    if ($(`#containerTypesTable select[name="container_types[${containerTypeId}][id]"]`).length > 0) {
                        swal({
                            title: 'Error',
                            text: 'This container type is already selected.',
                            icon: 'error'
                        });
                        $(this).val('').selectpicker('refresh'); // Reset select picker
                        return;
                    }

                    let containerTypeName = $(this).find('option:selected').text();
                    let newRow = `
                <tr>
                    <td>
                        <select name="container_types[${containerTypeId}][id]" class="form-control">
                            <option value="${containerTypeId}" selected>${containerTypeName}</option>
                            @foreach ($containersTypes as $type)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                    </select>
                </td>
                <td>
                    <button type="button" class="btn btn-primary open-modal" data-toggle="modal" data-target="#periodModal" data-container-type-id="${containerTypeId}" data-container-type-name="${containerTypeName}">
                            <i class="fa fa-plus"></i> Edit Periods
                        </button>
                        <button type="button" class="btn btn-danger delete-row"><i class="fa fa-trash"></i> Delete</button>
                    </td>
                </tr>
            `;
                    $('#containerTypesTable tbody').append(newRow);
                    $(this).val('').selectpicker('refresh'); // Reset select picker
                }
            });

            // Add Row in Period Table
            $('#addPeriodRow').on('click', function() {
                let rowCount = $('#periodTable tbody tr').length;
                let newRow = `
            <tr>
                <td><input type="text" name="period[${rowCount}][period]" class="form-control" required></td>
                <td><input type="text" name="period[${rowCount}][rate]" class="form-control" required></td>
                <td><input type="text" name="period[${rowCount}][days]" class="form-control" required></td>
                <td class="text-center"><button type="button" class="btn btn-danger remove-row"><i class="fa fa-trash"></i></button></td>
            </tr>
        `;
                $('#periodTable tbody').append(newRow);
            });

            // Remove Row
            $(document).on('click', '.remove-row', function() {
                if ($('#periodTable tbody tr').length > 1) {
                    $(this).closest('tr').remove();
                } else {
                    swal({
                        title: 'Error',
                        text: 'At least one period must be present.',
                        icon: 'error'
                    });
                }
            });

            // Remove Container Type Row
            $(document).on('click', '.delete-row', function() {
                let containerTypeId = $(this).closest('tr').find('select[name^="container_types"]').val();
                delete periodDataStore[containerTypeId];
                deletedContainerTypes.push(containerTypeId); // Track deleted container types
                $(this).closest('tr').remove();
            });

            // Save Periods
            $('#savePeriods').on('click', function() {
                let periodData = $('#periodForm').serializeArray();
                let containerTypeId = $('#periodModal').data('container-type-id');

                if (!periodDataStore[containerTypeId]) {
                    periodDataStore[containerTypeId] = [];
                }

                periodDataStore[containerTypeId] = periodData.reduce((acc, { name, value }) => {
                    const match = name.match(/\[([0-9]+)\]\[(.+)\]/);
                    if (match) {
                        const [_, index, key] = match;
                        if (!acc[index]) acc[index] = {};
                        acc[index][key] = value;
                    }
                    return acc;
                }, []);

                $('#periodModal').modal('hide');
            });

            // Attach container type id to modal when opened
            $(document).on('click', '.open-modal', function() {
                let containerTypeId = $(this).data('container-type-id');
                selectedContainerTypeName = $(this).data('container-type-name');
                $('#periodModal').data('container-type-id', containerTypeId);
                $('#periodForm')[0].reset();
                $('#periodTable tbody').html('');

                if (periodDataStore[containerTypeId]) {
                    periodDataStore[containerTypeId].forEach((item, index) => {
                        $('#periodTable tbody').append(`
                    <tr>
                        <td><input type="text" name="period[${index}][period]" class="form-control" value="${item.period}" required></td>
                        <td><input type="text" name="period[${index}][rate]" class="form-control" value="${item.rate}" required></td>
                        <td><input type="text" name="period[${index}][days]" class="form-control" value="${item.days}" required></td>
                        <td class="text-center"><button type="button" class="btn btn-danger remove-row"><i class="fa fa-trash"></i></button></td>
                    </tr>
                `);
                    });
                }

                // Update modal title with selected container type name
                $('#periodModalLabel').text(`Edit Periods for ${selectedContainerTypeName}`);
            });

            // Append stored period data to main form before submission
            $('#createForm').on('submit', function(e) {
                let hasContainerTypes = Object.keys(periodDataStore).length > 0;
                if (!hasContainerTypes) {
                    e.preventDefault();
                    swal({
                        title: 'Error',
                        text: 'At least one container tariff should be added to save.',
                        icon: 'error'
                    });
                    return;
                }

                for (let containerTypeId in periodDataStore) {
                    periodDataStore[containerTypeId].forEach((period, index) => {
                        for (let key in period) {
                            let hiddenInput = `<input type="hidden" name="container_types[${containerTypeId}][periods][${index}][${key}]" value="${period[key]}">`;
                            $('#createForm').append(hiddenInput);
                        }
                    });
                }

                // Add deleted container types to the form
                deletedContainerTypes.forEach((containerTypeId) => {
                    let hiddenInput = `<input type="hidden" name="deleted_container_types[]" value="${containerTypeId}">`;
                    $('#createForm').append(hiddenInput);
                });

                // Allow form submission
                this.submit();
            });

            // Load ports and terminals based on selected country
            $('#country').on('change', function(e) {
                let value = e.target.value;
                $.get(`/api/master/ports/${value}`).then(function(data) {
                    let ports = data.ports || '';
                    let list = [`<option value=''>Select...</option>`];
                    for (let i = 0; ports.length > i; i++) {
                        list.push(`<option value='${ports[i].id}'>${ports[i].name} </option>`);
                    }
                    $('#port').html(list.join('')).selectpicker('refresh');
                });
            });

            $('#port').on('change', function(e) {
                let value = e.target.value;
                $.get(`/api/master/terminals/${value}`).then(function(data) {
                    let terminals = data.terminals || '';
                    let list = [`<option value=''>Select...</option>`];
                    for (let i = 0; terminals.length > i; i++) {
                        list.push(`<option value='${terminals[i].id}'>${terminals[i].name} </option>`);
                    }
                    $('#terminal').html(list.join('')).selectpicker('refresh');
                });
            });

            // Pre-fill period data store with existing periods
            @foreach($slabs as $slab)
                periodDataStore['{{ $slab->container_type_id }}'] = [];
            @foreach($slab->periods as $period)
                periodDataStore['{{ $slab->container_type_id }}'].push({
                period: '{{ $period->period }}',
                rate: '{{ $period->rate }}',
                days: '{{ $period->number_off_dayes }}'
            });
            @endforeach
            @endforeach
        });



    </script>
@endpush
