@extends('layouts.app')
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">

        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                </div>
                <div class="widget-content widget-content-area">
                <form id="createForm" action="{{ route('profile.update', ['user' => $user->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label>Switch Line</label>
                                <select class="selectpicker form-control" id="company_id" data-live-search="true" name="company_id" data-size="10"
                                 title="{{trans('forms.select')}}" required>
                                    @foreach ($lines as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('company_id',$user->company_id) ? 'selected':''}}>{{$item->line_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="email">{{trans('user.email')}}</label>
                                <input type="text" class="form-control" id="email" name="email" value="{{$user->email}}" disabled >
                            </div>
                            <div class="form-group col-md-6">
                                <label for="fullName">{{trans('user.full_name')}}</label>
                                <input type="text" class="form-control" id="fullName"  value="{{$user->full_name}}" disabled>
                            </div>
                        </div> 

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="status">{{trans('user.status')}}</label>
                                <input type="text" class="form-control"  value="{{$user->is_active == "1" ? 'Enabled':'Disabled'}}" disabled>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="role">{{trans('user.role')}} <span class="text-warning"></label>
                                    <input type="text" class="form-control"  value="{{optional($user->roles->first())->name}}" disabled>

                            </div>
                        </div>
                        <hr/>

                       <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary mt-3">Switch</button>
                                <a href="{{route('home')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
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
<link href="{{asset('assets/css/elements/avatar.css')}}" rel="stylesheet" type="text/css" />
<style>
.avatar img {
    object-fit: scale-down;
}
.companies li{
    color: #212529;
}
</style>
@endpush

