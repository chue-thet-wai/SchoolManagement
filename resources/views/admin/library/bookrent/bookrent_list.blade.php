@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
	<h1>Book Rent</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
			<li class="breadcrumb-item active">Book Rent</li>
			<li class="breadcrumb-item active">Book Rent</li>
		</ol>
	</nav>
	@include('layouts.error')
</div><!-- End Page Title -->

<section class="card">
    <div class="card-body">
        <br />       
        <div class="row g-4">
            <div class="col-md-11" style='color:#012970;'>
                <h4><b>Book Rent List</b></h4>
            </div>
            <div class="col-md-1">
                <a class="btn btn-sm btn-primary" href="{{url('admin/book_rent/create')}}" id="form-header-btn"> Create</a>
            </div>
        </div>
        <br />
        <form class="row g-4" method="POST" href="{{ url('admin/book_rent/list') }}" enctype="multipart/form-data">
			@csrf
			<div class='row g-4'>
				<div class="form-group col-md-3">
					<label for="student_name"><b>Name</b></label>
					<div class="col-sm-10">
						<input type="text" name="student_name" class="form-control" value="{{ request()->input('bookrent_studnet') }}">
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
                        <th>Book</th>
                        <th>Student Name</th>
                        <th>Rent Date</th>
                        <th>Return Date</th>
                        <th>Actual Return Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($list_result) && $list_result->count())
                        @php $i=1;@endphp
                        @foreach($list_result as $res)
                        <tr>
                            <td>@php echo $i;$i++; @endphp</td>
                            <td>{{ $books[$res->book_id] }}</td>
                            <td>{{ $studentList[$res->student_id] }}</td>
                            <td>{{ $res->rent_date }}</td>
                            <td>{{ $res->return_date }}</td>
                            <td>{{ $res->actual_return_date }}</td>
                            <td class="center">
                                <a href="{{ url('admin/book_rent/edit/'.$res->id) }}">
                                    <button type="submit" value="edit" class="btn m-1 p-0 border-0">
                                        <span id="boot-icon" class="bi bi-pencil-square" style="font-size: 20px; color:rgb(58 69 207);"></span>
                                    </button>                            
                                </a>
                                <form method="post" action="{{ url('admin/book_rent/delete/'.$res->id) }}" style="display: inline;">
                                    @csrf
                                    {{ method_field('DELETE') }}
                                    <button type="submit" value="delete" class="btn m-1 p-0 border-0">
                                        <span id="boot-icon" class="bi bi-trash" style="font-size: 20px; color: rgb(58 69 207);"></span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    @else
                    <tr>
                        <td colspan="6">There are no data.</td>
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

