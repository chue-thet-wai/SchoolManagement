@extends('layouts.dashboard')

@section('content')
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="{{ asset('js/certificate.js') }}"></script>
<div class="pagetitle">
    <h1>Update Certificate</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item active">Exam</li>
            <li class="breadcrumb-item active">Certificate</li>
        </ol>
    </nav>
    @include('layouts.error')
</div><!-- End Page Title -->

<section class="card">
    <div class="card-body">
        <br />
        
        <div class="row g-4">
            <div class="col-md-1"></div>
            <div class="col-md-9 content-title">
                <h4><b>Update Certificate</b></h4>
            </div>
            <div class="col-md-2">
                <a class="btn btn-sm btn-primary" href="{{url('admin/certificate/list')}}" id="form-header-btn"><i class="bi bi-skip-backward-fill"></i> Back</a>
            </div>
        </div>

        <br />
        <form method="POST" action="{{route('certificate.update',$result[0]->id)}}" enctype="multipart/form-data">
            @csrf
            <br />
            {{method_field('PUT')}}
            <input type="hidden" id="token" value="<?php echo csrf_token(); ?>" />
            <div class="row g-4 mt-1">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <div class='col-sm-4'>
                            <label for="class_id"><b>Class</b><span style="color:brown">*</span></label>
                        </div>
                        <div class='col-sm-8'>
                            <select class="form-select" id="class_id" name="class_id" required>
                                <option value="">--Select--</option>
                                @foreach($class_list as $key => $value)
                                    <option  value="{{$key}}"
                                    @if ($result[0]->class_id == $key)
                                        selected
                                    @endif
                                    >{{$value}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-4 mt-1">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <div class='col-sm-4'>
                            <label for="student_id"><b>Student</b><span style="color:brown">*</span></label>
                        </div>
                        <div class='col-sm-8'>
                            <select class="form-select" id="student_id" name="student_id" required>
                                @foreach($student_list as $key => $value)
                                <option  value="{{$key}}"
                                    @if ($result[0]->student_id == $key)
                                        selected
                                    @endif
                                    >{{$value}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-4 mt-1">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="title"><b>Title</b><span style="color:brown">*</span></label>
                        <div class="col-sm-8">
                            <input type="text" id="title" name="title" class="form-control" value="{{$result[0]->title}}" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-4 mt-1">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="description"><b>Description</b><span style="color:brown">*</span></label>
                        <div class="col-sm-8">
                            <textarea name="description" class="form-control" required>{{$result[0]->description}}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-4 mt-1">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group col-md-10">
                        <label for="certificate_file"><b>Certificate File</b></label>
                        <div class="col-sm-10">
                            <input type="file" name="certificate_file" class="form-control" >
                        </div>
                    </div>
                    <div class="form-group col-md-10 mt-1">
                        <label for="file-upload"><b>Previous Uploaded Qualification</b></label>
                        <div class="col-sm-10">
                            <a class="btn btn-labeled btn-info" href="{{asset('assets/certificate_files/'.$result[0]->certificate_file)}}" download> 
                                <span id="boot-icon" class="bi bi-download" style="font-size: 20px; color: rgb(58 69 207); margin:2px;"></span>{{$result[0]->certificate_file}}
                            </a>
                            <input type="hidden" id="previous_certificate_file" name="previous_certificate_file" value="{{$result[0]->certificate_file}}">
                        </div>
                    </div> 
                </div>
            </div>
            <div class="row g-4 mt-1">
                <div class="col-md-1">
                    <input type="hidden" id="previous_image" name="previous_image" value="{{$result[0]->certificate_file}}">
                </div>
                <div class="form-group col-md-10">
                    <label for="image"><b>Upload Profile</b></label>
                    <div class="image-preview-container">
                        <div class="preview">
                            <img id="preview-selected-image" src="{{asset('assets/certificate_images/'.$result[0]->image)}}" style='display: block;'/>
                        </div>
                        <label for="file-upload">Upload Image</label>
                        <input type="file" id="file-upload" name='image' accept="image/*" onchange="previewImage(event);" />
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
            <br />
        </form>
        <br />
    </div>
</section>


@endsection