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
                                <li class="breadcrumb-item"><a href="{{route('containers.index')}}">Containers</a></li>
                                <li class="breadcrumb-item active"><a
                                            href="javascript:void(0);"> {{trans('forms.edit')}}</a></li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                    </div>
                    <div class="widget-content widget-content-area">
                        <form id="createForm" action="{{route('containers.update',['container'=>$container])}}"
                              method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            @if(session('alert'))
                                <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ session('alert') }}</p>
                            @endif
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="countryInput">Container Type</label>
                                    <select class="selectpicker form-control" id="countryInput" data-live-search="true"
                                            name="container_type_id" data-size="10"
                                            title="{{trans('forms.select')}}">
                                        @foreach ($container_types as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('container_type_id',$container->container_type_id) ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('container_type_id')
                                    <div style="color: red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="codeInput">Container Number</label>
                                    <input type="text" class="form-control container-number" id="codeInput" name="code"
                                           value="{{old('code',$container->code)}}"
                                           placeholder="Container Numbere" autocomplete="off">
                                    @error('code')
                                    <div style="color: red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="countryInput"> Container Ownership Type</label>
                                    <select class="selectpicker form-control" id="countryInput" data-live-search="true"
                                            name="container_ownership_id" data-size="10"
                                            title="{{trans('forms.select')}}">
                                        @foreach ($container_ownership as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('container_ownership_id',$container->container_ownership_id) ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('container_ownership_id')
                                    <div style="color: red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="tar_weightInput">Tare Weight</label>
                                    <input type="text" class="form-control" id="tar_weightInput" name="tar_weight"
                                           value="{{old('tar_weight',$container->tar_weight)}}"
                                           placeholder="Tare Weight" autocomplete="off">
                                    @error('tar_weight')
                                    <div style="color: red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="max_payloadInput">Max Payload</label>
                                    <input type="text" class="form-control" id="max_payloadInput" name="max_payload"
                                           value="{{old('max_payload',$container->max_payload)}}"
                                           placeholder="Max Payload" autocomplete="off">
                                    @error('max_payload')
                                    <div style="color: red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="production_yearInput">Production Year</label>
                                    <input type="text" class="form-control" id="production_yearInput"
                                           name="production_year"
                                           value="{{old('production_year',$container->production_year)}}"
                                           placeholder="Production Year" autocomplete="off">
                                    @error('production_year')
                                    <div style="color: red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="countryInput">Container Ownership</label>
                                    <select class="selectpicker form-control" id="countryInput" data-live-search="true"
                                            name="description" data-size="10"
                                            title="{{trans('forms.select')}}">
                                        @foreach ($sellers as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('description',$container->description) ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('description')
                                    <div style="color: red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="isoInput">Iso</label>
                                    <input type="text" class="form-control" id="isoInput" name="iso"
                                           value="{{old('iso',$container->iso)}}"
                                           placeholder="Iso" autocomplete="off">
                                    @error('iso')
                                    <div style="color: red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="soc-coc-Input"> SOC/COC </label>
                                    <select class="selectpicker form-control" id="soc-coc-select"
                                            data-live-search="true" name="SOC_COC" data-size="10"
                                            title="{{trans('forms.select')}}">
                                        <option value="SOC" {{ ($container->SOC_COC != "SOC") ?: 'selected' }}>SOC</option>
                                        <option value="COC" {{ ($container->SOC_COC != "COC") ?: 'selected' }}>COC</option>
                                    </select>
                                    @error('soc-coc')
                                    <div style="color: red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-8 form-group">
                                    <label> Notes </label>
                                    <textarea class="form-control" name="notes">{{old('notes',$container->notes)}}</textarea>
                                </div>
                                <div class="form-group col-md-4">
                                    <div class="custom-file-container" data-upload-id="certificat">
                                        <label> <span style="color:#3b3f5c" ;> Certificate </span><a
                                                    href="javascript:void(0)" class="custom-file-container__image-clear"
                                                    title="Clear Image"></a></label>
                                        <label class="custom-file-container__custom-file">
                                            <input type="file"
                                                   class="custom-file-container__custom-file__custom-file-input"
                                                   name="certificat"
                                                   value="{{old('certificat',$container->certificat)}}" accept="pdf">
                                            <input type="hidden" name="MAX_FILE_SIZE" disabled value="10485760"/>
                                            <span class="custom-file-container__custom-file__custom-file-control"></span>
                                        </label>
                                        <div class="custom-file-container__image-preview"></div>
                                    </div>
                                </div>
                            </div>
                            <!-- <hr/>
                        <table id="containerRepairs" class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Invoice NO</th>
                                    <th>Invoice Date</th>
                                    <th>supplier</th>
                                    <th>Part Code</th>
                                    <th>Description</th>
                                    <th>Repair Date</th>
                                    <th>qty</th>
                                    <th>Price</th>
                                    <th>Hours</th>
                                    <th>Labor</th>
                                    <th>Total</th>
                                    <th>
                                        <a id="add"> Add <i class="fas fa-plus"></i></a>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($container_repairs as $key => $item)
                                    <tr>
                                        <input type="hidden" value="{{ $item->id }}"
                                               name="containerRepairs[{{ $key }}][id]">
                                        <td>
                                            <input type="text" name="containerRepairs[{{$key}}][invoice_no]" class="form-control" autocomplete="off" 
                                                placeholder="Invoiceb No" value="{{old('invoice_no',$item->invoice_no)}}" required>
                                        </td>
                                        <td>
                                            <input type="date" name="containerRepairs[{{$key}}][invoice_date]" class="form-control" autocomplete="off" 
                                                placeholder="Invoice Date" value="{{old('invoice_date',$item->invoice_date)}}" required>
                                        </td>
                                        <td>
                                            <input type="text" name="containerRepairs[{{$key}}][supplier]" class="form-control" autocomplete="off" 
                                                placeholder="Supplier" value="{{old('supplier',$item->supplier)}}">
                                        </td>
                                        <td>
                                            <input type="text" name="containerRepairs[{{$key}}][part_code]" class="form-control" autocomplete="off" 
                                                placeholder="Part Code" value="{{old('part_code',$item->part_code)}}">
                                        </td>
                                        <td>
                                            <input type="text" name="containerRepairs[{{$key}}][part_description]" class="form-control" autocomplete="off" 
                                                placeholder="Part Description" value="{{old('part_description',$item->part_description)}}">
                                        </td>
                                        <td>
                                            <input type="date" name="containerRepairs[{{$key}}][repair_date]" class="form-control" autocomplete="off" 
                                                placeholder="Repair Date" value="{{old('repair_date',$item->repair_date)}}">
                                        </td>
                                        <td>
                                            <input type="text" name="containerRepairs[{{$key}}][qty]" class="form-control" autocomplete="off" 
                                                placeholder="Qty" value="{{old('qty',$item->qty)}}">
                                        </td>                                        
                                        <td>
                                            <input type="text" name="containerRepairs[{{$key}}][price]" class="form-control" autocomplete="off" 
                                                placeholder="Price" value="{{old('price',$item->price)}}">
                                        </td>                                        
                                        <td>
                                            <input type="text" name="containerRepairs[{{$key}}][hours]" class="form-control" autocomplete="off" 
                                                placeholder="Hours" value="{{old('hours',$item->hours)}}">
                                        </td>                                        
                                        <td>
                                            <input type="text" name="containerRepairs[{{$key}}][labor]" class="form-control" autocomplete="off" 
                                                placeholder="Labor" value="{{old('labor',$item->labor)}}">
                                        </td>                                        
                                        <td>
                                            <input type="text" name="containerRepairs[{{$key}}][total]" class="form-control" autocomplete="off" 
                                                placeholder="Total" value="{{old('total',$item->total)}}">
                                        </td>
                                        <td style="width:85px;">
                                            <button type="button" class="btn btn-danger remove"
                                                    onclick="removeItem({{$item->id}})"><i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                        </table>
                            <input name="removed" id="removed" type="hidden" value=""> -->

                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="submit"
                                            class="btn btn-primary mt-3">{{trans('forms.update')}}</button>
                                    <a href="{{route('containers.index')}}"
                                       class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
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
    @include('master.containers._validate')
@endpush
@push('styles')
    <style>
        .input-container {
            display: flex;
        }

        .input-container input {
            flex: 1;
            margin-right: 5px;
        }

        .mySpan::after {
            content: "✖";
            color: red;
            visibility: hidden;
        }

        .invalid + span::after {
            content: "✖";
            color: red;
            visibility: visible;
        }

        .valid + span::after {
            content: "✓";
            color: green;
            visibility: visible;

        }


    </style>
@endpush
<!-- @push('scripts')
    <script>
        var removed = [];
        function removeItem( item )
        {
            removed.push(item);
            console.log(removed);
            document.getElementById("removed").value = removed;
        }
        $(document).ready(function(){
            $("#containerRepairs").on("click", ".remove", function () {
            $(this).closest("tr").remove();
            });
            var counter  = <?= isset($key)? ++$key : 0 ?>;

            $("#add").click(function(){
                var tr = '<tr>'+
                '<td><input type="text" name="containerRepairs['+counter+'][invoice_no]" class="form-control" autocomplete="off" placeholder="Invoice oN"></td>'+
                '<td><input type="date" name="containerRepairs['+counter+'][invoice_date]" class="form-control" autocomplete="off" placeholder="Invoice Date"></td>'+
                '<td><input type="text" name="containerRepairs['+counter+'][supplier]" class="form-control" autocomplete="off" placeholder="Supplier"></td>'+
                '<td><input type="text" name="containerRepairs['+counter+'][part_code]" class="form-control" autocomplete="off" placeholder="Part Code"></td>'+
                '<td><input type="text" name="containerRepairs['+counter+'][part_description]" class="form-control" autocomplete="off" placeholder="Description"></td>'+
                '<td><input type="date" name="containerRepairs['+counter+'][repair_date]" class="form-control" autocomplete="off" placeholder="Repair Date"></td>'+
                '<td><input type="text" name="containerRepairs['+counter+'][qty]" class="form-control" autocomplete="off" placeholder="Qty"></td>'+
                '<td><input type="text" name="containerRepairs['+counter+'][price]" class="form-control" autocomplete="off" placeholder="Price"></td>'+
                '<td><input type="text" name="containerRepairs['+counter+'][hours]" class="form-control" autocomplete="off" placeholder="Hours"></td>'+
                '<td><input type="text" name="containerRepairs['+counter+'][labor]" class="form-control" autocomplete="off" placeholder="Labor"></td>'+
                '<td><input type="text" name="containerRepairs['+counter+'][total]" class="form-control" autocomplete="off" placeholder="Total"></td>'+
                '<td style="width:85px;"><button type="button" class="btn btn-danger remove"><i class="fa fa-trash"></i></button></td>'
                '</tr>';
                counter++;
                $('#containerRepairs').append(tr);
            });
        });
    </script>
@endpush -->