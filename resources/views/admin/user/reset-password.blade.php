@extends('layouts.app')
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                <h5 class="">{{trans('home.reset_password')}}</h5>
                </div>
                <div class="widget-content widget-content-area">
                <form id="createForm" action="{{route('user.reset-password')}}" method="POST">
                        @csrf
                        @method('put')
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="passwordInput">{{trans('login.password')}}</label>
                                <input type="password" class="form-control" id="passwordInput" name="password"
                                    placeholder="{{trans('login.password')}}" autocomplete="off" autofocus>
                                @error('password')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="passwordConfirmInput">{{trans('login.password_confirmation')}}</label>
                                <input type="password" class="form-control" id="passwordConfirmInput" name="password_confirmation"
                                    placeholder="{{trans('login.password_confirmation')}}" autocomplete="off">
                            </div>
                        </div>

                       <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary mt-3">{{ __('Reset Password') }}</button>
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
