@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
	<h1>Additional Fee</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
			<li class="breadcrumb-item active">Category</li>
			<li class="breadcrumb-item active">Additional Fee</li>
		</ol>
	</nav>
	@include('layouts.error')
</div><!-- End Page Title -->

<section class="card">
	@php
        $gradeList      = [];
    @endphp
	<div class="card-body">
		<form class="row g-4" method="POST" action="{{route('additional_fee.store')}}" enctype="multipart/form-data">
			@csrf
			<div class="form-group col-md-3">
				<label for="name"><b>Name</b></label>
				<div class="col-sm-10">
					<input type="text" name="name" class="form-control" required>
				</div>
			</div>
			<div class="form-group col-md-3">
                <label for="">Amount</label>
                <div class="col-sm-10">
					<input type="text" name="amount" class="form-control" required>
				</div>
            </div>
			<div class="form-group col-md-3">
				<label for="">Grade</label>
				<div class="col-sm-10">
					<select class="form-select" id="grade_id" name="grade_id" >
						<option  value="99">select grade</option>
						@foreach($grade_list as $g)
							<option  value="{{$g->id}}">{{$g->name}}</option>
							@php  $gradeList[$g->id] = $g->name; @endphp
						@endforeach
					</select>
				</div>
			</div>
			<div class="form-group col-md-2">
				<div class="d-grid mt-4">
					<input type="submit" value="Add" class="btn btn-primary">
				</div>
			</div>
		</form>
		<br />
		<br />
		<table cellpadding="0" cellspacing="0" border="0" class="datatable table table-striped table-bordered">
			<thead>
				<tr>
					<th>No</th>
					<th>Name</th>
					<th>Amount</th>
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
						<td>{{$res->name}}</td>
						<td>{{$res->additional_amount}}</td>
						@if ($res->grade_id != null) 
							<td>{{$gradeList[$res->grade_id]}}</td>
						@else 
							<td></td>
						@endif
						
						<td class="center">
							<form method="post" action="{{route('additional_fee.destroy',$res->id)}}" style="display: inline;">
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
		<div class="d-flex">
            {!! $list_result->links() !!}
        </div>
	</div>
	</div>
	</div>
</section>

@endsection