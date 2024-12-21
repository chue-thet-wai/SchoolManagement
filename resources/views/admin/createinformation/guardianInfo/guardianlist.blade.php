@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
	<h1>Guardian Information</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
			<li class="breadcrumb-item active">Create Information</li>
			<li class="breadcrumb-item active">Guardian Information</li>
		</ol>
	</nav>
	@include('layouts.error')
</div><!-- End Page Title -->

<section class="card">
    <div class="card-body">
        <br />
        
        <div class="row g-4">
            <div class="col-md-11 content-title">
                <h4><b>Guardian Information List</b></h4>
            </div>
        </div>
        <form class="row g-4" method="POST" href="{{ url('admin/guardian_info/list') }}" enctype="multipart/form-data">
			@csrf
			<div class='row g-4'>
				<div class="form-group col-md-3">
					<label for="guardian_name"><b>Name</b></label>
					<div class="col-sm-10">
						<input type="text" name="guardianinfo_name" class="form-control" value="{{ request()->input('guardianinfo_name') }}">
					</div>
				</div>
                <div class="form-group col-md-3">
					<label for="phone"><b>Phone</b></label>
					<div class="col-sm-10">
						<input type="text" name="guardianinfo_phone" class="form-control" value="{{ request()->input('guardianinfo_phone') }}">
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
                        <th>Phone</th>
                        <th>Email</th>
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
                            <td>{{ $res->name}}</td>
                            <td>{{ $res->phone }}</td>
                            <td>{{ $res->email }}</td>
                            <td>{{ $res->address }}</td>
                            <td class="center">
                                <a href="{{ url('admin/guardian_info/edit/'.$res->id) }}">
                                    <button type="submit" value="edit" class="btn m-1 p-0 border-0">
                                        <span id="boot-icon" class="bi bi-pencil-square" style="font-size: 20px; color:rgb(58 69 207);"></span>
                                    </button>                            
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    @else
                    <tr>
                        <td colspan="10">There are no data.</td>
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

