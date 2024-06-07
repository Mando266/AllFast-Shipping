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
                        <div class="row">
                            <div class="col-md-12 text-right mb-5">
                                <a href="{{route('lines.create')}}" class="btn btn-primary">Add New Port Charge</a>
                            </div>
                        </div>
                    </div>
                    <div class="widget-content widget-content-area">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-condensed mb-4">
                                <thead>
                                <tr>
                                    <th colspan=2>THC</th>
                                    <th colspan=3>STORAGE</th>
                                    <th colspan=2>POWER</th>
                                    <th colspan=2>SHIFTING</th>
                                    <th colspan=2>DISINF</th>
                                    <th colspan=2>HAND-FES-EM</th>
                                    <th colspan=2>GAT-LIFT OFF-INBND-EM-FT40</th>
                                    <th colspan=2>GAT-LIFT ON-INBND-EM-FT40</th>
                                    <th colspan=3>PTI</th>
                                    <th colspan=2>WIRE-TRNSHP</th>
                                </tr>
                                <tr>
                                    <th rowspan=2 height=98>20FT</th>
                                    <th rowspan=2>40FT</th>
                                    <th rowspan=2>20FT</th>
                                    <th colspan=2>40FT</th>
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
                                    <th colspan=2>40FT</th>
                                    <th rowspan=2>20FT</th>
                                    <th rowspan=2>40FT</th>
                                </tr>
                                <tr>
                                    <th>rate
                                        first <br>
                                        5 days
                                    </th>
                                    <th>rate after<br>
                                        <span style='mso-spacerun:yes'> </span>5 days
                                    </th>
                                    <th>failed</th>
                                    <th>pass</th>
                                </tr>
                                </thead>
                                <tbody>
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
