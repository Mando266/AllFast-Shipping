@extends('layouts.app')
@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">

            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-one">
                    <div class="widget-heading">
                        <nav class="breadcrumb-two" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('demurrage.index')}}">Demurrages</a></li>
                                <li class="breadcrumb-item active"><a href="javascript:void(0);">Demurrage Details</a></li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                    </div>
                    <div class="widget-content widget-content-area">
                        <h5><span style='color:#1b55e2'>Tariff Ref No:</span> {{{optional($demurrages->tarriffType)->code}}} - {{{optional($demurrages->ports)->code}}} - {{$demurrages->tariff_id}}</h5>

                        <div class="form-row">
                            <!-- <div class="form-group col-md-4">
                                <label for="countryInput">{{trans('company.country')}}</label>
                                <input type="text" class="form-control" value="{{optional($demurrages->country)->name}}" readonly>
                            </div> -->
                            <div class="form-group col-md-12">
                                <label for="port">Port</label>
                                <input type="text" class="form-control" value="{{optional($demurrages->ports)->name}}" readonly>
                            </div>
                            <!-- <div class="form-group col-md-4">
                                <label for="terminal">Terminal</label>
                                <input type="text" class="form-control" value="{{optional($demurrages->terminal)->name}}" readonly>
                            </div> -->
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="validity_from">Validity From</label>
                                <input type="text" class="form-control" value="{{$demurrages->validity_from}}" readonly>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="validity_to">Validity to</label>
                                <input type="text" class="form-control" value="{{$demurrages->validity_to}}" readonly>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="Triffs">Tariff</label>
                                <input type="text" class="form-control" value="{{$demurrages->tariff_id}}" readonly>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="tariff_type_id">Tariff Type</label>
                                <input type="text" class="form-control" id="tariff_type_id" value="{{$demurrages->tarriffType->code }} - {{ $demurrages->tarriffType->description }}" readonly>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="container_status">Container Status</label>
                                <input class="form-control" id="container_status" value="{{$demurrages->container_status}}" readonly>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="is_storage">Detention OR Storage</label>
                                <input class="form-control" id="is_storage" value="{{$demurrages->is_storge}}" readonly>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="bound_id">Bound</label>
                                <input class="form-control" id="bound_id" value="{{$demurrages->bound_id}}" readonly>
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
                                                    <th class="col-8">Equipment Type</th>
                                                    <th class="col-4">View Periods</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @forelse ($slabs as $slab)
                                                    <tr>
                                                        <td>{{optional($slab->containersType)->name}}</td>
                                                        <td><button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#periodModal" data-slabs="{{ json_encode($slab->periods) }}">View</button></td>
                                                    </tr>
                                                @empty
                                                    <tr class="text-center">
                                                        <td colspan="2">{{ trans('home.no_data_found') }}</td>
                                                    </tr>
                                                @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 text-center">
                                <a href="{{route('demurrage.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                            </div>
                        </div>
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
                    <h5 class="modal-title" id="periodModalLabel">Periods</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Period</th>
                                <th>Rate per Day</th>
                                <th>Calendar Days</th>
                            </tr>
                            </thead>
                            <tbody id="periodTableBody">
                            <!-- Periods will be dynamically added here -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link href="{{asset('plugins/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css">
@endpush
@push('scripts')
    <script src="{{asset('plugins/bootstrap-select/bootstrap-select.min.js')}}"></script>
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

            // Trigger updateInputs on page load
            let initialSelectedOption = $('#tariff_type_id').val();
            updateInputs(initialSelectedOption);

            $('#periodModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var slabs = button.data('slabs');
                var modal = $(this);
                var tableBody = modal.find('#periodTableBody');
                tableBody.empty();

                if (slabs && slabs.length > 0) {
                    slabs.forEach(function (slab) {
                        var row = '<tr>' +
                            '<td>' + slab.period + '</td>' +
                            '<td>' + slab.rate + '</td>' +
                            '<td>' + slab.number_off_dayes + '</td>' +
                            '</tr>';
                        tableBody.append(row);
                    });
                } else {
                    var noDataRow = '<tr><td colspan="3" class="text-center">{{ trans("home.no_data_found") }}</td></tr>';
                    tableBody.append(noDataRow);
                }
            });
        });
    </script>
@endpush
