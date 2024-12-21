@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
    <h1>Driver Information</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item active">Create Information</li>
            <li class="breadcrumb-item active">Driver Information</li>
        </ol>
    </nav>
    @include('layouts.error')
</div><!-- End Page Title -->

<section class="card">
    @php
        $gradeList = [];
    @endphp
    <div class="card-body">
        <br />
        
        <div class="row g-4">
            <div class="col-md-1"></div>
            <div class="col-md-9 content-title">
                <h4><b>Create Driver Information</b></h4>
            </div>
            <div class="col-md-2">
                <a class="btn btn-sm btn-primary" href="{{url('admin/driver_info/list')}}" id="form-header-btn"><i class="bi bi-skip-backward-fill"></i> Back</a>
            </div>
        </div>

        <br />
        <form method="POST" action="{{route('driver_info.store')}}" enctype="multipart/form-data">
            @csrf
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name"><b>Name<span style="color:brown">*</span></b></label>
                        <div class="col-sm-10">
                            <input type="text" name="name" class="form-control" required>
                        </div>
                    </div>
                    <br />
                    <div class="form-group">
                        <label for="date_of_birth"><b>Date of Birth<span style="color:brown">*</span></b></label>
                        <div class="col-sm-10">
                            <input type="date" name="date_of_birth" class="form-control" required>
                        </div>
                    </div>
                    <br />
                    <div class="form-group">
                        <label for="phone"><b>Phone</b><span style="color:brown">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" name="phone" class="form-control" required>
                        </div>
                    </div>
                    <br />
                    <div class="form-group">
                        <label for="password"><b>Password</b><span style="color:brown">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" name="password" class="form-control" required>
                        </div>
                    </div>
                    <br />
                    <div class="form-group">
                        <label for="township"><b>Township</b><span style="color:brown">*</span></label>
                        <div class="col-sm-10">
                            <select class="form-select" id="township" name="township" required>
                                @foreach($township as $key => $value)
                                <option value="{{$key}}">{{$value}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <br />
                    <div class="form-group">
                        <label for="address"><b>Address</b></label>
                        <div class="col-sm-10">
                            <textarea name="address" class="form-control"></textarea>
                        </div>
                    </div> 

                </div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="profile"><b>Upload Profile</b></label>
                        <div class="image-preview-container" style='width:65% !important;'>
                            <div class="preview">
                                <img id="preview-selected-image" style='height:140px;'/>
                            </div>
                            <label for="file-upload">Upload Image</label>
                            <input type="file" id="file-upload" name='driver_profile' accept="image/*" onchange="previewImage(event);" />
                        </div>
                    </div>   
                </div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="driver_license"><b>Type of License</b></label>
                        <div class="col-sm-10">
                            <input type="file" class="form-control" id="driver_license" name='driver_license' />  
                        </div>
                    </div> 
                    <br />
                    <div class="form-group">
                        <label for="license_number"><b>License Number</b></label>
                        <div class="col-sm-10">
                            <input type="text" name="license_number" class="form-control" required>
                        </div>
                    </div> 
                    <br />
                    <div class="form-group">
                        <label for="year_of_experience"><b>Year of Experience</b></label>
                        <div class="col-sm-10">
                            <input type="number" name="year_of_experience" class="form-control" required>
                        </div>
                    </div> 
                    <br />
                    <div class="form-group">
                        <label for="start_date"><b>Start Date</b></label>
                        <div class="col-sm-10">
                            <input type="date" name="start_date" class="form-control" required>
                        </div>
                    </div> 
                    <br />
                    <div class="form-group">
                        <label for="resign_date"><b>Resign Date</b></label>
                        <div class="col-sm-10">
                            <input type="date" name="resign_date" class="form-control">
                        </div>
                    </div>              
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="form-group col-md-2">
                    <div class="d-grid mt-4">
                        <input type="submit" value="Add" class="btn btn-primary">
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>
        </form>
    </div>
</section>


@endsection