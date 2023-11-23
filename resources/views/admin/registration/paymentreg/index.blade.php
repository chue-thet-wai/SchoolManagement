@extends('layouts.dashboard')

@section('content')
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

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
            <div class="col-md-10" style='color:#012970;'>
                <h4><b>Invoice List</b></h4>
            </div>
            <div class="col-md-2">
                <a class="btn btn-primary" href="{{route('payment.create')}}" id="form-header-btn" style="color:white;"><span class="bi bi-plus"></span> Invoice</a>
            </div>
        </div>
        <br />
        <form class="row g-4" method="POST" action="{{ url('admin/payment/list') }}" enctype="multipart/form-data">
			@csrf
			<div class='row g-4'>
				<div class="form-group col-md-3">
					<label for="payment_invoiceid"><b>Invoice ID</b></label>
					<div class="col-sm-10">
						<input type="text" name="payment_invoiceid" class="form-control" value="{{ request()->input('payment_invoiceid') }}">
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
                        <th>Invoice ID</th>
                        <th>Registration No</th>
                        <th>Pay Date</th>
                        <th>Payment Type</th>
                        <th>Total Amount</th>
                        <th>Discount (%)</th>
                        <th>Net Amount</th>
                        <th>Paid Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($list_result) && $list_result->count())
                        @php $i=1;@endphp
                        @foreach($list_result as $res)
                        <tr>
                            <td>@php echo $i;$i++; @endphp</td>
                            <td>{{ $res->invoice_id }}</td>
                            <td>{{ $res->registration_no }}</td>
                            <td> @if ($res->paid_date != "") {{date('Y-m-d',strtotime($res->paid_date))}} @endif </td>
                            <td>{{ $payment_type[$res->payment_type] }}</td>
                            <td>{{ $res->total_amount }}</td>
                            <td>{{ $res->discount_percent }}</td>
                            <td>{{ $res->net_total }}</td>
                            <td> @if ($res->paid_status == 0)
                                    <a href="#" data-toggle="modal" data-target="#myModalPaid{{$res->invoice_id}}">Unpaid</a>
                                @else
                                    Paid
                                @endif
                            </td>
                            <td class="center">
                                <a href="{{route('payment.edit',$res->invoice_id)}}">
                                    <button type="submit" value="delete" class="btn m-1 p-0 border-0">
                                        <span id="boot-icon" class="bi bi-pencil-square" style="font-size: 20px; color:rgb(58 69 207);"></span>
                                    </button>                            
                                </a>
                                <form method="post" action="{{route('payment.destroy',$res->invoice_id)}}" style="display: inline;">
                                    @csrf
                                    {{ method_field('DELETE') }}
                                    <button type="submit" value="delete" class="btn m-1 p-0 border-0">
                                        <span id="boot-icon" class="bi bi-trash" style="font-size: 20px; color: rgb(165, 42, 42);"></span>
                                    </button>
                                </form>
                            </td>
                            <!-- Paid Modal -->
                            <div class="modal" id="myModalPaid{{$res->invoice_id}}">
                                <div class="modal-dialog">
                                    <form class="row g-4" method="POST" action="{{ url('admin/payment/paid') }}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-content">
                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h4 class="modal-title">Paid</h4>
                                            </div>

                                            <!-- Modal Body -->
                                            <div class="modal-body">
                                                <div class='row g-4'>
                                                    <div class="col-md-1"></div>
                                                    <div class="form-group col-md-10">
                                                        <label for="paid_invoiceid"><b>Invoice ID</b></label>
                                                        <div class="col-sm-10">
                                                            <input type="text" name="paid_invoiceid" class="form-control" value="{{ $res->invoice_id }}" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <br />
                                                <div class='row g-4'>
                                                    <div class="col-md-1"></div>
                                                    <div class="form-group col-md-10">
                                                        <label for="paid_registrationno"><b>Registraion No.</b></label>
                                                        <div class="col-sm-10">
                                                            <input type="text" name="paid_registrationno" class="form-control" value="{{ $res->registration_no }}" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <br />
                                                <div class='row g-4'>
                                                    <div class="col-md-1"></div>
                                                    <div class="form-group col-md-10">
                                                        <label for="paid_nettotal"><b>Net Total</b></label>
                                                        <div class="col-sm-10">
                                                            <input type="text" name="paid_nettotal" class="form-control" value="{{ $res->net_total }}" readonly>
                                                        </div>
                                                    </div>
                                                </div>	
                                                <br />
                                                <div class='row g-4'>
                                                    <div class="col-md-1"></div>
                                                    <div class="form-group col-md-10">
                                                        <label for="paid_paidtype"><b>Paid Type</b></label>
                                                        <div class="col-sm-10">
                                                            <select class="form-select" id="paid_paidtype" name="paid_paidtype">
                                                                @foreach($paid_type as $key => $value)
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
                                                        <label for="paid_paiddate"><b>Paid Date</b><span style="color:brown">*</span></label>
                                                        <div class="col-sm-10">
                                                            <input type="date" id="paid_paiddate" name="paid_paiddate" class="form-control" required>
                                                        </div>
                                                    </div>
                                                </div>	
                                                <br />	
                                                <div class='row g-4'>
                                                    <div class="col-md-1"></div>
                                                    <div class="form-group col-md-10">
                                                        <label for="paid_remark"><b>Remark</b></label>
                                                        <div class="col-sm-10">
                                                            <textarea class="form-control" id="paid_remark" name="paid_remark"></textarea>
                                                        </div>
                                                    </div>
                                                </div>	
                                                <div class="row g-4">
                                                    <div class="col-md-1"></div>
                                                    <div class="form-group col-md-3">
                                                        <div class="d-grid mt-4">
                                                            <input type="submit" value="Paid" class="btn btn-primary">
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

