@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
	<h1>Ferry Student</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
			<li class="breadcrumb-item active">Registration</li>
			<li class="breadcrumb-item active">Ferry Student</li>
		</ol>
	</nav>
	@include('layouts.error')
</div><!-- End Page Title -->

<section class="card">
    <div class="card-body">
        <br />
        
        <div class="row g-4">
            <div class="col-md-10 content-title">
                <h4><b>Ferry Student List</b></h4>
            </div>
            <div class="col-md-2">
                <a class="btn btn-sm btn-primary" href="{{route('ferry_student.create')}}" id="form-header-btn"><span class="bi bi-plus"></span> Create</a>
            </div>
        </div>
        <br />
        <form class="row g-4" method="POST" action="{{ url('admin/ferry_student/list') }}" enctype="multipart/form-data">
			@csrf
			<div class='row g-4'>
				<div class="form-group col-md-3">
					<label for="ferrystudent_regno"><b>Registraion No.</b></label>
					<div class="col-sm-10">
						<input type="text" name="ferrystudent_regno" class="form-control" value="{{ request()->input('ferrystudent_regno') }}">
					</div>
				</div>
                <div class="form-group col-md-3">
					<label for="ferrystudent_studentid"><b>Student ID</b></label>
					<div class="col-sm-10">
						<input type="text" name="ferrystudent_studentid" class="form-control" value="{{ request()->input('ferrystudent_studentid') }}">
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
                        <th>Registration No.</th>
                        <th>Student ID</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Township</th>
                        <th>Status</th>
                        <th>Ferry Way</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($list_result) && $list_result->count())
                        @php $i=1;@endphp
                        @foreach($list_result as $res)
                        <tr>
                            <td>@php echo $i;$i++; @endphp</td>
                            <td>{{ $res->registration_no }}</td>
                            <td>{{ $res->student_id }}</td>
                            <td>{{ $res->phone }}</td>
                            <td>{{ $res->address }}</td>
                            <td>{{ $township[$res->township] }}</td>
                            <td>{{ $status[$res->status] }}</td>
                            <td>{{ $ferry_ways[$res->way] }}</td>
                            <td class="center">
                                <a href="{{route('ferry_student.edit',$res->id)}}">
                                    <button type="submit" value="delete" class="btn m-1 p-0 border-0">
                                        <span id="boot-icon" class="bi bi-pencil-square" style="font-size: 20px; color:rgb(58 69 207);"></span>
                                    </button>                            
                                </a>
                                @if ($res->status =='0')
                                <form method="post" action="{{route('ferry_student.destroy',$res->id)}}" style="display: inline;">
                                    @csrf
                                    {{ method_field('DELETE') }}
                                    <button type="submit" value="delete" class="btn m-1 p-0 border-0">
                                        <span id="boot-icon" class="bi bi-trash" style="font-size: 20px; color: rgb(165, 42, 42);"></span>
                                    </button>
                                </form>
                                @endif
                            </td>
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

