@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <!--<div class="card-header">{{ __('Login') }}</div>-->

                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-4 row">
                                <img src="{{ asset('school_logo.png') }}" alt="Your Logo">
                            </div>
                            <div class="mb-2 row">
                                <div class="col-md-2"></div>
                                <label for="email" class="col-md-4">
                                    {{ __('E-Mail Address') }} :
                                </label>
                            </div>
                            <div class="mb-3 row">
                                <div class="col-md-2"></div>
                                <div class="col-md-8">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                        name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-2 row">
                                <div class="col-md-2"></div>
                                <label for="password" class="col-md-4">
                                    {{ __('Password') }} :
                                </label>
                            </div>
                            <div class="mb-3 row">
                                <div class="col-md-2"></div>
                                <div class="col-md-8">
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

                            <!--<div class="mb-3 row">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                            {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="remember">
                                            {{ __('Remember Me') }}
                                        </label>
                                    </div>
                                </div>
                            </div>-->
                            <br />
                            <div class="mb-5 row">
                                <div class="col-md-8 offset-md-2">
                                    <button type="submit" class="btn btn-primary" style="color: white;padding: 2% 7% 2% 7%;width:100%;">
                                        {{ __('Login') }}
                                    </button>

                                    <!--@if (Route::has('password.request'))
                                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                    @endif-->
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
