@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
	<h1>Student Information</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
			<li class="breadcrumb-item active">Create Information</li>
			<li class="breadcrumb-item active">Student Information</li>
		</ol>
	</nav>
	@include('layouts.error')
</div><!-- End Page Title -->

<section class="card">
    <div class="card-body">
        <br />
        
        <div class="row g-4">
            <div class="col-md-11 content-title">
                <h4><b>Student Information List</b></h4>
            </div>
        </div>
        <form class="row g-4" method="POST" href="{{ url('admin/student_info/list') }}" enctype="multipart/form-data">
			@csrf
			<div class='row g-4'>
				<div class="form-group col-md-3">
					<label for="student_id"><b>Student ID</b></label>
					<div class="col-sm-10">
						<input type="text" name="studentinfo_studentid" class="form-control" value="{{ request()->input('studentinfo_studentid') }}">
					</div>
				</div>
				<div class="form-group col-md-3">
					<label for="student_name"><b>Name</b></label>
					<div class="col-sm-10">
						<input type="text" name="studentinfo_name" class="form-control" value="{{ request()->input('studentinfo_name') }}">
					</div>
				</div>
				<div class="form-group col-md-3">
					<label for="gender"><b>Gender</b></label>
					<div class="col-sm-10">
                        <select class="form-select" id="studentinfo_gender" name="studentinfo_gender">
                            <option value=''>--Select--</option>
                            @foreach($gender as $key => $value)
                            <option value="{{$key}}" 
                            @if (request()->input('studentinfo_gender') == $key)
                                selected
                            @endif
                            >{{$value}}</option>
                            @endforeach
                        </select>
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
                        <th>Name</th>
                        <th>Date of Birth</th>
                        <th>Gender</th>
                        <th>Father Name</th>
                        <th>Mother Name</th>
                        <th>Father Phone</th>
                        <th>Mother Phone</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($list_result) && $list_result->count())
                        @php $i=1;@endphp
                        @foreach($list_result as $res)
                        <tr>
                            <td>@php echo $i;$i++; @endphp</td>
                            <td>{{ $res->student_id}}</td>
                            <td>{{ $res->name }}</td>
                            <td>{{ date('Y-m-d',strtotime($res->date_of_birth)) }}</td>
                            <td>{{ $gender[$res->gender] }}</td>
                            <td>{{ $res->father_name }}</td>
                            <td>{{ $res->mother_name }}</td>
                            <td>{{ $res->father_phone }}</td>
                            <td>{{ $res->mother_phone }}</td>
                            <td class="center">
                                <a href="{{ url('admin/student_info/edit/'.$res->student_id) }}">
                                    <button type="submit" value="edit" class="btn m-1 p-0 border-0">
                                        <span id="boot-icon" class="bi bi-pencil-square" style="font-size: 20px; color:rgb(58 69 207);"></span>
                                    </button>                            
                                </a>
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

