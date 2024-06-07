@extends('layouts.app')
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">

        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Administration</a></li>
                            <li class="breadcrumb-item"><a href="{{route('roles.index')}}">Roles</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);"> {{trans('forms.edit')}}</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                <form id="createForm" action="{{route('roles.update',['role'=>$role])}}" method="POST">
                        @csrf
                        @method('put')
                        <div class="form-row mb-4">
                            <div class="form-group col-md-6">
                                <label for="roleName">{{trans('roles.name')}} *</label>
                            <input type="text" class="form-control" id="roleName" name="name" value="{{old('name',$role->name)}}"
                                 placeholder="{{trans('roles.name')}}" autocomplete="off" autofocus>
                                @error('name')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <hr/>
                        <h4 class="kt-section__title">{{trans('roles.role_permissions')}}:*</h4>
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width:350px;" class="align-middle">{{trans('roles.permission_name')}}</th>
                                            <th>{{trans('roles.permission_actions')}}
                                                <button type="button" id="selectAll" data-selected="0" class="btn btn-info btn-sm mx-1">Select All</button>
                                            </th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($permissions as $group=>$actions)
                                        <tr>
                                            <td class="text-left">{{ ucwords($group)}}</td>
                                            <td>
                                                @foreach ($actions as $action)
                                                <div class="n-chk">
                                                    <label class="new-control new-checkbox checkbox-danger">
                                                        <input type="checkbox" class="new-control-input"
                                                        name="permissions[]" data-name="{{ $action->name }}"
                                                        {{in_array($action->id,old('permissions',$rolePermissions)) ? 'checked' : ''}} value="{{ $action->id }}">
                                                        <span class="new-control-indicator"></span>{{$action->display_name}}
                                                    </label>
                                                </div>
                                                @endforeach
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                       <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary mt-3">{{trans('forms.update')}}</button>
                                <a href="{{route('roles.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
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
<link href="{{asset('plugins/sweetalerts/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('plugins/sweetalerts/sweetalert.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/css/components/custom-sweetalert.css')}}" rel="stylesheet" type="text/css" />
@endpush
@push('scripts')
    <script src="{{asset('plugins/sweetalerts/sweetalert2.min.js')}}"></script>
    <script src="{{asset('plugins/sweetalerts/custom-sweetalert.js')}}"></script>
    <script src="{{ asset('app/admin/role.js') }}"></script>
@endpush

