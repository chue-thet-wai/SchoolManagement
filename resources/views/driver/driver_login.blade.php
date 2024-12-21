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
        <link href="{{ asset('css/driver.css') }}" rel="stylesheet">
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    </head>
    <body style="background: rgba(0, 0, 0, 0.075);">
        <main>
            <div id="app" style="background-color: #72af74;">
                <div class="page">
                    <div class="driver-login-form">
                        <form method="POST" action="{{ url('driver/login/submit') }}">
                            @csrf
                            <div class="mb-4 mt-4 row">
                                <div class="col-12 mx-auto text-center" id="login-image">
                                    <img src="{{ asset('driver/driver_login.png') }}" alt="Your Logo">
                                </div>
                            </div>
                            @include('layouts.error')
                            <div class="mb-4 row">
                                <div class="col-10 mx-auto">
                                    <input id="phone" type="text" class="form-control" name="phone" value=""
                                        required autocomplete="phone" autofocus placeholder="Phone">
                                </div>
                            </div>
                            <div class="mb-2 row">
                                <div class="col-10 mx-auto">
                                    <input id="password" type="password" class="form-control" name="password" placeholder="Password" required>
                                </div>
                            </div>
                            <div class="mb-3 mt-5 row">
                                <div class="col-10 mx-auto">
                                    <button type="submit" class="form-control sing-in px-4 py-1">
                                        {{ __('LOG IN') }}
                                    </button>
                                    <br/>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </body>
</html>
