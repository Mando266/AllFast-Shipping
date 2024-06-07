@extends('layouts.app')
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">

        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Containers Movement </a></li>
                            <li class="breadcrumb-item"><a href="{{route('movements.index')}}">Movements</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);"> {{trans('forms.edit')}}</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                
                </br>
            <h5><span style='color:#1b55e2';>Container No :</span> {{$containers->find($movement->container_id)->code}}</h5>
            </br>
                <div class="widget-content widget-content-area">
                <form id="createForm" action="{{route('movements.update',['movement'=>$movement])}}" method="POST">
                        @csrf
                        @method('put')
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="containersMovementsInput">Movement <span class="text-warning"> * (Required.) </span></label>
                                <select class="selectpicker form-control" id="containersMovementsInput" data-live-search="true" name="movement_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($containersMovements as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('movement_id',$movement->movement_id) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('movement_id')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="movement_dateInput">Movement Date <span class="text-warning"> * (Required.) </span></label>
                                <input type="date" class="form-control" id="movement_dateInput" name="movement_date" value="{{old('movement_date',$movement->movement_date)}}"
                                     autocomplete="off" >
                                @error('movement_date')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="portlocationInput">Activity Location <span class="text-warning"> * (Required.) </span></label>
                                <select class="selectpicker form-control" id="portlocationInput" data-live-search="true" name="port_location_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($ports as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('port_location_id',$movement->port_location_id) ? 'selected':''}}>{{$item->code}} - {{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('port_location_id')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="portofloadInput">Port Of Load</label>
                                <select class="selectpicker form-control" id="portofloadInput" data-live-search="true" name="pol_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($ports as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('pol_id',$movement->pol_id) ? 'selected':''}}>{{$item->code}}</option>
                                    @endforeach
                                </select>
                                @error('pol_id')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="portofdisch">Port Of Discharge</label>
                                <select class="selectpicker form-control" id="portofdisch" data-live-search="true" name="pod_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($ports as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('pod_id',$movement->pod_id) ? 'selected':''}}>{{$item->code}}</option>
                                    @endforeach
                                </select>
                                @error('pod_id')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="vessel_idInput">Vessel Name</label>
                                <select class="selectpicker form-control" id="vessel_id" data-live-search="true" name="vessel_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($vessels as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('vessel_id',$movement->vessel_id) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('vessel_id')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="">Voyage No</label>
                                <select class="selectpicker form-control" id="voyage" data-live-search="true" name="voyage_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                 <option value="">Select</option>
                                    @foreach ($voyages as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('voyage_id',$movement->voyage_id) ? 'selected':''}}>{{$item->voyage_no}} - {{ optional($item->leg)->name }}</option>    
                                    @endforeach
                                </select>
                                @error('voyage_id')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="booking_noInput">Booking No</label>
                                <select class="selectpicker form-control" id="booking_noInput" data-live-search="true" name="booking_no" data-size="10"
                                 title="{{trans('forms.select')}}">
                                 <option value="">Select</option>
                                    @foreach ($bookings as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('booking_no',$movement->booking_no) ? 'selected':''}}>{{$item->ref_no}}</option>    
                                    @endforeach
                                </select>
                                @error('booking_no')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="billInput">Bill Of Lading</label>
                                <input type="text" class="form-control" id="billInput" name="bl_no" value="{{old('bl_no',$movement->bl_no)}}"
                                    placeholder="Bill Of Loading" autocomplete="off">
                                @error('bl_no')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="TransshipmentInput">Transshipment Port </label>
                                <select class="selectpicker form-control" id="TransshipmentInput" data-live-search="true" name="transshipment_port_id" data-size="10"
                                title="{{trans('forms.select')}}">
                                    @foreach ($ports as $item)
                                        <option value="{{$item->name}}" {{$item->name == old('transshipment_port_id',$movement->transshipment_port_id) ? 'selected':''}}>{{$item->code}} - {{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('transshipment_port_id')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="BookingInput">Booking Agent </label>
                                <select class="selectpicker form-control" id="BookingInput" data-live-search="true" name="booking_agent_id" data-size="10"
                                title="{{trans('forms.select')}}">
                                    @foreach ($agents as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('booking_agent_id',$movement->booking_agent_id) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('booking_agent_id')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="BookingInput">Import Agent </label>
                                <select class="selectpicker form-control" id="BookingInput" data-live-search="true" name="import_agent" data-size="10"
                                title="{{trans('forms.select')}}">
                                    @foreach ($agents as $item)
                                        @if(isset($movement))
                                        <option value="{{$item->id}}" {{$item->id == old('import_agent') || $item->id == $movement->import_agent ? 'selected':''}}>{{$item->name}}</option>
                                        @else
                                        <option value="{{$item->id}}" {{$item->id == old('import_agent') ? 'selected':''}}>{{$item->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('import_agent')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="BookingInput">container Status </label>
                                <select class="selectpicker form-control" id="BookingInput" data-live-search="true" name="container_status" data-size="10"
                                title="{{trans('forms.select')}}">
                                    @foreach ($containerstatus as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('container_status',$movement->container_status) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('container_status')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="RemarkesInput">Free Time</label>
                                <input type="text" class="form-control" id="RemarkesInput" name="free_time" value="{{old('free_time',$movement->free_time)}}"
                                    placeholder="Free Time" autocomplete="off">
                                @error('free_time')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="RemarkesInput">Remarkes</label>
                                <input type="text" class="form-control" id="RemarkesInput" name="remarkes" value="{{old('remarkes',$movement->remarkes)}}"
                                    placeholder="Remarkes" autocomplete="off">
                                @error('remarkes')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>

                      <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit"  class="btn btn-primary mt-3">{{trans('forms.update')}}</button>
                                <a href="{{route('movements.index')}}" onclick="newDoc()" class="btn btn-danger mt-3">Back</a>
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
<script>
            function newDoc() {
                setTimeout(function(){window.history.go(-1)});
            }
</script>
<script>
         $(function(){
                    let vessel = $('#vessel_id');
                    $('#vessel_id').on('change',function(e){
                        let value = e.target.value;
                        let response =    $.get(`/api/vessel/voyages/${vessel.val()}`).then(function(data){
                            let voyages = data.voyages || '';
                            let list2 = [];
                            for(let i = 0 ; i < voyages.length; i++){
                                list2.push(`<option value='${voyages[i].id}'>${voyages[i].voyage_no} - ${voyages[i].leg}</option>`);
                            }
                    let voyageno = $('#voyage');
                    voyageno.html(list2.join(''));
                    $('.selectpicker').selectpicker('refresh');
                        });
                    });
                });
</script>
@endpush
