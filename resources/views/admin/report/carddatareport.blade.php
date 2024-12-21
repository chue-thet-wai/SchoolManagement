@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
	<h1>Card Data Report</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
			<li class="breadcrumb-item active">Reporting</li>
			<li class="breadcrumb-item active">Card Data Report</li>
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
		<form class="row g-4" method="POST" action="{{ url('admin/reporting/card_data_report') }}" enctype="multipart/form-data">
			@csrf
			<div class='row g-4'>
				<div class="form-group col-md-3">
					<label for="carddata_studentid"><b>Student ID</b></label>
					<div class="col-sm-10">
						<input type="text" name="carddata_studentid" class="form-control" value="{{ request()->input('carddata_studentid') }}">
					</div>
				</div>
				<div class="form-group col-md-3">
					<label for="carddata_studentname"><b>Student Name</b></label>
					<div class="col-sm-10">
						<input type="text" name="carddata_studentname" class="form-control" value="{{ request()->input('carddata_studentname') }}">
					</div>
				</div>
				<div class="form-group col-md-3">
					<label for="carddata_cardid"><b>Card ID</b></label>
					<div class="col-sm-10">
						<input type="text" name="carddata_cardid" class="form-control" value="{{ request()->input('carddata_cardid') }}">
					</div>
				</div>
			</div>
            <div class='row p-3'>
            <div class="form-group col-md-3">
					<label for="carddata_fromdate"><b>From Date</b></label>
					<div class="col-sm-10">
						<input type="date" name="carddata_fromdate" class="form-control" value="{{ request()->input('carddata_fromdate') }}">
					</div>
				</div>
				<div class="form-group col-md-3">
					<label for="carddata_todate"><b>To Date</b></label>
					<div class="col-sm-10">
						<input type="date" name="carddata_todate" class="form-control" value="{{ request()->input('carddata_todate') }}">
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
						<th>Student ID</th>
						<th>Card ID</th>
						<th>Name</th>
						<th>Amount</th>
						<th>Status</th>
                        <th>Date</th>
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
							<td>{{$res->name}}</td>
                            <td>{{$res->amount}}</td>
                            <td>{{$card_status[$res->status]}}</td>
							<td>{{date('Y-m-d',strtotime($res->created_at))}}</td>
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