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
                            <li class="breadcrumb-item active"><a href="javascript:void(0);"> Add New Movement</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                <form id="createForm" action="{{route('movements.store')}}" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="ContainerInput">Container Number <span class="text-warning"> * (Required.) </span></label>
                                <select class="selectpicker form-control" id="ContainerInput" data-live-search="true" name="movement[][container_id]" data-size="10"
                                 title="{{trans('forms.select')}}"  multiple="multiple" required>
                                 @if(isset($containers))
                                    @foreach ($containers as $item)
                                        <option value="{{$item->id}}" data-code="{{$item->container_type_id}}" {{$item->id == old('container_id') ? 'selected':''}}>{{$item->code}}</option>
                                    @endforeach
                                    <input type="hidden" id="containersTypesInput" class="form-control" name="container_type_id" placeholder="Container Type" autocomplete="off" value="{{request()->input('container_type_id')}}">
                                @else
                                    <option value="{{$container->id}}" selected data-code="{{$container->container_type_id}}" {{$container->id == old('container_id') ? 'selected':''}}>{{$container->code}}</option>
                                    <input type="hidden" id="containersTypesInput" class="form-control" name="container_type_id" placeholder="Container Type" autocomplete="off" value={{$container_type}}>
                                @endif
                                </select>
                                @error('container_id')
                                <div class ="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="containersMovementsInput">Movement <span class="text-warning"> * (Required.) </span></label>
                                <select class="selectpicker form-control" id="containersMovementsInput" data-live-search="true" name="movement_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($containersMovements as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('movement_id') ? 'selected':''}}>{{$item->name}}</option>
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
                                <input type="datetime-local" class="form-control" id="movement_dateInput" name="movement_date" value="{{old('movement_date')}}"
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
                                        <option value="{{$item->id}}" {{$item->id == old('port_location_id') ? 'selected':''}}>{{$item->code}} - {{$item->name}}</option>
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
                                        @if(isset($movement))
                                        <option value="{{$item->id}}" {{$item->id == old('pol_id') || $item->id == $movement->pol_id ? 'selected':''}}>{{$item->code}}</option>
                                        @else
                                        <option value="{{$item->id}}" {{$item->id == old('pol_id') ? 'selected':''}}>{{$item->code}}</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('pol_id')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="portofloadInput">Port Of Discharge</label>
                                <select class="selectpicker form-control" id="portofloadInput" data-live-search="true" name="pod_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($ports as $item)
                                        @if(isset($movement))
                                        <option value="{{$item->id}}" {{$item->id == old('pod_id') || $item->id == $movement->pod_id ? 'selected':''}}>{{$item->code}}</option>
                                        @else
                                        <option value="{{$item->id}}" {{$item->id == old('pod_id') ? 'selected':''}}>{{$item->code}}</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('pod_id')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            @if(isset($movement))
                            <div class="form-group col-md-4">
                                <label for="vessel_id">Vessel Name</label>
                                <select class="selectpicker form-control" id="vessel_id" name="vessel_id" data-live-search="true"  data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($vessels as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('vessel_id') || $item->id == $movement->vessel_id ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('vessel_id')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            @else
                            <div class="form-group col-md-4">
                                <label for="vessel_id">Vessel Name</label>
                                <select class="selectpicker form-control" id="vessel_id" name="vessel_id"  data-live-search="true"  data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($vessels as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('vessel_id') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('vessel_id')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            @endif
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group col-md-4">
                            <label for="">Voyage No</label>
                                <select class="selectpicker form-control" id="voyage" data-live-search="true" name="voyage_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                 <option value="">Select</option>
                                    @foreach ($voyages as $item)
                                        @if(isset($movement))
                                        <option value="{{$item->id}}" {{$item->id == old('voyage_id') || $item->voyage_no == $movement->voyage_id ? 'selected':''}}>{{$item->voyage_no}} - {{ optional($item->leg)->name }}</option>
                                        @else
                                        <option value="{{$item->id}}" {{$item->id == old('voyage_id') ? 'selected':''}}>{{$item->voyage_no}} - {{ optional($item->leg)->name }}</option>    
                                        @endif
                                    @endforeach
                                </select>
                                @error('voyage_id')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <!-- <div class="form-group col-md-4">
                                <label for="voyage_idInput">Voyage No</label>
                                <input type="text" class="form-control" id="voyage_idInput" name="voyage_id" value="{{old('voyage_id')}}"
                                    placeholder="Voyage No" autocomplete="off">
                                @error('voyage_id')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div> -->
                            <div class="form-group col-md-4">
                                <label for="booking_noInput">Booking No</label>
                                <select class="selectpicker form-control" id="booking_noInput" data-live-search="true" name="booking_no" data-size="10"
                                 title="{{trans('forms.select')}}">
                                 <option value="">Select</option>
                                    @foreach ($bookings as $item)
                                        @if(isset($movement))
                                        <option value="{{$item->id}}" {{$item->id == old('booking_no') || $item->id == $movement->booking_no ? 'selected':''}}>{{$item->ref_no}}</option>
                                        @else
                                        <option value="{{$item->id}}" {{$item->id == old('booking_no') ? 'selected':''}}>{{$item->ref_no}}</option>    
                                        @endif
                                    @endforeach
                                </select>
                                
                                @error('booking_no')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="TransshipmentInput">Transshipment Port </label>
                                <select class="selectpicker form-control" id="TransshipmentInput" data-live-search="true" name="transshipment_port_id" data-size="10"
                                title="{{trans('forms.select')}}">
                                    @foreach ($ports as $item)
                                        <option value="{{$item->name}}" {{$item->name == old('transshipment_port_id') ? 'selected':''}}>{{$item->code}} - {{$item->name}}</option>
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
                                        @if(isset($movement))
                                        <option value="{{$item->id}}" {{$item->id == old('booking_agent_id') || $item->id == $movement->booking_agent_id ? 'selected':''}}>{{$item->name}}</option>
                                        @else
                                        <option value="{{$item->id}}" {{$item->id == old('booking_agent_id') ? 'selected':''}}>{{$item->name}}</option>
                                        @endif
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
                            <div class="form-group col-md-4">
                                <label for="BookingInput">container Status </label>
                                <select class="selectpicker form-control" id="BookingInput" data-live-search="true" name="container_status" data-size="10"
                                title="{{trans('forms.select')}}">
                                    @foreach ($containerstatus as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('container_status') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('container_status')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                            <div class="form-group col-md-4">
                                <label for="billInput">Bill Of Lading</label>
                                @if(isset($movement))
                                <input type="text" class="form-control" id="billInput" name="bl_no" value="{{$movement->bl_no}}"
                                    placeholder="Bill Of Loading" autocomplete="off">
                                @else
                                <input type="text" class="form-control" id="billInput" name="bl_no" value="{{old('bl_no')}}"
                                    placeholder="Bill Of Loading" autocomplete="off">
                                @endif
                                @error('bl_no')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="RemarkesInput">Remarkes</label>
                                @if(isset($movement))
                                <input type="text" class="form-control" id="RemarkesInput" name="remarkes" value="{{$movement->remarkes}}"
                                    placeholder="Remarkes" autocomplete="off">
                                @else
                                <input type="text" class="form-control" id="RemarkesInput" name="remarkes" value="{{old('remarkes')}}"
                                    placeholder="Remarkes" autocomplete="off">
                                @endif
                                @error('remarkes')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>

                       <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" id="submit" class="btn btn-primary mt-3">{{trans('forms.create')}}</button>
                                <a href="{{route('movements.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
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
    $(function(){
        $('#ContainerInput').on('change',function(){
            var option = $(this).find(":selected");
            var code = option.data('code');
            $('#containersTypesInput').val(code);
        });
    });
</script>
<!-- <script>
    $(function(){
        $('#vessel_id').on('change',function(){
            var option = $(this).find(":selected");
            var code = option.data('code');
            $('#vessel').val(code);
        });
    });
</script> -->

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

document.getElementById('submit').onclick = function() {
    var selected = [];
    for (var option of document.getElementById('ContainerInput').options)
    {
        if (option.selected) {
            selected.push(option.value);
        }
    }
    // alert(selected);
}
</script>

@endpush
