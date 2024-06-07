@extends('layouts.app')
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">

        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading"> 
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Operation </a></li>
                            <li class="breadcrumb-item"><a href="{{route('trucker.index')}}">Truckers</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);"> Edit</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                <form id="createForm" action="{{route('trucker.update',['trucker'=>$trucker])}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="nameInput">Company Name <span class="text-warning"> * (Required.) </span></label>
                        <input type="text" class="form-control" id="nameInput" name="company_name" value="{{old('company_name',$trucker->company_name)}}"
                                placeholder="Company Name " autocomplete="off" autofocus required>
                            @error('name')
                            <div style="color: red;">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="contactInput">Contact Person</label>
                            <input type="text" class="form-control" id="contact_personInput" name="contact_person" value="{{old('contact_person',$trucker->contact_person)}}"
                                placeholder="Contact Person" autocomplete="off">
                            @error('contact_person')
                            <div style="color: red;">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="mobileInput">Mobile</label>
                            <input type="text" class="form-control" id="mobileInput" name="mobile" value="{{old('mobile',$trucker->mobile)}}"
                                placeholder="Mobile" autocomplete="off">
                            @error('mobile')
                            <div style="color: red;">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="phoneInput">Phone</label>
                            <input type="text" class="form-control" id="phoneInput" name="phone" value="{{old('phone',$trucker->phone)}}"
                                placeholder="Phone" autocomplete="off">
                            @error('phone')
                            <div style="color: red;">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                    
                        <div class="form-group col-md-4">
                            <label for="emailInput">Email </label>
                            <input type="text" class="form-control" id="emailInput" name="email" value="{{old('email',$trucker->email)}}"
                                placeholder="Email" autocomplete="off">
                            @error('email')
                            <div style="color: red;">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="cityInput">Address</label>
                            <input type="text" class="form-control" id="addressInput" name="address" value="{{old('address',$trucker->address)}}"
                                placeholder="Address" autocomplete="off">
                            @error('address')
                            <div style="color: red;">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="tax">Tax</label>
                            <input type="text" class="form-control" id="tax" name="tax" value="{{old('tax',$trucker->tax)}}"
                                placeholder="Tax" autocomplete="off">
                            @error('tax')
                            <div style="color: red;">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                                <div class="custom-file-container" data-upload-id="certificat">
                                    <label> <span style="color:#3b3f5c";> File Upload </span><a href="javascript:void(0)" class="custom-file-container__image-clear" title="Clear Image"></a></label>
                                    <label class="custom-file-container__custom-file" >
                                        <input type="file" class="custom-file-container__custom-file__custom-file-input" name="certificat" accept="pdf">
                                        <input type="hidden" name="MAX_FILE_SIZE" disabled value="10485760" />
                                        <span class="custom-file-container__custom-file__custom-file-control"></span>
                                    </label>
                                    <div class="custom-file-container__image-preview"></div>
                            </div>
                                @error('certificat')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                    </div>

                    <table id="delegatedPerson" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Name <span class="text-warning"> * (Required) </span></th>
                                        <th>Degattion From <span class="text-warning"> * (Required) </span></th>
                                        <th>Degattion To <span class="text-warning"> * (Required) </span></th>
                                        <th>Id Number</th>
                                        <th>Mobile</th>  
                                        <th><a id="add"> Add <i class="fas fa-plus"></i></a> </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($trucker_person as $key => $item)
                                <tr>
                                <input type="hidden" value ="{{ $item->id }}" name="delegatedPerson[{{ $key }}][id]">
                                 
                                    <td>
                                        <input type="text" value ="{{ $item->name }}" name="delegatedPerson[{{ $key }}][name]" class="form-control" placeholder="Name">
                                    </td>
                                    <td>
                                        <input type="date" value ="{{ $item->degattion_from }}" name="delegatedPerson[{{ $key }}][degattion_from]" class="form-control" placeholder="Degattion From">
                                    </td>
                                    <td>
                                        <input type="date" value ="{{ $item->degattion_to }}" name="delegatedPerson[{{ $key }}][degattion_to]" class="form-control" placeholder="Degattion To">
                                    </td>
                                    <td>
                                        <input type="text" value ="{{ $item->id_number }}" name="delegatedPerson[{{ $key }}][id_number]" class="form-control" placeholder="Id Number">
                                    </td>
                                    <td>
                                        <input type="text" value ="{{ $item->mobile }}" name="delegatedPerson[{{ $key }}][mobile]" class="form-control" placeholder="Mobile">
                                    </td>
                                    <td style="width:85px;">
                                        <button type="button" class="btn btn-danger remove" onclick="removeItem({{$item->id}})"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                                    @endforeach
                                </tbody>
                    </table>
                            
                            <input name="removed" id="removed" type="hidden"  value="">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-primary mt-3">{{trans('forms.update')}}</button>
                            <a href="{{route('trucker.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
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
var removed = [];
function removeItem( item )
{
    removed.push(item);
    console.log(removed);
    document.getElementById("removed").value = removed;
}
$(document).ready(function(){
    $("#delegatedPerson").on("click", ".remove", function () {
    $(this).closest("tr").remove();
    });
    var counter  = <?= isset($key)? ++$key : 0 ?>;

    $("#add").click(function(){
            var tr = '<tr>'+
            '<td><input type="text" name="delegatedPerson['+counter+'][name]" class="form-control" autocomplete="off" placeholder="Name"></td>'+
            '<td><input type="date" name="delegatedPerson['+counter+'][degattion_from]" class="form-control" autocomplete="off" placeholder="Degattion From"></td>'+
            '<td><input type="date" name="delegatedPerson['+counter+'][degattion_to]" class="form-control" autocomplete="off" placeholder="Degattion To"></td>'+
            '<td><input type="text" name="delegatedPerson['+counter+'][id_number]" class="form-control" autocomplete="off" placeholder="Id Number"></td>'+
            '<td><input type="text" name="delegatedPerson['+counter+'][mobile]" class="form-control" autocomplete="off" placeholder="Mobile"></td>'+
            '<td style="width:85px;"><button type="button" class="btn btn-danger remove"><i class="fa fa-trash"></i></button></td>'

        '</tr>';
        counter++;
        $('#delegatedPerson').append(tr);
    });
});
</script>
@endpush
