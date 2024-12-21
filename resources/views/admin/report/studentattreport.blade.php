@extends('layouts.dashboard')

@section('content')
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> 

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
            <div class="col-md-11 content-title">
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
						<th>Status</th>
						<th>Teacher Remark</th>
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
							<td> @if ($res->status < 1)
								<a href="#" data-toggle="modal" data-target="#myModalLeaveStatus{{$res->id}}">{{ $approveStatus[$res->status]}}</a>
								 @else 
								 	{{ $approveStatus[$res->status]}}
								@endif
							</td>
							<td>{{$res->teacher_remark}}</td>
							<td>{{$res->remark}}</td>
							<!-- Confirm Modal -->
                            <div class="modal" id="myModalLeaveStatus{{$res->id}}">
                                <div class="modal-dialog">
                                    <form class="row g-4" method="POST" action="{{url('admin/reporting/student_attendance_report/approve')}}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-content">
                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h4 class="modal-title">Leave</h4>
                                            </div>

                                            <!-- Modal Body -->
                                            <div class="modal-body">
												<input type="hidden" name="attendance_id" value="{{$res->id}}" />
                                                <div class='row g-4'>
                                                    <div class="col-md-1"></div>
                                                    <div class="form-group col-md-10">
                                                        <label for="leave_studentid"><b>Student ID</b></label>
                                                        <div class="col-sm-10">
                                                            <input type="text" name="leave_studentid" class="form-control" value="{{ $res->student_id }}" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <br />
                                                <div class='row g-4'>
                                                    <div class="col-md-1"></div>
                                                    <div class="form-group col-md-10">
                                                        <label for="paid_paidtype"><b>Status</b></label>
                                                        <div class="col-sm-10">
                                                            <select class="form-select" id="leave_approvestatus" name="leave_approvestatus">
                                                                @foreach($approveStatus as $key => $value)
                                                                    <option  value="{{$key}}" >{{$value}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <br />
                                                <div class='row g-4'>
                                                    <div class="col-md-1"></div>
                                                    <div class="form-group col-md-10">
                                                        <label for="leave_teacherremark"><b>Teacher Remark</b></label>
                                                        <div class="col-sm-10">
                                                            <textarea class="form-control" id="leave_teacherremark" name="leave_teacherremark"></textarea>
                                                        </div>
                                                    </div>
                                                </div>	
                                                <div class="row g-4">
                                                    <div class="col-md-1"></div>
                                                    <div class="form-group col-md-3">
                                                        <div class="d-grid mt-4">
                                                            <input type="submit" value="Submit" class="btn btn-primary">
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <div class="d-grid mt-4">
                                                            <input type="submit" value="Close" class="btn btn-primary" data-dismiss="modal">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1"></div>
                                                </div>
                                                <br />
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- End Paid Modal-->
						</tr>
						@endforeach
					@else
					<tr>
						<td colspan="10">There are no data.</td>
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