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
                                <li class="breadcrumb-item"><a href="{{route('suppliers.index')}}">Suppliers</a></li>
                                <li class="breadcrumb-item active"><a
                                            href="javascript:void(0);"> {{trans('forms.edit')}}</a></li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                    </div>
                    <div class="widget-content widget-content-area">
                        <form id="createForm" action="{{route('suppliers.update',['supplier'=>$supplier])}}"
                              method="POST">
                            @csrf
                            @method('put')
                            @if(session('alert'))
                                <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ session('alert') }}</p>
                            @endif
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="nameInput">Name *</label>
                                    <input type="text" class="form-control" id="nameInput" name="name"
                                           value="{{ old('name', $supplier->name) }}"
                                           placeholder="Name" autocomplete="disabled" autofocus>
                                    @error('name')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="countryInput">{{trans('company.country')}}</label>
                                    <select class="selectpicker form-control" id="countryInput" data-live-search="true"
                                            name="country_id" data-size="10"
                                            title="{{ trans('forms.select') }}">
                                        @foreach ($countries ?? [] as $item)
                                            <option value="{{ $item->id }}" {{ $item->id == old('country_id', $supplier->country_id) ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('country_id')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="addressInput">Address *</label>
                                    <input type="text" class="form-control" id="addressInput" name="address_line_1"
                                           value="{{ old('address_line_1', $supplier->address_line_1) }}"
                                           placeholder="Address" required>
                                    @error('address_line_1')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="phoneInput">Phone *</label>
                                    <input type="text" class="form-control" id="phoneInput" name="phone"
                                           value="{{old('phone', $supplier->phone)}}"
                                           placeholder="Phone" required>
                                    @error('phone')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="emailInput">Email *</label>
                                    <input type="email" class="form-control" id="emailInput" name="email"
                                           value="{{old('email', $supplier->email)}}"
                                           placeholder="Email" required>
                                    @error('email')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="taxCardInput">Tax Card</label>
                                    <input type="text" class="form-control" id="taxCardInput" name="tax_card"
                                           value="{{old('tax_card', $supplier->tax_card)}}"
                                           placeholder="Tax Card">
                                    @error('tax_card')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="currencyInput">Currency</label>
                                    <select class="selectpicker form-control" id="currencyInput" data-live-search="true"
                                            name="currency_id" data-size="10"
                                            title="{{trans('forms.select')}}">
                                        @foreach($currencies as $currency)
                                            <option value="{{$currency->id}}" {{$currency->id == old('currency_id', $supplier->currency_id) ? 'selected':''}}>{{$currency->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('currency_id')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="secondaryCurrencyInput">Secondary Currency</label>
                                    <select class="selectpicker form-control" id="secondaryCurrencyInput"
                                            data-live-search="true" name="secondary_currency_id" data-size="10"
                                            title="{{trans('forms.select')}}">
                                        @foreach($currencies as $currency)
                                            <option value="{{$currency->id}}" {{$currency->id == old('secondary_currency_id',$supplier->secondary_currency_id) ? 'selected':''}}>{{$currency->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('secondary_currency_id')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="noteInput">Note</label>
                                    <textarea class="form-control" id="noteInput" name="note" rows="3"
                                              placeholder="Note">{{old('note', $supplier->note)}}</textarea>
                                    @error('note')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="supplierTypeInput">Supplier Type</label>
                                    <div>
                                        <input type="hidden" name="is_container_depot" value="0">
                                        <input type="checkbox" id="isContainerDepotInput" name="is_container_depot"
                                               value="1" {{ !$supplier->is_container_depot ?: 'checked' }}>
                                        <label for="isContainerDepotInput">Container Depot</label>
                                    </div>
                                    <div>
                                        <input type="hidden" name="is_container_services_provider" value="0">
                                        <input type="checkbox" id="isContainerServicesProviderInput"
                                               name="is_container_services_provider"
                                               value="1" {{ !$supplier->is_container_services_provider ?: 'checked' }}>
                                        <label for="isContainerServicesProviderInput">Container Services
                                            Provider</label>
                                    </div>
                                    <div>
                                        <input type="hidden" name="is_container_seller" value="0">
                                        <input type="checkbox" id="isContainerSellerInput" name="is_container_seller"
                                               value="1" {{ !$supplier->is_container_seller ?: 'checked' }}>
                                        <label for="isContainerSellerInput">Container Seller</label>
                                    </div>
                                    <div>
                                        <input type="hidden" name="is_container_trucker" value="0">
                                        <input type="checkbox" id="isContainerTruckerInput" name="is_container_trucker"
                                               value="1" {{ !$supplier->is_container_trucker ?: 'checked' }}>
                                        <label for="isContainerTruckerInput">Container Trucker</label>
                                    </div>
                                    <div>
                                        <input type="hidden" name="is_container_lessor" value="0">
                                        <input type="checkbox" id="isContainerLessorInput" name="is_container_lessor"
                                               value="1" {{ !$supplier->is_container_lessor ?: 'checked' }}>
                                        <label for="isContainerLessorInput">Container Lessor</label>
                                    </div>
                                    <div>
                                        <input type="hidden" name="is_container_haulage" value="0">
                                        <input type="checkbox" id="isContainerHaulageInput" name="is_container_haulage"
                                               value="1" {{ !$supplier->is_container_haulage ?: 'checked' }}>
                                        <label for="isContainerHaulageInput">Container Haulage</label>
                                    </div>
                                    <div>
                                        <input type="hidden" name="is_container_terminal" value="0">
                                        <input type="checkbox" id="isContainerTerminalInput"
                                               name="is_container_terminal"
                                               value="1" {{ !$supplier->is_container_terminal ?: 'checked' }}>
                                        <label for="isContainerTerminalInput">Container Terminal</label>
                                    </div>
                                    @error('supplier_type')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <x-contact-people-table :contact-people="$contactPeople"></x-contact-people-table>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="submit"
                                            class="btn btn-primary mt-3">{{trans('forms.update')}}</button>
                                    <a href="{{route('suppliers.index')}}"
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