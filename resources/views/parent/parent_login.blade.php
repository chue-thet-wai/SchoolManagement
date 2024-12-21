<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    
    <!-- Styles -->
    <link href="{{ asset('css/parent.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

</head>
<style>
    .input-with-icon {
        position: relative;
    }

    .input-icon {
        position: absolute;
        left: 15px; /* Adjust the left position as needed */
        top: 50%;
        transform: translateY(-50%);
        color: #000000; /* Icon color */
        z-index: 1;
        font-size:1.2em;
    }

    .input-with-icon input {
        padding-left: 30px; /* Space for the icon */
        border: none;
        border-bottom: 1px solid #4dc0b5; /* Bottom line color */
        border-radius: 0;
        outline: none;
        box-shadow: none;
    }
    #login-image img{
        width:75%;
    }
    #forgot-link{
        text-decoration:none;
        color:#000000;
    }
</style>

<body style="background: rgba(0, 0, 0, 0.075);">
<main>
    <div id="app">
        <header>
            <div class="system-bar">
            </div>
        </header>
        <div class="page">
            <div class="parent-login-form">
                <form method="POST" action="{{ url('parent/login/submit') }}">
                    @csrf
                    <div class="mb-4 row">
                        <div class="col-12 mx-auto text-center" id="login-image">
                            <img src="{{ asset('techyschool_parentlogo.png') }}" alt="Your Logo">
                        </div>
                    </div>
                    @include('layouts.error')
                    <div class="mb-4 row">
                        <div class="col-10 mx-auto input-with-icon">
                            <i class="bi bi bi-envelope-fill input-icon"></i>
                            <input id="phone" type="text" class="form-control" name="phone" value=""
                                   required autocomplete="phone" autofocus placeholder="Phone">
                        </div>
                    </div>
                    <div class="mb-2 row">
                        <div class="col-10 mx-auto input-with-icon">
                            <i class="bi bi-lock-fill input-icon"></i>
                            <input id="password" type="password" class="form-control" name="password" placeholder="Password" required>
                        </div>
                    </div>
                    <div class="mb-4 row remember-forgot">
                        <div class="col-5 offset-1">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                       {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    {{ __('Keep me signed in') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-6" style="margin:-2% 0% 0% 0%;">
                            <div class="form-check">
                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" id="forgot-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-10 mx-auto">
                            <button type="submit" class="form-control sing-in px-4 py-1" style="background:#2B7A4A;">
                                {{ __('LOG IN') }}
                            </button>
                            <br/>
                            <!--<button type="submit" class="form-control sing-in px-4 py-1">
                                {{ __('REGISTER') }}
                            </button>-->
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
</body>
</html>
