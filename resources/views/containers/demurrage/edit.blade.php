@extends('layouts.app')
@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-one">
                    <div class="widget-heading">
                        <nav class="breadcrumb-two" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Triffs </a></li>
                                <li class="breadcrumb-item"><a a href="{{route('demurrage.index')}}">Demurrage &
                                        Dentention</a></li>
                                <li class="breadcrumb-item active"><a href="javascript:void(0);">Add New Demurrage &
                                        Dentention</a></li>

                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                    </div>
                    <div class="widget-content widget-content-area">
                        <form id="createForm" action="{{route('demurrage.update',['demurrage'=>$demurrage])}}"
                              method="POST">
                            @csrf
                            @method('put')
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="countryInput">{{trans('company.country')}} <span class="text-warning"> * (Required.) </span></label>
                                    <select class="selectpicker form-control" id="country" data-live-search="true"
                                            name="country_id" data-size="10"
                                            title="{{trans('forms.select')}}" required>
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
                                    <label for="port">Port <span class="text-warning"> * (Required.) </span></label>
                                    <select class="selectpicker form-control" id="port" data-live-search="true"
                                            name="port_id" data-size="10"
                                            title="{{trans('forms.select')}}" required>
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
                                    <label for="currency">Currency</label>
                                    <select class="selectpicker form-control" id="currency" data-live-search="true"
                                            name="currency" data-size="10"
                                            title="{{trans('forms.select')}}" required>
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
                                    <label for="validity_from">Validity From <span
                                                class="text-warning"> * (Required.) </span></label>
                                    <input type="date" class="form-control" id="currency" name="validity_from"
                                           value="{{old('validity_from',$demurrage->validity_from)}}"
                                           placeholder="Validity From" autocomplete="off" required>
                                    @error('validity_from')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="validity_from">Validity to <span
                                                class="text-warning"> * (Required.) </span></label>
                                    <input type="date" class="form-control" id="currency" name="validity_to"
                                           value="{{old('validity_to',$demurrage->validity_to)}}"
                                           placeholder="Validity To" autocomplete="off" required>
                                    @error('validity_to')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="Triffs">Triff</label>
                                    <select class="selectpicker form-control" id="Triffs" data-live-search="true"
                                            name="tariff_id" data-size="10"
                                            title="{{trans('forms.select')}}" autofocus>
                                        @foreach ($triffs as $item)
                                            <option value="{{$item->name}}" {{$item->name == old('tariff_id') || $item->name == $demurrage->tariff_id ? 'selected':''}}>{{$item->name}}</option>
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
                                <div class="form-group col-md-12">
                                    <label for="containersTypesInput">Terminals</label>
                                    <select class="selectpicker form-control" id="terminal" data-live-search="true"
                                            name="terminal_id" data-size="10"
                                            title="{{trans('forms.select')}}" autofocus>
                                        @foreach ($terminals as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('terminal_id',$demurrage->terminal_id) ? 'selected':''}}>{{$item->name}} {{$item->code}}</option>
                                        @endforeach
                                    </select>
                                    @error('terminal_id')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="tariff_type_id">Tariff Type</label>
                                    <select class="form-control" id="tariff_type_id" name="tariff_type_id" required>
                                        <option value="" selected hidden>Select Tariff Type...</option>
                                        @foreach ($tariffTypes as $tariffType)
                                            <option value="{{ $tariffType->id }}" {{ $tariffType->id == old('tariff_type_id',$demurrage->tariff_type_id) ? 'selected':'' }}>
                                                {{ $tariffType->code }} - {{ $tariffType->description }}</option>
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
                                    <input class="form-control" id="container_status" name="container_status"
                                           value="{{old('container_status',$demurrage->container_status)}}" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="is_storage">Detention OR Storage</label>
                                    <input class="form-control" id="is_storage" name="is_storge"
                                           value="{{old('is_storge',$demurrage->is_storge)}}" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="bound_id">Bound</label>
                                    <input class="form-control" id="bound_id" name="bound_id"
                                           value="{{old('bound_id',$demurrage->bound_id)}}" readonly>
                                </div>
                            </div>
                            <div class="container-fluid">
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="row align-items-center">
                                                    <div class="col-md-8">
                                                        <h5 class="card-title">Period</h5>
                                                    </div>
                                                    <div class="col-md-4 text-right">
                                                        <button type="button" class="btn btn-sm btn-success"
                                                                id="addSlab">Create Slab
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="row mt-3">
                                                    <div class="col-md-3 offset-md-1">
                                                        <label for="containersTypesInputHeader"
                                                               class="text-center w-30">Select Container Type:</label>
                                                        <select class="selectpicker form-control"
                                                                id="containersTypesInputHeader"
                                                                data-live-search="true" name="container_type_id"
                                                                data-size="10"
                                                                required>
                                                            @foreach ($containersTypes as $item)
                                                                <option value="{{$item->id}}">{{$item->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table id="period" class="table table-bordered table-hover">
                                                        <thead class="thead-light">
                                                        <tr>
                                                            <th>Period</th>
                                                            <th>Rate</th>
                                                            <th>Calendar Days</th>
                                                            <th>
                                                                <a id="add"> Add Period <i class="fas fa-plus"></i></a>
                                                            </th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($slabs as $slab)
                                                            @foreach ($slab->periods as $key => $period)
                                                                <tr class="d-none row-{{ $slab->demurage_id }}">
                                                                    <input type="hidden" value="{{ $period->id }}"
                                                                           name="period[{{ $key }}][id]">
                                                                    <td>
                                                                        <input type="text" id="period"
                                                                               name="period[{{$key}}][period]"
                                                                               class="form-control period"
                                                                               autocomplete="off"
                                                                               value="{{old('slab[$key][period]',$period->period)}}">
                                                                        <input name="period[{{$key}}][container_type]"
                                                                               class="container_type" hidden>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" id="rate"
                                                                               name="period[{{$key}}][rate]"
                                                                               class="form-control rate"
                                                                               autocomplete="off"
                                                                               value="{{old('slab[$key][rate]',$period->rate)}}">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" id="days"
                                                                               name="period[{{$key}}][number_off_days]"
                                                                               class="form-control days"
                                                                               autocomplete="off"
                                                                               value="{{old('slab[$key][number_off_days]',$period->number_off_dayes)}}">
                                                                    </td>
                                                                    <td style="width:85px;">
                                                                        <button type="button"
                                                                                class="btn btn-danger remove"
                                                                                onclick="removeItem({{ $period->id }})">
                                                                            <i class="fa fa-trash"></i></button>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @endforeach
                                                        </tbody>
                                                    </table>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="card-title">Slabs</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table id="slabs" class="table table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <th>Equipment Type</th>
                                                            <th>Status</th>
                                                            <th></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($slabs as $key => $slab)
                                                            <tr>
                                                                <input type="hidden" value="{{ $slab->id }}"
                                                                       name="slab[{{ $key }}][id]">
                                                                <td>
                                                                    <input type="text" id="slab"
                                                                           name="slab[{{$key}}][container_type_id]"
                                                                           class="form-control equipmentType"
                                                                           autocomplete="off"
                                                                           value="{{old('slab[$key][container_type_id]',$slab->containersType->name)}}">
                                                                </td>
                                                                <td>
                                                                    <input type="text" id="rate"
                                                                           name="slab[{{$key}}][status]"
                                                                           class="form-control" autocomplete="off"
                                                                           value="{{old('slab[$key][rate]',$slab->status())}}">
                                                                </td>
                                                                <td>
                                                                    <button class="removeSlabBtn btn btn-danger"
                                                                            type="button"
                                                                            id="row-{{ $slab->demurage_id }}"
                                                                    ><i class="fa fa-trash"></i></button>
                                                                    <button class="edit-row btn btn-info" type="button"
                                                                            id="row-{{ $slab->demurage_id }}"><i
                                                                                class="fa fa-pen"></i></button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="submit"
                                            class="btn btn-primary mt-3">{{trans('forms.update')}}</button>
                                    <a href="{{route('demurrage.index')}}"
                                       class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <script>
        var counter = 0
        var selectedTypes = []
        document.addEventListener('DOMContentLoaded', function () {
            const addSlabButton = document.getElementById('addSlab');
            const periodTableBody = document.querySelector('#period tbody');
            const slabsTableBody = document.querySelector('#slabs tbody');
            const containersTypesInput = document.getElementById('containersTypesInputHeader');

            $(document).on('click', ".edit-row", function () {
                const rowClass = this.id
                const row = $(this).closest('tr')
                $(`.${rowClass}`).removeClass('d-none')
                const equipmentTypeToRemove = row.find('.equipmentType').val();
                const indexToRemove = selectedTypes.indexOf(equipmentTypeToRemove);
                if (indexToRemove !== -1) {
                    selectedTypes.splice(indexToRemove, 1);
                }
                row.remove()
            })

            $(document).on('click', '.removeSlabBtn', function (e) {
                const row = $(this).closest('tr')
                const equipmentTypeToRemove = row.find('.equipmentType').val();
                const rowId = e.target.id;
                row.remove();
                document.querySelectorAll(`.${rowId}`).forEach(row => row.remove());

                const indexToRemove = selectedTypes.indexOf(equipmentTypeToRemove);
                if (indexToRemove !== -1) {
                    selectedTypes.splice(indexToRemove, 1);
                }
            });


            addSlabButton.addEventListener('click', function () {
                let equipmentType = containersTypesInput.selectedOptions[0].textContent
                let equipmentTypeValue = containersTypesInput.options[containersTypesInput.selectedIndex].textContent;
                if (selectedTypes.includes(equipmentTypeValue)) {
                    swal({
                        title: `A slab was already created for the ${equipmentType} container type.`,
                        icon: 'error'
                    });
                    return;
                }

                const periodRows = Array.from(periodTableBody.querySelectorAll('tr:not(.d-none)'));
                let hasEmptyInput = false;
                let alerted = false;
                periodRows.forEach(row => {
                    const periodInput = row.querySelector('.period').value;
                    const rateInput = row.querySelector('.rate').value;
                    const daysInput = row.querySelector('.days').value;
                    if (periodInput === '' || rateInput === '' || daysInput === '') {
                        // Show a SweetAlert indicating the empty input
                        swal({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Please fill in all input fields before proceeding!',
                        });

                        alerted = true;
                        hasEmptyInput = true;
                    }
                    let trimmedInput = periodInput.replace(/\s/g, '');
                    let periodIsAlpha = /^[a-zA-Z0-9]+$/.test(trimmedInput);
                    let rateIsNumeric = /^[0-9]+(\.[0-9]+)?$/.test(rateInput);
                    let daysIsNumeric = /^[0-9]+$/.test(daysInput);
                    if (!periodIsAlpha) {
                        swal({
                            icon: 'error',
                            title: 'Invalid Input',
                            text: 'The period input should only contain letters and numbers.',
                        });
                        alerted = true;
                        hasEmptyInput = true;
                    }
                    if (!rateIsNumeric) {
                        swal({
                            icon: 'error',
                            title: 'Invalid Input',
                            text: 'The rate input should only contain numbers.',
                        });
                        alerted = true;
                        hasEmptyInput = true;
                    }
                    if (!daysIsNumeric) {
                        swal({
                            icon: 'error',
                            title: 'Invalid Input',
                            text: 'The days input should only contain numbers.',
                        });
                        alerted = true;
                        hasEmptyInput = true;
                    }
                });
                if (hasEmptyInput || periodRows.length === 0) {
                    if (!alerted) {
                        swal({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Please fill in all input fields before proceeding!',
                        });
                    }
                    return;
                }
                selectedTypes.push(equipmentTypeValue);

                let count = counter++

                const newRow = document.createElement('tr');
                newRow.className = "row-" + counter
                newRow.innerHTML = `
                <input type="text" class="equipmentType form-control bg-transparent border-0 period" value="${equipmentType}" disabled/>
                <input type="hidden" name="equipmentType" value="${equipmentTypeValue}" disabled/>
                <td><input type="text" class="status form-control bg-transparent border-0 period" value="Active" disabled/></td>
                <td>
                    <button class="removeSlabBtn btn btn-danger" type="button" id="row-${count}"><i class="fa fa-trash"></i></button>
                    <button class="edit-row btn btn-info" type="button" id="row-${count}"><i class="fa fa-pen"></i></button>
                </td>`;

                slabsTableBody.appendChild(newRow);
                

                periodRows.forEach(row => {
                    const periodInput = row.querySelector('.period').value;
                    const rateInput = row.querySelector('.rate').value;
                    const daysInput = row.querySelector('.days').value;

                    row.querySelector('.container_type').value = equipmentTypeValue
                    row.className = `d-none row-${count}`;
                });
            });
        });
    </script>


    <script>
        $(document).ready(function () {
            $("#period").on("click", ".remove", function () {
                $(this).closest("tr").remove();
            });
            var counter = 1;
            $("#add").click(function () {
                var tr = '<tr>' +
                    '<td><input type="text" name="period[' + counter + '][period]" class="form-control period"><input name="period[' + counter + '][container_type]" class="container_type" hidden></td>' +
                    '<td><input type="text" name="period[' + counter + '][rate]" class="form-control rate"></td>' +
                    '<td><input type="text" id="days" name="period[' + counter + '][number_off_days]" class="form-control days" autocomplete="off"></td>' +
                    '<td style="width:85px;"><button type="button" class="btn btn-danger remove"><i class="fa fa-trash"></i></button></td>' +
                    '</tr>';

                counter++;
                $('#period').append(tr);
                $('.selectpicker').selectpicker('refresh');
            });

        });
    </script>


    <script>
        $(document).ready(function () {
            $('#tariff_type_id').on('change', function () {
                let selectedOption = $(this).find('option:selected').text();
                updateInputs(selectedOption);
            });

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

            $('#triff_kind').on('change', function (e) {
                console.log(123)
                let selectedOption = $(this).val();

                if (selectedOption.includes('Customer')) {
                    $("#tariff_type_id option[value='5'], #tariff_type_id option[value='6']").hide();

                    if ($("#tariff_type_id option:selected").is(':hidden')) {
                        $("#tariff_type_id").val($("#tariff_type_id option:visible:first").val());
                        $("#bound_id").val('')
                        $("#is_storage").val('')
                        $("#container_status").val('')
                    }
                } else {
                    $("#tariff_type_id option[value='5'], #tariff_type_id option[value='6']").show();
                }

                $('.selectpicker').selectpicker('refresh');
            })
        })
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
                    for (let i = 0; i < terminals.length; i++) {
                        list2.push(`<option value='${terminals[i].id}'>${terminals[i].name} </option>`);
                    }
                    let terminal = $('#terminal');
                    terminal.html(list2.join(''));
                    $('.selectpicker').selectpicker('refresh');
                });
            });
        });
    </script>
@endpush
