@extends('layouts.auth')

@section('content')
    <h4 class="">{{ __('Reset Password') }}</h4>
    <form class="text-left" action="{{ route('password.email') }}" method="post">
        @csrf
        <div class="form">
            <div id="username-field" class="field-wrapper input">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                <input id="email" name="email" type="text" class="form-control" placeholder="Email" autocomplete="off" autofocus>
                @error('email')
                <div class="invalid-feedback">
                    {{$message}}
                </div>
                @enderror
                @if (session('status'))
                <div class="valid-feedback">
                {{ session('status') }}
                </div>
                @endif
            </div>
            <div class="d-sm-flex justify-content-between">
                
                <div class="field-wrapper">
                    <button type="submit" class="btn btn-danger"> {{ __('Send Password Reset Link') }}</button>
                </div>
                
            </div>
            

        </div>
    </form>
@endsection
