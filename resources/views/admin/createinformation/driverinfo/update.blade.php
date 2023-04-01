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
            <div class="col-md-9" style='color:#012970;'>
                <h4><b>Update Driver Information</b></h4>
            </div>
            <div class="col-md-1">
                <a class="btn btn-sm btn-primary" href="{{route('driver_info.index')}}" id="form-header-btn"> Back</a>
            </div>
            <div class="col-md-1"></div>
        </div>

        <br />
        <form method="POST" action="{{route('driver_info.update',$result[0]->driver_id)}}" enctype="multipart/form-data">
            @csrf
            <br />
            {{method_field('PUT')}}
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name"><b>Name<span style="color:brown">*</span></b></label>
                        <div class="col-sm-10">
                            <input type="text" name="name" value="{{$result[0]->name}}"  class="form-control" required>
                        </div>
                    </div>
                    <br />
                    <div class="form-group">
                        <label for="date_of_birth"><b>Date of Birth<span style="color:brown">*</span></b></label>
                        <div class="col-sm-10">
                            <input type="date" name="date_of_birth" value="{{date('Y-m-d',strtotime($result[0]->date_of_birth))}}" class="form-control" required>
                        </div>
                    </div>
                    <br />
                    <div class="form-group">
                        <label for="phone"><b>Phone</b></label>
                        <div class="col-sm-10">
                            <input type="text" name="phone" value="{{$result[0]->phone}}" class="form-control" required>
                        </div>
                    </div>
                    <br />
                    <div class="form-group">
                        <label for="address"><b>Address</b></label>
                        <div class="col-sm-10">
                            <textarea name="address" class="form-control">{{$result[0]->address}}</textarea>
                        </div>
                    </div> 

                </div>
                <div class="col-md-5">
                    <input type="hidden" id="previous_image" name="previous_image" value="{{$result[0]->profile_image}}">
                    <div class="form-group">
                        <label for="profile"><b>Upload Profile</b></label>
                        <div class="image-preview-container" style='width:65% !important;'>
                            <div class="preview">
                                <img id="preview-selected-image" src="{{asset('assets/driver_images/'.$result[0]->profile_image)}}" style='height:140px;display:block;'/>
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
                        <label for="file-upload"><b>Previous Uploaded Licence</b></label>
                        <div class="col-sm-10">
                            <a class="btn btn-labeled btn-info" href="{{asset('assets/driver_licences/'.$result[0]->type_of_licence)}}" download> 
                                <span id="boot-icon" class="bi bi-download" style="font-size: 20px; color: rgb(58 69 207); margin:2px;"></span>{{$result[0]->type_of_licence}}
                            </a>
                            <input type="hidden" id="previous_licence" name="previous_licence" value="{{$result[0]->type_of_licence}}">
                        </div>
                    </div> 
                    <br />
                    <div class="form-group">
                        <label for="file-upload"><b>Type of Licence</b></label>
                        <div class="col-sm-10">
                            <input type="file" class="form-control" id="file-upload" name='driver_licence' />  
                        </div>
                    </div> 
                    <br />
                    <div class="form-group">
                        <label for="year_of_experience"><b>Year of Experience</b></label>
                        <div class="col-sm-10">
                            <input type="number" name="year_of_experience" class="form-control" value="{{$result[0]->year_of_experience}}" required>
                        </div>
                    </div> 
                    <br />
                    <div class="form-group">
                        <label for="start_date"><b>Start Date</b></label>
                        <div class="col-sm-10">
                            <input type="date" name="start_date" class="form-control" value="{{date('Y-m-d',strtotime($result[0]->start_date))}}" required>
                        </div>
                    </div> 
                    <br />
                    <div class="form-group">
                        <label for="resign_date"><b>Resign Date</b></label>
                        <div class="col-sm-10">
                            @if ($result[0]->resign_date == '') 
                                <input type="date" name="resign_date" class="form-control">
                            @else
                                <input type="date" name="resign_date" value="{{date('Y-m-d',strtotime($result[0]->resign_date))}}" class="form-control">
                            @endif                            
                        </div>
                    </div>              
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="form-group col-md-2">
                    <div class="d-grid mt-4">
                        <input type="submit" value="Update" class="btn btn-primary">
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>
        </form>
        <br />
    </div>
</section>


@endsection