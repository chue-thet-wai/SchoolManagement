@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
	<h1>Branch</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
			<li class="breadcrumb-item active">Category</li>
			<li class="breadcrumb-item active">Branch</li>
		</ol>
	</nav>
	@include('layouts.error')
</div><!-- End Page Title -->

<section class="card">

	<div class="card-body">
		<div class="row g-4">
            <div class="col-md-10" style='color:#012970;'>
                <h4><b>Branch</b></h4>
            </div>
            <div class="col-md-2">
                <a class="btn btn-sm btn-primary" href="{{route('branch.create')}}" id="form-header-btn"><span class="bi bi-plus"></span> Create</a>
            </div>
        </div>
        <br />
		<form class="row g-4" method="POST" action="{{ url('admin/branch/list') }}" enctype="multipart/form-data">
			@csrf
			<div class='row g-4'>
                <div class="form-group col-md-3">
					<label for="branch_name"><b>Name</b></label>
					<div class="col-sm-10">
						@if (request()->input('branch_name')=='')
                            <input type="text" id="branch_name" name="branch_name" class="form-control">
                        @else 
                            <input type="text" id="branch_name" name="branch_name" value="{{request()->input('branch_name')}}" class="form-control">
                        @endif
					</div>
				</div>
				<div class="form-group col-md-3">
					<label for="branch_phone"><b>Phone</b></label>
					<div class="col-sm-10">
						@if (request()->input('branch_phone')=='')
                            <input type="text" id="branch_phone" name="branch_phone" class="form-control">
                        @else 
                            <input type="text" id="branch_phone" name="branch_phone" value="{{request()->input('branch_phone')}}" class="form-control">
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
		<table cellpadding="0" cellspacing="0" class="datatable table table-striped table-bordered" style="table-layout: fixed;">
			<thead>
				<tr>
					<th>No</th>
					<th>Name</th>
					<th>Phone</th>
					<th>Address</th>
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
						<td>{{$res->phone}}</td>
						<td style="word-wrap:break-word;">{{$res->address}}</td>
						<td class="center">
							<a href="{{route('branch.edit',$res->id)}}">
								<button type="submit" value="edit" class="btn m-1 p-0 border-0">
									<span id="boot-icon" class="bi bi-pencil-square" style="font-size: 20px; color:rgb(58 69 207);"></span>
								</button>                            
							</a>
							<form method="post" action="{{route('branch.destroy',$res->id)}}" style="display: inline;">
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