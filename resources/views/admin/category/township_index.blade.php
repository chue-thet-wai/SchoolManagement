@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
	<h1>Township</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
			<li class="breadcrumb-item active">Category</li>
			<li class="breadcrumb-item active">Township</li>
		</ol>
	</nav>
	@include('layouts.error')
</div><!-- End Page Title -->

<section class="card">

	<div class="card-body">
		<h5 style='color:#012970;'><b>Upload Township</b></h5>
		<br />
        <form action="{{ url('admin/township/import') }}" method="POST" enctype="multipart/form-data">
            @csrf
			<div class="form-group col-md-5">
				<div class="col-sm-10">
					<input type="file" name="file" class="form-control">
				</div>
			</div>
			<div class="form-group col-md-2">
				<div class="d-grid mt-4">
					<input type="submit" value="Import" class="btn btn-primary">
				</div>
			</div>
        </form>
		<br />
		<br />
		<table cellpadding="0" cellspacing="0" border="0" class="datatable table table-striped table-bordered">
			<thead>
				<tr>
					<th>No</th>
					<th>Code</th>
                    <th>Name</th>
				</tr>
			</thead>
			<tbody>
				@if(!empty($list_result) && $list_result->count())
					@php $i=1;@endphp
					@foreach($list_result as $res)
					<tr>
						<td>@php echo $i;$i++; @endphp</td>
						<td>{{$res->code}}</td>
                        <td>{{$res->name}}</td>
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
</section>

@endsection