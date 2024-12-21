@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
	<h1>Cash In History</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
			<li class="breadcrumb-item active">Wallet</li>
			<li class="breadcrumb-item active">Cash In History</li>
		</ol>
	</nav>
	@include('layouts.error')
</div><!-- End Page Title -->

<section class="card">
    <div class="card-body">
        <br />       
        <div class="row g-4">
            <div class="col-md-10 content-title">
                <h4><b>Cash In History</b></h4>
            </div>
        </div>
        <br />
        <form class="row g-4" method="POST" href="{{ url('admin/cash_counter/list') }}" enctype="multipart/form-data">
			@csrf
			<div class='row g-4'>
				<div class="form-group col-md-3">
					<label for="cashcounter_cardid"><b>Card ID</b></label>
					<div class="col-sm-10">
						<input type="text" name="cashcounter_cardid" class="form-control" value="{{ request()->input('cashcounter_cardid') }}">
					</div>
				</div>
				<div class="form-group col-md-3">
                    <label for="cashcounter_studentid"><b>Student ID</b></label>
					<div class="col-sm-10">
						<input type="text" name="cashcounter_studentid" class="form-control" value="{{ request()->input('cashcounter_studentid') }}">
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
                        <th>Student ID</th>
                        <th>Card ID</th>
                        <th>Name</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($list_result) && $list_result->count() > 0)
                        @php $i=1;@endphp
                        @foreach($list_result as $res)
                            @php 
                                $rowCount = 1;
                                if(array_key_exists($res->card_id,$wallet_result)) 
                                {
                                    $rowCount = count($wallet_result[$res->card_id]);
                                    $wallet_count = 1;

                            @endphp
                            <tr>
                                <td rowspan="{{$rowCount}}">@php echo $i;$i++; @endphp</td>
                                <td rowspan="{{$rowCount}}">{{ $res->student_id }}</td>
                                <td rowspan="{{$rowCount}}">{{ $res->card_id }}</td>
                                <td rowspan="{{$rowCount}}">{{ $res->name }}</td>
                                    @foreach($wallet_result[$res->card_id] as $w)
                                        @if($wallet_count != 1)
                                        <tr>
                                            <td>{{ $w['amount'] }}</td>
                                            <td>{{ $w['cash_status'] }}</td>
                                        </tr>
                                        @else
                                            <td>{{ $w['amount'] }}</td>
                                            <td>{{ $w['cash_status'] }}</td>
                                            <td rowspan="{{$rowCount}}">{{ $w['total_amount'] }}</td>   
                                            </tr>
                                        @endif
                                        @php $wallet_count +=1; @endphp
                                    @endforeach                                 
                            @php
                                unset($wallet_result[$res->card_id]);
                                }
                            @endphp
                        @endforeach
                    @else
                    <tr>
                        <td colspan="7">There are no data.</td>
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

