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
                            <li class="breadcrumb-item active"><a href="javascript:void(0);"> Add New Voyage</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
            <div class="widget-content widget-content-area">
                <form id="regForm" action="{{route('voyages.store')}}" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="vessel_idInput">Name <span class="text-warning"> * </span></label>
                                <select class="selectpicker form-control" id="vessel_idInput" data-live-search="true" name="vessel_id" data-size="10"
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
                            <div class="form-group col-md-4">
                                <label for="voyage_noInput">Voyage No <span class="text-warning"> * </span></label>
                                <input type="text" class="form-control" id="voyage_noInput" name="voyage_no" value="{{old('voyage_no')}}"
                                 placeholder="Voyage No" autocomplete="off" autofocus>
                                @error('voyage_no')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label>Job No</label>
                                <input type="text" class="form-control" name="job_no" value="{{old('job_no')}}"
                                 placeholder="Job No" autocomplete="off" autofocus>
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
                                <select class="selectpicker form-control" id="leg_idInput" data-live-search="true" name="leg_id" data-size="10"
                                    title="{{trans('forms.select')}}">
                                    @foreach ($legs as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('leg_id') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('leg_id')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                              <label for="Principal">Principal Name </label>
                              <select class="selectpicker form-control" id="Principal" data-live-search="true" name="principal_name" data-size="10"
                              title="{{trans('forms.select')}}">
                                  @foreach ($lines as $item)
                                      <option value="{{$item->id}}" {{$item->id == old('principal_name') ? 'selected':''}}>{{$item->name}}</option>
                                  @endforeach
                              </select>
                              @error('principal_name')
                              <div style="color: red;">
                                  {{$message}}
                              </div>
                              @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="line_idInput">Vessel Operator <span class="text-warning"> * </span></label>
                                <select class="selectpicker form-control" id="line_idInput" data-live-search="true" name="line_id" data-size="10"
                                    title="{{trans('forms.select')}}" required>
                                    @foreach ($lines as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('line_id') ? 'selected':''}}>{{$item->name}}</option>
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
                                <input type="datetime-local" class="form-control" name="arrived_on" value="{{old('arrived_on')}}"
                                  autocomplete="off">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Berthed On </label>
                                <input type="datetime-local" class="form-control" name="berthed_on" value="{{old('berthed_on')}}"
                                  autocomplete="off">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Discharge Commenced On</label>
                                <input type="datetime-local" class="form-control" name="discharge_commenced_on" value="{{old('discharge_commenced_on')}}"
                                  autocomplete="off">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>Discharge Completed On</label>
                                <input type="datetime-local" class="form-control" name="discharge_completed_on" value="{{old('discharge_completed_on')}}"
                                  autocomplete="off">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Loading Commenced On</label>
                                <input type="datetime-local" class="form-control" name="loading_commenced_on" value="{{old('loading_commenced_on')}}"
                                  autocomplete="off">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Loading Completed On</label>
                                <input type="datetime-local" class="form-control" name="loading_completed_on" value="{{old('loading_completed_on')}}"
                                  autocomplete="off">
                            </div>
                        </div>
                        <div class="form-row">
                            <!-- <div class="form-group col-md-2">
                                <label for="exchange_rate">Exchange Rate ETA</label>
                                <input type="text" class="form-control" id="Exchange" name="exchange_rate" value="{{old('exchange_rate')}}"
                                 placeholder="Exchange Rate" autocomplete="off" autofocus>
                                @error('exchange_rate')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-2">
                                <label for="exchange_rate_etd">Exchange Rate ETD</label>
                                <input type="text" class="form-control" id="Exchange" name="exchange_rate_etd" value="{{old('exchange_rate_etd')}}"
                                 placeholder="Exchange Rate" autocomplete="off" autofocus>
                                @error('exchange_rate_etd')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div> -->
                            <div class="form-group col-md-4">
                                <label>Sailed On</label>
                                <input type="datetime-local" class="form-control" name="sailed_on" value="{{old('sailed_on')}}"
                                  autocomplete="off">
                            </div>
                            <div class="form-group col-md-8">
                                <label for="notes">Notes</label>
                                <textarea class="form-control" id="notes" name="notes" value="{{old('notes')}}"
                                 placeholder="Notes" autocomplete="off" autofocus></textarea>
                                @error('notes')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <table id="voyagePort" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Port</th>
                                    <th>Terminal</th>
                                    <th>Road No</th>
                                    <th>ETA</th>
                                    <th>ETD</th>
                                    <th>
                                        <a id="add"> Add Port <i class="fas fa-plus"></i></a>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="ports">
                                    <select class="selectpicker form-control" id="port" data-live-search="true" name="voyageport[0][port_from_name]" data-size="10"
                                            title="{{trans('forms.select')}}" required>
                                            @foreach ($ports as $item)
                                                <option value="{{$item->id}}" {{$item->name == old('port_from_name') ? 'selected':''}}>{{$item->name}}</option>
                                            @endforeach
                                    </select>
                                </td>
                                <td class="terminal">
                                  <select class="form-control" id="terminal" data-live-search="true" name="voyageport[0][terminal_name]" data-size="10"
                                          title="{{trans('forms.select')}}" required>
                                      <option value="">Select..</option>
                                          @foreach ($terminals as $item)
                                              <option value="{{$item->id}}" {{$item->name == old('terminal_name') ? 'selected':''}}>{{$item->name}} {{$item->code}}</option>
                                          @endforeach
                                  </select>
                                </td>
                                <td>
                                <input type="text" id="roadInput" name="voyageport[0][road_no]" class="form-control" autocomplete="off">
                                </td>
                                <td>
                                <input type="date" id="etaInput" name="voyageport[0][eta]" class="form-control" required>
                                </td>
                                <td>
                                <input type="date" id="etdInput" name="voyageport[0][etd]" class="form-control" required>
                                </td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                        </div>
                        <div class="form-row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary mt-3">{{trans('forms.create')}}</button>
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
      $(document).ready(function(){
        $("#voyagePort").on("click", ".remove", function () {
        $(this).closest("tr").remove();
        });
     var counter  = 1;
        $("#add").click(function(){
                var tr = '<tr>'+
            '<td class="ports"><select class="form-control port" id="port" data-live-search="true" name="voyageport['+counter+'][port_from_name]" data-size="10"><option value="">Select...</option>@foreach ($ports as $item)<option value="{{$item->id}}" {{$item->name == old('port_from_name') ? 'selected':''}}>{{$item->name}}</option>@endforeach</select></td>'+
            '<td class="terminal"><select class="form-control" id="terminal" data-live-search="true" name="voyageport['+counter+'][terminal_name]" data-size="10"><option value="">Select...</option>@foreach ($terminals as $item)<option value="{{$item->id}}" {{$item->name == old('terminal_name') ? 'selected':''}}>{{$item->name}} {{$item->code}}</option>@endforeach</select></td>'+
            '<td><input type="text" name="voyageport['+counter+'][road_no]" class="form-control" autocomplete="off"></td>'+
            '<td><input type="date" name="voyageport['+counter+'][eta]" class="form-control" required></td>'+
            '<td><input type="date" name="voyageport['+counter+'][etd]" class="form-control" required></td>'+
            '<td style="width:85px;"><button type="button" class="btn btn-danger remove"><i class="fa fa-trash"></i></button></td>'
            '</tr>';
            counter++;
            $('#voyagePort').append(tr);
        });
    });
</script>

<script>
  $(document).ready(function (){

        $(function(){
                $('#voyagePort').on('change','td.ports select' , function(e){
                  let self = $(this);
                  let parent = self.closest('tr');
                    let value = e.target.value;
                    let terminal = $('td.terminal select' , parent);
                    let response =    $.get(`/api/master/terminals/${value}`).then(function(data){
                        let terminals = data.terminals || '';
                      //  console.log(terminals);
                        let list2 = [`<option value=''>Select...</option>`];
                        for(let i = 0 ; i < terminals.length; i++){
                            list2.push(`<option value='${terminals[i].id}'>${terminals[i].name} ${terminals[i].code}</option>`);
                        }
               // let terminal = $('.terminal',parent);
                terminal.html(list2.join(''));
                });
            });
        });
  });
</script>
@endpush

@push('styles')
<style>
* {
  box-sizing: border-box;
}

body {
  background-color: #f1f1f1;
}

#regForm {
  font-family: Raleway;
  width: 100%;
  min-width: 300px;
}

h1 {
  text-align: center;
}


/* Mark input boxes that gets an error on validation: */
input.invalid {
  background-color: #ffdddd;
}

/* Hide all steps by default: */
.tab {
  display: none;
}

button {
  background-color: #1b55e2;
  color: #ffffff;
  border: none;
  padding: 10px 20px;
  font-size: 17px;
  font-family: Raleway;
  cursor: pointer;
}

button:hover {
  opacity: 0.8;
}

#prevBtn {
  background-color: #bbbbbb;
}

/* Make circles that indicate the steps of the form: */
.step {
  height: 15px;
  width: 15px;
  margin: 0 2px;
  background-color: #bbbbbb;
  border: none;
  border-radius: 50%;
  display: inline-block;
  opacity: 0.5;
}

.step.active {
  opacity: 1;
}

/* Mark the steps that are finished and valid: */
.step.finish {
  background-color: #1b55e2;
}
</style>
@endpush
