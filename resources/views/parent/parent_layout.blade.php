<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Styles -->
        <link href="{{ asset('css/parent.css') }}" rel="stylesheet">
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    </head>

    <body style="background: rgba(0, 0, 0, 0.075);"> 
        <main>                
            <div id="app">   
                @yield('parent_content')
                <footer>
                    <a href="{{ url('parent/home') }}" class="footer-item"><img src="{{ asset('parent/home.png') }}" width="20"></i>Home</a>
                    <a href="{{ url('parent/annoucement') }}" class="footer-item"><img src="{{ asset('parent/calendar.png') }}" width="20">Calendar</a>
                    <a href="{{ url('parent/messages') }}" class="footer-item"><i class="bi bi-chat-square-dots"></i>Message</a>
                    <!--<a href="{{ url('parent/profile') }}" class="footer-item"><i class="bi bi-person"></i>Profile</a>-->
                    <a href="{{ url('parent/login') }}" class="footer-item"><i class="bi bi-info-circle"></i>About</a>
                </footer>
            </div>        
        </main>
    </body>
</html>