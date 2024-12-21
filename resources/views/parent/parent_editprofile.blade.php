@extends('parent.parent_layout')

@section('parent_content')
    <style>
        #edit-profile-form {
            color:#0F4D19;
        }
        #edit-profile-form input {
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
            <div class="centre">Edit Profile</div>
            <div class="right">
                <!--<a href="#" class="plus-button"><i class="bi bi-plus-lg"></i></a>-->
            </div>
        </div>
    </header>
    <div class="page">
        @include('layouts.error')
        <div class="editprofile-container">
            <form method="POST" id="edit-profile-form" action="{{url('parent/edit_profile/submit')}}" enctype="multipart/form-data">
                @csrf
                <div class="row g-4 mb-2">
                    <div class="col-md-1"></div>
                    <div class="col-md-10">
                        <div class="form-group">
                            <label for="email"><b>Your email</b></label>
                            <div class="col-sm-11">
                                <input type="text" name="email" class="form-control" value="{{$guardian_data->email}}" placeholder="aaa@email.com" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-4 mb-2">
                    <div class="col-md-1"></div>
                    <div class="col-md-10">
                        <div class="form-group">
                            <label for="phone"><b>Your Phone</b></label>
                            <div class="col-sm-11">
                                <input type="text" name="phone" class="form-control" value="{{$guardian_data->phone}}" placeholder="09-xxxxxxxx" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-4 mb-2">
                    <div class="col-md-1"></div>
                    <div class="col-md-10">
                        <div class="form-group">
                            <label for="address"><b>Address</b></label>
                            <div class="col-sm-11">
                                <input type="text" name="address" class="form-control" value="{{$guardian_data->address}}" placeholder="xxxxxxx" required>
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
            </form>
        </div>        
    </div>
@endsection