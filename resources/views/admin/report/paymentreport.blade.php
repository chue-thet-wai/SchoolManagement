@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
	<h1>Payment Report</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
			<li class="breadcrumb-item active">Report</li>
			<li class="breadcrumb-item active">Payment Report</li>
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
		<form class="row g-4" method="POST" action="{{ url('admin/reporting/payment_report') }}" enctype="multipart/form-data">
			@csrf
			<div class='row g-4'>
				<div class="form-group col-md-3">
					<label for="payment_name"><b>Name</b></label>
					<div class="col-sm-10">
						<input type="text" name="payment_name" class="form-control" value="{{ request()->input('payment_name') }}">
					</div>
				</div>
				<div class="form-group col-md-3">
					<label for="payment_regno"><b>Registration No</b></label>
					<div class="col-sm-10">
						<input type="text" name="payment_regno" class="form-control" value="{{ request()->input('payment_regno') }}">
					</div>
				</div>
				<div class="form-group col-md-3">
					<label for="payment_paymentId"><b>Payment ID</b></label>
					<div class="col-sm-10">
						<input type="text" name="payment_paymentId" class="form-control" value="{{ request()->input('payment_paymentId') }}">
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
		<table cellpadding="0" cellspacing="0" border="0" class="datatable table table-striped table-bordered">
			<thead>
				<tr>
					<th>No</th>
                    <th>Payment ID</th>
					<th>Registration No</th>	
                    <th>Name</th>					
					<th>Pay Date</th>
					<th>Total Amount</th>
                    <th>Discount Percent</th>
                    <th>Net Total</th>
				</tr>
			</thead>
			<tbody>
				@if(!empty($list_result) && $list_result->count())
					@php $i=1;@endphp
					@foreach($list_result as $res)
					<tr>
						<td>@php echo $i;$i++; @endphp</td>
                        <td>{{$res->payment_id}}</td>
						<td>{{$res->registration_no}}</td>	
                        <td>{{$res->name}}</td>					
						@if ($res->pay_date != null) 
							<td>{{date('Y-m-d',strtotime($res->pay_date))}}</td>
						@else 
							<td></td>
						@endif
						<td>{{$res->total_amount}}</td>
                        <td>{{$res->discount_percent}}</td>
                        <td>{{$res->net_total}}</td>
					</tr>
					@endforeach
				@else
				<tr>
					<td colspan="8">There are no data.</td>
				</tr>
				@endif
			</tbody>
		</table>
		<div class="d-flex">
            {!! $list_result->links() !!}
        </div>
	</div>
	</div>
	</div>
</section>

@endsection