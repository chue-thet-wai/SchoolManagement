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
                <div class="col-6 center">Profile</div>
                <div class="col-3 right"></div>
            </div>
        </div>
    </header>
    <div class="page">
        <div class="profile-container">
            @include('layouts.error')
            <!--<form method="POST" id="edit-profile-form" action="{{url('driver/profile/submit')}}" enctype="multipart/form-data">
                @csrf-->
                <div class="row g-4 mx-3 mt-1 form-group">
                    <div class="col-4"><label for="name"><b>Name :</b></label></div>
                    <div class="col-8">
                        <input type="text" name="name" class="form-control" value="{{$driver_data->name}}" required readonly>
                    </div>
                </div>
                <div class="row g-4 mx-3 mt-1 form-group">
                    <div class="col-4"><label for="phone"><b>Contact :</b></label></div>
                    <div class="col-8">
                        <input type="text" name="phone" class="form-control" value="{{$driver_data->phone}}" required readonly>
                    </div>
                </div>
                <div class="row g-4 mx-3 mt-1 form-group">
                    <div class="col-4"><label for="phone"><b>Photo :</b></label></div>
                    <div class="col-8">
                        <img src="{{asset('assets/driver_images/'.$driver_data->profile_image)}}" id="driver_profile_image" alt="Image">
                    </div>
                </div>
                <div class="row g-4 mx-3 mt-1 form-group">
                    <div class="col-4"><label for="driver_id"><b>Driver ID :</b></label></div>
                    <div class="col-8">
                        <input type="text" name="driver_id" class="form-control" value="{{$driver_data->driver_id}}" required readonly>
                    </div>
                </div>
                <div class="row g-4 mx-3 mt-1 form-group">
                    <div class="col-4"><label for="car_no"><b>Car No. :</b></label></div>
                    <div class="col-8">
                        <input type="text" name="car_no" class="form-control" value="{{$driver_data->car_no}}" required readonly>
                    </div>
                </div>
                <div class="row g-4 mx-3 mt-1 form-group">
                    <div class="col-4"><label for="address"><b>Address :</b></label></div>
                    <div class="col-8">
                        <textarea name="address" class="form-control" required readonly>{{$driver_data->address}}</textarea>
                    </div>
                </div>
                <div class="row g-4 mx-3 mt-1 form-group">
                    <div class="col-4"><label for="address"><b>License :</b></label></div>
                    <div class="col-8">
                        <a class="btn btn-labeled btn-info" href="{{asset('assets/driver_licenses/'.$driver_data->type_of_license)}}" download> 
                            <span id="boot-icon" class="bi bi-download" style="font-size: 20px; color: rgb(58 69 207); margin:2px;"></span>{{$driver_data->type_of_license}}
                        </a>
                    </div>
                </div>
                
                <!--<div class="row g-4">
                    <div class="col-md-1"></div>
                    <div class="form-group col-md-2 mx-auto">
                        <div class="d-grid mt-4">
                            <input type="submit" value="Save" class="btn save-btn">
                        </div>
                    </div>
                    <div class="col-md-1"></div>
                </div>
            </form>-->
        </div>        
    </div>
@endsection