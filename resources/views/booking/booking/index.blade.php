@extends('layouts.app')
@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-one">
                    <div class="widget-heading">
                        <nav class="breadcrumb-two" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a a href="javascript:void(0);">Booking</a></li>
                                <li class="breadcrumb-item  active"><a href="javascript:void(0);">Booking Gates</a></li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                    </div>

                    <div class="row">
                        @if(Session::has('message'))
                            <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('message') }}</p>
                        @endif
                            </br>
                            <div class="col-md-12 text-right mb-5">
                                @permission('Booking-Create')
                                <a href="{{route('booking.selectQuotation')}}" class="btn btn-primary">New Booking</a>
                                @endpermission
                                @permission('Booking-List')
                                <button id="export-current" class="btn btn-warning" type="button">Export</button>
                                <button id="export-current-loadlist" class="btn btn-info" type="button">Load List /
                                    Discharge
                                </button>
                                @endpermission
                            </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-right mb-6">
                        @permission('Booking-Create')

                            <form action="{{route('importBooking')}}" method="POST" enctype="multipart/form-data">
                                @csrf

                                {{ csrf_field() }}
                                <input type="file" name="file" onchange="unlock();">
                                <button id="buttonSubmit" class="btn btn-success mt-3" disabled>Import Booking
                                    Containers
                                </button>
                            </form>
                            @endpermission
                        </div>
                    </div>
                    </br>
                    <form id="search-form">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="ref_no">Quotation Ref No</label>
                                <select class="selectpicker form-control" name="quotation_id" data-live-search="true"
                                        data-size="10"
                                        title="{{trans('forms.select')}}">
                                    @foreach ($quotation as $item)
                                        <option
                                            value="{{$item->id}}" {{$item->id == old('quotation_id',request()->input('quotation_id')) ? 'selected':''}}>{{$item->ref_no}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="Refrance">BOOKING REF NO</label>
                                <select class="selectpicker form-control" id="Refrance" data-live-search="true"
                                        name="ref_no" data-size="10"
                                        title="{{trans('forms.select')}}">
                                    @foreach ($bookingNo as $item)
                                        <option
                                            value="{{$item->ref_no}}" {{$item->ref_no == old('ref_no',request()->input('ref_no')) ? 'selected':''}}>{{$item->ref_no}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="customer_id">Shipper</label>
                                <select class="selectpicker form-control" id="customer_id" data-live-search="true"
                                        name="customer_id" data-size="10"
                                        title="{{trans('forms.select')}}">
                                    @foreach ($customers as $item)
                                        <option
                                            value="{{$item->id}}" {{$item->id == old('customer_id',request()->input('customer_id')) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="ffw_id">Fright Forwarder</label>
                                <select class="selectpicker form-control" id="ffw_id" data-live-search="true"
                                        name="ffw_id" data-size="10"
                                        title="{{trans('forms.select')}}">
                                    @foreach ($ffw as $item)
                                        <option
                                            value="{{$item->id}}" {{$item->id == old('ffw_id',request()->input('ffw_id')) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Consignee</label>
                                <select class="selectpicker form-control" id="customer_consignee_id" data-live-search="true"
                                        name="customer_consignee_id" data-size="10"
                                        title="{{trans('forms.select')}}">
                                    @foreach ($consignee as $item)
                                        <option
                                            value="{{$item->id}}" {{$item->id == old('customer_consignee_id',request()->input('customer_consignee_id')) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
   
                        <div class="form-row">
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

                            <div class="form-group col-md-3">
                                <label for="voyage_id_both">Vessel / Voyage </label>
                                <select class="selectpicker form-control" id="voyage_id_both" data-live-search="true"
                                        name="voyage_id_both" data-size="10"
                                        title="{{trans('forms.select')}}">
                                    @foreach ($voyages as $item)
                                        <option
                                            value="{{$item->id}}" {{$item->id == old('voyage_id_both',request()->input('voyage_id_both')) ? 'selected':''}}>{{optional($item->vessel)->name}}
                                            / {{$item->voyage_no}} - {{ optional($item->leg)->name }}</option>
                                    @endforeach
                                </select>
                                @error('voyage_id_both')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>

                            <div class="form-group col-md-3">
                                <label for="Principal">Principal Name  </span></label>
                                <select class="selectpicker form-control" id="Principal" data-live-search="true"
                                        name="principal_name" data-size="10"
                                        title="{{trans('forms.select')}}">
                                    @foreach ($line as $item)
                                        <option
                                            value="{{$item->id}}" {{$item->id == old('principal_name',request()->input('principal_name')) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('principal_name')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="status">Booking Status </span></label>
                                <select class="selectpicker form-control" data-live-search="true" name="booking_confirm"
                                        title="{{trans('forms.select')}}">
                                    <option value="1" {{ request()->input('booking_confirm') == "1" ? 'selected':'' }}>
                                        Confirm
                                    </option>
                                    <option value="3" {{ request()->input('booking_confirm') == "3" ? 'selected':'' }}>
                                        Draft
                                    </option>
                                    <option value="2" {{ request()->input('booking_confirm') == "2" ? 'selected':'' }}>
                                        Cancelled
                                    </option>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="Container">Container No</label>
                                <select class="selectpicker form-control" id="Container" data-live-search="true"
                                        name="container_id" data-size="10"
                                        title="{{trans('forms.select')}}">
                                    @foreach ($containers as $item)
                                        <option
                                            value="{{$item->id}}" {{$item->id == old('container_id',request()->input('container_id')) ? 'selected':''}}>{{$item->code}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="transhipment">Is Transhipment</label>
                                <select class="selectpicker form-control" id="is_transhipment" data-live-search="true"
                                        name="is_transhipment" data-size="10"
                                        title="{{trans('forms.select')}}">
                                    <option
                                        value="1" {{"1" == old('is_transhipment',request()->input('is_transhipment')) ? 'selected':''}}>
                                        Yes
                                    </option>
                                    <option
                                        value="0" {{"0" == old('is_transhipment',request()->input('is_transhipment')) ? 'selected':''}}>
                                        No
                                    </option>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="booking_type">Booking Type</label>
                                <select class="selectpicker form-control" id="booking_type" data-live-search="true"
                                        name="booking_type" data-size="10"
                                        title="{{trans('forms.select')}}">
                                    <option
                                        value="Import" {{"Import" == old('booking_type',request()->input('booking_type')) ? 'selected':''}}>
                                        Import
                                    </option>
                                    <option
                                        value="Export" {{"Export" == old('booking_type',request()->input('booking_type')) ? 'selected':''}}>
                                        Export
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-12 text-center">
                                <button id="search-btn" type="submit" class="btn btn-success mt-3">Search</button>
                                <button type="button" id="reset-select" class="btn btn-info mt-3">Reset</button>
                                <a href="{{route('booking.index')}}"
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
                                    <!-- <th>Quotation no</th> -->
                                    <th>Booking ref no</th>
                                    <th>Shipper</th>
                                    <th>Forwarder</th>
                                    <th>Consignee</th>
                                    <th>vessel</th>
                                    <th>voyage</th>
                                    <th>leg</th>
                                    <th>Main Line</th>
                                    <th>Vessel Operator</th>
                                    <th>load port</th>
                                    <th>discharge port</th>
                                    <th>Equipment Type</th>
                                    <th>qty</th>
                                    <th>Containers Status</th>
                                    <th>Booking Creation</th>
                                    <th>MT/FL</th>
                                    <th>Booking Status</th>
                                    <th class='text-center' style='width:100px;'>add bl</th>
                                    <th>S Order / D Order</th>
                                    <th>Gate In</th>
                                    <th>Gate Out</th>
                                    <th class='text-center' style='width:100px;'></th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($items as $item)
                                        <?php
                                        $qty = 0;
                                        $assigned = 0;
                                        $unassigned = 0;
                                        $containerTypes = $item->bookingContainerDetails->pluck('containerType.name')->unique();
                                        ?>
                                    @foreach($item->bookingContainerDetails as $bookingContainerDetail)
                                            <?php
                                            $qty += $bookingContainerDetail->qty;
                                            if ($bookingContainerDetail->qty == 1 && $bookingContainerDetail->container_id == "000") {
                                                $unassigned += 1;
                                            } elseif ($bookingContainerDetail->qty == 1 && $bookingContainerDetail->container_id == null) {
                                                $unassigned += 1;
                                            } elseif ($bookingContainerDetail->qty == 1) {
                                                $assigned += 1;
                                            } else {
                                                $unassigned += $bookingContainerDetail->qty;
                                            }
                                            ?>
                                    @endforeach
                                    <tr>
                                        <td>{{ App\Helpers\Utils::rowNumber($items,$loop)}}</td>
                                        <!-- <td>{{optional($item->quotation)->ref_no}}</td> -->
                                        <td>{{$item->ref_no}}</td>
                                        <td>{{optional($item->customer)->name}}</td>
                                        <td>{{optional($item->forwarder)->name}}</td>
                                        <td>{{optional($item->consignee)->name}}</td>
                                        @if (optional($item->quotation)->shipment_type == "Import" && optional($item)->transhipment_port != null)
                                            <td>{{optional(optional($item->secondvoyage)->vessel)->name}}</td>
                                            <td>{{optional($item->secondvoyage)->voyage_no}}</td>
                                            <td>{{optional(optional($item->secondvoyage)->leg)->name}}</td>
                                        @else
                                            <td>{{optional(optional($item->voyage)->vessel)->name}}</td>
                                            <td>{{optional($item->voyage)->voyage_no}}</td>
                                            <td>{{optional(optional($item->voyage)->leg)->name}}</td>
                                        @endif

                                        <td>{{optional($item->principal)->name}}</td>
                                        <td>{{optional($item->operator)->name}}</td>
                                        <td>{{optional($item->loadPort)->code}}</td>
                                        <td>{{optional($item->dischargePort)->code}}</td>
                                        <td>
                                            <table style="border: hidden;">
                                                <!-- @if($item->bookingContainerDetails->count() > 0)
                                                    <td>{{ optional($item->bookingContainerDetails[0]->containerType)->name }}</td>
                                                @endif -->
                                                @if($containerTypes->isNotEmpty())
                                                    <td>
                                                    {!! $containerTypes->implode('<br>') !!}
                                                    </td>
                                                @endif
                                            </table>
                                        </td>
                                        <td>{{ $qty }}</td>
                                        <td>
                                            {{ $assigned }} Assigned<br>
                                            {{ $unassigned }} Unassigned
                                        </td>
                                        <td>{{{$item->created_at}}}</td>
                                        <td>{{{$item->booking_type}}}</td>  
                                        <td class="text-center">
                                            @if($item->booking_confirm == 1)
                                                <span class="badge badge-info"> Confirm </span>
                                            @elseif($item->booking_confirm == 3)
                                                <span class="badge badge-warning"> Draft </span>
                                            @elseif($item->booking_confirm == 2)
                                                <span class="badge badge-danger"> Cancelled </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <ul class="table-controls">
                                                @if($item->booking_confirm == "1")
                                                    @permission('Booking-Create')
                                                    @if($item->has_bl == 0)
                                                        <li>
                                                            <a href="{{route('bldraft.create',['booking_id'=>$item->id])}}"
                                                               data-toggle="tooltip" data-placement="top" title=""
                                                               data-original-title="show">
                                                                <i class="fas fa-plus text-primary"></i>
                                                            </a>
                                                        </li>
                                                    @else
                                                        @if(optional($item->bldraft)->bl_status == 1)
                                                            <span class="badge badge-info"> Confirmed </span>
                                                        @else
                                                            <span class="badge badge-warning"> Draft </span>
                                                        @endif
                                                    @endif
                                                    @endpermission

                                                @endif
                                            </ul>
                                        </td>
                                        <td class="text-center">
                                            @permission('Booking-Show')
                                            <ul class="table-controls">
                                                @if($item->booking_confirm == 1 && $item->is_transhipment == 0)
                                                    <li>
                                                        @if(optional($item->quotation)->shipment_type == "Import")
                                                            <a href="{{route('booking.deliveryOrder',['booking'=>$item->id])}}"
                                                               target="_blank">
                                                                <i class="fas fa-file-pdf text-primary"
                                                                   style='font-size:large;'></i>
                                                            </a>
                                                        @else
                                                            <a href="{{route('booking.showShippingOrder',['booking'=>$item->id])}}"
                                                               target="_blank">
                                                                <i class="fas fa-file-pdf text-primary"
                                                                   style='font-size:large;'></i>
                                                            </a>
                                                        @endif
                                                    </li>
                                                @endif
                                            </ul>
                                            @endpermission
                                        </td>
                                        <td class="text-center">
                                            @permission('Booking-Show')
                                            <ul class="table-controls">
                                                @if($item->booking_confirm == 1  && $item->is_transhipment == 0)
                                                    <li>
                                                        @if(optional($item)->shipment_type == "Import")
                                                            <a href="{{route('booking.selectGateInImport',['booking'=>$item->id])}}"
                                                               target="_blank">
                                                                <i class="fas fa-file-pdf text-primary"
                                                                   style='font-size:large;'></i>
                                                            </a>
                                                        @else
                                                            <a href="{{route('booking.showGateIn',['booking'=>$item->id])}}"
                                                               target="_blank">
                                                                <i class="fas fa-file-pdf text-primary"
                                                                   style='font-size:large;'></i>
                                                            </a>
                                                        @endif
                                                    </li>
                                                @endif
                                            </ul>
                                            @endpermission
                                        </td>

                                        <td class="text-center">
                                            @permission('Booking-Show')
                                            <ul class="table-controls">
                                                @if($item->booking_confirm == 1  && $item->is_transhipment == 0)
                                                    <li>
                                                        <a href="{{route('booking.selectGateOut',['booking'=>$item->id])}}"
                                                           target="_blank">
                                                            <i class="fas fa-file-pdf text-primary"
                                                               style='font-size:large;'></i>
                                                        </a>
                                                    </li>
                                                @endif
                                            </ul>
                                            @endpermission
                                        </td>

                                        <td class="text-center">
                                            <ul class="table-controls">
                                                @if($item->certificat == !null)
                                                    <li>
                                                        <a href='{{asset($item->certificat)}}' target="_blank">
                                                            <i class="fas fa-file-pdf text-primary"
                                                               style='font-size:large;'></i>
                                                        </a>
                                                    </li>
                                                @endif
                                                @permission('Booking-Edit')
                                                <li>
                                                    <a href="{{route('booking.edit',['booking'=>$item->id,'quotation_id'=>$item->quotation_id])}}"
                                                       data-toggle="tooltip" data-placement="top" title=""
                                                       data-original-title="edit">
                                                        <i class="far fa-edit text-success"></i>
                                                    </a>
                                                </li>
                                                @endpermission
                                                @if($item->is_transhipment == 0)
                                                    @permission('Booking-Show')
                                                    <li>
                                                        <a href="{{route('booking.show',['booking'=>$item->id])}}"
                                                           data-toggle="tooltip" data-placement="top" title=""
                                                           data-original-title="show">
                                                            <i class="far fa-eye text-primary"></i>
                                                        </a>
                                                    </li>
                                                    @endpermission
                                                @endif
                                                {{-- @if($item->has_bl == 0)
                                                @permission('Booking-Delete')
                                                <li>
                                                    <form action="{{route('booking.destroy',['booking'=>$item->id])}}" method="post">
                                                        @method('DELETE')
                                                        @csrf
                                                    <button style="border: none; background: none;" type="submit" class="fa fa-trash text-danger show_confirm"></button>
                                                    </form>
                                                </li>
                                                @endpermission
                                                @endif --}}
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
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <script>
        function unlock() {
            document.getElementById('buttonSubmit').removeAttribute("disabled");
        }
    </script>

    <script type="text/javascript">
        $('.show_confirm').click(function (event) {
            var form = $(this).closest("form");
            var name = $(this).data("name");
            event.preventDefault();
            swal({
                title: `Are you sure you want to delete this Booking?`,
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
            searchForm.attr('action', '{{ route('export.booking') }}');
            searchForm.find('input[name="_token"]').prop('disabled', false);

            searchForm.submit();
        });
        $('#export-current-loadlist').click(() => {
            searchForm.attr('method', 'post');
            searchForm.attr('action', '{{ route('export.loadList') }}');
            searchForm.find('input[name="_token"]').prop('disabled', false);

            searchForm.submit();
        });
        $('#search-btn').click(() => {
            searchForm.attr('method', 'get');
            searchForm.attr('action', '{{ route('booking.index') }}');

            searchForm.submit();
        });
    </script>
@endpush
