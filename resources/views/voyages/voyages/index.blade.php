@extends('layouts.app')
@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-one">
                    <div class="widget-heading">
                        <nav class="breadcrumb-two" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item active"><a href="javascript:void(0);">Voyages</a></li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>

                        <div class="row">
                            <div class="col-md-12 text-right mb-5">
                            <a class="btn btn-warning" href="{{ route('export.voyages') }}">Export</a>
                            @permission('Voyages-Create')
                            <a href="{{route('voyages.create')}}" class="btn btn-primary">Add New Voyage</a>
                            @endpermission
                            </div>
                        </div>
                    </div>
                <form action="{{route('voyages.index')}}">
                    <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="vessel_port_idInput">Port From</label>
                                <select class="selectpicker form-control" id="vessel_port_idInput" data-live-search="true" name="From" data-size="10"
                                    title="{{trans('forms.select')}}">
                                    @foreach ($ports as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('From',request()->input('From')) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="vessel_port_idInput">Port To</label>
                                <select class="selectpicker form-control" id="vessel_port_idInput" data-live-search="true" name="To" data-size="10"
                                    title="{{trans('forms.select')}}">
                                    @foreach ($ports as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('To',request()->input('To')) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="vessel_port_idInput">Vessel Name</label>
                                <select class="selectpicker form-control" id="vessel_port_idInput" data-live-search="true" name="vessel_id" data-size="10"
                                    title="{{trans('forms.select')}}">
                                    @foreach ($vessels as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('vessel_id',request()->input('vessel_id')) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="voyage_noInput">Voyage No</label>
                                <input type="text" class="form-control" id="voyage_noInput" name="voyage_no" value="{{request()->input('voyage_no')}}"
                                placeholder="Voyage No" autocomplete="off">
                                </div>
                            <div class="col-md-12 text-center">
                                <button  type="submit" class="btn btn-success mt-3">Search</button>
                                <a href="{{route('voyages.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                            </div>
                    </div>
                </form>
                    <div class="widget-content widget-content-area">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-condensed mb-4">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Vessel Code</th>
                                        <th>Vessel Name</th>
                                        <th>Voyage No</th>
                                        <th>Job No</th>
                                        <th>Leg</th>
                                        <th>PORT</th>
                                        <th>ETA</th>
                                        <th>ETD</th>
                                        <th>terminal name</th>
                                        <th>road no</th>
                                        <th>Booking Engaged</th>
                                        <th>Bl Engaged</th>
                                        <th class='text-center'></th>
                                        <th class='text-center'>Add Port</th>
                                        <th class='text-center'></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($items as $item)
                                    @php
                                            $booking = $item->bookings->count();
                                    @endphp
                                    <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{{optional($item->vessel)->code}}}</td>
                                            <td>{{{optional($item->vessel)->name}}}</td>
                                            <td>{{$item->voyage_no}}</td>
                                            <td>{{$item->job_no}}</td>
                                            <td>{{{optional($item->leg)->name}}}</td>
                                            <td>
                                                @foreach($item->voyagePorts as $voyagePort)
                                                <table style="border: hidden;">
                                                    <td>{{ optional($voyagePort->port)->name}}</td>
                                                </table>
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach($item->voyagePorts as $voyagePort)
                                                <table style="border: hidden;">
                                                    <td>{{$voyagePort->eta}}</td>
                                                </table>
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach($item->voyagePorts as $voyagePort)
                                                <table style="border: hidden;">
                                                    <td>{{$voyagePort->etd}}</td>
                                                </table>
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach($item->voyagePorts as $voyagePort)
                                                <table style="border: hidden;">
                                                    <td>{{ optional($voyagePort->terminal)->name}}</td>
                                                </table>
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach($item->voyagePorts as $voyagePort)
                                                <table style="border: hidden;">
                                                    <td>{{$voyagePort->road_no}}</td>
                                                </table>
                                                @endforeach
                                            </td>
                                            <td>
                                                {{$item->bookings->where('booking_confirm','!=','2')->count() + $item->bookingSecondVoyage->where('booking_confirm','!=','2')->count()}}
                                            </td>
                                            <td>
                                                {{ $item->bldrafts->count() == 0 ? optional($item->transhipmentBldrafts)->count() : optional($item->bldrafts)->count() }}
                                            </td>
                                            <td class="text-center">
                                                <ul class="table-controls">
                                                @permission('Voyages-Edit')
                                                    <li>
                                                    <a href="{{route('voyages.edit',['voyage'=>$item->id])}}" data-toggle="tooltip" data-placement="top" title="" data-original-title="edit">
                                                        <i class="far fa-edit text-success"></i>
                                                    </a>
                                                    </li>
                                                @endpermission
                                            </td>

                                            <td class="text-center">
                                                <ul class="table-controls">
                                                @permission('Voyages-Create')

                                                    <li>
                                                        <a href="{{route('voyageports.create',['voyage_id' => $item->id])}}"  data-toggle="tooltip" data-placement="top" title="" data-original-title="show"> <i class="fas fa-plus text-primary"></i> </a>
                                                    </li>
                                                @endpermission

                                            </td>
                                        @if ($booking > 0)
                                            @else
                                            <td class="text-center">
                                                <ul class="table-controls">
                                                    @permission('Voyages-Delete')
                                                    <li>
                                                        <form action="{{route('voyages.destroy',['voyage'=>$item->id])}}" method="post">
                                                            @method('DELETE')
                                                            @csrf
                                                        <button style="border: none; background: none;" type="submit" class="fa fa-trash text-danger"></button>
                                                        </form>
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
