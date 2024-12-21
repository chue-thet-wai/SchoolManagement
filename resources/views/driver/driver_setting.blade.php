@extends('driver.driver_layout')

@section('driver_content')
    <header>
        <div class="system-bar">
            <div class="row">
                <div class="col-3 left">
                    <a href="{{ url('driver/home') }}" class="back-button">
                        <img src="{{asset('driver/back.png')}}" alt="Image">
                    </a>
                </div>
                <div class="col-6 center">Setting</div>
                <div class="col-3 right"></div>
            </div>
        </div>
    </header>
    <div class="page">
        <div class="setting-container">
            <h5><b>Change Password</b></h5>
            @include('layouts.error')
            <form method="POST" id="change-password-form" action="{{url('driver/setting/submit')}}" enctype="multipart/form-data">
                @csrf
                <div class="row g-4 mb-2">
                    <div class="col-md-1"></div>
                    <div class="col-md-10">
                        <div class="form-group">
                            <label for="current_password"><b>Type your current password</b></label>
                            <div class="col-sm-11">
                                <input type="text" name="current_password" class="form-control" value="" placeholder="........." required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-4 mb-2">
                    <div class="col-md-1"></div>
                    <div class="col-md-10">
                        <div class="form-group">
                            <label for="new_password"><b>Type your new password</b></label>
                            <div class="col-sm-11">
                                <input type="text" name="new_password" class="form-control" value="" placeholder="........." required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-4 mb-2">
                    <div class="col-md-1"></div>
                    <div class="col-md-10">
                        <div class="form-group">
                            <label for="confirm_password"><b>Confirm your new password</b></label>
                            <div class="col-sm-11">
                                <input type="text" name="confirm_password" class="form-control" vlaue="" placeholder="........." required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-4">
                    <div class="col-md-1"></div>
                    <div class="form-group col-md-2 mx-auto">
                        <div class="d-grid mt-4">
                            <input type="submit" value="Save" class="btn save-btn">
                        </div>
                    </div>
                    <div class="col-md-1"></div>
                </div>
                <br />
            </form>
        </div>        
    </div>
@endsection