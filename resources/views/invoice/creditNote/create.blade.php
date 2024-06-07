@extends('layouts.app')
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a a href="{{route('creditNote.index')}}">Credit Note</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">New Credit Note</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
           
                    <form id="createForm" action="{{route('creditNote.store')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="ref_no">Ref No</label>
                                        <input type="text" class="form-control" id="credit_no" name="credit_no" value="{{old('credit_no')}}"
                                            placeholder="Ref No" autocomplete="off"> 
                                </div>
                                <div class="form-group col-md-8">
                                    <label for="bl_no">BL NO (Optional)</label>
                                    <select class="selectpicker form-control" id="bl_no" data-live-search="true" name="bl_no" data-size="10"
                                     title="{{trans('forms.select')}}" >
                                     @foreach($blnos as $blno)
                                            <option value="{{$blno->id}}" {{$blno->id == old('bl_no',request()->input('bl_no')) ? 'selected':''}}>{{$blno->ref_no}}</option>
                                    @endforeach
                                    </select>
                                    @error('bl_no')
                                        <div style="color:red;">
                                            {{$message}}
                                        </div>
                                    @enderror
                                </div> 
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-10">
                                    <label for="Customer">Customer</label>
                                    <select class="selectpicker form-control" id="customer" data-live-search="true" name="customer_id" data-size="10"
                                     title="{{trans('forms.select')}}" >
                                     @foreach($customers as $customer)
                                            <option value="{{$customer->id}}" {{$customer->id == old('customer_id',request()->input('customer_id')) ? 'selected':''}}>{{$customer->name}}</option>
                                    @endforeach
                                    </select>
                                    @error('customer_id')
                                        <div style="color:red;">
                                            {{$message}}
                                        </div>
                                    @enderror
                                </div>   
                        
                       
                                <div class="form-group col-md-2">
                                    <div style="padding: 30px;">
                                        <input class="form-check-input" type="radio" name="currency" id="currency" value="credit_usd" checked>
                                        <label class="form-check-label" for="currency">
                                          USD
                                        </label>
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        <input class="form-check-input" type="radio" name="currency" id="currency" value="credit_egp">
                                        <label class="form-check-label" for="currency">
                                          EGP
                                        </label> 
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-12 form-group">
                                    <label> Notes </label>
                                    <textarea class="form-control" name="notes" value="{{old('notes')}}"></textarea>
                                </div> 
                            </div>
                            @error('creditNoteDesc')
                                <div style="color:red;">
                                    {{$message}}
                                </div>
                            @enderror
                            <table id="credit" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">Description</th>
                                        <th class="text-center">Amount</th>
                                        <th class="text-center"><a id="add"> Add <i class="fas fa-plus"></i></a></th>
                                    </tr>
                                </thead>
                            </table> 
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-primary mt-3">{{trans('forms.create')}}</button>
                                    <a href="{{route('creditNote.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                                </div>
                           </div>
                    </form>                 
                </div> 
          
                                
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    $(document).ready(function(){
        $("#credit").on("click", ".remove", function () {
            $(this).closest("tr").remove();
        });
        var counter  = 1;
        $("#add").click(function(){
           var tr = '<tr>'+
               '<td><input type="text" name="creditNoteDesc['+counter+'][description]" class="form-control" autocomplete="off" placeholder="Description" required></td>'+
               '<td><input type="text" name="creditNoteDesc['+counter+'][amount]" class="form-control" autocomplete="off" placeholder="Amount" required></td>'+
               '<td style="width:85px;"><button type="button" class="btn btn-danger remove"><i class="fa fa-trash"></i></button></td>'
           '</tr>';
           counter++;
          $('#credit').append(tr);
        });
    });
    </script>
@endpush

