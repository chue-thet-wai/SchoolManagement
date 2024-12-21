@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
	<h1>Ferry Payment Report</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
			<li class="breadcrumb-item active">Driver</li>
			<li class="breadcrumb-item active">Ferry Payment Report</li>
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
		<form class="row g-4" method="POST" action="{{ url('admin/ferry_payment') }}" enctype="multipart/form-data">
			@csrf
			<div class='row g-4'>
				<div class="form-group col-md-3">
					<label for="payment_name"><b>Name</b></label>
					<div class="col-sm-10">
						<input type="text" name="ferrypayment_name" class="form-control" value="{{ request()->input('ferrypayment_name') }}">
					</div>
				</div>
				<div class="form-group col-md-3">
					<label for="payment_regno"><b>Student ID</b></label>
					<div class="col-sm-10">
						<input type="text" name="ferrypayment_studentid" class="form-control" value="{{ request()->input('ferrypayment_studentid') }}">
					</div>
				</div>
				<div class="form-group col-md-3">
					<label for="payment_paymentId"><b>Invoice ID</b></label>
					<div class="col-sm-10">
						<input type="text" name="ferrypayment_paymentId" class="form-control" value="{{ request()->input('ferrypayment_paymentId') }}">
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
						<th>Invoice ID</th>
						<th>Student ID</th>	
						<th>Name</th>					
						<th>Pay Date</th>
						<th>Payment Type</th>
						<th>Pay Period From</th>
						<th>Pay Period To</th>
						<th>Net Total</th>
					</tr>
				</thead>
				<tbody>
					@if(!empty($list_result) && $list_result->count())
						@php $i=1;@endphp
						@foreach($list_result as $res)
						<tr>
							<td>@php echo $i;$i++; @endphp</td>
							<td>{{$res->invoice_id}}</td>
							<td>{{$res->student_id}}</td>
							<td>{{$res->name}}</td>					
							@if ($res->paid_date != null) 
								<td>{{date('Y-m-d',strtotime($res->paid_date))}}</td>
							@else 
								<td></td>
							@endif							
							<td>{{$payment_types[$res->payment_type]}}</td>
							<td>{{date('Y-m-d',strtotime($res->pay_from_period))}}</td>
							<td>{{date('Y-m-d',strtotime($res->pay_to_period))}}</td>
							<td>{{$res->net_total}}</td>
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
	</div>
	</div>
</section>

@endsection