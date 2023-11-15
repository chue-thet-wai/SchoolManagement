@extends('layouts.dashboard')

@section('content')
<script>
    function getDriverData(){
        var id = $("#driver_id").val();
        $.ajax({
           type:'POST',
           url:'/admin/school_bus_track/driver_search',
           data:{
                _token :'<?php echo csrf_token() ?>',
                driver_id  : id
            },
           
           success:function(data){
                if (data.msg == 'found') {
                    $("#driver_msg").html('Driver data found!.');
                    $("#driver_name").val(data.name);
                    $("#driver_phone").val(data.phone);
                } else {
                    $("#guardian_msg").html('Driver data not found !');
                    $("#driver_id").val('');
                    $("#driver_name").val('');
                    $("#driver_phone").val('');
                }             
            }
        });
    }
</script>
<div class="pagetitle">
    <h1>School Bus Track</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item active">Registration</li>
            <li class="breadcrumb-item active">School Bus Track</li>
        </ol>
    </nav>
    @include('layouts.error')
</div><!-- End Page Title -->

<section class="card">
    <div class="card-body">
        <br />
        
        <div class="row g-4">
            <div class="col-md-1"></div>
            <div class="col-md-9" style='color:#012970;'>
                <h4><b>Create School Bus Track</b></h4>
            </div>
            <div class="col-md-1">
                <a class="btn btn-sm btn-primary" href="{{url('admin/school_bus_track/list')}}" id="form-header-btn"> Back</a>
            </div>
            <div class="col-md-1"></div>
        </div>

        <br />
        <form method="POST" action="{{route('school_bus_track.store')}}" enctype="multipart/form-data">
            @csrf
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="track_no"><b>Track Number<span style="color:brown">*</span></b></label>
                        <div class="col-sm-10">
                            <input type="text" name="track_no" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <div class='row'>
                            <label for="driver_id"><b>Driver ID<span style="color:brown">*</span></b></label>
                            <div class="col-sm-10">
                                <input type="text" id="driver_id" name="driver_id" class="form-control" required>
                            </div>
                            <div class="col-md-2">
                                <button type="button" name="driver_search" id="driver_search" class="btn btn-sm btn-primary mt-1" onclick="getDriverData()">Search</button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <span name="driver_msg" id="driver_msg"></span>
                    </div>
                </div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="driver_name"><b>Driver Name</b></label>
                        <div class="col-sm-10">
                            <input type="text" name="driver_name"  id="driver_name" class="form-control" disabled>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="driver_phone"><b>Driver Phone</b></label>
                        <div class="col-sm-10">
                            <input type="text" name="driver_phone" id="driver_phone" class="form-control" disabled>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="car_type"><b>Car Type<span style="color:brown">*</span></b></label>
                        <div class="col-sm-10">
                            <input type="text" name="car_type" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="car_no"><b>Car Number<span style="color:brown">*</span></b></label>
                        <div class="col-sm-10">
                            <input type="text" name="car_no" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    <div class='row'>
                        <div class="col-md-6">
                            <label for="school_from_time"><b>School Time(From)<span style="color:brown">*</span></b></label>
                            <div class="form-group">                       
                                <div class="col-sm-10">
                                    <input type="text" name="school_from_time" class="form-control" required>
                                </div>
                            </div>
                        </div>
                       
                        <div class="col-md-6">
                            <label for="school_to_time"><b>School Time(To)<span style="color:brown">*</span></b></label>
                            <div class="form-group">                       
                                <div class="col-sm-10">
                                    <input type="text" name="school_to_time" class="form-control" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    <div class='row'>
                        <div class="col-md-6">
                            <label for="school_from_period"><b>School Period(From)<span style="color:brown">*</span></b></label>
                            <div class="form-group">                       
                                <div class="col-sm-10">
                                    <input type="text" name="school_from_period" class="form-control" required>
                                </div>
                            </div>
                        </div>
                       
                        <div class="col-md-6">
                            <label for="school_to_period"><b>School Period(To)<span style="color:brown">*</span></b></label>
                            <div class="form-group">                       
                                <div class="col-sm-10">
                                    <input type="text" name="school_to_period" class="form-control" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="arrive_student_no"><b>Arrive by Student Number<span style="color:brown">*</span></b></label>
                        <div class="col-sm-10">
                            <input type="number" name="arrive_student_no" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <fieldset class="form-group col-md-9 border m-2 p-3">
                    <legend for="reg_type" class="float-none w-auto" style='font-size:1.2em;'><b>Track Detail</b></legend>

                    <div class="form-group ">
                        <div class="row col-md-10">
                            <div class='col-sm-4'>
                                <label for="township"><b>Township</b><span style="color:brown">*</span></label>
                            </div>
                            <div class='col-sm-8'>
                                <select class="form-select" id="township" name="township" required>
                                    @foreach($township as $key => $value)
                                    <option value="{{$key}}">{{$value}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <br />
                    <div class="form-group ">
                        <div class="row col-md-10">
                            <div class='col-sm-4'>
                                <label for="two_way_amount"><b>Two Way Amount</b><span style="color:brown">*</span></label>
                            </div>
                            <div class='col-sm-8'>
                                <input type="number" name="two_way_amount" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <br />
                    <div class="form-group ">
                        <div class="row col-md-10">
                            <div class='col-sm-4'>
                                <label for="oneway_pickup"><b>One Way Pickup</b><span style="color:brown">*</span></label>
                            </div>
                            <div class='col-sm-8'>
                                <input type="number" name="oneway_pickup" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <br />
                    <div class="form-group ">
                        <div class="row col-md-10">
                            <div class='col-sm-4'>
                                <label for="oneway_back"><b>One Way Back</b><span style="color:brown">*</span></label>
                            </div>
                            <div class='col-sm-8'>
                                <input type="number" name="oneway_back" class="form-control" required>
                            </div>
                        </div>
                    </div>

                </fieldset>
                <div class="col-md-1"></div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="form-group col-md-2">
                    <div class="d-grid mt-4">
                        <input type="submit" value="Save" class="btn btn-primary">
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>
            <br />
        </form>
    </div>
</section>


@endsection