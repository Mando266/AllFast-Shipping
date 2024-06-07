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
                            <li class="breadcrumb-item"><a href="{{route('users.index')}}">Users</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><a href="javascript:void(0);">{{trans('user.create_new')}}</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                <form novalidate id="createForm" action="{{route('users.store')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="agent_id">Agent <span class="text-warning"> * </span></label>
                                <select class="selectpicker form-control" id="agent_id" data-live-search="true" name="agent_id" data-size="10"
                                 title="{{trans('forms.select')}}" required>
                                    @foreach ($user_agent as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('agent_id') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('agent_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="userName">{{trans('user.user_name')}}  <span class="text-warning"> * ( between 4 to 30 characters without spaces.) </span></label>
                            <input type="text" class="form-control" id="userName" name="name" value="{{old('name')}}"
                                 placeholder="{{trans('user.user_name')}}" autocomplete="off" maxlength="30" required>
                                @error('name')
                                <div style="color:red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="fullName">{{trans('user.full_name')}} <span class="text-warning"> * </span></label>
                            <input type="text" class="form-control" id="fullName" name="full_name" value="{{old('full_name')}}"
                                 placeholder="{{trans('user.full_name')}}" autocomplete="off" maxlength="128" required>
                                @error('full_name')
                                <div style="color:red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="passwordInput">{{trans('login.password')}} <span class="text-warning"> * ( between 6 to 30 characters.) </span></label>
                                <input type="password" class="form-control" id="passwordInput" name="password" maxlength="30"
                                    placeholder="{{trans('login.password')}}" autocomplete="off" required>
                                @error('password')
                                <div style="color:red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="passwordConfirmInput">{{trans('login.password_confirmation')}} <span class="text-warning"> * </span></label>
                                <input type="password" class="form-control" id="passwordConfirmInput" name="password_confirmation" maxlength="30"
                                    placeholder="{{trans('login.password_confirmation')}}" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="form-row mb-4">
                            <div class="form-group col-md-6">
                                <label for="email">{{trans('user.email')}} <span class="text-warning"> * </span></label>
                            <input type="text" class="form-control" id="email" name="email" value="{{old('email')}}" maxlength="128"
                                 placeholder="{{trans('user.email')}}" autocomplete="off" required>
                                @error('email')
                                <div style="color:red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="empCode">{{trans('user.employee_no')}}</label>
                            <input type="text" class="form-control" id="empCode" name="employee_no" value="{{old('employee_no')}}"
                                 placeholder="{{trans('user.employee_no')}}" autocomplete="off" maxlength="15" >
                                @error('employee_no')
                                <div style="color:red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <hr/>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="ownership"> Container Ownership <span class="text-warning"> * </span></label>
                                <select class="selectpicker form-control" id="ownership" data-live-search="true"
                                        name="lessor_id[][id]" data-size="10"
                                        title="{{trans('forms.select')}}"  multiple="multiple" required>
                                    <option value="0">All</option>
                                    @foreach ($lessors as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('lessor_id') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('lessor_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label>SOC/COC <span class="text-warning"> * </span></label>
                                <select class="selectpicker show-tick form-control" id="soc_coc" data-live-search="true" name="soc_coc" title="{{trans('forms.select')}}" required>
                                    <option value="">All</option>
                                    <option value="SOC">SOC</option>
                                    <option value="COC">COC</option>
                                </select>
                                @error('soc_soc')
                                <div style="color:red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="ownership"> Container Ownership Type <span class="text-warning"> * </span></label>
                                <select class="selectpicker form-control" id="ownershipType" data-live-search="true"
                                        name="container_ownership_type[][id]" data-size="10"
                                        title="{{trans('forms.select')}}"  multiple="multiple">
                                    <option value="0">All</option>
                                    @foreach ($container_ownership as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('container_ownership_type') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('container_ownership_type')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="role">{{trans('user.role')}} <span class="text-warning"> (user will be disabled if no role is selected)</span></label>
                                <select class="selectpicker show-tick form-control" id="role" data-live-search="true" name="role" title="{{trans('forms.select')}}">
                                    @foreach ($roles as $item)
                                    <option value="{{$item->id}}" {{$item->id == old('role') ? 'selected' :''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('role')
                                <div style="color:red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="status">{{trans('user.status')}}</label>
                                <select class="selectpicker form-control"  name="is_active">
                                    <option value="1" {{ old('status') == "1" ? 'selected':'' }}>Enabled</option>
                                    <option value="0" {{ old('status') == "0" ? 'selected':'' }}>Disabled</option>
                                </select>
                                @error('status')
                                <div style="color:red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>

                        </div>
                        <hr/>

                       <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary mt-3">{{trans('forms.create')}}</button>
                                <a href="{{route('users.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
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
<link href="{{asset('plugins/file-upload/file-upload-with-preview.min.css')}}" rel="stylesheet" type="text/css" />
<style>
    .custom-file-container label {
    color: #3b3f5c;
}
.custom-file-container__image-preview{
    display: none;
}
</style>
@endpush
@push('scripts')
    <script>
        $('#ownershipType').change(function () {
                var selectedValue = $(this).val();
                if (selectedValue.length > 1 && selectedValue.includes('0')) {
                    selectedValue = selectedValue.filter(function (value) {
                        return value !== '0';
                    });
                    $(this).val(selectedValue);
                }

                if (selectedValue.includes('0')) {
                    $('#ownershipType option:not(:selected)').prop('disabled', true);
                } else {
                    $('#ownershipType option').prop('disabled', false);
                }
                $('#ownershipType').selectpicker('refresh');
            });
    </script>
    <script>
        $('#ownership').change(function () {
                var selectedValue = $(this).val();
                if (selectedValue.length > 1 && selectedValue.includes('0')) {
                    selectedValue = selectedValue.filter(function (value) {
                        return value !== '0';
                    });
                    $(this).val(selectedValue);
                }

                if (selectedValue.includes('0')) {
                    $('#ownership option:not(:selected)').prop('disabled', true);
                } else {
                    $('#ownership option').prop('disabled', false);
                }
                $('#ownership').selectpicker('refresh');
            });
    </script>
    <script src="{{asset('plugins/sweetalerts/sweetalert2.min.js')}}"></script>
    <script src="{{asset('plugins/sweetalerts/custom-sweetalert.js')}}"></script>
    <script src="{{ asset('plugins/file-upload/file-upload-with-preview.min.js')}}"></script>
    <script src="{{ asset('app/admin/user.js') }}"></script>
    <script>
        var firstUpload = new FileUploadWithPreview('avatar',{
            images: {
                    baseImage: '{{asset('assets/img/profile.png')}}',
                }
        })
    </script>
@endpush

