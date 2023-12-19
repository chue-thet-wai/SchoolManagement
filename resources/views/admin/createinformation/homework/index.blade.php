@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
	<h1>Homework</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
			<li class="breadcrumb-item active">Create Information</li>
			<li class="breadcrumb-item active">Homework</li>
		</ol>
	</nav>
	@include('layouts.error')
</div><!-- End Page Title -->

<section class="card">
    <div class="card-body">
        <br />
        
        <div class="row g-4">
            <div class="col-md-10 content-title">
                <h4><b>Homework List</b></h4>
            </div>
            <div class="col-md-2">
                <a class="btn btn-sm btn-primary" href="{{route('homework.create')}}" id="form-header-btn"><span class="bi bi-plus"></span> Create</a>
            </div>
        </div>
        <br />
        <form class="row g-4" method="POST" action="{{ url('admin/homework/list') }}" enctype="multipart/form-data">
			@csrf
			<div class='row g-4'>
                <div class="form-group col-md-3">
					<label for="homework_title"><b>Title</b></label>
					<div class="col-sm-10">
                        <input type="text" id="homework_title" name="homework_title"  value="{{request()->input('homework_title')}}" class="form-control">
					</div>
				</div>
                <div class="form-group col-md-3">
					<label for="homework_classid"><b>Class</b></label>
					<div class="col-sm-10">
                        <select class="form-select" id="homework_classid" name="homework_classid">
                            @if (request()->input('homework_classid') == '' || request()->input('homework_classid')=='99')
                                <option value='99' selected>--Select--</option>
                            @else
                                <option value='99'>--Select--</option>
                            @endif
                            @foreach($classes as $key => $value)
                            <option value="{{$key}}" 
                            @if (request()->input('homework_classid') == $key)
                                selected
                            @endif
                            >{{$value}}</option>
                            @endforeach
                        </select>
					</div>
				</div>
                <div class="form-group col-md-3">
					<label for="homework_subject"><b>Subject</b></label>
					<div class="col-sm-10">
                        <select class="form-select" id="homework_subject" name="homework_subject">
                            @if (request()->input('homework_subject') == '' || request()->input('homework_subject')=='99')
                                <option value='99' selected>--Select--</option>
                            @else
                                <option value='99'>--Select--</option>
                            @endif
                            @foreach($subject as $key => $value)
                            <option value="{{$key}}" 
                            @if (request()->input('homework_subject') == $key)
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
                        <th>Title</th>
                        <th>Class</th>
                        <th>Academic Year</th>
                        <th>Subject</th>
                        <th>Description</th>
                        <th>Due Date</th>
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
                            <td>{{ $classes[$res->class_id] }}</td>
                            <td>{{ $academic[$res->academic_year_id] }}</td>
                            <td>{{ $subject[$res->subject_id] }}</td>
                            <td>{{ $res->description }}</td>
                            <td>@if ($res->due_date != '') {{ date('Y-m-d',strtotime($res->due_date)) }} @else {{''}} @endif</td>
                            <td class="center">
                                <a href="{{route('homework.edit',$res->id)}}">
                                    <button type="submit" value="update" class="btn m-1 p-0 border-0">
                                        <span id="boot-icon" class="bi bi-pencil-square" style="font-size: 20px; color:rgb(58 69 207);"></span>
                                    </button>                            
                                </a>
                                <form method="post" action="{{route('homework.destroy',$res->id)}}" style="display: inline;">
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

