@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
	<h1>Driver Attendance Report</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
			<li class="breadcrumb-item active">Reporting</li>
			<li class="breadcrumb-item active">Driver Attendance Report</li>
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
		<form class="row g-4" method="POST" action="{{ url('admin/reporting/driver_attendance_report') }}" enctype="multipart/form-data">
			@csrf
			<div class='row g-4'>
				<div class="form-group col-md-3">
					<label for="driver_name"><b>Name</b></label>
					<div class="col-sm-10">
						<input type="text" name="driver_name" class="form-control" value="{{ request()->input('driver_name') }}">
					</div>
				</div>
				<div class="form-group col-md-3">
					<label for="driver_attfrom"><b>Attendance From</b></label>
					<div class="col-sm-10">
						<input type="date" name="driver_attfrom" class="form-control" value="{{ request()->input('driver_attfrom') }}">
					</div>
				</div>
				<div class="form-group col-md-3">
					<label for="driver_attTo"><b>Attendance To</b></label>
					<div class="col-sm-10">
						<input type="date" name="driver_attTo" class="form-control" value="{{ request()->input('driver_attTo') }}">
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
						<th>Driver Id</th>
						<th>Name</th>
						<th>Date</th>
						<th>Check In</th>
						<th>Check Out</th>
					</tr>
				</thead>
				<tbody>
					@if(!empty($list_result) && $list_result->count())
						@php $i=1;@endphp
						@foreach($list_result as $res)
						<tr>
							<td>@php echo $i;$i++; @endphp</td>
							<td>{{$res->driver_id}}</td>
							<td>{{$res->name}}</td>
							<td>{{date('Y-m-d',strtotime($res->attendance_date))}}</td>
							<td>{{ $res->check_in}}</td>
							<td>{{$res->check_out}}</td>
						</tr>
						@endforeach
					@else
					<tr>
						<td colspan="6">There are no data.</td>
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