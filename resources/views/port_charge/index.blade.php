@extends('layouts.app')
@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-one">
                    <div class="widget-heading">
                        <nav class="breadcrumb-two" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                {{--                                <li class="breadcrumb-item"><a href="javascript:void(0);">Master Data</a></li>--}}
                                <li class="breadcrumb-item active"><a href="javascript:void(0);">Port Charges</a></li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                        {{--                        <div class="row">--}}
                        {{--                            <div class="col-md-12 text-right mb-5">--}}
                        {{--                                <button class="btn btn-primary add-button">Add New Port Charge</button>--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}
                    </div>
                    <div class="widget-content widget-content-area">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-condensed mb-4">
                                <thead>
                                <tr>
                                    <th rowspan="3" style="min-width: 222px">NAME</th>
                                    <th colspan=2>THC</th>
                                    <th colspan=7>STORAGE</th>
                                    <th colspan=3>POWER</th>
                                    <th colspan=2>SHIFTING</th>
                                    <th colspan=2>DISINF</th>
                                    <th colspan=2>HAND-FES-EM</th>
                                    <th colspan=2>GAT-LIFT OFF-INBND-EM-FT40</th>
                                    <th colspan=2>GAT-LIFT ON-INBND-EM-FT40</th>
                                    <th colspan=2>OTBND</th>
                                    <th colspan=2>PTI</th>
                                    <th colspan=2>ADD-PLAN</th>
                                    <th rowspan="3">Edit</th>
                                </tr>
                                <tr>
                                    <th rowspan=2 height=98>20FT</th>
                                    <th rowspan=2>40FT</th>
                                    <th rowspan=2>Free Time</th>
                                    <th colspan=3>Slab1</th>
                                    <th colspan=3>Slab2</th>
                                    <th rowspan=2>Free Time</th>
                                    <th rowspan=2>20FT</th>
                                    <th rowspan=2>40FT</th>
                                    <th rowspan=2>20FT</th>
                                    <th rowspan=2>40FT</th>
                                    <th rowspan=2>20FT</th>
                                    <th rowspan=2>40FT</th>
                                    <th rowspan=2>20FT</th>
                                    <th rowspan=2>40FT</th>
                                    <th rowspan=2>20FT</th>
                                    <th rowspan=2>40FT</th>
                                    <th rowspan=2>20FT</th>
                                    <th rowspan=2>40FT</th>
                                    <th rowspan=2>20FT</th>
                                    <th rowspan=2>40FT</th>
                                    <th colspan=2>20FT & 40FT</th>
                                    <th rowspan=2>20FT</th>
                                    <th rowspan=2>40FT</th>
                                </tr>
                                <tr>
                                    <th>Period</th>
                                    <th>20 FT</th>
                                    <th>40 FT</th>
                                    <th>Period</th>
                                    <th>20 FT</th>
                                    <th>40 FT</th>
                                    <th>failed</th>
                                    <th>pass</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($portCharges as $portCharge)
                                    <tr>
                                        <td>{{ $portCharge->chargeMatrix->name }}</td>
                                        <td class="editable">{{ $portCharge->thc_20ft }}</td>
                                        <td class="editable">{{ $portCharge->thc_40ft }}</td>
                                        <td class="editable">{{ $portCharge->storage_free }}</td>
                                        <td class="editable">{{ $portCharge->storage_slab1_period }}</td>
                                        <td class="editable">{{ $portCharge->storage_slab1_20ft }}</td>
                                        <td class="editable">{{ $portCharge->storage_slab1_40ft }}</td>
                                        <td class="editable">{{ $portCharge->storage_slab2_period }}</td>
                                        <td class="editable">{{ $portCharge->storage_slab2_20ft }}</td>
                                        <td class="editable">{{ $portCharge->storage_slab2_40ft }}</td>
                                        <td class="editable">{{ $portCharge->power_free }}</td>
                                        <td class="editable">{{ $portCharge->power_20ft }}</td>
                                        <td class="editable">{{ $portCharge->power_40ft }}</td>
                                        <td class="editable">{{ $portCharge->shifting_20ft }}</td>
                                        <td class="editable">{{ $portCharge->shifting_40ft }}</td>
                                        <td class="editable">{{ $portCharge->disinf_20ft }}</td>
                                        <td class="editable">{{ $portCharge->disinf_40ft }}</td>
                                        <td class="editable">{{ $portCharge->hand_fes_em_20ft }}</td>
                                        <td class="editable">{{ $portCharge->hand_fes_em_40ft }}</td>
                                        <td class="editable">{{ $portCharge->gat_lift_off_inbnd_em_ft40_20ft }}</td>
                                        <td class="editable">{{ $portCharge->gat_lift_off_inbnd_em_ft40_40ft }}</td>
                                        <td class="editable">{{ $portCharge->gat_lift_on_inbnd_em_ft40_20ft }}</td>
                                        <td class="editable">{{ $portCharge->gat_lift_on_inbnd_em_ft40_40ft }}</td>
                                        <td class="editable">{{ $portCharge->otbnd_20ft }}</td>
                                        <td class="editable">{{ $portCharge->otbnd_40ft }}</td>
                                        <td class="editable">{{ $portCharge->pti_failed }}</td>
                                        <td class="editable">{{ $portCharge->pti_passed }}</td>
                                        <td class="editable">{{ $portCharge->add_plan_20ft }}</td>
                                        <td class="editable">{{ $portCharge->add_plan_40ft }}</td>
                                        <td>
                                            <button class="btn btn-info edit-button" data-id="{{ $portCharge->id }}">
                                                Edit
                                            </button>
                                            {{--                                            <button class="btn btn-danger delete-button"--}}
                                            {{--                                                    data-id="{{ $portCharge->id }}">--}}
                                            {{--                                                Delete--}}
                                            {{--                                            </button>--}}
                                        </td>
                                    </tr>
                                @endforeach
                                <tr class="add-row d-none">
                                    <td class="editable">
                                        <input class="form-control" type="text" name="thc_20ft"/>
                                    </td>
                                    <td class="editable">
                                        <input class="form-control" type="text" name="thc_40ft"/>
                                    </td>
                                    <td class="editable">
                                        <input class="form-control" type="text" name="storage_20ft"/>
                                    </td>
                                    <td class="editable">
                                        <input class="form-control" type="text" name="storage_40ft_first_5"/>
                                    </td>
                                    <td class="editable">
                                        <input class="form-control" type="text" name="storage_40ft_after_5"/>
                                    </td>
                                    <td class="editable">
                                        <input class="form-control" type="text" name="power_free"/>
                                    </td>
                                    <td class="editable">
                                        <input class="form-control" type="text" name="power_20ft"/>
                                    </td>
                                    <td class="editable">
                                        <input class="form-control" type="text" name="power_40ft"/>
                                    </td>
                                    <td class="editable"><input class="form-control" type="text" name="shifting_20ft"/>
                                    </td>
                                    <td class="editable"><input class="form-control" type="text" name="shifting_40ft"/>
                                    </td>
                                    <td class="editable"><input class="form-control" type="text" name="disinf_20ft"/>
                                    </td>
                                    <td class="editable"><input class="form-control" type="text" name="disinf_40ft"/>
                                    </td>
                                    <td class="editable"><input class="form-control" type="text"
                                                                name="hand_fes_em_20ft"/>
                                    </td>
                                    <td class="editable"><input class="form-control" type="text"
                                                                name="hand_fes_em_40ft"/>
                                    </td>
                                    <td class="editable">
                                        <input class="form-control" type="text"
                                               name="gat_lift_off_inbnd_em_ft40_20ft"/>
                                    </td>
                                    <td class="editable">
                                        <input class="form-control" type="text"
                                               name="gat_lift_off_inbnd_em_ft40_40ft"/></td>
                                    <td class="editable">
                                        <input class="form-control" type="text" name="gat_lift_on_inbnd_em_ft40_20ft"/>
                                    </td>
                                    <td class="editable">
                                        <input class="form-control" type="text" name="gat_lift_on_inbnd_em_ft40_40ft"/>
                                    </td>
                                    <td class="editable">
                                        <input class="form-control" type="text" name="pti_20ft"/>
                                    </td>
                                    <td class="editable">
                                        <input class="form-control" type="text" name="pti_40ft_failed"/>
                                    </td>
                                    <td class="editable">
                                        <input class="form-control" type="text" name="pti_40ft_pass"/>
                                    </td>
                                    <td class="editable">
                                        <input class="form-control" type="text" name="add_plan_20ft"/>
                                    </td>
                                    <td class="editable">
                                        <input class="form-control" type="text" name="add_plan_40ft"/>
                                    </td>
                                    <td class="actions-td">
                                        <button class="btn btn-success save-new-button">
                                            Save
                                        </button>
                                    </td>
                                </tr>


                                </tbody>

                            </table>
                        </div>
                        <div class="paginating-container">
                            {{ $portCharges->links() }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
@push('styles')
    <style>
        input {
            min-width: 111px; /* Adjust the width as needed */
        }
    </style>

@endpush
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    @include('port_charge._js')
@endpush