@extends('layouts.app')

@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-one">
                    <div class="widget-heading">
                        <nav class="breadcrumb-two" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Port Charge</a></li>
                                <li class="breadcrumb-item active"><a href="{{route('movements.index')}}">Invoice</a>
                                </li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                    </div>
                    <div class="widget-content widget-content-area">
                        <form action="{{ route('port-charge-invoices.do-export-date') }}" method="post">
                            @csrf
                            <div class="form-group col-md-12">
                                <div class="input-group">
                                    <div class="col-md-2">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text bg-transparent border-0"
                                                   for="from_date">
                                                From Date
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="date" class="form-control" id="from_date"
                                               name="from_date" value="{{old('from_date')}}"
                                               autocomplete="off" required>
                                    </div>
                                </div>
                                @error('invoice_date')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-12">
                                <div class="input-group">
                                    <div class="col-md-2">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text bg-transparent border-0"
                                                   for="to_date">
                                                To Date
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="date" class="form-control" id="to_date"
                                               name="to_date" value="{{old('to_date')}}"
                                               autocomplete="off" required>
                                    </div>
                                </div>
                                @error('invoice_date')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                                @error('invoices')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="row justify-content-center align-items-center">
                                <div class="text-center mx-4">
                                    <button type="submit" id="submit"
                                            class="btn btn-primary mt-3">{{ trans('forms.create') }}</button>
                                </div>
                                <div class="text-center">
                                    <a href="{{ route('port-charge-invoices.index') }}" class="btn btn-success mt-3">Go
                                        Back</a>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection