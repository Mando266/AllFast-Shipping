@extends('layouts.app')
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a a href="">EDI</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">EDI Calculation</a>
                            </li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Upload Form -->
                    <form action="{{ route('edi.upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="edi_file">Upload EDI File</label>
                            <input type="file" class="form-control" name="edi_file" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </form>

                    <!-- Filter Form -->
                    <h2 class="mt-5">Filter Records</h2>
                    <form method="GET" action="{{ route('edi.index') }}">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="from_date">From Date</label>
                                <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="to_date">To Date</label>
                                <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </form>

                    <!-- Records Table -->
                    <h2 class="mt-5">EDI Records</h2>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Container No</th>
                                <th>Voyage Number</th>
                                <th>IMO</th>
                                <th>Vessel Name</th>
                                <th>Country Code</th>
                                <th>Gross Weight</th>
                                <th>Movement Type</th>
                                <th>ISO</th>
                                <th>Booking Number</th>
                                <th>Goods Description</th>
                                <th>Arrival Date</th>
                                <th>Departure Date</th>
                                <th>Activity Location</th>
                                <th>POL</th>
                                <th>POD</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ediRecords as $record)
                                <tr>
                                    <td>{{ $record->container_no }}</td>
                                    <td>{{ $record->voyage_number }}</td>
                                    <td>{{ $record->imo_number }}</td>
                                    <td>{{ $record->ship_name }}</td>
                                    <td>{{ $record->country_code }}</td>
                                    <td>{{ $record->gross_weight }}</td>
                                    <td>{{ $record->movement_type }}</td>
                                    <td>{{ $record->iso_number }}</td>
                                    <td>{{ $record->booking_number }}</td>
                                    <td>{{ $record->goods_description }}</td>
                                    <td>{{ $record->arrival_date }}</td>
                                    <td>{{ $record->departure_date }}</td>
                                    <td>{{ $record->activity_location }}</td>
                                    <td>{{ $record->pol }}</td>
                                    <td>{{ $record->pod }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- Pagination Links -->
                    <div class="d-flex justify-content-center">
                    {{ $ediRecords->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
