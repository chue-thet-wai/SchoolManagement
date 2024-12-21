@extends('parent.parent_layout')

@section('parent_content')
    <style>
        #change-password-form {
            color:#0F4D19;
        }
        #change-password-form input {
            background: #ede2e2;
            color:#0F4D19;
        }
        .save-btn {
            background: #0F4D19 !important;
            border: 1px solid #0F4D19;
            color: #fff !important;
        }
    </style>
    <header>
        <div class="system-bar">
            <div class="left">
                <a href="{{ url('parent/home') }}" class="back-button">Back</a>
            </div>
            <div class="centre">Change Password</div>
            <div class="right">
                <!--<a href="#" class="plus-button"><i class="bi bi-plus-lg"></i></a>-->
            </div>
        </div>
    </header>
    <div class="page">
        @include('layouts.error')
        <div class="editprofile-container">
            <form method="POST" id="change-password-form" action="{{url('parent/change_password/submit')}}" enctype="multipart/form-data">
                @csrf
                <div class="row g-4 mb-2">
                    <div class="col-md-1"></div>
                    <div class="col-md-10">
                        <div class="form-group">
                            <label for="current_password"><b>Type your current password</b></label>
                            <div class="col-sm-11">
                                <input type="text" name="current_password" class="form-control" value="" placeholder="xxxxxx" required>
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
                                <input type="text" name="new_password" class="form-control" value="" placeholder="xxxxxxxx" required>
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
                                <input type="text" name="confirm_password" class="form-control" vlaue="" placeholder="xxxxxxxx" required>
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