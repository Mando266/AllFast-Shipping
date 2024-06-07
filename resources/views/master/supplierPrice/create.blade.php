@extends('layouts.app')
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">

        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Triffs</a></li>
                            <li class="breadcrumb-item"><a href="{{route('supplierPrice.index')}}">Slot Rates Triffs</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);"> Add New Rate</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                <form id="createForm" action="{{route('supplierPrice.store')}}" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="supplier">Supplier <span class="text-warning"> * (Required.) </span></label>
                                <select class="selectpicker form-control" id="supplier" data-live-search="true" name="supplier_id" data-size="10"
                                title="{{trans('forms.select')}}" required>
                                    @foreach ($line as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('supplier_id') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>

                            <div class="form-group col-md-3">
                                <label for="Rate_refInput">Rate Ref <span class="text-warning"> * (Required.) </span></label>
                                <input type="text" class="form-control" id="rateInput" name="ref_rate" value="{{old('ref_rate')}}"
                                 placeholder="Rate Ref" autocomplete="off" required>
                                @error('code')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="Pol">Pol <span class="text-warning"> * (Required.) </span></label>
                                <select class="selectpicker form-control" id="pol_id" data-live-search="true" name="pol_id" data-size="10"
                                 title="{{trans('forms.select')}}" required>
                                    @foreach ($ports as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('pol_id') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('pol_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="Pod">Pod <span class="text-warning"> * (Required.) </span></label>
                                <select class="selectpicker form-control" id="Pod" data-live-search="true" name="pod_id" data-size="10"
                                 title="{{trans('forms.select')}}" required>
                                    @foreach ($ports as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('pod_id') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('pod_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="equipment_type_id">Equipment Type</label>
                                <select class="selectpicker form-control" id="equipment_type_id" data-live-search="true" name="equipment_type_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($equipment_types as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('equipment_type_id') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('equipment_type_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="validity_from">Validity From <span class="text-warning"> * (Required.) </span></label>
                                <input type="date" class="form-control" id="validity_from" name="validity_from" value="{{old('validity_from')}}"
                                     autocomplete="off" required>
                                @error('validity_from')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="validity_to">Validity To <span class="text-warning"> * (Required.) </span></label>
                                <input type="date" class="form-control" id="validity_to" name="validity_to" value="{{old('validity_to')}}"
                                     autocomplete="off" required>
                                @error('validity_to')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="Shipment">Terms Of Shipment </label>
                                    <input type="text" class="form-control" id="Shipment" name="term_of_shipment" value="{{old('term_of_shipment')}}"
                                 placeholder="Terms Of Shipment" autocomplete="off" >
                                @error('term_of_shipment')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="Slot">Slot Rate MTY</label>
                                    <input type="text" class="form-control" id="Slot" name="slot_rate_mty" value="{{old('slot_rate_mty')}}"
                                 placeholder="Slot Rate MTY" autocomplete="off" >
                                @error('slot_rate_mty')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label for="Baf">Baf For MTY/TEU</label>
                                    <input type="text" class="form-control" id="Baf" name="baf_for_mty" value="{{old('baf_for_mty')}}"
                                 placeholder="Baf For MTY/TEU" autocomplete="off" >
                                @error('baf_for_mty')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="EWRI">EWRI MTY/TEU</label>
                                    <input type="text" class="form-control" id="EWRI" name="ewri_mty" value="{{old('ewri_mty')}}"
                                 placeholder="EWRI MTY/TEU" autocomplete="off" >
                                @error('ewri_mty')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="Slot">Slot Rate LADEN</label>
                                    <input type="text" class="form-control" id="Slot" name="slot_rate_leden" value="{{old('slot_rate_leden')}}"
                                 placeholder="Slot Rate LADEN" autocomplete="off" >
                                @error('slot_rate_mty')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label for="Baf">Baf For LADEN/TEU</label>
                                    <input type="text" class="form-control" id="Baf" name="baf_for_leden" value="{{old('baf_for_leden')}}"
                                 placeholder="Baf For LADEN/TEU" autocomplete="off" >
                                @error('baf_for_leden')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label for="EWRI">EWRI LADEN/TEU</label>
                                    <input type="text" class="form-control" id="EWRI" name="ewri_leden" value="{{old('ewri_leden')}}"
                                 placeholder="EWRI LADEN/TEU" autocomplete="off" >
                                @error('ewri_leden')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="Reefer">Reefer SC Per Unit</label>
                                    <input type="text" class="form-control" id="Slot" name="reefer_sc" value="{{old('reefer_sc')}}"
                                 placeholder="Reefer SC Per Unit" autocomplete="off" >
                                @error('reefer_sc')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label for="IMCO">IMCO SC PER TEU</label>
                                    <input type="text" class="form-control" id="IMCO" name="imco_sc" value="{{old('imco_sc')}}"
                                 placeholder="IMCO SC PER TEU" autocomplete="off" >
                                @error('imco_sc')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label for="OOG">OOG Charges PER TEU</label>
                                    <input type="text" class="form-control" id="OOG" name="oog_char" value="{{old('oog_char')}}"
                                 placeholder="OOG Charges PER TEU" autocomplete="off" >
                                @error('oog_char')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="FLIEX">FLIEX SC PER TEU</label>
                                    <input type="text" class="form-control" id="FLIEX" name="flexi_sc" value="{{old('flexi_sc')}}"
                                 placeholder="FLIEX SC PER TEU" autocomplete="off" >
                                @error('flexi_sc')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label for="Slot">Remarks</label>
                                    <input type="text" class="form-control" id="Remarks" name="remarkes" value="{{old('remarkes')}}"
                                 placeholder="Remarks" autocomplete="off" >
                                @error('remarkes')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        
                       <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary mt-3">{{trans('forms.create')}}</button>
                                <a href="{{route('supplierPrice.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                            </div>
                       </div>


                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
