@extends('layouts.dashboard')

@section('content')
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> 

<div class="pagetitle">
	<h1>Pocket Money Report</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
			<li class="breadcrumb-item active">Reporting</li>
			<li class="breadcrumb-item active">Pocket Money Report</li>
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
		<form class="row g-4" method="POST" action="{{ url('admin/reporting/pocket_money_report') }}" enctype="multipart/form-data">
			@csrf
			<div class='row g-4'>
				<div class="form-group col-md-3">
					<label for="pocketmoney_studentid"><b>Student ID</b></label>
					<div class="col-sm-10">
						<input type="text" name="pocketmoney_studentid" class="form-control" value="{{ request()->input('pocketmoney_studentid') }}">
					</div>
				</div>
				<div class="form-group col-md-3">
					<label for="pocketmoney_studentname"><b>Student Name</b></label>
					<div class="col-sm-10">
						<input type="text" name="pocketmoney_studentname" class="form-control" value="{{ request()->input('pocketmoney_studentname') }}">
					</div>
				</div>
				<div class="form-group col-md-3">
					<label for="pocketmoney_guardianname"><b>Guardian Name</b></label>
					<div class="col-sm-10">
						<input type="text" name="pocketmoney_guardianname" class="form-control" value="{{ request()->input('pocketmoney_guardianname') }}">
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
						<th>Student Id</th>
						<th>Card ID</th>
                        <th>Student Name</th>
                        <th>Guardian Name</th>
                        <th>Amount</th>
						<th>Date</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody>
					@if(!empty($list_result) && $list_result->count())
						@php $i=1;@endphp
						@foreach($list_result as $res)
						<tr>
							<td>@php echo $i;$i++; @endphp</td>
							<td>{{$res->student_id}}</td>
                            <td>{{$res->card_id}}</td>
							<td>{{$res->student_name}}</td>
                            <td>{{$res->guardian_name}}</td>
                            <td>{{$res->amount}}</td>
							<td>{{date('Y-m-d',strtotime($res->created_at))}}</td>
							<td> {{ $approveStatus[$res->status]}}</td>
							
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