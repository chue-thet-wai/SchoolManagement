@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
	<h1>Academic Year</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
			<li class="breadcrumb-item active">Category</li>
			<li class="breadcrumb-item active">Academic Year</li>
		</ol>
	</nav>
	@include('layouts.error')
</div><!-- End Page Title -->

<section class="card">

	<div class="card-body">
		<div class="row g-4">
            <div class="col-md-10 content-title">
                <h4><b>Academic Year List</b></h4>
            </div>
            <div class="col-md-2">
                <a class="btn btn-sm btn-primary" href="{{route('academic_year.create')}}" id="form-header-btn"><span class="bi bi-plus"></span> Create</a>
            </div>
        </div>
        <br />
		<form class="row g-4" method="POST" action="{{ url('admin/academic_year/list') }}" enctype="multipart/form-data">
			@csrf
			<div class='row g-4'>
                <div class="form-group col-md-3">
					<label for="academic_year_name"><b>Name</b></label>
					<div class="col-sm-10">
						@if (request()->input('academic_year_name')=='')
                            <input type="text" id="academic_year_name" name="academic_year_name" class="form-control">
                        @else 
                            <input type="text" id="academic_year_name" name="academic_year_name" value="{{request()->input('academic_year_name')}}" class="form-control">
                        @endif
					</div>
				</div>
				<div class="form-group col-md-3">
					<label for="academic_year_startdate"><b>Start Year</b></label>
					<div class="col-sm-10">
                        @if (request()->input('academic_year_startdate')=='')
                            <input type="date" id="academic_year_startdate" name="academic_year_startdate" class="form-control">
                        @else 
                            <input type="date" id="academic_year_startdate" name="academic_year_startdate" value="{{date('Y-m-d',strtotime(request()->input('academic_year_startdate')))}}" class="form-control">
                        @endif
					</div>
				</div>
				<div class="form-group col-md-3">
					<label for="academic_year_enddate"><b>End Year</b></label>
					<div class="col-sm-10">
                        @if (request()->input('academic_year_enddate')=='')
                            <input type="date" id="academic_year_enddate" name="academic_year_enddate" class="form-control">
                        @else 
                            <input type="date" id="academic_year_enddate" name="academic_year_enddate" value="{{date('Y-m-d',strtotime(request()->input('academic_year_enddate')))}}" class="form-control">
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
						<th>Name</th>
						<th>Start</th>
						<th>End</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					@if(!empty($list_result) && $list_result->count())
						@php $i=1;@endphp
						@foreach($list_result as $res)
						<tr>
							<td>@php echo $i;$i++; @endphp</td>
							<td>{{$res->name}}</td>
							@if ($res->start_date != null) 
								<td>{{date('Y-m-d',strtotime($res->start_date))}}</td>
							@else 
								<td></td>
							@endif
							@if ($res->end_date != null) 
								<td>{{date('Y-m-d',strtotime($res->end_date))}}</td>
							@else 
								<td></td>
							@endif
							<td class="center">
								<a href="{{route('academic_year.edit',$res->id)}}">
									<button type="submit" value="edit" class="btn m-1 p-0 border-0">
										<span id="boot-icon" class="bi bi-pencil-square" style="font-size: 20px; color:rgb(58 69 207);"></span>
									</button>                            
								</a>
								<form method="post" action="{{route('academic_year.destroy',$res->id)}}" style="display: inline;">
									@csrf
									{{ method_field('DELETE') }}
									<button type="submit" value="delete" class="btn m-0 p-0 border-0">
										<span id="boot-icon" class="bi bi-trash" style="font-size: 20px; color: rgb(165, 42, 42);"></span>
									</button>
								</form>
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
		<div class="d-flex">
            {!! $list_result->links() !!}
        </div>
	</div>
</section>

@endsection