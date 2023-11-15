@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
    <h1>Exam Marks</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item active">Exam</li>
            <li class="breadcrumb-item active">Exam Marks</li>
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
                <h4><b>Create Exam Marks</b></h4>
            </div>
            <div class="col-md-1">
                <a class="btn btn-sm btn-primary" href="{{url('admin/exam_marks/list')}}" id="form-header-btn"> Back</a>
            </div>
            <div class="col-md-1"></div>
        </div>

        <br />
        <form method="POST" action="{{url('admin/exam_marks/save')}}" enctype="multipart/form-data">
            @csrf
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-10">                    
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="student_id"><b>Student ID<span style="color:brown">*</span></b></label>
                            <div class="col-sm-10">
                                <select class="form-select" id="student_id" name="student_id" >
                                    <option  value="99">--Select Student--</option>
                                    @foreach($student_list as $a)
                                        <option  value="{{$a->student_id}}">{{$a->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-5">
                            <label for=""><b>Exam Terms</b></label>
                            <div class="col-sm-10">
                                <select class="form-select" id="exam_terms_id" name="exam_terms_id" >
                                    <option  value="99">--Select Exam Terms--</option>
                                    @foreach($examterms as $a)
                                        <option  value="{{$a->id}}">{{$a->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                <div class="col-md-1"></div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-10">                    
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for=""><b>Class</b></label>
                            <div class="col-sm-10">
                                <select class="form-select" id="class_id" name="class_id" >
                                    <option  value="99">--Select Class--</option>
                                    @foreach($classes as $a)
                                        <option  value="{{$a->id}}">{{$a->name}}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for=""><b>Subject</b></label>
                            <div class="col-sm-10">
                                <select class="form-select" id="subject_id" name="subject_id" >
                                    <option  value="99">--Select Subject--</option>
                                    @foreach($subjects as $a)
                                        <option  value="{{$a->id}}">{{$a->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>                           
                </div>
                <div class="col-md-1"></div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="form-group col-md-5">
                    <label for="mark"><b>Mark<span style="color:brown">*</span></b></label>
                    <div class="col-sm-10">
                        <input type="text" name="mark" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-6"></div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="form-group col-md-2">
                    <div class="d-grid mt-4">
                        <input type="submit" value="Add" class="btn btn-primary">
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>
            <br />
        </form>
    </div>
</section>


@endsection