@extends('layouts.app')
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">

        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('voyages.index')}}">Voyages</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);"> {{trans('forms.edit')}}</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                    <form id="createForm" action="{{route('voyages.update',['voyage'=>$voyage])}}" method="POST">
                        @csrf
                        @method('put')
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="vessel_idInput">Name *</label>
                                <select class="selectpicker form-control" id="vessel_idInput" data-live-search="true" name="vessel_id" data-size="10" title="{{trans('forms.select')}}">
                                    @foreach ($vessels as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('vessel_id',$voyage->vessel_id) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('vessel_id')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="voyage_noInput">Voyage No *</label>
                                <input type="text" class="form-control" id="voyage_noInput" name="voyage_no" value="{{old('voyage_no',$voyage->voyage_no)}}" placeholder="Voyage No" autocomplete="off" autofocus>
                                @error('voyage_no')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label>Job No</label>
                                <input type="text" class="form-control" name="job_no" value="{{old('job_no',$voyage->job_no)}}" placeholder="Job No" autocomplete="off" autofocus>
                                @error('job_no')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="leg_idInput">Leg</label>
                                <select class="selectpicker form-control" id="leg_idInput" data-live-search="true" name="leg_id" data-size="10" title="{{trans('forms.select')}}">
                                    @foreach ($legs as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('leg_id',$voyage->leg_id) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('leg_id')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="Principal">Principal Name</label>
                                <select class="selectpicker form-control" id="Principal" data-live-search="true" name="principal_name" data-size="10" title="{{trans('forms.select')}}">
                                    @foreach ($lines as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('principal_name',$voyage->principal_name) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('principal_name')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="line_idInput">Vessel Operator</label>
                                <select class="selectpicker form-control" id="line_idInput" data-live-search="true" name="line_id" data-size="10" title="{{trans('forms.select')}}">
                                    @foreach ($lines as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('line_id',$voyage->line_id) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('line_id')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>Arrived On</label>
                                <input type="datetime-local" class="form-control" name="arrived_on" value="{{old('arrived_on',$voyage->arrived_on)}}"
                                  autocomplete="off">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Berthed On </label>
                                <input type="datetime-local" class="form-control" name="berthed_on" value="{{old('berthed_on',$voyage->berthed_on)}}"
                                  autocomplete="off">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Discharge Commenced On</label>
                                <input type="datetime-local" class="form-control" name="discharge_commenced_on" value="{{old('discharge_commenced_on',$voyage->discharge_commenced_on)}}"
                                  autocomplete="off">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>Discharge Completed On</label>
                                <input type="datetime-local" class="form-control" name="discharge_completed_on" value="{{old('discharge_completed_on',$voyage->discharge_completed_on)}}"
                                  autocomplete="off">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Loading Commenced On</label>
                                <input type="datetime-local" class="form-control" name="loading_commenced_on" value="{{old('loading_commenced_on',$voyage->loading_commenced_on)}}"
                                  autocomplete="off">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Loading Completed On</label>
                                <input type="datetime-local" class="form-control" name="loading_completed_on" value="{{old('loading_completed_on',$voyage->loading_completed_on)}}"
                                  autocomplete="off">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>Sailed On</label>
                                <input type="datetime-local" class="form-control" name="sailed_on" value="{{old('sailed_on',$voyage->sailed_on)}}"
                                  autocomplete="off">
                            </div>
                            <div class="form-group col-md-8">
                                <label for="notes">Notes</label>
                                <textarea class="form-control" id="notes" name="notes" placeholder="Notes" autocomplete="off">{{ old('notes',$voyage->notes) }}</textarea>
                                @error('notes')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <h4>Voyages Port</h4>
                        <table id="voyageport" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Port</th>
                                    <th>Terminal</th>
                                    <th>Road No</th>
                                    <th>ETA</th>
                                    <th>ETD</th>
                                    <th>
                                        <a>Remove<i></i></a>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($voyage_ports as $key => $item)
                            <tr>
                                <input type="hidden" value ="{{ $item->id }}" name="voyageport[{{ $key }}][id]">
                                <td class="ports">
                                    <select class="selectpicker form-control" id="port" data-live-search="true" name="voyageport[{{ $key }}][port_from_name]" data-size="10" title="{{trans('forms.select')}}">
                                        @foreach ($ports as $port)
                                        <option value="{{$port->id}}" {{$port->id == old('port_from_name',$item->port_from_name) ? 'selected':''}}>{{$port->name}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="terminal">
                                    <select class="selectpicker form-control" id="terminal" data-live-search="true" name="voyageport[{{ $key }}][terminal_name]" data-size="10" title="{{trans('forms.select')}}">
                                        @foreach ($terminals as $terminal)
                                        <option value="{{$terminal->id}}" {{$terminal->id == old('terminal_name',$item->terminal_name) ? 'selected':''}}>{{$terminal->name}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="text" id="road_no" name="voyageport[{{ $key }}][road_no]" class="form-control input" autocomplete="off" value="{{old('road_no',$item->road_no)}}">
                                </td>
                                <td>
                                    <input type="date" id="eta" name="voyageport[{{ $key }}][eta]" class="form-control input" autocomplete="off" value="{{old('eta',$item->eta)}}">
                                </td>
                                <td>
                                    <input type="date" id="etd" name="voyageport[{{ $key }}][etd]" class="form-control input" autocomplete="off" value="{{old('etd',$item->etd)}}">
                                </td>
                                <td style="width:85px;">
                                    <button type="button" class="btn btn-danger remove" onclick="removeItem({{$item->id}})"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <input name="removed" id="removed" type="hidden" value="">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary mt-3">{{trans('forms.update')}}</button>
                                <a href="{{route('voyages.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
</div>
@endsection
@push('scripts')

<script>
    var removed = [];
    function removeItem(item) {
        removed.push(item);
        document.getElementById("removed").value = removed;
    }
    $(document).ready(function() {
        $("#voyageport").on("click", ".remove", function () {
            $(this).closest("tr").remove();
        });
    });
</script>

<script>
    $(document).ready(function (){
        $(function(){
            $('#voyageport').on('change','td.ports select', function(e){
                let self = $(this);
                let parent = self.closest('tr');
                let value = e.target.value;
                let terminal = $('td.terminal select', parent);
                $.get(`/api/master/terminals/${value}`).then(function(data){
                    let terminals = data.terminals || '';
                    let list2 = [`<option value=''>Select...</option>`];
                    for(let i = 0; i < terminals.length; i++){
                        list2.push(`<option value='${terminals[i].id}'>${terminals[i].name} ${terminals[i].code}</option>`);
                    }
                    terminal.html(list2.join(''));
                    $(terminal).selectpicker('refresh');
                });
            });
        });

        $('#createForm').on('submit', function() {
            $('.selectpicker').each(function() {
                if($(this).attr('name') !== undefined) {
                    let value = $(this).val();
                    $(this).val(value);
                }
            });
        });
    });
</script>
@endpush
