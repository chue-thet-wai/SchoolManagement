@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
	<h1>Cancel Report</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
			<li class="breadcrumb-item active">Reporting</li>
			<li class="breadcrumb-item active">Cancel Report</li>
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
		<form class="row g-4" method="POST" action="{{ url('admin/reporting/cancel_report') }}" enctype="multipart/form-data">
			@csrf
			<div class='row g-4'>
				<div class="form-group col-md-3">
					<label for="cancel_name"><b>Name</b></label>
					<div class="col-sm-10">
						<input type="text" name="cancel_name" class="form-control" value="{{ request()->input('cancel_name') }}">
					</div>
				</div>
				<div class="form-group col-md-3">
					<label for="cancel_regno"><b>Registration No</b></label>
					<div class="col-sm-10">
						<input type="text" name="cancel_regno" class="form-control" value="{{ request()->input('cancel_regno') }}">
					</div>
				</div>
				<div class="form-group col-md-3">
					<label for="cancel_studentId"><b>Student ID</b></label>
					<div class="col-sm-10">
						<input type="text" name="cancel_studentId" class="form-control" value="{{ request()->input('cancel_studentId') }}">
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
						<th>Student ID</th>
						<th>Name</th>
						<th>Cancel Date</th>
						<th>Refund Amount</th>
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
							@if ($res->cancel_date != null) 
								<td>{{date('Y-m-d',strtotime($res->cancel_date))}}</td>
							@else 
								<td></td>
							@endif
							<td>{{$res->refund_amount}}</td>
						</tr>
						@endforeach
					@else
					<tr>
						<td colspan="5">There are no data.</td>
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