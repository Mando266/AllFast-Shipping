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
                            <li class="breadcrumb-item active"><a href="javascript:void(0);"> Add New Voyage Ports</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
            <div class="widget-content widget-content-area">
                <form id="regForm" action="{{route('voyageports.store',['voyage_id'=>request()->input('voyage_id')])}}" method="POST">
                        @csrf
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
                                            title="{{trans('forms.select')}}">
                                            @foreach ($ports as $item)
                                                <option value="{{$item->id}}" {{$item->id == old('port_from_name') ? 'selected':''}}>{{$item->name}}</option>
                                            @endforeach
                                    </select>
                                </td>
                                <td class="terminal">
                                  <select class="form-control" id="terminal" data-live-search="true" name="voyageport[0][terminal_name]" data-size="10"
                                          title="{{trans('forms.select')}}">
                                                                                     @foreach ($terminals as $item)
                                              <option value="{{$item->id}}" {{$item->id == old('terminal_name') ? 'selected':''}}>{{$item->name}} {{$item->code}}</option>
                                          @endforeach
                                  </select>
                                </td>
                                <td>
                                <input type="text" id="roadInput" name="voyageport[0][road_no]" class="form-control" autocomplete="off">
                                </td>
                                <td>
                                <input type="date" id="etaInput" name="voyageport[0][eta]" class="form-control">
                                </td>
                                <td>
                                <input type="date" id="etdInput" name="voyageport[0][etd]" class="form-control">
                                </td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>

                        </div>
                        <div class="row">
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
            '<td class="ports"><select class="form-control port" id="port" data-live-search="true" name="voyageport['+counter+'][port_from_name]" data-size="10">  @foreach ($ports as $item)<option value="{{$item->id}}" {{$item->id == old('port_from_name') ? 'selected':''}}>{{$item->name}}</option>@endforeach</select></td>'+
            '<td class="terminal"><select class="form-control" id="terminal" data-live-search="true" name="voyageport['+counter+'][terminal_name]" data-size="10">  @foreach ($terminals as $item)<option value="{{$item->id}}" {{$item->id == old('terminal_name') ? 'selected':''}}>{{$item->name}} {{$item->code}}</option>@endforeach</select></td>'+
            '<td><input type="text" name="voyageport['+counter+'][road_no]" class="form-control" autocomplete="off"></td>'+
            '<td><input type="date" name="voyageport['+counter+'][eta]" class="form-control"></td>'+
            '<td><input type="date" name="voyageport['+counter+'][etd]" class="form-control"></td>'+
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
