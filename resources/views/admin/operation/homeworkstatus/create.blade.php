@extends('layouts.dashboard')

@section('content')
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="{{ asset('js/homeworkstatus.js') }}"></script>
<div class="pagetitle">
    <h1>Homework Status</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item active">Operation</li>
            <li class="breadcrumb-item active">Homework Status</li>
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
                <h4><b>Create Homework Status</b></h4>
            </div>
            <div class="col-md-2">
                <form method="post" action="{{ url('admin/homework_status/list') }}" style="display: inline;">
                    @csrf
                    <input type="hidden" name="homework_id" value="{{$homework_data['id']}}" />
                    <button type="submit" value="detail" class="btn btn-sm btn-primary" id="form-header-btn">
                        <i class="bi bi-skip-backward-fill"></i> Back
                    </button>
                </form>
            </div>
        </div>
        <br />
        <form method="POST" action="{{route('homework_status.store')}}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="token" value="<?php echo csrf_token(); ?>" />
            <input type="hidden" name="homework_id" value="{{$homework_data['id']}}" />
            <div class="row g-4 mt-1">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <div class='col-sm-4'>
                            <label for="class_id"><b>Class</b></label>
                        </div>
                        <div class='col-sm-8'>
                            <input type="text" name="class_name" class="form-control" value="{{$homework_data['class_name']}}" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-4 mt-1">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <div class='col-sm-4'>
                            <label for="homework_id"><b>Homework</b></label>
                        </div>
                        <div class='col-sm-8'>
                            <input type="text" name="homework_title" class="form-control" value="{{$homework_data['title']}}" readonly>
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
                                @foreach($student_list as $student)
                                    <option value="{{$student->student_id}}">{{$student->name}}</option>
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
                            <label for="status"><b>Status</b><span style="color:brown">*</span></label>
                        </div>
                        <div class='col-sm-8'>
                            <select class="form-select" id="status" name="status" required>
                                @foreach($status_list as $key => $value)
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
                        <label for="remark"><b>Remark</b></label>
                        <div class="col-sm-8">
                            <textarea name="remark" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
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