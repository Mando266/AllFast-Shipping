@extends('layouts.app')
@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-one">
                    <div class="widget-heading">
                        <nav class="breadcrumb-two" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a a href="javascript:void(0);">Quotations</a></li>
                                <li class="breadcrumb-item  active"><a href="javascript:void(0);">Export Quotations</a>
                                </li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                        <div class="row">
                            <div class="col-md-12 text-right mb-6">
                                @permission('Quotation-Edit')
                                <a href="{{route('quotations.create')}}" class="btn btn-primary">New Quotation</a>
                                @endpermission
                                <button id="export-current" class="btn btn-warning" type="button">Export</button>
                            </div>
                        </div>
                    </div>
                    </br>
                    <form id="search-form">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="Refrance">Refrance Number </label>
                                <select class="selectpicker form-control" id="Refrance" data-live-search="true"
                                        name="ref_no" data-size="10"
                                        title="{{trans('forms.select')}}">
                                    @foreach ($quotation as $item)
                                        <option
                                            value="{{$item->ref_no}}" {{$item->ref_no == old('ref_no',request()->input('ref_no')) ? 'selected':''}}>{{$item->ref_no}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="status">Status </label>
                                <select class="selectpicker form-control" id="status" data-live-search="true"
                                        name="status" data-size="10"
                                        title="{{trans('forms.select')}}">
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="validity_from">Validity From</label>
                                <input type="date" class="form-control" id="validity_from" name="validity_from"
                                       value="{{request()->input('validity_from')}}">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="validity_to">Validity To</label>
                                <input type="date" class="form-control" id="validity_to" name="validity_to"
                                       value="{{request()->input('validity_to')}}">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="customer_id">Customer</label>
                                <select class="selectpicker form-control" id="customer_id" data-live-search="true"
                                        name="customer_id" data-size="10"
                                        title="{{trans('forms.select')}}">
                                    @foreach ($customers as $item)
                                        <option
                                            value="{{$item->id}}" {{$item->id == old('customer_id',request()->input('customer_id')) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="POL">POL</label>
                                <select class="selectpicker form-control" id="POL" data-live-search="true"
                                        name="load_port_id" data-size="10"
                                        title="{{trans('forms.select')}}">
                                    @foreach ($ports as $item)
                                        <option
                                            value="{{$item->id}}" {{$item->id == old('load_port_id',request()->input('load_port_id')) ? 'selected':''}}>{{$item->code}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="place_of_delivery_id">POD</label>
                                <select class="selectpicker form-control" id="discharge_port_id" data-live-search="true"
                                        name="discharge_port_id" data-size="10"
                                        title="{{trans('forms.select')}}">
                                    @foreach ($ports as $item)
                                        <option
                                            value="{{$item->id}}" {{$item->id == old('discharge_port_id',request()->input('discharge_port_id')) ? 'selected':''}}>{{$item->code}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-12 text-center">
                                <button id="search-btn" type="submit" class="btn btn-success mt-3">Search</button>
                                <a href="{{route('quotations.index')}}"
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
                                    <th>ref no</th>
                                    <!-- <th>Agent</th> -->
                                    <th>Main Line</th>
                                    <th>Agreement Party</th>
                                    <th>validity from</th>
                                    <th>validity to</th>
                                    <th>Equipment Type</th>
                                    <th>Ofr</th>
                                    <th>free time</th>
                                    <th>place of acceptence</th>
                                    <th>load port</th>
                                    <th>discharge port</th>
                                    <th>Status</th>
                                    <th class='text-center' style='width:100px;'></th>
                                    <th class='text-center' style='width:100px;'> Add Booking</th>
                                </tr>
                                </thead>
                                <tbody>

                                @forelse ($items as $item)
                                @php
                                    $quotation = $item->quotation;
                                    
                                @endphp
                                    <tr>
                                        <td>{{ App\Helpers\Utils::rowNumber($items,$loop)}}</td>
                                        <td>{{$item->ref_no}}</td>
                                        <td>{{ optional($item->principal)->name }}</td>
                                        <!-- <td>{{optional($item->disAgent)->name}}</td> -->
                                        <td>{{optional($item->customer)->name}}</td>
                                        <td>{{$item->validity_from}}</td>
                                        <td>{{$item->validity_to}}</td>
                                        <td>
                                            @foreach($item->quotationDesc as $desc)
                                            {{ optional($desc->equipmentsType)->name }}@if (!$loop->last)<br>@endif
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach($item->quotationDesc as $desc)
                                            {{ optional($desc)->ofr }}@if (!$loop->last)<br>@endif
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach($item->quotationDesc as $desc)
                                            {{ optional($desc)->free_time }}@if (!$loop->last)<br>@endif
                                            @endforeach
                                        </td>
                                        <td>{{optional($item->placeOfAcceptence)->code}}</td>
                                        <td>{{optional($item->loadPort)->code}}</td>
                                        <td>{{{optional($item->dischargePort)->code}}}</td>

                                        <td class="text-center">
                                            @if($item->status == "pending")
                                                <span class="badge badge-info"> Pending </span>
                                            @elseif($item->status == "approved")
                                                <span class="badge badge-success"> Approved </span>
                                            @elseif($item->status == "rejected")
                                                <span class="badge badge-danger"> Rejected </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <ul class="table-controls">
                                                @permission('Quotation-Edit')
                                                @if($item->status == "pending")
                                                <li>
                                                        <a href="{{route('quotations.edit',['quotation'=>$item->id])}}"
                                                           data-toggle="tooltip" data-placement="top" title=""
                                                           data-original-title="edit">
                                                            <i class="far fa-edit text-success"></i>
                                                        </a>
                                                    </li>
                                                @endif
                                                @endpermission
                                                @permission('Quotation-Show')
                                                <li>
                                                    <a href="{{route('quotations.show',['quotation'=>$item->id])}}"
                                                       data-toggle="tooltip" data-placement="top" title=""
                                                       data-original-title="show">
                                                        <i class="far fa-eye text-primary"></i>
                                                    </a>
                                                </li>
                                                @endpermission
                                                @if ($quotation && $quotation->booking->count() > 0)
                                                    @permission('Quotation-Delete')
                                                    <li>
                                                        <form
                                                            action="{{route('quotations.destroy',['quotation'=>$item->id])}}"
                                                            method="post">
                                                            @method('DELETE')
                                                            @csrf
                                                            <button style="border: none; background: none;"
                                                                    type="submit"
                                                                    class="fa fa-trash text-danger show_confirm"></button>
                                                        </form>
                                                    </li>
                                                    @endpermission
                                                @endif
                                            </ul>
                                        </td>
                                        <td class="text-center">
                                            <ul class="table-controls">
                                                @if($item->status == "approved")
                                                    @permission('Booking-Create')
                                                    <li>
                                                        <a href="{{route('booking.create',['quotation_id'=>$item->id])}}"
                                                           data-toggle="tooltip" data-placement="top" title=""
                                                           data-original-title="show">
                                                            <i class="fas fa-plus text-primary"></i>
                                                        </a>
                                                    </li>
                                                    @endpermission
                                                @endif
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
                        <div class="paginating-container">
                            {{ $items->appends(request()->query())->links()}}
                        </div>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <script type="text/javascript">

        $('.show_confirm').click(function (event) {
            var form = $(this).closest("form");
            var name = $(this).data("name");
            event.preventDefault();
            swal({
                title: `Are you sure you want to delete this Quotation?`,
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        form.submit();
                    }
                });
        });
        const searchForm = $("#search-form");
        $('#export-current').click(() => {
            searchForm.attr('method', 'post');
            searchForm.attr('action', '{{ route('export.quotation') }}');
            searchForm.find('input[name="_token"]').prop('disabled', false);

            searchForm.submit();
        });
        $('#search-btn').click(() => {
            searchForm.attr('method', 'get');
            searchForm.attr('action', '{{ route('quotations.index') }}');

            searchForm.submit();
        });
    </script>
@endpush
