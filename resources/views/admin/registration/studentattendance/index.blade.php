@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
	<h1>Student Attendance</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
			<li class="breadcrumb-item active">Registration</li>
			<li class="breadcrumb-item active">Student Attendance</li>
		</ol>
	</nav>
	@include('layouts.error')
</div><!-- End Page Title -->

<section class="card">

	<div class="card-body">
		<div class="row g-4">
            <div class="col-md-11 content-title">
                <h4><b>Search</b></h4>
            </div>
        </div>
		<form class="row g-4" method="GET" action="{{route('student_attendance.create')}}" enctype="multipart/form-data">
			@csrf
			<div class='row g-4'>
				<div class="form-group col-md-3">
					<label for="attendance_grade"><b>Grade</b></label>
					<div class="col-sm-10">
						<select class="form-select" id="attendance_grade" name="attendance_grade">
							@foreach($grade_list as $key => $value)
							<option value="{{$key}}">{{$value}}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="form-group col-md-3">
					<label for="attendance_section"><b>Section</b></label>
					<div class="col-sm-10">
						<select class="form-select" id="attendance_section" name="attendance_section">
							@foreach($section_list as $key => $value)
							<option value="{{$key}}">{{$value}}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="form-group col-md-3">
					<label for="attendance_studentdate"><b>Choose Date</b></label>
					<div class="col-sm-10">
						<input type="date" name="attendance_studentdate" class="form-control" required>
					</div>
				</div>
				<div class="form-group col-md-2">
					<div class="d-grid mt-4">
						<input type="submit" value="Apply" class="btn btn-primary">
					</div>
				</div>
			</div>
		</form>
		<br />
	</div>
</section>

@endsection