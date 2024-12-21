@extends('layouts.dashboard')

@section('content')
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="{{ asset('js/exammarks.js') }}"></script>
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
    @php
        $gradeList = [];
    @endphp
    <div class="card-body">
        <br />
        
        <div class="row g-4">
            <div class="col-md-1"></div>
            <div class="col-md-9 content-title">
                <h4><b>Update Exam Marks</b></h4>
            </div>
            <div class="col-md-2">
                <a class="btn btn-sm btn-primary" href="{{ url('admin/exam_marks/list') }}" id="form-header-btn"><i class="bi bi-skip-backward-fill"></i> Back</a>
            </div>
        </div>

        <br />
        <form method="POST" action="{{ url('admin/exam_marks/update/'.$result[0]->id) }}" enctype="multipart/form-data">
            @csrf
            <br />
            {{method_field('POST')}}
            <input type="hidden" id="token" value="<?php echo csrf_token(); ?>" />
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
                                        <option  value="{{$a->id}}"
                                        @if ($result[0]->class_id == $a->id)
                                            selected
                                        @endif
                                        >{{$a->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="student_id"><b>Student ID<span style="color:brown">*</span></b></label>
                            <div class="col-sm-10">
                                <select class="form-select" id="student_id" name="student_id" >
                                    <option  value="99">--Select Student--</option>
                                    @foreach($student_list as $a)
                                        <option  value="{{$a->student_id}}"
                                        @if ($result[0]->student_id == $a->student_id)
                                            selected
                                        @endif
                                        >{{$a->name}}</option>
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
                            <label for=""><b>Exam Terms</b></label>
                            <div class="col-sm-10">
                                <select class="form-select" id="exam_terms_id" name="exam_terms_id" >
                                    <option  value="99">--Select Exam Terms--</option>
                                    @foreach($examterms as $a)
                                        <option  value="{{$a->id}}"
                                        @if ($result[0]->exam_terms_id == $a->id)
                                            selected
                                        @endif
                                        >{{$a->name}}</option>
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
                                        <option  value="{{$a->id}}"
                                        @if ($result[0]->subject_id == $a->id)
                                            selected
                                        @endif
                                        >{{$a->name}}</option>
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
                        <input type="text" name="mark" value="{{$result[0]->mark}}" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-6"></div>
            </div>
            <br />
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