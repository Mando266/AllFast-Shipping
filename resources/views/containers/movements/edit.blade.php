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
                                <label for="booking_noInput">Booking No</label>
                                <select class="selectpicker form-control" id="booking_noInput" data-live-search="true" name="booking_no" data-size="10" title="{{trans('forms.select')}}">
                                      
                                    @foreach ($bookings as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('booking_no', $movement->booking_no) ? 'selected':''}}>{{$item->ref_no}}</option>
                                    @endforeach
                                </select>
                                @error('booking_no')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                @enderror
                            </div>
                            
                            <div class="form-group col-md-4">
                                <label for="voyage">Voyage No</label>
                                <input type="text" class="form-control" id="voyage" readonly value="{{ optional($movement->voyage)->voyage_no }}">
                                <input type="hidden" id="voyage_id" name="voyage_id" value="{{ $movement->voyage_id }}">
                            </div>
                            
                            <div class="form-group col-md-4">
                                <label for="vessel_id">Vessel Name</label>
                                <input type="text" class="form-control" id="vessel_name" value="{{$vessel_name}}" readonly>
                                <input type="hidden" id="vessel_id" name="vessel_id" value="{{old('vessel_id', $movement->vessel_id)}}">
                            </div>                              
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="ContainerInput">Container Number <span class="text-warning"> * (Required.) </span></label>
                                <select class="selectpicker form-control" id="ContainerInput" data-live-search="true" name="movement[][container_id]" data-size="10"
                                    title="{{trans('forms.select')}}" multiple="multiple" required>
                                    <option value="{{$movement->container_id}}" selected>{{$containers->find($movement->container_id)->code}}</option>
                                </select>
                                <input type="hidden" id="containersTypesInput" class="form-control" name="container_type_id" placeholder="Container Type" autocomplete="off" value="{{ $movement->container_type_id }}">
                                @error('container_id')
                                    <div class ="invalid-feedback">
                                        {{$message}}
                                    </div>
                                @enderror
                            </div>                      
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="containersMovementsInput">Movement Type <span class="text-warning"> * (Required.) </span></label>
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
                                <input type="datetime-local" class="form-control" id="movement_dateInput" name="movement_date" value="{{old('movement_date',$movement->movement_date)}}"
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
                                <label for="portofload">Port Of Load</label>
                                <input type="text" class="form-control" id="portofload" readonly value="{{ optional($movement->pol)->code }}">
                                <input type="hidden" id="pol_id" name="pol_id" value="{{ $movement->pol_id }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="portofdischarge">Port Of Discharge</label>
                            <input type="text" class="form-control" id="portofdischarge" readonly value="{{ optional($movement->pod)->code }}">
                            <input type="hidden" id="pod_id" name="pod_id" value="{{ $movement->pod_id }}">
                        </div>
                                                  
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
                        </div>
                        <div class="form-row">
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
                                        <option value="{{$item->id}}" {{$item->id == old('import_agent', $movement->import_agent) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('import_agent')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md4">
                                <label for="BookingInput">Container Status </label>
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
                        </div>
                       
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="billInput">Bill Of Lading</label>
                                <input type="text" class="form-control" id="billInput" name="bl_no" value="{{old('bl_no', $movement->bl_no)}}"
                                    placeholder="Bill Of Loading" autocomplete="off">
                                @error('bl_no')
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
                                <button type="submit" id="submit" class="btn btn-primary mt-3">{{trans('forms.update')}}</button>
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

<script>
let eta = '';
let etd = '';

$(document).ready(function(){
    // Booking Number Change Handling
    $('#booking_noInput').on('change', function() {
        var bookingNo = $(this).val();

        if (bookingNo) {
            $.ajax({
                url: '{{ route("booking.fetchDetails") }}',
                type: 'GET',
                data: { booking_no: bookingNo },
                success: function(response) {
                    console.log(response); // Log the entire response for debugging
                    if(response.success) {
                        var voyageNo = response.data.voyage_no || '';
                        var legName = response.data.leg_name || '';
                        var vesselName = response.data.vessel_name || '';
                        var voyageId = response.data.voyage_id || '';
                        var vesselId = response.data.vessel_id || '';
                        var loadPortCode = response.data.load_port_code || '';
                        var loadPortId = response.data.load_port_id || '';
                        var dischargePortCode = response.data.discharge_port_code || '';
                        var dischargePortId = response.data.discharge_port_id || '';
                        var containers = response.data.containers || [];

                        // Set the voyage_no and leg_name in the text input
                        $('#voyage').val(voyageNo + ' - ' + legName);
                        // Set the hidden input for voyage_id
                        $('#voyage_id').val(voyageId);
                        // Set the vessel_name in the text input
                        $('#vessel_name').val(vesselName);
                        // Set the hidden input for vessel_id
                        $('#vessel_id').val(vesselId);

                        // Set the Port of Load in the text input
                        $('#portofload').val(loadPortCode);
                        // Set the hidden input for pol_id
                        $('#pol_id').val(loadPortId);

                        // Set the Port of Discharge in the text input
                        $('#portofdischarge').val(dischargePortCode);
                        // Set the hidden input for pod_id
                        $('#pod_id').val(dischargePortId);

                        // Populate the ContainerInput dropdown
                        var containerOptions = containers.map(function(container) {
                            return `<option value="${container.id}" data-code="${container.container_type_id}">${container.code}</option>`;
                        });
                        $('#ContainerInput').html(containerOptions.join(''));
                        $('#ContainerInput').selectpicker('refresh');
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    alert('An error occurred while fetching details.');
                }
            });
        } else {
            // Clear the fields if no booking is selected
            $('#voyage').val('');
            $('#voyage_id').val('');
            $('#vessel_name').val('');
            $('#vessel_id').val('');
            $('#portofload').val('');
            $('#pol_id').val('');
            $('#portofdischarge').val('');
            $('#pod_id').val('');
            $('#ContainerInput').html('');
            $('#ContainerInput').selectpicker('refresh');
        }
    });

    // Movement Type Change Handling
    $('#containersMovementsInput').on('change', function() {
        var movementType = $(this).val();
        var voyageId = $('#voyage_id').val();
        var portId;

        if (movementType == 'Discharge Full' || movementType == 'Loaded Full') {
            if (movementType == 'Discharge Full') {
                portId = $('#pod_id').val();
            } else {
                portId = $('#pol_id').val();
            }

            $.ajax({
                url: '{{ route("fetchVoyagePortDetails") }}',
                type: 'GET',
                data: { voyage_id: voyageId, port_id: portId },
                success: function(response) {
                    console.log(response); // Log the entire response for debugging
                    if(response.success) {
                        eta = response.data.eta || '';
                        etd = response.data.etd || '';
                        console.log("ETA:", eta); // Log ETA
                        console.log("ETD:", etd); // Log ETD
                        $('#movement_dateInput').attr('min', eta);
                        $('#movement_dateInput').attr('max', etd);
                    } else {
                        console.log("Error:", response.message);
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    alert('An error occurred while fetching voyage port details.');
                }
            });
        } else {
            $('#movement_dateInput').removeAttr('min');
            $('#movement_dateInput').removeAttr('max');
        }
    });

    // Submit Button Handling
    document.getElementById('submit').onclick = function(event) {
        var selectedDate = new Date($('#movement_dateInput').val());
        var etaDate = new Date(eta);
        var etdDate = new Date(etd);

        if (eta && etd && (selectedDate < etaDate || selectedDate > etdDate)) {
            alert('Movement date must be between ETA and ETD.');
            event.preventDefault(); // Prevent form submission
        }
    };
});
</script>

@endpush
