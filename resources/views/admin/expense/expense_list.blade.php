@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
	<h1>Expense</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
			<li class="breadcrumb-item active">Expense</li>
		</ol>
	</nav>
	@include('layouts.error')
</div><!-- End Page Title -->

<section class="card">
    <div class="card-body">
        <br />
       
        <div class="row g-4">
            <div class="col-md-10 content-title">
                <h4><b>Expense List</b></h4>
            </div>
            <div class="col-md-2">
                <a class="btn btn-sm btn-primary" href="{{url('admin/expense/create')}}" id="form-header-btn"><span class="bi bi-plus"></span> Create</a>
            </div>
        </div>
        <br />
        <form class="row g-4" method="POST" href="{{ url('admin/expense/list') }}" enctype="multipart/form-data">
			@csrf
			<div class='row g-4'>
				<div class="form-group col-md-3">
					<label for="expense_title"><b>Title</b></label>
					<div class="col-sm-10">
						<input type="text" name="expense_title" class="form-control" value="{{ request()->input('expense_title') }}">
					</div>
				</div>
                <div class="form-group col-md-3">
					<label for="expensefilter_date"><b>Expense Date</b></label>
					<div class="col-sm-10">
                        @if (request()->input('expensefilter_date')=='')
                            <input type="date" id="expensefilter_date" name="expensefilter_date" class="form-control">
                        @else 
                            <input type="date" id="expensefilter_date" name="expensefilter_date" value="{{date('Y-m-d',strtotime(request()->input('expensefilter_date')))}}" class="form-control">
                        @endif
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
                        <th>Title</th>
                        <th>Expense Date</th>
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
                            <td>{{ $res->title }}</td>
                            <td>@if($res->expense_date != '') 
                                {{ date('Y-m-d',strtotime($res->expense_date)) }} @else '' @endif
                            </td>
                            <td>{{ $res->amount }}</td>
                            <td class="center">
                                <a href="{{ url('admin/expense/edit/'.$res->id) }}">
                                    <button type="submit" value="edit" class="btn m-1 p-0 border-0">
                                        <span id="boot-icon" class="bi bi-pencil-square" style="font-size: 20px; color:rgb(58 69 207);"></span>
                                    </button>                            
                                </a>
                                <form method="post" action="{{ url('admin/expense/delete/'.$res->id) }}" style="display: inline;">
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
</section>


@endsection

