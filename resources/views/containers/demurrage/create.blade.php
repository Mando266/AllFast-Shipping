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
                                <li class="breadcrumb-item active"><a href="javascript:void(0);">Add New Demurrage & Dentention</a></li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                    </div>
                    <div class="widget-content widget-content-area">
                        <form novalidate id="createForm" action="{{route('demurrage.store')}}" method="POST">
                            @csrf
                            <!-- Existing form fields -->
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="countryInput">{{trans('company.country')}} <span class="text-warning"> * </span></label>
                                    <select class="selectpicker form-control" id="country" data-live-search="true" name="country_id" data-size="10" title="{{trans('forms.select')}}" required>
                                        @foreach ($countries as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('country_id') ? 'selected':''}}>{{$item->name}}</option>
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
                                                                                 @foreach ($ports as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('port_id') ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('port_id')
                                    <div style="color:red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="terminal">Terminal <span class="text-warning"> * </span></label>
                                    <select class="selectpicker form-control" id="terminal" data-live-search="true" name="terminal_id" data-size="10" required>
                                                                                 @foreach ($terminals as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('terminal_id') ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('terminal_id')
                                    <div style="color:red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="validity_from">Validity From <span class="text-warning"> * </span></label>
                                    <input type="date" class="form-control" id="validity_from" name="validity_from" value="{{old('validity_from')}}" placeholder="Validity From" autocomplete="off" required>
                                    @error('validity_from')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="validity_from">Validity to <span class="text-warning"> * </span></label>
                                    <input type="date" class="form-control" id="validity_to" name="validity_to" value="{{old('validity_to')}}" placeholder="Validity To" autocomplete="off" required>
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
                                            <option value="{{$item->name}}" {{$item->name == old('tariff_id') ? 'selected':''}}>{{$item->name}}</option>
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
                                    <label for="currency">Currency <span class="text-warning"> * </span></label>
                                    <select class="selectpicker form-control" id="currency" data-live-search="true" name="currency" data-size="10" title="{{trans('forms.select')}}" required>
                                        @foreach ($currency as $item)
                                            <option value="{{$item->name}}" {{$item->id == old('currency') ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('currency')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-8">
                                    <label for="tariff_type_id">Tariff Type</label>
                                    <select class="selectpicker form-control" id="tariff_type_id" name="tariff_type_id" required>
                                        <option value="" selected hidden>Select Tariff Type...</option>
                                        @foreach ($tariffTypes as $tariffType)
                                            <option value="{{ $tariffType->id }}">{{ $tariffType->code }} - {{ $tariffType->description }}</option>
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
                                    <input class="form-control" id="container_status" name="container_status" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="is_storage">Detention OR Storage</label>
                                    <input class="form-control" id="is_storage" name="is_storge" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="bound_id">Bound</label>
                                    <input class="form-control" id="bound_id" name="bound_id" readonly>
                                </div>
                            </div>
                            <!-- Existing code end -->

                            <!-- New Code -->
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="containersTypesInput">Container Types</label>
                                    <select class="selectpicker form-control" id="containersTypesInput" data-live-search="true" data-size="10">
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
                                    <!-- Container Types will be added here dynamically -->
                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-primary mt-3">{{trans('forms.create')}}</button>
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
                    <h5 class="modal-title" id="periodModalLabel">Add Periods</h5>
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
                                <td><input type="text" name="period[0][period]" class="form-control"></td>
                                <td><input type="text" name="period[0][rate]" class="form-control"></td>
                                <td><input type="text" name="period[0][days]" class="form-control"></td>
                                <td class="text-center"><button type="button" class="btn btn-danger remove-row"><i class="fa fa-trash"></i></button></td>
                            </tr>
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-success" id="addPeriodRow"><i class="fa fa-plus"></i>Add Slab</button>
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let isSubmitting = false;

            const createForm = document.getElementById('createForm');
            const tariffTypeId = document.getElementById('tariff_type_id');
            const boundId = document.getElementById('bound_id');
            const isStorage = document.getElementById('is_storage');
            const containerStatus = document.getElementById('container_status');

            createForm.addEventListener('submit', async function (e) {
                e.preventDefault();

                if (isSubmitting) return;
                isSubmitting = true;

                const companyId = "{{optional(auth()->user())->company->id}}";
                const portId = document.getElementById('port').value;
                const from = document.getElementById('validity_from').value;
                const to = document.getElementById('validity_to').value;
                const tariffType = tariffTypeId.value;

                try {
                    const response = await fetch(`/api/validate/demurrage/${companyId}/${portId}/${from}/${to}/${tariffType}`);
                    const data = await response.json();

                    if (data.valid) {
                        swal({
                            title: `There is overlap`,
                            icon: 'error'
                        });
                        isSubmitting = false;
                    } else {
                        if (validatePeriods()) {
                            createForm.submit();
                        } else {
                            isSubmitting = false;
                        }
                    }
                } catch (error) {
                    console.error(error);
                    isSubmitting = false;
                }
            });

            function validatePeriods() {
                let valid = true;
                document.querySelectorAll('#periodTable .form-control').forEach(function (input) {
                    if (!input.value) {
                        input.style.border = '1px solid red';
                        if (!input.nextElementSibling || !input.nextElementSibling.classList.contains('required-msg')) {
                            const requiredMsg = document.createElement('div');
                            requiredMsg.className = 'required-msg';
                            requiredMsg.style.color = 'red';
                            requiredMsg.textContent = 'Required';
                            input.insertAdjacentElement('afterend', requiredMsg);
                        }
                        valid = false;
                    } else {
                        input.style.border = '';
                        if (input.nextElementSibling && input.nextElementSibling.classList.contains('required-msg')) {
                            input.nextElementSibling.remove();
                        }
                    }
                });
                return valid;
            }

            tariffTypeId.addEventListener('change', function () {
                const selectedOption = tariffTypeId.options[tariffTypeId.selectedIndex].text;
                updateInputs(selectedOption);
            });

            function updateInputs(selectedOption) {
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
                    boundId.value = bound;
                    isStorage.value = storage;
                    containerStatus.value = status;
                }
            }

            document.getElementById('country').addEventListener('change', function (e) {
                const value = e.target.value;
                fetch(`/api/master/ports/${value}`)
                    .then(response => response.json())
                    .then(data => {
                        const ports = data.ports || '';
                        const list = [`<option value=''>Select...</option>`];
                        for (let i = 0; i < ports.length; i++) {
                            list.push(`<option value='${ports[i].id}'>${ports[i].name} </option>`);
                        }
                        document.getElementById('port').innerHTML = list.join('');
                        $('.selectpicker').selectpicker('refresh');
                    });
            });

            document.getElementById('port').addEventListener('change', function (e) {
                const value = e.target.value;
                fetch(`/api/master/terminals/${value}`)
                    .then(response => response.json())
                    .then(data => {
                        const terminals = data.terminals || '';
                        const list = [`<option value=''>Select...</option>`];
                        for (let i = 0; i < terminals.length; i++) {
                            list.push(`<option value='${terminals[i].id}'>${terminals[i].name} </option>`);
                        }
                        document.getElementById('terminal').innerHTML = list.join('');
                        $('.selectpicker').selectpicker('refresh');
                    });
            });

            let periodDataStore = {};
            let selectedContainerTypeName = '';

            document.getElementById('containersTypesInput').addEventListener('change', function () {
                const containerTypeId = this.value;
                if (containerTypeId) {
                    if (document.querySelector(`#containerTypesTable input[name="container_types[${containerTypeId}][id]"]`)) {
                        swal({
                            title: 'Error',
                            text: 'This container type is already selected.',
                            icon: 'error'
                        });
                        this.value = '';
                        $('.selectpicker').selectpicker('refresh');
                        return;
                    }

                    const containerTypeName = this.options[this.selectedIndex].text;
                    const newRow = `
            <tr>
                <td>
                    <input type="hidden" name="container_types[${containerTypeId}][id]" value="${containerTypeId}">
                    ${containerTypeName}
                </td>
                <td>
                    <button type="button" class="btn btn-primary open-modal" data-toggle="modal" data-target="#periodModal" data-container-type-id="${containerTypeId}" data-container-type-name="${containerTypeName}">
                        <i class="fa fa-plus"></i> Add Periods
                    </button>
                    <button type="button" class="btn btn-danger delete-row"><i class="fa fa-trash"></i> Delete</button>
                </td>
            </tr>`;
                    document.querySelector('#containerTypesTable tbody').insertAdjacentHTML('beforeend', newRow);
                    this.value = '';
                    $('.selectpicker').selectpicker('refresh');
                }
            });

            document.getElementById('addPeriodRow').addEventListener('click', function () {
                const rowCount = document.querySelectorAll('#periodTable tbody tr').length;
                const newRow = `
        <tr>
            <td><input type="text" name="period[${rowCount}][period]" class="form-control period" required></td>
            <td><input type="text" name="period[${rowCount}][rate]" class="form-control rate" required></td>
            <td><input type="text" name="period[${rowCount}][days]" class="form-control days" required></td>
            <td class="text-center"><button type="button" class="btn btn-danger remove-row"><i class="fa fa-trash"></i></button></td>
        </tr>`;
                document.querySelector('#periodTable tbody').insertAdjacentHTML('beforeend', newRow);
            });

            document.addEventListener('click', function (e) {
                if (e.target.classList.contains('remove-row')) {
                    const row = e.target.closest('tr');
                    if (document.querySelectorAll('#periodTable tbody tr').length > 1) {
                        row.remove();
                    }
                }

                if (e.target.classList.contains('delete-row')) {
                    const containerTypeId = e.target.closest('tr').querySelector('input[name^="container_types"]').value;
                    delete periodDataStore[containerTypeId];
                    e.target.closest('tr').remove();
                }

                if (e.target.classList.contains('open-modal')) {
                    const containerTypeId = e.target.dataset.containerTypeId;
                    selectedContainerTypeName = e.target.dataset.containerTypeName;
                    document.getElementById('periodModal').dataset.containerTypeId = containerTypeId;
                    document.getElementById('periodForm').reset();
                    document.querySelector('#periodTable tbody').innerHTML = `
            <tr>
                <td><input type="text" name="period[0][period]" class="form-control period" required></td>
                <td><input type="text" name="period[0][rate]" class="form-control rate" required></td>
                <td><input type="text" name="period[0][days]" class="form-control days" required></td>
                <td class="text-center"><button type="button" class="btn btn-danger remove-row"><i class="fa fa-trash"></i></button></td>
            </tr>`;
                    if (periodDataStore[containerTypeId]) {
                        periodDataStore[containerTypeId].forEach((item, index) => {
                            document.querySelector('#periodTable tbody').insertAdjacentHTML('beforeend', `
                <tr>
                    <td><input type="text" name="period[${index}][period]" class="form-control period" value="${item.period}" required></td>
                    <td><input type="text" name="period[${index}][rate]" class="form-control rate" value="${item.rate}" required></td>
                    <td><input type="text" name="period[${index}][days]" class="form-control days" value="${item.days}" required></td>
                    <td class="text-center"><button type="button" class="btn btn-danger remove-row"><i class="fa fa-trash"></i></button></td>
                </tr>`);
                        });
                    }
                    document.getElementById('periodModalLabel').textContent = `Add Periods for ${selectedContainerTypeName}`;
                }
            });

            document.getElementById('savePeriods').addEventListener('click', function () {
                let valid = true;
                document.querySelectorAll('#periodTable .form-control').forEach(function (input) {
                    if (!input.value) {
                        input.style.border = '1px solid red';
                        if (!input.nextElementSibling || !input.nextElementSibling.classList.contains('required-msg')) {
                            const requiredMsg = document.createElement('div');
                            requiredMsg.className = 'required-msg';
                            requiredMsg.style.color = 'red';
                            requiredMsg.textContent = 'Required';
                            input.insertAdjacentElement('afterend', requiredMsg);
                        }
                        valid = false;
                    } else {
                        input.style.border = '';
                        if (input.nextElementSibling && input.nextElementSibling.classList.contains('required-msg')) {
                            input.nextElementSibling.remove();
                        }
                    }
                });

                if (valid) {
                    const periodData = new FormData(document.getElementById('periodForm'));
                    const containerTypeId = document.getElementById('periodModal').dataset.containerTypeId;

                    if (!periodDataStore[containerTypeId]) {
                        periodDataStore[containerTypeId] = [];
                    }

                    const periodArray = Array.from(periodData.entries()).reduce((acc, [name, value]) => {
                        const match = name.match(/\[([0-9]+)\]\[(.+)\]/);
                        if (match) {
                            const [_, index, key] = match;
                            if (!acc[index]) acc[index] = {};
                            acc[index][key] = value;
                        }
                        return acc;
                    }, []);

                    periodDataStore[containerTypeId] = periodArray;
                    $('#periodModal').modal('hide');
                }
            });

            createForm.addEventListener('submit', function (e) {
                if (Object.keys(periodDataStore).length === 0) {
                    e.preventDefault();
                    swal({
                        title: 'Error',
                        text: 'At least one container tariff should be added to save.',
                        icon: 'error'
                    });
                } else {
                    for (let containerTypeId in periodDataStore) {
                        periodDataStore[containerTypeId].forEach((period, index) => {
                            for (let key in period) {
                                const hiddenInput = `<input type="hidden" name="container_types[${containerTypeId}][periods][${index}][${key}]" value="${period[key]}">`;
                                createForm.insertAdjacentHTML('beforeend', hiddenInput);
                            }
                        });
                    }
                }
            });
        });
    </script>


@endpush
