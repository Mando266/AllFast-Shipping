@extends('layouts.app')
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">

        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Master Data </a></li>
                            <li class="breadcrumb-item"><a href="{{route('vessels.index')}}">Vessels</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);"> {{trans('forms.edit')}}</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                <form id="createForm" action="{{route('vessels.update',['vessel'=>$vessel])}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        @if(session('alert'))
                                <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ session('alert') }}</p>
                            @endif
                        <div class="form-row">
                        <div class="form-group col-md-4">
                                <label for="CodeInput">Code <span class="text-warning"> * </span></label>
                                <input type="text" class="form-control" id="CodeInput" name="code" value="{{old('code',$vessel->code)}}"
                                    placeholder="Code" autocomplete="off" autofocus>
                                @error('Code')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="nameInput">Name <span class="text-warning"> * </span></label>
                            <input type="text" class="form-control" id="nameInput" name="name" value="{{old('name',$vessel->name)}}"
                                 placeholder="Name" autocomplete="off">
                                @error('name')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label for="call_signInput">Call Sign</label>
                                <input type="text" class="form-control" id="call_signInput" name="call_sign" value="{{old('call_sign',$vessel->call_sign)}}"
                                    placeholder="Call Sign" autocomplete="off">
                                @error('call_sign')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
  
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="imo_numberInput">IMO Number</label>
                            <input type="text" class="form-control" id="imo_numberInput" name="imo_number" value="{{old('imo_number',$vessel->imo_number)}}"
                                placeholder="IMO Number" autocomplete="off">
                            @error('imo_number')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                            @enderror
                        </div>

                        <div class="form-group col-md-4">
                            <label for="yearbuiltInput">Year Built</label>
                            <input type="text" class="form-control" id="yearbuiltInput" name="production_year" value="{{old('production_year',$vessel->production_year)}}"
                                placeholder="Year Built" autocomplete="off">
                            @error('production_year')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="mmsiInput">MMSI</label>
                            <input type="text" class="form-control" id="mmsiInput" name="mmsi" value="{{old('mmsi',$vessel->mmsi)}}"
                                placeholder="MMSI" autocomplete="off">
                            @error('mmsi')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                                <label for="countryInput">Flag</label>
                                <select class="selectpicker form-control" id="countryInput" data-live-search="true" name="country_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($countries as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('country_id',$vessel->country_id) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('country_id')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="gw_noInput">G.W</label>
                            <input type="text" class="form-control" id="gw_noInput" name="gw_no" value="{{old('gw_no',$vessel->gw_no)}}"
                                placeholder="G.W" autocomplete="off">
                            @error('gw_no')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="dwt_noInput">DWT</label>
                            <input type="text" class="form-control" id="dwt_noInput" name="dwt_no" value="{{old('dwt_no',$vessel->dwt_no)}}"
                                placeholder="DWT" autocomplete="off">
                            @error('dwt_no')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="total_teuInput">Total TEU</label>
                            <input type="text" class="form-control" id="total_teuInput" name="total_teu" value="{{old('total_teu',$vessel->total_teu)}}"
                                placeholder="Total TEU" autocomplete="off">
                            @error('total_teu')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="vessel_type_idInput">Vessel Type <span class="text-warning"> * </span></label>
                            <select class="selectpicker form-control" id="vessel_type_idInput" data-live-search="true" name="vessel_type_id" data-size="10"
                                title="{{trans('forms.select')}}" required>
                                @foreach ($vessel_types as $item)
                                    <option value="{{$item->id}}" {{$item->id == old('vessel_type_id',$vessel->vessel_type_id) ? 'selected':''}}>{{$item->name}}</option>
                                @endforeach
                            </select>
                            @error('vessel_type_id')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="operator_idInput">Vessel Operator <span class="text-warning"> * </span></label>
                            <select class="selectpicker form-control" id="operator_idInput" data-live-search="true" name="operator_id" data-size="10"
                                title="{{trans('forms.select')}}" required>
                                @foreach ($vesselOperators as $item)
                                    <option value="{{$item->id}}" {{$item->id == old('operator_id',$vessel->operator_id) ? 'selected':''}}>{{$item->name}}</option>
                                @endforeach
                            </select>
                            @error('operator_id')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                            <div class="form-group col-md-4">
                                <div class="custom-file-container" data-upload-id="certificat">
                                    <label> <span style="color:#3b3f5c";> Certificat </span><a href="javascript:void(0)" class="custom-file-container__image-clear" title="Clear Image"></a></label>
                                    <label class="custom-file-container__custom-file" >
                                        <input type="file" class="custom-file-container__custom-file__custom-file-input" name="certificat" value="{{old('certificat',$vessel->certificat)}}" accept="pdf">
                                        <input type="hidden" name="MAX_FILE_SIZE" disabled value="10485760" />
                                        <span class="custom-file-container__custom-file__custom-file-control"></span>
                                    </label>
                                    <div class="custom-file-container__image-preview"></div>
                                </div>
                            </div>
                            <div class="form-group col-md-8">
                                <label for="notes">Notes</label>
                                <textarea class="form-control" id="notes" name="notes" value="{{old('notes',$vessel->notes)}}"
                                 placeholder="Notes" autocomplete="off" autofocus></textarea>
                                @error('notes')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                    </div>

                       <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary mt-3">{{trans('forms.update')}}</button>
                                <a href="{{route('vessels.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                            </div>
                       </div>


                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
