@extends('layouts.app')
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a a href="{{route('localporttriff.index')}}">Triff Port Triff</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">Edit Local Port Triff</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                    <form id="editForm" action="{{route('localporttriff.update',['localporttriff'=>$localporttriff])}}" method="POST">
                            @csrf
                            @method('put')
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="countryInput">{{trans('company.country')}} <span class="text-warning"> *</span></label>
                                    <select class="selectpicker form-control" id="country" data-live-search="true" name="country_id" data-size="10"
                                     title="{{trans('forms.select')}}" required>
                                        @foreach ($country as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('country_id',$localporttriff->country_id) ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('country_id')
                                    <div style="color:red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="port">Port <span class="text-warning"> *</span></label>
                                    <select class="form-control" id="port" data-live-search="true" name="port_id" data-size="10"
                                            title="{{trans('forms.select')}}" required>
                                            <option value="">Select...</option>
                                            @foreach ($ports as $item)
                                                <option value="{{$item->id}}" {{$item->id == old('port_id',$localporttriff->port_id) ? 'selected':''}}>{{$item->name}}</option>
                                            @endforeach
                                    </select>
                                    @error('port_id')
                                    <div style="color:red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="containersTypesInput">Trminal <span class="text-warning"> *</span></label>
                                    <select class="form-control" id="terminal" data-live-search="true" name="terminal_id" data-size="10"
                                    title="{{trans('forms.select')}}" required>
                                    <option value="">Select...</option>
                                        @foreach ($terminals as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('terminal_id',$localporttriff->terminal_id) ? 'selected':''}}>{{$item->name}}</option>
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
                                    <label for="validity_from">Validity From <span class="text-warning"> *</span></label>
                                    <input type="date" class="form-control" id="currency" name="validity_from" value="{{old('validity_from',$localporttriff->validity_from)}}"
                                     placeholder="Validity From" autocomplete="off" required>
                                    @error('validity_from')
                                    <div style="color:red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="validity_from">Validity to <span class="text-warning"> *</span></label>
                                    <input type="date" class="form-control" id="currency" name="validity_to" value="{{old('validity_to',$localporttriff->validity_to)}}"
                                     placeholder="Validity To" autocomplete="off" required>
                                    @error('validity_to')
                                    <div style="color:red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <table id="triffPriceDetailes" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>charge type</th>
                                        <th>Equipment Type</th>
                                        <th>unit</th>
                                        <th>Customer Rate</th>
                                        <th>IMO Rate</th>
                                        <th>currancy</th>
                                        <th>payer</th>
                                        <th>Shipment Type</th>
                                        <th>standard Or customise</th>
                                        <th>
                                            <a id="add"> Add <i class="fas fa-plus"></i></a>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($localporttriff->triffPriceDetailes as $key=>$item)
                                <tr> 
                                <input type="hidden" value ="{{ $item->id }}" name="triffPriceDetailes[{{ $key }}][id]">
                                    <td>
                                        <select class="selectpicker form-control" id="triffPriceDetailes" data-live-search="true" name="triffPriceDetailes[{{$key}}][charge_type]" data-size="10"
                                            title="{{trans('forms.select')}}" autofocus>
                                            @foreach ($charges as $charge)
                                                <option value="{{$charge->id}}" {{$item->id == old('charge_type') ||  $item->charge_type == $charge->id? 'selected':''}}>{{$charge->name}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    
                                    <td>
                                        <select class="selectpicker form-control" id="equipment_type_id" data-live-search="true" name="triffPriceDetailes[{{$key}}][equipment_type_id]" data-size="10"
                                        title="{{trans('forms.select')}}" autofocus>
                                        <option value="100" {{$item->id == old('equipment_type_id') ||  $item->equipment_type_id == "100"? 'selected':''}}>All</option>
                                            @foreach ($equipment_types as $equipment_type)
                                                <option value="{{$equipment_type->id}}" {{$item->id == old('equipment_type_id') ||  $item->equipment_type_id == $equipment_type->id? 'selected':''}}>{{$equipment_type->name}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select class="selectpicker form-control" id="unit" data-live-search="true" name="triffPriceDetailes[{{$key}}][unit]" data-size="10"
                                            title="{{trans('forms.select')}}">
                                                <option value="Container" {{$item->id == old('unit') ||  $item->unit == "Container"? 'selected':''}}>Container</option>
                                                <option value="Document" {{$item->id == old('unit') ||  $item->unit == "Document" ? 'selected':''}}>Document</option>
                                        </select>
                                        @error('unit')
                                        <div style="color:red;">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="text" id="dayes" name="triffPriceDetailes[{{$key}}][selling_price]" class="form-control" autocomplete="off" value="{{old('selling_price',$item->selling_price)}}" required>
                                            @error('selling_price')
                                            <div style="color:red;">
                                                {{$message}}
                                            </div>
                                            @enderror
                                    </td>
                                    <td>
                                        <input type="text" id="dayes" name="triffPriceDetailes[{{$key}}][imo_selling_price]" class="form-control" autocomplete="off" value="{{old('imo_selling_price',$item->imo_selling_price)}}" required>
                                            @error('imo_selling_price')
                                            <div style="color:red;">
                                                {{$message}}
                                            </div>
                                            @enderror
                                    </td>
                                    <!-- <td>
                                        <input type="text" id="cost" name="triffPriceDetailes[{{$key}}][cost]" class="form-control" autocomplete="off" value="{{old('cost',$item->cost)}}">
                                            @error('cost')
                                            <div style="color:red;">
                                                {{$message}}
                                            </div>
                                            @enderror
                                    </td> -->
                                    <td>
                                        <select class="selectpicker form-control" id="currency" data-live-search="true" name="triffPriceDetailes[{{$key}}][currency]" data-size="10"
                                        title="{{trans('forms.select')}}" autofocus>
                                            @foreach ($currency as $currencys)
                                                <option value="{{$currencys->name}}" {{$item->id == old('currency') ||  $item->currency == $currencys->name? 'selected':''}}>{{$currencys->name}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <!-- <td>
                                        <input type="text" id="agency_revene"name="triffPriceDetailes[{{$key}}][agency_revene]" class="form-control" autocomplete="off" value="{{old('agency_revene',$item->agency_revene)}}">
                                            @error('agency_revene')
                                            <div style="color:red;">
                                                {{$message}}
                                            </div>
                                            @enderror
                                    </td> 
                                     
                                    <td>
                                        <input type="text" id="liner" name="triffPriceDetailes[{{$key}}][liner]" class="form-control" autocomplete="off" value="{{old('liner',$item->liner)}}">
                                    </td> -->
                                    <td>
                                    <select class="selectpicker form-control" id="payer" data-live-search="true" name="triffPriceDetailes[{{$key}}][payer]" data-size="10"
                                    title="{{trans('forms.select')}}">
                                            <option value="Liner" {{$item->id == old('payer') ||  $item->payer == "Liner"? 'selected':''}}>Liner</option>
                                            <option value="Shipper" {{$item->id == old('payer') ||  $item->payer == "Shipper"? 'selected':''}}>Shipper</option>
                                            <option value="Conee" {{$item->id == old('payer') ||  $item->payer == "Conee"? 'selected':''}}>Consignee</option>
                                            <option value="Else" {{$item->id == old('payer') ||  $item->payer == "Else"? 'selected':''}}>Else</option>
                                    </select>
                                            @error('payer')
                                            <div style="color:red;">
                                                {{$message}}
                                            </div>
                                            @enderror
                                    </td>

                                    <td>
                                        <select class="selectpicker form-control" id="is_import_or_export" data-live-search="true" name="triffPriceDetailes[{{$key}}][is_import_or_export]" data-size="10"
                                            title="{{trans('forms.select')}}">
                                                <option value="0" {{$item->id == old('is_import_or_export') ||  $item->is_import_or_export == "0"? 'selected':''}}>IMPORT</option>
                                                <option value="1" {{$item->id == old('is_import_or_export') ||  $item->is_import_or_export == "1" ? 'selected':''}}>EXPORT</option>
                                                <option value="2" {{$item->id == old('is_import_or_export') ||  $item->is_import_or_export == "2" ? 'selected':''}}>Empty</option>
                                                <option value="3" {{$item->id == old('is_import_or_export') ||  $item->is_import_or_export == "3" ? 'selected':''}}>Transshipment</option>

                                        </select>
                                        @error('is_import_or_export')
                                        <div style="color:red;">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </td>
                                    <!-- <td>
                                        <label for="rate_sh">Y</label>&nbsp;
                                            <input type="radio" id="add_to_quotation" name="triffPriceDetailes[{{$key}}][add_to_quotation]"  value="1" {{$item->add_to_quotation == 1 ? 'checked="checked"' :''}}>
                                        <label for="rate_sh">N</label>&nbsp;
                                            <input type="radio" id="add_to_quotation" name="triffPriceDetailes[{{$key}}][add_to_quotation]"  value="0" {{$item->add_to_quotation == 0 ? 'checked="checked"' :''}}>
                                            @error('add_to_quotation')
                                            <div style="color:red;">
                                                {{$message}}
                                            </div>
                                            @enderror
                                    </td> -->
                                    <td>
                                        <label>S</label>&nbsp;
                                            <input type="radio" id="standard_or_customise" name="triffPriceDetailes[{{$key}}][standard_or_customise]"  value="1" {{$item->standard_or_customise == 1 ? 'checked="checked"' :''}}>
                                        <label>C</label>&nbsp;
                                            <input type="radio" id="standard_or_customise" name="triffPriceDetailes[{{$key}}][standard_or_customise]"  value="0" {{$item->standard_or_customise == 0 ? 'checked="checked"' :''}}>
                                            @error('standard_or_customise')
                                            <div style="color:red;">
                                                {{$message}}
                                            </div>
                                            @enderror
                                    </td>
                                    <td style="width:85px;">
                                    <button type="button" class="btn btn-danger remove" onclick="removeItem({{$item->id}})"><i class="fa fa-trash"></i></button>
                                </td>
                                </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-primary mt-3">{{trans('forms.edit')}}</button>
                                    <a href="{{route('localporttriff.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                                </div>
                           </div>
                           <input name="removed" id="removed" type="hidden"  value="">
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
var removed = [];
function removeItem( item )
{
    removed.push(item);
    document.getElementById("removed").value = removed;
}
$(document).ready(function(){
    $("#triffPriceDetailes").on("click", ".remove", function () {
    $(this).closest("tr").remove();
    });
    var counter  = <?= isset($key)? ++$key : 0 ?>;

    $("#add").click(function(){
            var tr = '<tr>'+
        '<td><select class="selectpicker form-control" data-live-search="true" id="selectpickers" name="triffPriceDetailes['+counter+'][charge_type]" data-size="10"><option>Select</option>@foreach ($charges as $item)<option value="{{$item->id}}">{{$item->name}}</option>@endforeach</select></td>'+
        '<td><select class="selectpicker form-control" data-live-search="true" id="selectpickers"  name="triffPriceDetailes['+counter+'][equipment_type_id]" data-size="10"><option>Select</option><option value="All">All</option>@foreach ($equipment_types as $item)<option value="{{$item->id}}">{{$item->name}}</option>@endforeach</select></td>'+
        '<td><select class="selectpicker form-control" data-live-search="true" id="selectpickers" name="triffPriceDetailes['+counter+'][unit]"><option>Select</option><option value="Container">Container</option><option value="Document" >Document</option></select></td>'+
        '<td><input type="text" name="triffPriceDetailes['+counter+'][selling_price]" id="selectpickers" class="form-control" autocomplete="off" required></td>'+
        '<td><input type="text" name="triffPriceDetailes['+counter+'][imo_selling_price]" id="selectpickers" class="form-control" autocomplete="off" required></td>'+
        // '<td><input type="text" name="triffPriceDetailes['+counter+'][cost]" class="form-control" autocomplete="off"></td>'+
        '<td><select class="selectpicker form-control" data-live-search="true" name="triffPriceDetailes['+counter+'][currency]" data-size="10"><option>Select</option>@foreach ($currency as $item)<option value="{{$item->name}}">{{$item->name}}</option>@endforeach</select></td>'+
        // '<td><input type="text" name="triffPriceDetailes['+counter+'][agency_revene]" class="form-control"></td>'+
        // '<td><input type="text" name="triffPriceDetailes['+counter+'][liner]" class="form-control" autocomplete="off"></td>'+
        '<td><select class="selectpicker form-control" data-live-search="true" id="selectpickers" name="triffPriceDetailes['+counter+'][payer]"><option>Select</option><option value="Liner" >Liner</option><option value="Shipper" >Shipper</option><option value="Conee">Consignee</option><option value="Else" >Else</option></select></td>'+
        '<td><select class="selectpicker form-control" data-live-search="true" id="selectpickers" name="triffPriceDetailes['+counter+'][is_import_or_export]"><option>Select</option><option value="0">IMPORT</option><option value="1" >EXPORT</option><option value="2" >Empty</option><option value="3" >Transshipment</option></select></td>'+
        // '<td><label for="rate_sh">Y</label>&nbsp;<input type="radio" required name="triffPriceDetailes['+counter+'][add_to_quotation]" value="1">&nbsp;<label for="rate_sh">N</label> &nbsp;<input type="radio" name="triffPriceDetailes['+counter+'][add_to_quotation]" value="0"></td>'+
        '<td><label for="rate_sh">S</label>&nbsp;<input type="radio" required name="triffPriceDetailes['+counter+'][standard_or_customise]" value="1">&nbsp;<label for="rate_sh">C</label> &nbsp;<input type="radio" name="triffPriceDetailes['+counter+'][standard_or_customise]" value="0"></td>'+
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
                });
            });
        });
</script>
@endpush
