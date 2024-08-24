@extends('layouts.app')
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">

        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0);"> Master Data </a></li>
                            <li class="breadcrumb-item"><a href="{{route('terminals.index')}}"> Terminals</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);"> Add New Terminal</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                <form id="createForm" action="{{route('terminals.store')}}" method="POST">
                        @csrf
                            @if(session('alert'))
                                <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ session('alert') }}</p>
                            @endif
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="nameInput"> Name <span class="text-warning"> * </span></label>
                            <input type="text" class="form-control" id="nameInput" name="name" value="{{old('name')}}"
                                 placeholder="Name" autocomplete="off" autofocus required>
                                @error('name')
                                <div style="color:red">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="codeInput">Code <span class="text-warning"> * </span></label>
                                <input type="text" class="form-control" id="codeInput" name="code" value="{{old('code')}}"
                                    placeholder="Code" autocomplete="off" required>
                                @error('code')
                                <div style="color:red">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="countryInput">{{trans('company.country')}} <span class="text-warning"> * </span></label>
                                <select class="selectpicker form-control" id="country" data-live-search="true" name="country_id" data-size="10"
                                 title="{{trans('forms.select')}}" required>
                                    @foreach ($countries as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('country_id') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('country_id')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="portInput">Port Name <span class="text-warning"> * </span></label>
                                <select class="form-control" id="port" data-live-search="true" name="port_id" data-size="10" required>
                                                                     @foreach ($ports as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('port_id') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('port_id')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>

                       <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary mt-3">{{trans('forms.create')}}</button>
                                <a href="{{route('terminals.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
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
@endpush