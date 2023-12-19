@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
	<h1>Ferry Report</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
			<li class="breadcrumb-item active">Reporting</li>
			<li class="breadcrumb-item active">Ferry Report</li>
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
		<form class="row g-4" method="POST" action="{{ url('admin/reporting/ferry_report') }}" enctype="multipart/form-data">
			@csrf
			<div class='row g-4'>
				<div class="form-group col-md-3">
					<label for="ferry_driverName"><b>Driver Name</b></label>
					<div class="col-sm-10">
						<input type="text" name="ferry_driverName" class="form-control" value="{{ request()->input('ferry_driverName') }}">
					</div>
				</div>
                <div class="form-group col-md-3">
					<label for="ferry_driverId"><b>Driver ID</b></label>
					<div class="col-sm-10">
						<input type="text" name="ferry_driverId" class="form-control" value="{{ request()->input('ferry_driverId') }}">
					</div>
				</div>
				<div class="form-group col-md-3">
					<label for="ferry_trackNo"><b>Track No</b></label>
					<div class="col-sm-10">
						<input type="text" name="ferry_trackNo" class="form-control" value="{{ request()->input('ferry_trackNo') }}">
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
						<th>Track No</th>
						<th>Driver ID</th>
						<th>Name</th>
						<th>Phone</th>
						<th>Car Type</th>
						<th>Car No</th>
						<th>School Time</th>
						<th>School Period</th>
					</tr>
				</thead>
				<tbody>
					@if(!empty($list_result) && $list_result->count())
						@php $i=1;@endphp
						@foreach($list_result as $res)
						<tr>
							<td>@php echo $i;$i++; @endphp</td>
							<td>{{$res->track_no}}</td>
							<td>{{$res->driver_id}}</td>
							<td>{{$res->name}}</td>
							<td>{{$res->phone}}</td>
							<td>{{$res->car_type}}</td>
							<td>{{$res->car_no}}</td>
							<td>{{$res->school_from_time}}-{{$res->school_to_time}}</td>
							<td>{{$res->school_from_period}}-{{$res->school_to_period}}</td>					
						</tr>
						@endforeach
					@else
					<tr>
						<td colspan="9">There are no data.</td>
					</tr>
					@endif
				</tbody>
			</table>
		</div>
		<div class="d-flex">
            {!! $list_result->links() !!}
        </div>
	</div>
	</div>
	</div>
</section>

@endsection