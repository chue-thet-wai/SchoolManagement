@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
	<h1>Student Attendance Report</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
			<li class="breadcrumb-item active">Reporting</li>
			<li class="breadcrumb-item active">Student Attendance Report</li>
		</ol>
	</nav>
	@include('layouts.error')
</div><!-- End Page Title -->

<section class="card">

	<div class="card-body">
		<div class="row g-4">
            <div class="col-md-11" style='color:#012970;'>
                <h4><b>Search</b></h4>
            </div>
        </div>
		<form class="row g-4" method="POST" action="{{ url('admin/reporting/student_attendance_report') }}" enctype="multipart/form-data">
			@csrf
			<div class='row g-4'>
				<div class="form-group col-md-3">
					<label for="student_name"><b>Name</b></label>
					<div class="col-sm-10">
						<input type="text" name="student_name" class="form-control" value="{{ request()->input('student_name') }}">
					</div>
				</div>
				<div class="form-group col-md-3">
					<label for="student_attfrom"><b>Attendance From</b></label>
					<div class="col-sm-10">
						<input type="date" name="student_attfrom" class="form-control" value="{{ request()->input('student_attfrom') }}">
					</div>
				</div>
				<div class="form-group col-md-3">
					<label for="student_attTo"><b>Attendance To</b></label>
					<div class="col-sm-10">
						<input type="date" name="student_attTo" class="form-control" value="{{ request()->input('student_attTo') }}">
					</div>
				</div>
			</div>
			
			<div class='row p-3'>
				<div class="form-group col-sm-1 p-2">
					<div class="d-grid mt-2">
						<button type="submit" name="action" value="search" class="btn btn-sm btn-primary">Search</button>
					</div>
				</div>
				<div class="form-group col-sm-1 p-2">
					<div class="d-grid mt-2">
						<button type="submit" name="action" value="reset" class="btn btn-sm btn-primary">Reset</button>
					</div>
				</div>	
				<div class='col-sm-9'></div>
				<div class="form-group col-sm-1 p-2">
					<div class="d-grid mt-2">
						<button type="submit" name="action" value="export" class="btn btn-sm btn-primary">Export</button>
					</div>
				</div>					
			</div>
		</form>
		<br />
		<div class="row g-4 m-2" style="display: flex;overflow-x: auto;">
			<table cellpadding="0" cellspacing="0" border="0" class="datatable table table-striped table-bordered">
				<thead>
					<tr>
						<th>No</th>
						<th>Registration No</th>
						<th>Student Id</th>
						<th>Name</th>
						<th>Date</th>
						<th>Attendance</th>
						<th>Remark</th>
					</tr>
				</thead>
				<tbody>
					@if(!empty($list_result) && $list_result->count())
						@php $i=1;@endphp
						@foreach($list_result as $res)
						<tr>
							<td>@php echo $i;$i++; @endphp</td>
							<td>{{$res->registration_no}}</td>
							<td>{{$res->student_id}}</td>
							<td>{{$res->name}}</td>
							<td>{{date('Y-m-d',strtotime($res->attendance_date))}}</td>
							<td>{{ $attendance[$res->attendance_status]}}</td>
							<td>{{$res->remark}}</td>
						</tr>
						@endforeach
					@else
					<tr>
						<td colspan="7">There are no data.</td>
					</tr>
					@endif
				</tbody>
			</table>
		</div>
		<div class="d-flex">
            {!! $list_result->links() !!}
        </div>
	</div>
</section>

@endsection