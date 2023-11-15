@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
	<h1>Payment Registration</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
			<li class="breadcrumb-item active">Registration</li>
			<li class="breadcrumb-item active">Payment Registration</li>
		</ol>
	</nav>
	@include('layouts.error')
</div><!-- End Page Title -->

<section class="card">
    <div class="card-body">
        <br />
        
        <div class="row g-4">
            <div class="col-md-11" style='color:#012970;'>
                <h4><b>Payment Registration List</b></h4>
            </div>
            <div class="col-md-1">
                <a class="btn btn-sm btn-primary" href="{{route('payment.create')}}" id="form-header-btn"> Create</a>
            </div>
        </div>
        <br />
        <form class="row g-4" method="POST" action="{{ url('admin/payment/list') }}" enctype="multipart/form-data">
			@csrf
			<div class='row g-4'>
				<div class="form-group col-md-3">
					<label for="payment_id"><b>Payment ID</b></label>
					<div class="col-sm-10">
						<input type="text" name="payment_id" class="form-control" value="{{ request()->input('payment_id') }}">
					</div>
				</div>
				<div class="form-group col-md-3">
					<label for="payment_regno"><b>Registraion No.</b></label>
					<div class="col-sm-10">
						<input type="text" name="payment_regno" class="form-control" value="{{ request()->input('payment_regno') }}">
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
			</div>
		</form>
		<br />
        <div class="row g-4 m-2" style="display: flex;overflow-x: auto;">
            <table cellpadding="0" cellspacing="0" class="datatable table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Payment ID</th>
                        <th>Registration No</th>
                        <th>Pay Date</th>
                        <th>Payment Type</th>
                        <th>Total Amount</th>
                        <th>Discount (%)</th>
                        <th>Net Amount</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($list_result) && $list_result->count())
                        @php $i=1;@endphp
                        @foreach($list_result as $res)
                        <tr>
                            <td>@php echo $i;$i++; @endphp</td>
                            <td>{{ $res->payment_id }}</td>
                            <td>{{ $res->registration_no }}</td>
                            <td>{{ date('Y-m-d',strtotime($res->pay_date)) }}</td>
                            <td>{{ $payment_type[$res->payment_type] }}</td>
                            <td>{{ $res->total_amount }}</td>
                            <td>{{ $res->discount_percent }}</td>
                            <td>{{ $res->net_total }}</td>
                            <td class="center">
                                <a href="{{route('payment.edit',$res->payment_id)}}">
                                    <button type="submit" value="delete" class="btn m-1 p-0 border-0">
                                        <span id="boot-icon" class="bi bi-pencil-square" style="font-size: 20px; color:rgb(58 69 207);"></span>
                                    </button>                            
                                </a>
                                <form method="post" action="{{route('payment.destroy',$res->payment_id)}}" style="display: inline;">
                                    @csrf
                                    {{ method_field('DELETE') }}
                                    <button type="submit" value="delete" class="btn m-1 p-0 border-0">
                                        <span id="boot-icon" class="bi bi-trash" style="font-size: 20px; color: rgb(165, 42, 42);"></span>
                                    </button>
                                </form>
                            </td>
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
</section>


@endsection

