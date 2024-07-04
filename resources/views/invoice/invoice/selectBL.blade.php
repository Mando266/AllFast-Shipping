@extends('layouts.app')
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a a href="{{route('invoice.index')}}">Invoice</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">New Debit Note</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                    <form id="createForm" action="{{route('invoice.create')}}" method="get">
                            @csrf
                            <form>
                    <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="Bldraft">BlDraft Number <span class="text-warning"> * (Required.) </span></label>
                                <select class="selectpicker form-control" id="Bldraft" data-live-search="true" name="bldraft_id" data-size="10"
                                 title="{{trans('forms.select')}}" required>
                                 <!-- <option value="0" >Customized Debit</option> -->
                                    @foreach ($bldrafts as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('bldraft_id',request()->input('bldraft_id')) ? 'selected':''}}>{{$item->ref_no}}</option>
                                    @endforeach
                                </select>
                            </div>
                    </div>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary mt-3">Next</button>
                                <a href="{{route('invoice.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

