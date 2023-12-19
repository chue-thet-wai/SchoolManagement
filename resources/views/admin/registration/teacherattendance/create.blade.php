@extends('layouts.dashboard')

@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script>
    function selectAll(){
        if($('#select_all').is(':checked')){
            $('.attendance_check').each(function(){
                this.checked = true;
            });
        }else{
             $('.attendance_check').each(function(){
                this.checked = false;
            });
        }
    }

    function unselectAll() {
        if($('.attendance_check:checked').length == $('.attendance_check').length){
            $('#select_all').prop('checked',true);
        }else{
            $('#select_all').prop('checked',false);
        }
    }    
    
</script>
<div class="pagetitle">
	<h1>Teacher Attendance Management</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
			<li class="breadcrumb-item active">Registration</li>
			<li class="breadcrumb-item active">Teacher Attendance</li>
		</ol>
	</nav>
	@include('layouts.error')
</div><!-- End Page Title -->

<section class="card">

	<div class="card-body">
		<div class="row g-4">
            <div class="col-md-11 content-title">
                <h4><b>Attendance</b></h4>
            </div>
        </div>
		<form class="row g-4" method="GET" action="{{route('teacher_attendance.create')}}" enctype="multipart/form-data">
			@csrf
			<div class='row g-4'>
                <div class="form-group col-md-3">
					<label for="attendance_teacherclass"><b>Class</b></label>
					<div class="col-sm-10">
						<select class="form-select" id="attendance_teacherclass" name="attendance_teacherclass">
							@foreach($class_list as $key => $value)
                                <option value="{{$key}}"
                                @if ($key == $selected_class) 
							        selected
                                @endif
                                >{{$value}}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="form-group col-md-3">
					<label for="attendance_teacher"><b>Teacher</b></label>
					<div class="col-sm-10">
						<select class="form-select" id="attendance_teacher" name="attendance_teacher">
							@foreach($teacher_list as $key => $value)
                                <option value="{{$key}}"
                                @if ($key == $selected_teacher) 
							        selected
                                @endif
                                >{{$value}}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="form-group col-md-3">
					<label for="attendance_teacherdate"><b>Choose Date</b></label>
					<div class="col-sm-10">
						<input type="date" name="attendance_teacherdate" class="form-control" value="{{date('Y-m-d',strtotime($date_time))}}" required>
					</div>
				</div>
				<div class="form-group col-md-2">
					<div class="d-grid mt-4">
						<input type="submit" value="Apply" class="btn btn-primary">
					</div>
				</div>
			</div>
		</form>
		<br />
        <br />
        <form method="POST" action="{{route('teacher_attendance.store')}}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="attendance_teacherdate" class="form-control" value="{{date('Y-m-d',strtotime($date_time))}}">
            <div class="table-wrapper-scroll-y my-custom-scrollbar">
                <table cellpadding="0" cellspacing="0" border="0" class="datatable table table-striped table-bordered" id="attendance-table">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" class="check-all" id="select_all" onclick="selectAll()">
                            </th>
                            <th>Name</th>
                            <th>DateTime</th>
                            <th>Attendance</th>
                            <th>Remark</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($list_result) && $list_result->count())
                            @php $i=1;@endphp
                            @foreach($list_result as $res)
                            <tr>
                                <td><input type="checkbox" class="attendance_check" name="checkAttendance[]"  value="{{$res->user_id}}" onclick="unselectAll()"></td>
                                <td>{{$res->name}}</td>
                                <td>{{$date_time}}</td>
                                <td>
                                    <select class="form-select" id="attendance" name="{{$res->user_id.'-attendance'}}">
                                        @foreach($attendance as $key => $value)
                                        <option value="{{$key}}">{{$value}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="{{$res->user_id.'-remark'}}">
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
            <div class='row g-4'>
                <div class="col-md-10"></div>
                <div class="form-group col-md-2">
					<div class="d-grid mt-4">
						<input type="submit" value="Save" class="btn btn-primary">
					</div>
				</div>
            </div>
        </form>
        <br />
	</div>
</section>
<style>
.my-custom-scrollbar {
    position: relative;
    max-height: 500px;
    overflow: auto;
}
.table-wrapper-scroll-y {
    display: block;
}
</style>

@endsection