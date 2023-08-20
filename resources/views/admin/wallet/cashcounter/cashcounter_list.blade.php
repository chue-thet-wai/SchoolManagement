@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
	<h1>Cash Counter</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
			<li class="breadcrumb-item active">Wallet</li>
			<li class="breadcrumb-item active">Cash Counter</li>
		</ol>
	</nav>
	@include('layouts.error')
</div><!-- End Page Title -->

<section class="card">
    <div class="card-body">
        <br />
       
        <div class="row g-4">
            <div class="col-md-11" style='color:#012970;'>
                <h4><b>Cash Counter List</b></h4>
            </div>
            <div class="col-md-1">
                <a class="btn btn-sm btn-primary" href="{{url('admin/cash_counter/create')}}" id="form-header-btn"> Create</a>
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
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($list_result) && $list_result->count())
                        @php $i=1;@endphp
                        @foreach($list_result as $res)
                        <tr>
                            <td>@php echo $i;$i++; @endphp</td>
                            <td>{{ $res->student_id }}</td>
                            <td>{{ $res->card_id }}</td>
                            <td>{{ $res->name }}</td>
                            <td>{{ $res->amount }}</td>
                            <td class="center">
                                <a href="{{ url('admin/cash_counter/edit/'.$res->id) }}">
                                    <button type="submit" value="edit" class="btn m-1 p-0 border-0">
                                        <span id="boot-icon" class="bi bi-pencil-square" style="font-size: 20px; color:rgb(58 69 207);"></span>
                                    </button>                            
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    @else
                    <tr>
                        <td colspan="8">There are no data.</td>
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

