@extends('layouts.app')

@section('content')
<link href="{{ asset('css/login.css') }}" rel="stylesheet">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <!--<div class="card-header">{{ __('Login') }}</div>-->

                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-2 row">
                                <div class="col-12 mx-auto text-center" id="login-image">
                                    <img src="{{ asset('techyschool_logo.png') }}" alt="Your Logo">
                                </div>
                            </div>
                            <div class="mb-2 row">
                                <div class="col-12 mx-auto text-center" id="login-title">
                                    <h4><strong>School Management System</strong></h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3"></div>
                                <label for="email" class="col-md-4 ms-md-4">
                                    <strong>{{ __('E-Mail Address') }}</strong>
                                </label>
                            </div>
                            <div class="mb-2 row">
                                <div class="col-md-3"></div>
                                <div class="col-md-5 ms-md-4 input-with-image">
                                    <div class="image-section">
                                        <img src="{{ asset('email.png') }}" alt="Your Image">
                                    </div>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                        name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3"></div>
                                <label for="password" class="col-md-4 ms-md-4">
                                    <strong>{{ __('Password') }}</strong>
                                </label>
                            </div>
                            <div class="mb-2 row">
                                <div class="col-md-3"></div>
                                <div class="col-md-5 ms-md-4 input-with-image">
                                    <div class="image-section">
                                        <img src="{{ asset('password.png') }}" alt="Your Image">
                                    </div>
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required autocomplete="current-password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-2 row remember-forgot">
                                <div class="col-md-3 offset-md-3">
                                    <div class="form-check ms-md-3">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                            {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="remember">
                                            {{ __('Keep me signed in') }}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4" style="margin-left: -3%;">
                                    <div class="form-check">
                                        @if (Route::has('password.request'))
                                            <a class="btn btn-link forgot-link" href="{{ route('password.request') }}">
                                                {{ __('Forgot Your Password?') }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <div class="col-md-2 mx-auto">
                                    <button type="submit" class="sing-in px-md-4 py-md-1">
                                        {{ __('Sign In') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
