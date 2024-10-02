@extends('layouts.app')
@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-one">
                    <div class="widget-heading">
                        <nav class="breadcrumb-two" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Tariffs</a></li>
                                <li class="breadcrumb-item active"><a href="javascript:void(0);">Demurrage & Dentention</a></li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                        @permission('Demurrage-Create')
                        <div class="row">
                            <div class="col-md-12 text-right mb-5">
                            <a href="{{route('demurrage.create')}}" class="btn btn-primary">Add New Demurrage & Dentention</a>
                            </div>
                        </div>
                        @endpermission
                    </div>
                    <form>
                        <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="countryInput">Tariffs</label>
                                    <select class="selectpicker form-control" id="id" data-live-search="true" name="id" data-size="10"
                                     title="{{trans('forms.select')}}">
                                        @foreach ($demurrage as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('id',request()->input('id')) ? 'selected':''}}>{{$item->is_storge}} {{{optional($item->bound)->name}}} {{{optional($item->ports)->code}}} {{{optional($item->containersType)->name}}}</option>
                                        @endforeach
                                    </select>
                                    @error('Triff_id')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="countryInput">Ports</label>
                                    <select class="selectpicker form-control" id="countryInput" data-live-search="true" name="port_id" data-size="10"
                                        title="{{trans('forms.select')}}">
                                        @foreach ($ports as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('port_id',request()->input('port_id')) ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="is_storge">Detention OR Storage</label>
                                    <select class="selectpicker form-control" id="is_storge" data-live-search="true" name="is_storge" data-size="10"
                                    title="{{trans('forms.select')}}" autofocus>
                                            <option value="EXPORT/DETENTION">EXPORT/DETENTION</option>
                                            <option value="IMPORT/DETENTION">IMPORT/DETENTION</option>
                                            <option value="EXPORT/STORAGE">EXPORT/STORAGE</option>
                                            <option value="IMPORT/STORAGE">IMPORT/STORAGE</option>
                                    </select>
                                    @error('is_storge')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12 text-center">
                                <button  type="submit" class="btn btn-success mt-3">Search</button>
                                <a href="{{route('demurrage.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                            </div>
                    </form>
                        <div class="widget-content widget-content-area">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-condensed mb-4">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Tariff Ref No</th>
                                            <th>validity from</th>
                                            <th>validity to</th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($items as $item)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{{optional($item->tarriffType)->description}}} - {{{optional($item->ports)->code}}} - {{$item->tariff_id}}</td>
                                            <td>{{$item->validity_from}}</td>
                                            <td>{{$item->validity_to}}</td>
                                           <td class="text-center">
                                               <ul class="table-controls">
                                                   @permission('Demurrage-Show')
                                                   <li>
                                                        <a href="{{route('demurrage.show',['demurrage'=>$item->id])}}" data-toggle="tooltip" data-placement="top" title="" data-original-title="show">
                                                            <i class="far fa-eye text-primary"></i>
                                                        </a>
                                                    </li>
                                                    @endpermission
                                                    @permission('Demurrage-Edit')
                                                    <li>
                                                        <a href="{{route('demurrage.edit',['demurrage'=>$item->id])}}" data-toggle="tooltip" data-placement="top" title="" data-original-title="edit">
                                                            <i class="far fa-edit text-success"></i>
                                                        </a>
                                                    </li>
                                                    @endpermission
                                                </ul>
                                           </td>
                                            <td class="text-center">
                                                <ul class="table-controls">
                                                    @permission('Demurrage-Delete')
                                                    <li>
                                                        <form action="{{route('demurrage.destroy',['demurrage'=>$item->id])}}" method="post">
                                                            @method('DELETE')
                                                            @csrf
                                                        <button style="border: none; background: none;" type="submit" class="fa fa-trash text-danger"></button>
                                                        </form>
                                                    </li>
                                                    @endpermission
                                                </ul>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr class="text-center">
                                            <td colspan="20">{{ trans('home.no_data_found')}}</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
