@extends('driver.driver_layout')

@section('driver_content')
    <header>
        <div class="system-bar">
            <div class="row">
                <div class="col-3 left"></div>
                <div class="col-6 center">Home</div>
                <div class="col-3 right"><a href="{{ url('driver/logout') }}" class="logout">
                    <img src="{{asset('driver/logout.png')}}" alt="Image"></a>
                </div>
            </div>
        </div>
    </header>
    <div class="mt-5 page">
        @include('layouts.error')
        <div class="row profile-card">
            <div class="col-1"></div>
            <div class="col-5">
                <a href="{{ url('driver/profile') }}">
                    <div class="card">
                        <div class="center-content">
                            <div class="mt-2 mb-2 center-text">My Profile</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-5">
                <a href="{{ url('driver/schedule') }}">
                    <div class="card">
                        <div class="center-content">
                            <div class="mt-2 mb-2 center-text">My Schedule</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-1"></div>
        </div>
        <br />
        <div class="row profile-card">
            <div class="col-1"></div>
            <div class="col-5">
                <a href="{{ url('driver/setting') }}">
                    <div class="card">
                        <div class="center-content">
                            <div class="mt-2 mb-2 center-text">Setting</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-5">
                <a href="{{ url('driver/attendance') }}">
                    <div class="card">
                        <div class="center-content">
                            <div class="mt-2 mb-2 center-text">My Attendance</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-1"></div>
        </div>
    </div>
@endsection