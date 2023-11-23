@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
	<h1>Class Setup</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
			<li class="breadcrumb-item active">Create Information</li>
			<li class="breadcrumb-item active">Class Setup</li>
		</ol>
	</nav>
	@include('layouts.error')
</div><!-- End Page Title -->

<section class="card">
    <div class="card-body">
        <br />
        
        <div class="row g-4">
            <div class="col-md-10" style='color:#012970;'>
                <h4><b>Class List</b></h4>
            </div>
            <div class="col-md-2">
                <a class="btn btn-sm btn-primary" href="{{route('class_setup.create')}}" id="form-header-btn"><span class="bi bi-plus"></span> Create</a>
            </div>
        </div>
        <br />
        <form class="row g-4" method="POST" action="{{ url('admin/class_setup/list') }}" enctype="multipart/form-data">
			@csrf
			<div class='row g-4'>
				<div class="form-group col-md-3">
					<label for="class_name"><b>Name</b></label>
					<div class="col-sm-10">
						<input type="text" name="classsetup_name" class="form-control" value="{{ request()->input('classsetup_name') }}">
					</div>
				</div>
				<div class="form-group col-md-3">
					<label for="class_room"><b>Room</b></label>
					<div class="col-sm-10">
                        <select class="form-select" id="classsetup_room" name="classsetup_room">
                            <option value=''>--Select--</option>
                            @foreach($room_list as $key => $value)
                            <option value="{{$key}}" 
                            @if (request()->input('classsetup_room') == $key)
                                selected
                            @endif
                            >{{$value}}</option>
                            @endforeach
                        </select>
					</div>
				</div>
				<div class="form-group col-md-3">
					<label for="studentinfo_gender"><b>Academic Year</b></label>
					<div class="col-sm-10">
                        <select class="form-select" id="classsetup_academic" name="classsetup_academic">
                            <option value=''>--Select--</option>
                            @foreach($academic_list as $key => $value)
                            <option value="{{$key}}" 
                            @if (request()->input('classsetup_academic') == $key)
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
                        <th>Name</th>
                        <th>Room</th>
                        <th>Grade</th>
                        <th>Section</th>
                        <th>Academic Year</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($list_result) && $list_result->count())
                        @php $i=1;@endphp
                        @foreach($list_result as $res)
                        <tr>
                            <td>@php echo $i;$i++; @endphp</td>
                            <td>{{ $res->name }}</td>
                            <td>{{ $room_list[$res->room_id] }}</td>
                            <td>{{ $grade_list[$res->grade_id] }}</td>
                            <td>{{ $section_list[$res->section_id] }}</td>
                            <td>{{ $academic_list[$res->academic_year_id] }}</td>
                            <td class="center">
                                <a href="{{route('class_setup.edit',$res->id)}}">
                                    <button type="submit" value="delete" class="btn m-1 p-0 border-0">
                                        <span id="boot-icon" class="bi bi-pencil-square" style="font-size: 20px; color:rgb(58 69 207);"></span>
                                    </button>                            
                                </a>
                                <form method="post" action="{{route('class_setup.destroy',$res->id)}}" style="display: inline;">
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

