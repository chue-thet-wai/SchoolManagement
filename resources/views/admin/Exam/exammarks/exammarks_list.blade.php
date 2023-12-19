@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
	<h1>Exam Marks</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
			<li class="breadcrumb-item active">Exam</li>
			<li class="breadcrumb-item active">Exam Marks</li>
		</ol>
	</nav>
	@include('layouts.error')
</div><!-- End Page Title -->

<section class="card">
    <div class="card-body">
        <br />
       
        <div class="row g-4">
            <div class="col-md-10 content-title">
                <h4><b>Exam Marks List</b></h4>
            </div>
            <div class="col-md-2">
                <a class="btn btn-sm btn-primary" href="{{url('admin/exam_marks/create')}}" id="form-header-btn"><span class="bi bi-plus"></span> Create</a>
            </div>
        </div>
        <br />
        <form class="row g-4" method="POST" href="{{ url('admin/exam_marks/list') }}" enctype="multipart/form-data">
			@csrf
			<div class='row g-4'>
				<div class="form-group col-md-3">
					<label for="exammarks_studentid"><b>Name</b></label>
					<div class="col-sm-10">
						<input type="text" name="exammarks_studentid" class="form-control" value="{{ request()->input('exammarks_studentid') }}">
					</div>
				</div>
				<div class="form-group col-md-3">
					<label for="grade"><b>Exam Term</b></label>
					<div class="col-sm-10">
                        <select class="form-select" id="exammarks_examterms" name="exammarks_examterms">
                            <option value=''>--Select--</option>
                            @foreach($examterms as $key => $value)
                            <option value="{{$key}}" 
                            @if (request()->input('exammarks_examterms') == $key)
                                selected
                            @endif
                            >{{$value}}</option>
                            @endforeach
                        </select>
					</div>
				</div>
                <div class="form-group col-md-3">
					<label for="class_id"><b>Class</b></label>
					<div class="col-sm-10">
                        <select class="form-select" id="exammarks_classid" name="exammarks_classid">
                            <option value=''>--Select--</option>
                            @foreach($classes as $key => $value)
                            <option value="{{$key}}" 
                            @if (request()->input('exammarks_classid') == $key)
                                selected
                            @endif
                            >{{$value}}</option>
                            @endforeach
                        </select>
					</div>
				</div>
                <div class="form-group col-md-3">
					<label for="class_id"><b>Grade</b></label>
					<div class="col-sm-10">
                        <select class="form-select" id="exammarks_gradeid" name="exammarks_gradeid">
                            <option value=''>--Select--</option>
                            @foreach($grade_list as $key => $value)
                            <option value="{{$key}}" 
                            @if (request()->input('exammarks_gradeid') == $key)
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
                        <th>Exam Terms</th>
                        <th>Class</th>
                        <th>Subject</th>
                        <th>Mark</th>
                        <th>Grade</th>
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
                            <td>{{ $examterms[$res->exam_terms_id] }}</td>
                            <td>{{ $classes[$res->class_id] }}</td>
                            <td>{{ $subjects[$res->subject_id] }}</td>
                            <td>{{ $res->mark }}</td>
                            <td>@if(array_key_exists($res->id,$exam_rules)) {{$exam_rules[$res->id]}} @else {{""}} @endif</td>
                            <td class="center">
                                <a href="{{ url('admin/exam_marks/edit/'.$res->id) }}">
                                    <button type="submit" value="edit" class="btn m-1 p-0 border-0">
                                        <span id="boot-icon" class="bi bi-pencil-square" style="font-size: 20px; color:rgb(58 69 207);"></span>
                                    </button>                            
                                </a>
                                <form method="post" action="{{ url('admin/exam_marks/delete/'.$res->id) }}" style="display: inline;">
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

