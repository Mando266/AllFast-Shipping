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
                            <li class="breadcrumb-item"><a href="{{route('customers.index')}}">Customers</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);"> Customer Show</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                <form id="createForm" action="{{route('customers.edit',['customer'=>$customer])}}" method="get">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="nameInput">Name</label>
                                <input type="text" class="form-control" id="nameInput"  value="{{$customer->name}}" disabled>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="contactInput">Contact Person</label>
                                <input type="text" class="form-control" id="contactInput"  value="{{$customer->contact_person}}" disabled>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="codeInput">Code</label>
                                <input type="text" class="form-control" id="codeInput"  value="{{$customer->code}}" disabled>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="cityInput">City</label>
                                <input type="text" class="form-control" id="cityInput" value="{{$customer->city}}" disabled>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="address">Address Line 1 </label>
                                <input type="text" class="form-control" id="address" value="{{$customer->address}}" disabled>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="cust_address">Address Line 2 </label>
                                <input type="text" class="form-control" id="cust_address" value="{{$customer->cust_address}}" disabled>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="phoneInput">Phone</label>
                                <input type="text" class="form-control" id="phoneInput" value="{{$customer->phone}}" disabled>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="landline">landline</label>
                                <input type="text" class="form-control" id="landline" value="{{$customer->landline}}" disabled>
                            </div>
                            
                            <div class="form-group col-md-4">
                                <label for="emailInput">Email</label>
                                <input type="text" class="form-control" id="emailInput" value="{{$customer->email}}" disabled>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="emailInput">Tax Card No</label>
                            <input type="text" class="form-control" id="emailInput" value="{{$customer->tax_card_no}}" disabled>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="cityInput">Sales Person</label>
                                <input type="text" class="form-control" id="cityInput" value="{{optional($customer->User)->name}}" disabled>
                            </div>

                            <div class="form-group col-md-4">
                                    <label for="fax">Fax</label>
                                <input type="text" class="form-control" id="fax" value="{{$customer->Fax}}" disabled>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="Currency">Currency</label>
                            <input type="text" class="form-control" id="Currency" value="{{$customer->currency}}" disabled>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="cityInput">Other Currency</label>
                                <input type="text" class="form-control" id="cityInput" value="{{$customer->othercurrency}}" disabled>
                            </div>

                            <div class="form-group col-md-4">
                                    <label for="fax">Customer Website Url</label>
                                <input type="text" class="form-control" id="fax" value="{{$customer->customers_website}}" disabled>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="notes">Note</label>
                                <input type="text" class="form-control" id="notes" value="{{$customer->notes}}"
                                    placeholder="Note" autocomplete="off" disabled>
                            </div>  
                        </div>
                        <table id="customerRole" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>charge type</th>
                                        <th>
                                            <a id="add"> Add <i class="fas fa-plus"></i></a>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($customer_roles as $key => $item)
                                <tr>
                                <input type="hidden" value ="{{ $item->id }}" name="customerRole[{{ $key }}][id]">
                                 
                                    <td>
                                        <select class="selectpicker form-control" id="currency" data-live-search="true" name="customerRole[{{$key}}][role_id]" data-size="10"
                                        title="{{trans('forms.select')}}" disabled>
                                            @foreach ($roles as $role)
                                                <option value="{{$role->id}}" {{$role->id == old('role_id',$item->role_id)? 'selected':''}}>{{$role->name}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                
                                    <td style="width:85px;">
                                    </td>
                                </tr>
                                    @endforeach
                                </tbody>
                        </table>
                       <div class="row">
                            <div class="col-md-12 text-center">
                                @permission('Customer-Edit')
                                <button type="submit" class="btn btn-primary mt-3">{{trans('forms.edit')}}</button>
                                @endpermission
                                <a href="{{route('customers.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                            </div>
                       </div>


                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('styles')
    <link href="{{asset('plugins/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css" >
@endpush
@push('scripts')
<script src="{{asset('plugins/bootstrap-select/bootstrap-select.min.js')}}"></script>
@endpush
