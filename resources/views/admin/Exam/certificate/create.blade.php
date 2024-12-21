@extends('layouts.dashboard')

@section('content')
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="{{ asset('js/certificate.js') }}"></script>
<div class="pagetitle">
    <h1>Certificate</h1>
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
                <h4><b>Create Certificate</b></h4>
            </div>
            <div class="col-md-2">
                <a class="btn btn-sm btn-primary" href="{{url('admin/certificate/list')}}" id="form-header-btn"><i class="bi bi-skip-backward-fill"></i> Back</a>
            </div>
        </div>
        <br />
        <form method="POST" action="{{route('certificate.store')}}" enctype="multipart/form-data">
            @csrf
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
                                <option value="{{$key}}">{{$value}}</option>
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
                                <option value="">--Select--</option>
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
                            <input type="text" id="title" name="title" class="form-control" required>
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
                            <textarea name="description" class="form-control" required></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-4 mt-1">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="certificate_file"><b>Certificate File</b><span style="color:brown">*</span></label>
                        <div class="col-sm-10">
                            <input type="file" name="certificate_file" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-4 mt-1">
                <div class="col-md-1"></div>
                <div class="form-group col-md-10">
                    <label for="image"><b>Upload Profile</b></label>
                    <div class="image-preview-container">
                        <div class="preview">
                            <img id="preview-selected-image" />
                        </div>
                        <label for="file-upload">Upload Image</label>
                        <input type="file" id="file-upload" name='image' accept="image/*"
                            onchange="previewImage(event);" />
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>
            <div class="row g-4 mt-1">
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