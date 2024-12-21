@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
	<h1>Homework Status</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
			<li class="breadcrumb-item active">Operation</li>
			<li class="breadcrumb-item active">Homework Status</li>
		</ol>
	</nav>
	@include('layouts.error')
</div><!-- End Page Title -->

<section class="card">
    <div class="card-body">
        <div class="row g-4">
            <div class="col-md-10 content-title">
                <div class="exam-term-data m-2">
                    <h5><b>Homework Data</b></h5>
                    <table class="table table-bordered">
                        <tr>
                            <td><b>Subject</b></td>
                            <td>{{$homework_data['subject_name']}}</td>
                        </tr>
                        <tr>
                            <td><b>Title</b></td>
                            <td>{{$homework_data['title']}}</td>
                        </tr>
                        <tr>
                            <td><b>Description</b></td>
                            <td>{{$homework_data['description']}}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="col-md-2">
                <a class="btn btn-sm btn-primary" href="{{ url('admin/homework/list') }}" id="form-header-btn"><i
                        class="bi bi-skip-backward-fill"></i> Back</a>
            </div>
        </div>
        <br />
        <div class="row g-4">
            <div class="col-md-10 content-title">
                <h4><b>Homework Status List</b></h4>
            </div>
            <div class="col-md-2">
                <a class="btn btn-sm btn-primary" href="{{ route('homework_status.create', ['id' => $homework_data['id']]) }}" id="form-header-btn">
                    <span class="bi bi-plus"></span> Create
                </a>
            </div>
        </div>
        <br />
        <form class="row g-4" method="POST" action="{{ url('admin/homework_status/list') }}" enctype="multipart/form-data">
			@csrf
            <input type="hidden" name="homework_id" value="{{$homework_data['id']}}" />
			<div class='row g-4'>
				<div class="form-group col-md-3">
					<label for="homeworkstatus_studentid"><b>Student ID</b></label>
					<div class="col-sm-10">
						<input type="text" name="homeworkstatus_studentid" class="form-control" value="{{ request()->input('homeworkstatus_studentid') }}">
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
                        <th>Homework Title</th>
                        <th>Student ID</th>
                        <th>Registration ID</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($list_result) && $list_result->count())
                        @php $i=1;@endphp
                        @foreach($list_result as $res)
                        <tr>
                            <td>@php echo $i;$i++; @endphp</td>
                            <td>{{ $res->homework_title }}</td>
                            <td>{{ $res->student_id  }}</td>
                            <td>{{ $res->registration_id  }}</td>
                            <td>{{ $res->student_name  }}</td>
                            <td>{{ $homework_status[$res->status]  }}</td>
                            <td class="center">
                                <a href="{{route('homework_status.edit',$res->id)}}">
                                    <button type="submit" value="delete" class="btn m-1 p-0 border-0">
                                        <span id="boot-icon" class="bi bi-pencil-square" style="font-size: 20px; color:rgb(58 69 207);"></span>
                                    </button>                            
                                </a>
                                <form method="post" action="{{route('homework_status.destroy',$res->id)}}" style="display: inline;">
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

