@extends('layouts.auth')
@section('content')
    <h6 class="">{{trans('login.login_to')}} <span class="brand-name"> Deja Tech Shipping System </span></h6>
    <form class="text-left" action="{{route('login')}}" method="post">
        @csrf
        <div class="form">
            <div id="email-field" class="field-wrapper input">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                <input id="email" name="email" type="text" class="form-control" placeholder="{{trans('Email')}}" autocomplete="off">
                @error('email')
                <div style="color:red;">
                    {{$message}}
                </div>
                @enderror
            </div>

            <div id="password-field" class="field-wrapper input mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                <input id="password" name="password" type="password" class="form-control" placeholder="{{trans('login.password')}}">
                @error('password')
                <div style="color:red;">
                    {{$message}}
                </div>
                @enderror
            </div>
            <div class="d-sm-flex justify-content-between">
                <div class="field-wrapper">
                    <div class="n-chk new-checkbox checkbox-outline-primary">
                        <label class="new-control new-checkbox checkbox-outline-danger">
                        <input type="checkbox" class="new-control-input" value="1" name="remember">
                        <span class="new-control-indicator"></span>{{trans('login.keep_me_login')}}
                        </label>
                    </div>
                </div>
                <div class="field-wrapper">
                    <button type="submit" class="btn btn-danger">{{trans('login.login')}}</button>
                </div>
            </div>
        </div>
    </form>
@endsection
