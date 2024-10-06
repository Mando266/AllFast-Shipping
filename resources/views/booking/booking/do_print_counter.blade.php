@extends('layouts.app')

@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Booking</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">DO Print Counter</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                
                <div class="widget-content widget-content-area">
                    <div class="row align-items-center"> <!-- Ensure both fields align vertically in the center -->

                        <!-- Global Max Print Update Form -->
                        <div class="col-md-4">
                            <form method="POST" action="{{ route('booking.updateDOMaxPrint') }}">
                                @csrf
                                <div class="form-group">
                                    <label for="global_max_print">Max Print Number:</label>
                                    <div class="input-group mb-3">
                                        <input type="number" name="global_max_print" id="global_max_print" class="form-control" placeholder="Update max number" min="0" required value="{{ $maxPrint }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-success" type="submit">Update</button>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">The current max print number is {{ $maxPrint }}.</small>
                                </div>
                            </form>
                        </div>

                        <!-- Search Form -->
                        <div class="col-md-8">
                            <form method="GET" action="{{ route('booking.doPrintCounter') }}">
                                <div class="input-group mb-3">
                                    <input type="text" name="query" class="form-control" placeholder="Search by Booking Ref No" value="{{ old('query', $query) }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit">Search</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <hr>

                    @if(isset($bookings) && count($bookings) > 0)
                    <form method="POST" action="{{ route('booking.updateDoPrintCounter') }}">
                        @csrf
                        <input type="hidden" name="query" value="{{ $query }}">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 40%;">Booking Ref No</th>
                                    <th style="width: 40%;">Print Counter</th>
                                    <th style="width: 20%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bookings as $booking)
                                <tr>
                                    <td>{{ $booking->ref_no }}</td>
                                    <td>
                                        <input type="number" name="print_count[{{ $booking->id }}]" class="form-control" value="{{ $booking->print_count ?? 0 }}" min="0">
                                    </td>
                                    <td class="text-center">
                                        <button type="submit" class="btn btn-success">Update</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </form>
                    @else
                    <p>No results found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
