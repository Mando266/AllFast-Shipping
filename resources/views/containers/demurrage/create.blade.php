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
                        <form novalidate id="createForm" action="{{route('demurrage.store')}}" method="POST">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="countryInput">{{trans('company.country')}} <span class="text-warning"> * </span></label>
                                    <select class="selectpicker form-control" id="country" data-live-search="true"
                                            name="country_id" data-size="10"
                                            title="{{trans('forms.select')}}" required>
                                        @foreach ($countries as $item)
                                            <option
                                                    value="{{$item->id}}" {{$item->id == old('country_id') ? 'selected':''}}>{{$item->name}}</option>
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
                                    <select class="selectpicker form-control" id="port" data-live-search="true"
                                            name="port_id" data-size="10" required>
                                        <option value="">Select...</option>
                                        @foreach ($ports as $item)
                                            <option
                                                    value="{{$item->id}}" {{$item->id == old('port_id') ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('port_id')
                                    <div style="color:red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="terminal">Terminal <span
                                                class="text-warning"> * </span></label>
                                    <select class="selectpicker form-control" id="terminal" data-live-search="true"
                                            name="terminal_id" data-size="10" required>
                                        <option value="">Select...</option>
                                        @foreach ($terminals as $item)
                                            <option
                                                    value="{{$item->id}}" {{$item->id == old('terminal_id') ? 'selected':''}}>{{$item->name}}</option>
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
                                    <label for="validity_from">Validity From <span
                                                class="text-warning"> * </span></label>
                                    <input type="date" class="form-control" id="validity_from" name="validity_from"
                                           value="{{old('validity_from')}}"
                                           placeholder="Validity From" autocomplete="off" required>
                                    @error('validity_from')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="validity_from">Validity to <span
                                                class="text-warning"> * </span></label>
                                    <input type="date" class="form-control" id="validity_to" name="validity_to"
                                           value="{{old('validity_to')}}"
                                           placeholder="Validity To" autocomplete="off" required>
                                    @error('validity_to')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="Triffs">Triff</label>
                                    <select class="selectpicker form-control" id="triff_kind" data-live-search="true"
                                            name="tariff_id" data-size="10"
                                            title="{{trans('forms.select')}}" autofocus>
                                        @foreach ($triffs as $item)
                                            <option
                                                    value="{{$item->name}}" {{$item->name == old('tariff_id') ? 'selected':''}}>{{$item->name}}</option>
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
                                    <label for="currency">Currency <span
                                                class="text-warning"> * </span></label>
                                    <select class="selectpicker form-control" id="currency" data-live-search="true"
                                            name="currency" data-size="10"
                                            title="{{trans('forms.select')}}" required>
                                        @foreach ($currency as $item)
                                            <option
                                                    value="{{$item->name}}" {{$item->id == old('currency') ? 'selected':''}}>{{$item->name}}</option>
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
                                            <option value="{{ $tariffType->id }}">{{ $tariffType->code }}
                                                - {{ $tariffType->description }}</option>
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

                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="submit"
                                            class="btn btn-primary mt-3">{{trans('forms.create')}}</button>
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
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>
        $("#createForm").on('submit', e => functionName(e))
        const functionName = async e => {
            e.preventDefault();
            const companyId = "{{optional(auth()->user())->company->id}}";
            const portId = $('#port').val();
            const from = $('#validity_from').val();
            const to = $('#validity_to').val();
            const triffType = $('#tariff_type_id').val();
            let { data: { valid } }  = await axios.get(`/api/validate/demurrage/${companyId}/${portId}/${from}/${to}/${triffType}`)
            if ( valid === true){
                swal({
                    title: `There is overlap`,
                    icon: 'error'
                });
            }else{
                $("#createForm").off('submit').submit();
            }
        }
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
