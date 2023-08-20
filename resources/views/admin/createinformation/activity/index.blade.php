@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
	<h1>Activity</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
			<li class="breadcrumb-item active">Create Information</li>
			<li class="breadcrumb-item active">Activity</li>
		</ol>
	</nav>
	@include('layouts.error')
</div><!-- End Page Title -->

<section class="card">
    <div class="card-body">
        <br />
        
        <div class="row g-4">
            <div class="col-md-11" style='color:#012970;'>
                <h4><b>Activity List</b></h4>
            </div>
            <div class="col-md-1">
                <a class="btn btn-sm btn-primary" href="{{route('activity.create')}}" id="form-header-btn"> Create</a>
            </div>
        </div>
        <br />
        <form class="row g-4" method="POST" action="{{ url('admin/activity/list') }}" enctype="multipart/form-data">
			@csrf
			<div class='row g-4'>
                <div class="form-group col-md-3">
					<label for="activity_classid"><b>Class</b></label>
					<div class="col-sm-10">
                        <select class="form-select" id="activity_classid" name="activity_classid">
                            <option value=''>--Select--</option>
                            @foreach($classes as $key => $value)
                            <option value="{{$key}}" 
                            @if (request()->input('activity_classid') == $key)
                                selected
                            @endif
                            >{{$value}}</option>
                            @endforeach
                        </select>
					</div>
				</div>
				<div class="form-group col-md-3">
					<label for="activity_date"><b>Activity Date</b></label>
					<div class="col-sm-10">
                        @if (request()->input('activity_date')=='')
                            <input type="date" id="activity_date" name="activity_date" class="form-control">
                        @else 
                            <input type="date" id="activity_date" name="activity_date" value="{{date('Y-m-d',strtotime(request()->input('activity_date')))}}" class="form-control">
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
                        <th>Class</th>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Remark</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($list_result) && $list_result->count())
                        @php $i=1;@endphp
                        @foreach($list_result as $res)
                        <tr>
                            <td>@php echo $i;$i++; @endphp</td>
                            <td>{{ $classes[$res->class_id] }}</td>
                            <td>{{ date('Y-m-d',strtotime($res->date)) }}</td>
                            <td>{{ $res->description }}</td>
                            <td>{{ $res->remark }}</td>
                            <td class="center">
                                <a href="{{route('activity.edit',$res->id)}}">
                                    <button type="submit" value="update" class="btn m-1 p-0 border-0">
                                        <span id="boot-icon" class="bi bi-pencil-square" style="font-size: 20px; color:rgb(58 69 207);"></span>
                                    </button>                            
                                </a>
                                <form method="post" action="{{route('activity.destroy',$res->id)}}" style="display: inline;">
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
