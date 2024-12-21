@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
    <h1>Exam Term Detail</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item active">Exam</li>
            <li class="breadcrumb-item active">Exam Terms/ Detail</li>
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
                <h5><b>Exam Term Data</b></h5>
                <table class="table table-bordered">
                    <tr>
                        <td><b>Name</b></td>
                        <td>{{$exam_term_data['name']}}</td>
                    </tr>
                    <tr>
                        <td><b>Grade</b></td>
                        <td>{{$exam_term_data['grade']}}</td>
                    </tr>
                    <tr>
                        <td><b>Academic Year</b></td>
                        <td>{{$exam_term_data['academic_year']}}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-2">
                <form method="post" action="{{ url('admin/exam_terms_detail/list') }}" style="display: inline;">
                    @csrf
                    <input type="hidden" name="exam_terms_id" value="{{$exam_term_data['id']}}" />
                    <button type="submit" value="detail" class="btn btn-sm btn-primary" id="form-header-btn">
                        <i class="bi bi-skip-backward-fill"></i> Back
                    </button>
                </form>
            </div>
        </div>
        <br />
        <div class="row g-4">
            <div class="col-md-1"></div>
            <div class="col-md-9 card ms-3">
                <h5 class="mt-2"><b> Create Exam Terms Detail <b></h5>
                <form method="POST" action="{{ url('admin/exam_terms_detail/store') }}" enctype="multipart/form-data">
                    @csrf
                    <br />
                    <input type="hidden" name="exam_terms_id" value="{{$exam_term_data['id']}}" />
                    <div class="row g-4">
                        <div class="col-md-1"></div>
                        <div class="col-md-10">                    
                            <div class="row">
                                <div class="form-group col-md-10">
                                    <label for=""><b>Subject</b></label>
                                    <div class="col-sm-10">
                                        <select class="form-select" id="subject_id" name="subject_id" >
                                            <option  value="99">--Select Subject--</option>
                                            @foreach($subject_list as $a)
                                                <option  value="{{$a->id}}">{{$a->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        <div class="col-md-1"></div>
                    </div>
                    <div class="row g-4">
                        <div class="col-md-1"></div>
                        <div class="col-md-10">
                            <div class="form-group">
                                <label for="exam_date"><b>Exam Date</b></label>
                                <div class="col-sm-9">
                                    <input type="date" id="exam_date" name="exam_date" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1"></div>
                    </div>
                    <div class="row g-4">
                        <div class="col-md-1"></div>
                        <div class="col-md-10">
                            <div class="form-group">
                                <label for="subject_image"><b>Upload Profile</b></label>
                                <div class="image-preview-container" style='width:65% !important;'>
                                    <div class="preview">
                                        <img id="preview-selected-image" style='height:140px;'/>
                                    </div>
                                    <label for="file-upload">Upload Image</label>
                                    <input type="file" id="file-upload" name='subject_image' accept="image/*" onchange="previewImage(event);" />
                                </div>
                            </div>   
                        </div>
                    </div>
                    <div class="row g-4 mb-4">
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
        </div>
    </div>
</section>

@endsection