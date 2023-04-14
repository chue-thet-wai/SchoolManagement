@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
	<h1>Grade</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
			<li class="breadcrumb-item active">Category</li>
			<li class="breadcrumb-item active">Grade</li>
		</ol>
	</nav>
	@include('layouts.error')
</div><!-- End Page Title -->

<section class="card">

	<div class="card-body">
		@if ($action == 'Add')
			<form class="row g-4" method="POST" action="{{route('grade.store')}}" enctype="multipart/form-data">
				@csrf
				<div class="form-group col-md-3">
					<label for="name"><b>Name</b></label>
					<div class="col-sm-10">
						<input type="text" name="name" class="form-control" required>
					</div>
				</div>
				<div class="form-group col-md-2">
					<div class="d-grid mt-4">
						<input type="submit" value="Add" class="btn btn-primary">
					</div>
				</div>
			</form>
		@else
			<form class="row g-4" method="POST" action="{{route('grade.update',$result[0]->id)}}" enctype="multipart/form-data">
				@csrf
				{{method_field('PUT')}}
				<div class="form-group col-md-3">
					<label for="name"><b>Name</b></label>
					<div class="col-sm-10">
						<input type="text" name="name" value="{{$result[0]->name}}" class="form-control" required>
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
					<th>Name</th>
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
						<td class="center">
							<a href="{{route('grade.edit',$res->id)}}">
								<button type="submit" value="edit" class="btn m-1 p-0 border-0">
									<span id="boot-icon" class="bi bi-pencil-square" style="font-size: 20px; color:rgb(58 69 207);"></span>
								</button>                            
							</a>
							<form method="post" action="{{route('grade.destroy',$res->id)}}" style="display: inline;">
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