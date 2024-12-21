@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
	<h1>Driver Routes Detail</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
			<li class="breadcrumb-item active">Driver</li>
			<li class="breadcrumb-item active">Driver Routes / Detail</li>
		</ol>
	</nav>
	@include('layouts.error')
</div><!-- End Page Title -->

<section class="card">
    <div class="card-body">        
        <br />
        <div class="row g-4">
            <div class="col-md-10 content-title">
                <div class="exam-term-data m-2">
                    <h5><b>Driver Route Data</b></h5>
                    <table class="table table-bordered">
                        <tr>
                            <td><b>Track Number</b></td>
                            <td>{{$driver_routes_data['track_no']}}</td>
                        </tr>
                        <tr>
                            <td><b>Date</b></td>
                            <td>{{$driver_routes_data['day']}}</td>
                        </tr>
                        <tr>
                            <td><b>Start Time</b></td>
                            <td>{{$driver_routes_data['start_time']}}</td>
                        </tr>
                        <tr>
                            <td><b>End Time</b></td>
                            <td>{{$driver_routes_data['end_time']}}</td>
                        </tr>
                        <tr>
                            <td><b>Type</b></td>
                            <td>{{$route_status[$driver_routes_data['type']]}}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="col-md-2">
                <a class="btn btn-sm btn-primary" href="{{ url('admin/driver_routes/list') }}" id="form-header-btn"><i
                        class="bi bi-skip-backward-fill"></i> Back</a>
            </div>
        </div>
        <br />
        <div class="card">
            <h5 class="m-2"><b>Drive Routes Detail List</b></h5>
            <div class="row g-4 m-2" style="display: flex;overflow-x: auto;">
                <table cellpadding="0" cellspacing="0" class="datatable table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Cancel Note</th>
                            <th>Date Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($detail_data) && $detail_data->count())
                            @php $i=1;@endphp
                            @foreach($detail_data as $res)
                            <tr>
                                <td>@php echo $i;$i++; @endphp</td>
                                <td>{{$res->student_id }}</td>
                                <td>{{$res->name }}</td>
                                <td>{{$route_status[$res->status] }}</td>
                                <td>{{$res->cancel_note }}</td>
                                <td>{{$res->created_at }}</td>
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
                {!! $detail_data->links() !!}
            </div>
        </div>
    </div>
</section>


@endsection

