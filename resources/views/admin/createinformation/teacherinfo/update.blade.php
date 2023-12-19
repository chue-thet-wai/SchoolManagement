@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
    <h1>Teacher Information</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item active">Create Information</li>
            <li class="breadcrumb-item active">Teacher Information</li>
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
                <h4><b>Update Teacher Information</b></h4>
            </div>
            <div class="col-md-2">
                <a class="btn btn-sm btn-primary" href="{{url('admin/teacher_info/list')}}" id="form-header-btn"><i class="bi bi-skip-backward-fill"></i> Back</a>
            </div>
        </div>

        <br />
        <form method="POST" action="{{route('teacher_info.update',$result[0]->user_id)}}" enctype="multipart/form-data">
            @csrf
            <br />
            {{method_field('PUT')}}
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="form-group col-md-5">
                    <label for="name"><b>Name<span style="color:brown">*</span></b></label>
                    <div class="col-sm-10">
                        <input type="text" name="name" class="form-control" value="{{$result[0]->name}}" required>
                    </div>
                </div>
                <div class="form-group col-md-5">
                    <label for="name_mm"><b>Name(Myanmar)<span style="color:brown">*</span></b></label>
                    <div class="col-sm-10">
                        <input type="text" name="name_mm" class="form-control" value="{{$result[0]->name}}" required>
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="form-group col-md-5">
                    <label for="login_name"><b>Login Name<span style="color:brown">*</span></b></label>
                    <div class="col-sm-10">
                        <input type="text" name="login_name" class="form-control" value="{{$result[0]->login_name}}" required>
                    </div>
                </div>
                <div class="form-group col-md-5">
                    <label for="password"><b>Password<span style="color:brown">*</span></b></label>
                    <div class="col-sm-10">
                        <input type="password" name="password" class="form-control">
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="form-group col-md-5">
                    <label for="email"><b>Email<span style="color:brown">*</span></b></label>
                    <div class="col-sm-10">
                        <input type="text" name="email" class="form-control" value="{{$result[0]->email}}" required>
                    </div>
                </div>
                <div class="form-group col-md-5">
                    <label for="contact_no"><b>Contact No</b><span style="color:brown">*</span></label>
                    <div class="col-sm-10">
                        <input type="text" name="contact_no" class="form-control" value="{{$result[0]->contact_number}}" required>
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="form-group col-md-5">
                    <label for="position"><b>Position<span style="color:brown">*</span></b></label>
                    <div class="col-sm-10">
                        <input type="text" name="position" class="form-control" value="{{$result[0]->position}}" required>
                    </div>
                </div>
                <!--<div class="form-group col-md-5">
                    <label for=""><b>Grade</b><span style="color:brown">*</span></label>
                    <div class="col-sm-10">
                        <select class="form-select" id="grade_id" name="grade_id" >
                            <option value="99">select grade</option>
                            @foreach($grade_list as $g)
                                <option value="{{$g->id}}"
                                    @if($g->id==$result[0]->grade_id)
                                        selected
                                    @endif
                                >{{$g->name}}</option>
                                @php  $gradeList[$g->id] = $g->name; @endphp
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-1"></div>-->
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="form-group col-md-5">
                    <label for="startworking_date"><b>Start Working Date<span style="color:brown">*</span></b></label>
                    <div class="col-sm-10">
                        <input type="date" name="startworking_date" class="form-control" value="{{date('Y-m-d',strtotime($result[0]->startworking_date))}}" required>
                    </div>
                </div>
                <div class="form-group col-md-5">
                    <label for=""><b>Gender</b><span style="color:brown">*</span></label>
                    <div class="col-sm-10">
                        <select class="form-select" id="gender" name="gender">
                            @foreach($gender as $key => $value)
                            <option value="{{$key}}"
                            @if ($result[0]->gender == $key)
                                selected
                            @endif
                            >{{$value}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <fieldset class="form-group col-md-10 border p-3 m-3">
                    <legend for="reg_type" class="float-none w-auto" style='font-size:1.2em;'><b>Family</b></legend>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="father_name"><b>Father Name<span style="color:brown">*</span></b></label>
                            <div class="col-sm-10">
                                <input type="text" name="father_name" class="form-control" value="{{$result[0]->father_name}}" required>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="mother_name"><b>Mother Name<span style="color:brown">*</span></b></label>
                            <div class="col-sm-10">
                                <input type="text" name="mother_name" class="form-control" value="{{$result[0]->mother_name}}" required>
                            </div>
                        </div>
                    </div>
                    <br />
                </fieldset>  
                <div class="col-md-1"></div>
            </div>
            <br />

            <div class="row g-4">
                <div class="col-md-1"></div>
                <fieldset class="form-group col-md-10 border p-3 m-3">
                    <legend for="reg_type" class="float-none w-auto" style='font-size:1.2em;'><b>Qualification</b></legend>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="qualification_name"><b>Qualification Name</b></label>
                            <div class="col-sm-10">
                                <input type="text" name="qualification_name" class="form-control" value="{{$result[0]->qualification_name}}">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="year_attended"><b>Year Attended</b></label>
                            <div class="col-sm-10">
                                <input type="number" name="year_attended" class="form-control" value="{{$result[0]->year_attended}}">
                            </div>
                        </div>
                    </div>
                    <br />
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="qualification_desc"><b>Description of Qualification</b></label>
                            <div class="col-sm-10">
                                <input type="file" name="qualification_desc" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="file-upload"><b>Previous Uploaded Qualification</b></label>
                            <div class="col-sm-10">
                                <a class="btn btn-labeled btn-info" href="{{asset('assets/teacher_qualifications/'.$result[0]->qualification_desc)}}" download> 
                                    <span id="boot-icon" class="bi bi-download" style="font-size: 20px; color: rgb(58 69 207); margin:2px;"></span>{{$result[0]->qualification_desc}}
                                </a>
                                <input type="hidden" id="previous_qualification_desc" name="previous_qualification_desc" value="{{$result[0]->qualification_desc}}">
                            </div>
                        </div> 
                    </div>
                    <br />
                </fieldset>  
                <div class="col-md-1"></div>
            </div>
            <br />

            <div class="row g-4">
                <div class="col-md-1"></div>
                <fieldset class="form-group col-md-10 border p-3 m-3">
                    <legend for="reg_type" class="float-none w-auto" style='font-size:1.2em;'><b>Employment History</b></legend>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="job_title"><b>Job Title</b></label>
                            <div class="col-sm-10">
                                <input type="text" name="job_title" class="form-control" value="{{$result[0]->job_title}}">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="company_name"><b>Company Name</b></label>
                            <div class="col-sm-10">
                                <input type="text" name="company_name" class="form-control" value="{{$result[0]->company_name}}">
                            </div>
                        </div>
                    </div>
                    <br />
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="start_date"><b>Start Date</b></label>
                            <div class="col-sm-10">
                                @if($result[0]->start_date != null)
                                    <input type="date" name="start_date" class="form-control" value="{{date('Y-m-d',strtotime($result[0]->start_date))}}">
                                @else 
                                    <input type="date" name="start_date" class="form-control">
                                @endif
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="end_date"><b>End Date</b></label>
                            <div class="col-sm-10">
                                @if($result[0]->end_date != null) 
                                    <input type="date" name="end_date" class="form-control" value="{{date('Y-m-d',strtotime($result[0]->end_date))}}">
                                @else 
                                    <input type="date" name="end_date" class="form-control">
                                @endif
                            </div>
                        </div>
                    </div>
                    <br />
                </fieldset>  
                <div class="col-md-1"></div>
            </div>
            <br />

            <div class="row g-4">
                <div class="col-md-1"></div>
                <fieldset class="form-group col-md-10 border p-3 m-3">
                    <legend for="reg_type" class="float-none w-auto" style='font-size:1.2em;'><b>Education</b></legend>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="university"><b>Collage/University</b></label>
                            <div class="col-sm-10">
                                <input type="text" name="university" class="form-control" value="{{$result[0]->university}}">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="education_year"><b>Year</b></label>
                            <div class="col-sm-10">
                                <input type="text" name="education_year" class="form-control" value="{{$result[0]->education_year}}">
                            </div>
                        </div>
                    </div>
                    <br />
                </fieldset>  
                <div class="col-md-1"></div>
            </div>
            <br />

            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="form-group col-md-10">
                    <label for="address"><b>Address</b></label>
                    <div class="col-sm-10">
                        <textarea name="address" class="form-control" >{{$result[0]->address}}</textarea>
                    </div>
                </div>               
                <div class="col-md-1"></div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="form-group col-md-10">
                    <label for="remark"><b>Remark</b></label>
                    <div class="col-sm-10">
                        <textarea name="remark" class="form-control">{{$result[0]->remark}}</textarea>
                    </div>
                </div>               
                <div class="col-md-1"></div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="form-group col-md-10">
                    <label for="status"><b>Status</b></label>
                    <div class="col-sm-10">
                        <input type="radio" id="inactive" name="status" value="0" 
                            @if($result[0]->resign_status != "1")
                                checked
                            @endif
                            ><b> Inactive</b>
                        <input type="radio" id="active" name="status" value="1"
                            @if($result[0]->resign_status == "1")
                                checked
                            @endif
                            ><b> Active</b>
                    </div>
                </div>               
                <div class="col-md-1"></div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1">
                    <input type="hidden" id="previous_image" name="previous_image" value="{{$result[0]->profile_image}}">
                </div>
                <div class="form-group col-md-10">
                    <label for="profile"><b>Upload Profile</b></label>
                    <div class="image-preview-container">
                        <div class="preview">
                            <img id="preview-selected-image" src="{{asset('assets/teacher_images/'.$result[0]->profile_image)}}" style='display: block;'/>
                        </div>
                        <label for="file-upload">Upload Image</label>
                        <input type="file" id="file-upload" name='teacher_profile' accept="image/*" onchange="previewImage(event);" />
                    </div>
                </div>               
                <div class="col-md-1"></div>
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
    </div>
</section>


@endsection