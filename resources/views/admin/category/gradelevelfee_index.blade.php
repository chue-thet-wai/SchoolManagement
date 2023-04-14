@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
	<h1>Grade Level Fee</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
			<li class="breadcrumb-item active">Category</li>
			<li class="breadcrumb-item active">Grade Level Fee</li>
		</ol>
	</nav>
	@include('layouts.error')
</div><!-- End Page Title -->

<section class="card">
    @php
        $academicYrList = [];
        $gradeList      = [];
        $branchList     = [];
    @endphp
	<div class="card-body">
		@if ($action == 'Add')
			<form class="row g-4" method="POST" action="{{route('grade_level_fee.store')}}" enctype="multipart/form-data">
				@csrf
				<div class="row g-4">
					<div class="form-group col-md-3">
						<label for="">Branch</label>
						<div class="col-sm-10">
							<select class="form-select" id="branch_id" name="branch_id" >
								<option  value="99">select branch</option>
								@foreach($branch_list as $b)
									<option  value="{{$b->id}}">{{$b->name}}</option>
									@php  $branchList[$b->id] = $b->name; @endphp
								@endforeach
							</select>
						</div>
					</div>
					<div class="form-group col-md-3">
						<label for="">Academic Year</label>
						<div class="col-sm-10">
							<select class="form-select" id="academicyr_id" name="academicyr_id" >
								<option  value="99">select Academic Year</option>
								@foreach($academic_list as $a)
									<option  value="{{$a->id}}">{{$a->name}}</option>
									@php  $academicYrList[$a->id] = $a->name; @endphp
								@endforeach
							</select>
						</div>
					</div>
				</div>		
				<div class="row g-4">
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
					<div class="form-group col-md-3">
						<label for=""><b>Amount<span style="color:brown">*</span></b></label>
						<div class="col-sm-10">
							<input type="number" name="amount" class="form-control" required>
						</div>
					</div>
				</div>
				<div class="form-group col-md-2">
					<div class="d-grid mt-4">
						<input type="submit" value="Add" class="btn btn-primary">
					</div>
				</div>
			</form>
		@else
			<form class="row g-4" method="POST" action="{{route('grade_level_fee.update',$result[0]->id)}}" enctype="multipart/form-data">
				@csrf
				{{method_field('PUT')}}
				<div class="row g-4">
					<div class="form-group col-md-3">
						<label for="">Branch</label>
						<div class="col-sm-10">
							<select class="form-select" id="branch_id" name="branch_id" >
								<option  value="99">select branch</option>
								@foreach($branch_list as $b)
									<option  value="{{$b->id}}" 
									@if ($result[0]->branch_id == $b->id)
										selected
									@endif
									>{{$b->name}}</option>
									@php  $branchList[$b->id] = $b->name; @endphp
								@endforeach
							</select>
						</div>
					</div>
					<div class="form-group col-md-3">
						<label for="">Academic Year</label>
						<div class="col-sm-10">
							<select class="form-select" id="academicyr_id" name="academicyr_id" >
								<option  value="99">select Academic Year</option>
								@foreach($academic_list as $a)
									<option  value="{{$a->id}}" 
									@if ($result[0]->academic_year_id == $a->id)
										selected
									@endif
									>{{$a->name}}</option>
									@php  $academicYrList[$a->id] = $a->name; @endphp
								@endforeach
							</select>
						</div>
					</div>
				</div>		
				<div class="row g-4">
					<div class="form-group col-md-3">
						<label for="">Grade</label>
						<div class="col-sm-10">
							<select class="form-select" id="grade_id" name="grade_id" >
								<option  value="99">select grade</option>
								@foreach($grade_list as $g)
									<option  value="{{$g->id}}" 
									@if ($result[0]->grade_id == $g->id)
										selected
									@endif
									>{{$g->name}}</option>
									@php  $gradeList[$g->id] = $g->name; @endphp
								@endforeach
							</select>
						</div>
					</div>
					<div class="form-group col-md-3">
						<label for=""><b>Amount<span style="color:brown">*</span></b></label>
						<div class="col-sm-10">
							<input type="number" name="amount" value="{{$result[0]->grade_level_amount}}" class="form-control" required>
						</div>
					</div>
				</div>
				<div class="form-group col-md-2">
					<div class="d-grid mt-4">
						<input type="submit" value="Update" class="btn btn-primary">
					</div>
				</div>
			</form>
		@endif
		<br />
		<br />
		<table cellpadding="0" cellspacing="0" border="0" class="datatable table table-striped table-bordered">
			<thead>
				<tr>
					<th>No</th>
					<th>Grade</th>
					<th>Amount</th>
                    <th>Branch</th>
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
						<td>{{$gradeList[$res->grade_id]}}</td>
						<td>{{$res->grade_level_amount}}</td>
                        <td>{{$branchList[$res->branch_id]}}</td>
                        <td>{{$academicYrList[$res->academic_year_id]}}</td>
						<td class="center">
							<a href="{{route('grade_level_fee.edit',$res->id)}}">
								<button type="submit" value="edit" class="btn m-1 p-0 border-0">
									<span id="boot-icon" class="bi bi-pencil-square" style="font-size: 20px; color:rgb(58 69 207);"></span>
								</button>                            
							</a>
							<form method="post" action="{{route('grade_level_fee.destroy',$res->id)}}" style="display: inline;">
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
					<td colspan="6">There are no data.</td>
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