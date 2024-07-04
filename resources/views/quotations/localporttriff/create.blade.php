@extends('layouts.app')
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Triffs </a></li>
                            <li class="breadcrumb-item"><a a href="{{route('localporttriff.index')}}">Triff Port Triff</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">New Local Port Triff</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                    <form id="createForm" action="{{route('localporttriff.store')}}" method="POST">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="countryInput">{{trans('company.country')}} <span class="text-warning"> *  </span></label>
                                    <select class="selectpicker form-control" id="country" data-live-search="true" name="country_id" data-size="10"
                                     title="{{trans('forms.select')}}" required>
                                        @foreach ($country as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('country_id') ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('country_id')
                                    <div style="color:red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="port">Port <span class="text-warning"> *  </span></label>
                                    <select class="selectpicker form-control" id="port" data-live-search="true" name="port_id" data-size="10" required>
                                        <option value="">Select...</option>
                                            @foreach ($ports as $item)
                                                <option value="{{$item->id}}" {{$item->id == old('port_id') ? 'selected':''}}>{{$item->name}}</option>
                                            @endforeach
                                    </select>
                                    @error('port_id')
                                    <div style="color:red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="containersTypesInput">Trminal <span class="text-warning"> *  </span></label>
                                    <select class="selectpicker form-control" id="terminal" data-live-search="true" name="terminal_id" data-size="10" required>
                                        <option value="">Select...</option>
                                        @foreach ($terminals as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('terminal_id') ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('terminal_id')
                                    <div style="color:red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="validity_from">Validity From <span class="text-warning"> *  </span></label>
                                    <input type="date" class="form-control" id="currency" name="validity_from" value="{{old('validity_from')}}"
                                     placeholder="Validity From" autocomplete="off" required>
                                    @error('validity_from')
                                    <div style="color:red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="validity_from">Validity to <span class="text-warning"> *  </span></label>
                                    <input type="date" class="form-control" id="currency" name="validity_to" value="{{old('validity_to')}}"
                                     placeholder="Validity To" autocomplete="off" required>
                                    @error('validity_to')
                                    <div style="color:red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <table  id="triffPriceDetailes" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>charge type</th>
                                        <th>Equipment Type</th>
                                        <th>unit</th>
                                        <th>currency</th>
                                        <th>Customer Rate</th>
                                        <th>payer</th>
                                        <th>Shipment Type</th>
                                        <th>standard Or customise</th>

                                        <th>
                                            <a id="add"> Add <i class="fas fa-plus"></i></a>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                        <select class="selectpicker form-control" id="triffPriceDetailes" data-live-search="true" name="triffPriceDetailes[0][charge_type]" data-size="10"
                                            title="{{trans('forms.select')}}" required>
                                            @foreach ($charges as $item)
                                                <option value="{{$item->id}}" {{$item->id == old('charge_type') ? 'selected':''}}>{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    
                                    <td>
                                        <select class="selectpicker form-control" id="equipment_types" data-live-search="true" name="triffPriceDetailes[0][equipment_type_id]" data-size="10"
                                        title="{{trans('forms.select')}}">
                                            <option value="100">All</option>
                                            @foreach ($equipment_types as $item)
                                                <option value="{{$item->id}}" {{$item->id == old('equipment_type_id') ? 'selected':''}}>{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select class="selectpicker form-control" id="unit" data-live-search="true" name="triffPriceDetailes[0][unit]" data-size="10"
                                            title="{{trans('forms.select')}}">
                                                <option value="Container" >Container</option>
                                                <option value="Document" >Document</option>
                                        </select>
                                        @error('unit')
                                        <div style="color:red;">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </td>

                                    <td>
                                        <select class="selectpicker form-control" id="currency" data-live-search="true" name="triffPriceDetailes[0][currency]" data-size="10"
                                            title="{{trans('forms.select')}}" required style="max-height: 300px; overflow-y: auto;">
                                            @foreach ($currency as $item)
                                                <option value="{{$item->name}}" {{$item->id == old('currency') ? 'selected':''}}>{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" id="dayes" name="triffPriceDetailes[0][selling_price]" class="form-control" autocomplete="off" required>
                                            @error('selling_price')
                                            <div style="color:red;">
                                                {{$message}}
                                            </div>
                                            @enderror
                                    </td>
                                    <!-- <td>
                                        <input type="text" id="cost" name="triffPriceDetailes[0][cost]" class="form-control" autocomplete="off">
                                            @error('cost')
                                            <div style="color:red;">
                                                {{$message}}
                                            </div>
                                            @enderror
                                    </td>

                                    <td>
                                        <input type="text" id="agency_revene" name="triffPriceDetailes[0][agency_revene]" class="form-control" autocomplete="off">
                                            @error('agency_revene')
                                            <div style="color:red;">
                                                {{$message}}
                                            </div>
                                            @enderror
                                    </td>  
                                    <td>
                                        <input type="text" id="liner" name="triffPriceDetailes[0][liner]" class="form-control" autocomplete="off">
                                    </td> -->
                                    <td>
                                    <select class="selectpicker form-control" id="payer" data-live-search="true" name="triffPriceDetailes[0][payer]" data-size="10"
                                    title="{{trans('forms.select')}}">
                                            <option value="Liner" >Liner</option>
                                            <option value="Shipper">Shipper</option>
                                            <option value="Conee">Consignee</option>
                                            <option value="Else" >Else</option>
                                    </select>
                                            @error('payer')
                                            <div style="color:red;">
                                                {{$message}}
                                            </div>
                                            @enderror
                                    </td>

                                    <td>
                                        <select class="selectpicker form-control" id="unit" data-live-search="true" name="triffPriceDetailes[0][is_import_or_export]" data-size="10"
                                            title="{{trans('forms.select')}}">
                                                <option value="0" >Import</option>
                                                <option value="1" >Export</option>
                                                <option value="2" >Empty</option>
                                                <option value="3" >Transshipment</option>

                                        </select>
                                        @error('is_import_or_export')
                                        <div style="color:red;">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </td>
                                    <!-- <td>
                                        <label for="rate_sh">Y</label>&nbsp;
                                            <input type="radio" id="add_to_quotation" name="triffPriceDetailes[0][add_to_quotation]" required  value="1">
                                        <label for="rate_sh">N</label>&nbsp;
                                            <input type="radio" id="add_to_quotation" name="triffPriceDetailes[0][add_to_quotation]"  value="0" >
                                            @error('add_to_quotation')
                                            <div style="color:red;">
                                                {{$message}}
                                            </div>
                                            @enderror
                                    </td> -->
                                    <td>
                                        <label>S</label>&nbsp;
                                            <input type="radio" id="standard_or_customise" name="triffPriceDetailes[0][standard_or_customise]" required  value="1">
                                        <label for="rate_sh">C</label>&nbsp;
                                            <input type="radio" id="standard_or_customise" name="triffPriceDetailes[0][standard_or_customise]"  value="0" >
                                            @error('standard_or_customise')
                                            <div style="color:red;">
                                                {{$message}}
                                            </div>
                                            @enderror
                                    </td>
                                    <td></td>
                                </tr>
                                </tbody>
                            </table>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-primary mt-3">{{trans('forms.create')}}</button>
                                    <a href="{{route('localporttriff.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
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
    $("#triffPriceDetailes").on("click", ".remove", function () {
    $(this).closest("tr").remove();
    });
    var counter  = 1;
    $("#add").click(function(){
            var tr = '<tr>'+
        '<td><select class="selectpicker form-control" data-live-search="true" id="selectpickers" name="triffPriceDetailes['+counter+'][charge_type]" data-size="10"><option>Select</option>@foreach ($charges as $item)<option value="{{$item->id}}">{{$item->name}}</option>@endforeach</select></td>'+
        '<td><select class="selectpicker form-control" data-live-search="true" id="selectpickers" name="triffPriceDetailes['+counter+'][equipment_type_id]" data-size="10"><option>Select</option><option value="100">All</option>@foreach ($equipment_types as $item)<option value="{{$item->id}}">{{$item->name}}</option>@endforeach</select></td>'+
        '<td><select class="selectpicker form-control" data-live-search="true" id="selectpickers" name="triffPriceDetailes['+counter+'][unit]"><option>Select</option><option value="Container">Container</option><option value="Document">Document</option></select></td>'+
        '<td><select class="selectpicker form-control" data-live-search="true" id="selectpickers" name="triffPriceDetailes['+counter+'][currency]" data-size="10"><option>Select</option>@foreach ($currency as $item)<option value="{{$item->name}}">{{$item->name}}</option>@endforeach</select></td>'+
        '<td><input type="text" name="triffPriceDetailes['+counter+'][selling_price]" class="form-control" required></td>'+
        // '<td><input type="text" name="triffPriceDetailes['+counter+'][cost]" class="form-control"></td>'+
        // '<td><input type="text" name="triffPriceDetailes['+counter+'][agency_revene]" class="form-control" autocomplete="off" ></td>'+
        // '<td><input type="text" name="triffPriceDetailes['+counter+'][liner]" class="form-control" autocomplete="off"></td>'+
        '<td><select class="selectpicker form-control" data-live-search="true" id="selectpickers" name="triffPriceDetailes['+counter+'][payer]"><option>Select</option><option value="Liner" >Liner</option><option value="Shipper" >Shipper</option><option value="Conee" >Conee</option><option value="Else" >Else</option></select></td>'+
        '<td><select class="selectpicker form-control" data-live-search="true" id="selectpickers" name="triffPriceDetailes['+counter+'][is_import_or_export]"><option>Select</option><option value="0" >Import</option><option value="1" >Export</option><option value="2" >Empty</option><option value="3" >Transshipment</option></select></td>'+
        // '<td><label for="rate_sh">Y</label>&nbsp;<input type="radio" required name="triffPriceDetailes['+counter+'][add_to_quotation]" value="1">&nbsp;<label for="rate_sh">N</label>&nbsp;<input type="radio" name="triffPriceDetailes['+counter+'][add_to_quotation]" value="0"></td>'+
        '<td><label for="rate_sh">S</label>&nbsp;<input type="radio" required name="triffPriceDetailes['+counter+'][standard_or_customise]" value="1">&nbsp;<label for="rate_sh">C</label>&nbsp;<input type="radio" name="triffPriceDetailes['+counter+'][standard_or_customise]" value="0"></td>'+
        '<td style="width:85px;"><button type="button" class="btn btn-danger remove"><i class="fa fa-trash"></i></button></td>'
        '</tr>';
        counter++;
            $('#triffPriceDetailes').append(tr);
            $('.selectpicker').selectpicker("render");
            $('#selectpickers').selectpicker();
    });
});
</script>
<script>
        $(function(){
                let country = $('#country');
                $('#country').on('change',function(e){
                    let value = e.target.value;
                    let response =    $.get(`/api/master/ports/${country.val()}`).then(function(data){
                        let ports = data.ports || '';
                        let list2 = [`<option value=''>Select...</option>`];
                        for(let i = 0 ; i < ports.length; i++){
                            list2.push(`<option value='${ports[i].id}'>${ports[i].name} </option>`);
                        }
                let port = $('#port');
                port.html(list2.join(''));
                $(port).selectpicker('refresh');
                });
            });
        });
</script>
<script>
        $(function(){
                let port = $('#port');
                $('#port').on('change',function(e){
                    let value = e.target.value;
                    let response =    $.get(`/api/master/terminals/${port.val()}`).then(function(data){
                        let terminals = data.terminals || '';
                        let list2 = [`<option value=''>Select...</option>`];
                        for(let i = 0 ; i < terminals.length; i++){
                            list2.push(`<option value='${terminals[i].id}'>${terminals[i].name} </option>`);
                        }
                let terminal = $('#terminal');
                terminal.html(list2.join(''));
                $(terminal).selectpicker('refresh');
                
                });
            });
        });
</script>
@endpush
