@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
	<h1>Event</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
			<li class="breadcrumb-item active">Create Information</li>
			<li class="breadcrumb-item active">Event</li>
		</ol>
	</nav>
	@include('layouts.error')
</div><!-- End Page Title -->

<section class="card">
    <div class="card-body">
        <br />
        
        <div class="row g-4">
            <div class="col-md-10" style='color:#012970;'>
                <h4><b>Event List</b></h4>
            </div>
            <div class="col-md-2">
                <a class="btn btn-sm btn-primary" href="{{route('event.create')}}" id="form-header-btn"><span class="bi bi-plus"></span> Create</a>
            </div>
        </div>
        <br />
        <form class="row g-4" method="POST" action="{{ url('admin/event/list') }}" enctype="multipart/form-data">
			@csrf
			<div class='row g-4'>
                <div class="form-group col-md-3">
					<label for="event_title"><b>Title</b></label>
					<div class="col-sm-10">
                        <input type="text" id="event_title" name="event_title" value="{{request()->input('event_title')}}" class="form-control">
					</div>
				</div>
                <div class="form-group col-md-3">
					<label for="event_gradeid"><b>Grade</b></label>
					<div class="col-sm-10">
                        <select class="form-select" id="event_gradeid" name="event_gradeid">
                            @if (request()->input('event_gradeid') == '' || request()->input('event_gradeid')=='99')
                                <option value='99' selected>--Select--</option>
                            @else
                                <option value='99'>--Select--</option>
                            @endif
                            @foreach($grade as $key => $value)
                            <option value="{{$key}}" 
                            @if (request()->input('event_gradeid') == $key)
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
                        <th>Grade</th>
                        <th>Academic Year</th>
                        <th>Event From Date</th>
                        <th>Event To Date</th>
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
                            <td>{{ $grade[$res->grade_id] }}</td>
                            <td>{{ $academic[$res->academic_year_id] }}</td>
                            <td>@if ($res->event_from_date != '') {{ date('Y-m-d',strtotime($res->event_from_date)) }} @else {{''}} @endif</td>
                            <td>@if ($res->event_to_date != '') {{ date('Y-m-d',strtotime($res->event_to_date)) }} @else {{''}} @endif</td>
                            <td class="center">
                                <a href="{{route('event.edit',$res->id)}}">
                                    <button type="submit" value="update" class="btn m-1 p-0 border-0">
                                        <span id="boot-icon" class="bi bi-pencil-square" style="font-size: 20px; color:rgb(58 69 207);"></span>
                                    </button>                            
                                </a>
                                <form method="post" action="{{route('event.destroy',$res->id)}}" style="display: inline;">
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

