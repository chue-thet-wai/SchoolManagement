@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
	<h1>Driver Routes</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
			<li class="breadcrumb-item active">Driver</li>
			<li class="breadcrumb-item active">Driver Routes</li>
		</ol>
	</nav>
	@include('layouts.error')
</div><!-- End Page Title -->

<section class="card">
    <div class="card-body">
        <div class="row g-4">
            <div class="col-md-10 content-title">
                <h4><b>Driver Routes List</b></h4>
            </div>
            <div class="col-md-2">
                <a class="btn btn-sm btn-primary" href="{{route('driver_routes.create')}}" id="form-header-btn"><span class="bi bi-plus"></span> Create</a>
            </div>
        </div>
        <br />
        <form class="row g-4" method="POST" action="{{ url('admin/driver_routes/list') }}" enctype="multipart/form-data">
			@csrf
			<div class='row g-4'>
				<div class="form-group col-md-3">
					<label for="driverroutes_trackno"><b>Track Number</b></label>
					<div class="col-sm-10">
						<input type="text" name="driverroutes_trackno" class="form-control" value="{{ request()->input('driverroutes_trackno') }}">
					</div>
				</div>
				<div class="form-group col-md-3">
					<label for="driverroutes_date"><b>Date</b></label>
					<div class="col-sm-10">
						<input type="date" name="driverroutes_date" class="form-control" value="{{ request()->input('driverroutes_date') }}">
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
                        <th>Track Number</th>
                        <th>Date</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Type</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($list_result) && $list_result->count())
                        @php $i=1;@endphp
                        @foreach($list_result as $res)
                        <tr>
                            <td>@php echo $i;$i++; @endphp</td>
                            <td>{{ $res->track_no }}</td>
                            <td>{{ $weekdays[$res->day]  }}</td>
                            <td>{{ $res->start_time  }}</td>
                            <td>{{ $res->end_time  }}</td>
                            <td>{{ $route_types[$res->type]  }}</td>
                            <td class="center">
                                <a href="{{route('driver_routes.edit',$res->id)}}">
                                    <button type="submit" value="delete" class="btn m-1 p-0 border-0">
                                        <span id="boot-icon" class="bi bi-pencil-square" style="font-size: 20px; color:rgb(58 69 207);"></span>
                                    </button>                            
                                </a>
                                <form method="post" action="{{route('driver_routes.destroy',$res->id)}}" style="display: inline;">
                                    @csrf
                                    {{ method_field('DELETE') }}
                                    <button type="submit" value="delete" class="btn m-1 p-0 border-0">
                                        <span id="boot-icon" class="bi bi-trash" style="font-size: 20px; color: rgb(165, 42, 42);"></span>
                                    </button>
                                </form>
                                <a href="{{route('driver_routes_detail',$res->id)}}">
                                    <button type="submit" value="detail" class="btn m-1 p-0 border-0">
                                        <span id="boot-icon" class="bi bi-pen-fill" style="font-size: 20px; color: rgb(165, 42, 42);"></span>
                                    </button>                          
                                </a>
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

