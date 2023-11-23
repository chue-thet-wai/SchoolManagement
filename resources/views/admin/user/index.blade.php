@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
	<h1>User Management</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
			<li class="breadcrumb-item active">User Management</li>
            <li class="breadcrumb-item active">User</li>
		</ol>
	</nav>
	@include('layouts.error')
</div><!-- End Page Title -->

<section class="card">
    <div class="card-body">
        <br />
        
        <div class="row g-4">
            <div class="col-md-10" style='color:#012970;'>
                <h4><b>User List</b></h4>
            </div>
            <div class="col-md-2">
                <a class="btn btn-sm btn-primary" href="{{route('user.create')}}" id="form-header-btn"><span class="bi bi-plus"></span> Create</a>
            </div>
        </div>
        <br />
        <form class="row g-4" method="POST" action="{{ url('admin/user/list') }}" enctype="multipart/form-data">
			@csrf
			<div class='row g-4'>
				<div class="form-group col-md-3">
					<label for="staffinfo_name"><b>Name</b></label>
					<div class="col-sm-10">
						<input type="text" name="staffinfo_name" class="form-control" value="{{ request()->input('staffinfo_name') }}">
					</div>
				</div>
				<div class="form-group col-md-3">
					<label for="staffinfo_email"><b>Email</b></label>
					<div class="col-sm-10">
						<input type="text" name="staffinfo_email" class="form-control" value="{{ request()->input('staffinfo_email') }}">
					</div>
				</div>
				<div class="form-group col-md-3">
					<label for="staffinfo_contactno"><b>Contact Number</b></label>
					<div class="col-sm-10">
						<input type="text" name="staffinfo_contactno" class="form-control" value="{{ request()->input('staffinfo_contactno') }}">
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
                        <th>Login Name</th>
                        <th>Email</th>
                        <th>Contact Number</th>
                        <th>Start Working Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($list_result) && $list_result->count())
                        @php $i=1;@endphp
                        @foreach($list_result as $res)
                        <tr>
                            <td>@php echo $i;$i++; @endphp</td>
                            <td>{{ $res->name }}</td>
                            <td>{{ $res->login_name }}</td>
                            <td>{{ $res->email }}</td>
                            <td>{{ $res->contact_number }}</td>
                            <td>{{ $res->startworking_date }}</td>
                            <td>@if ($res->resign_status == '1')
                                    Active
                                @else
                                    Inactive
                                @endif
                            </td>
                            <td class="center">
                                <a href="{{route('user.edit',$res->user_id)}}">
                                    <button type="submit" value="delete" class="btn m-1 p-0 border-0">
                                        <span id="boot-icon" class="bi bi-pencil-square" style="font-size: 20px; color:rgb(58 69 207);"></span>
                                    </button>                            
                                </a>
                                <form method="post" action="{{route('user.destroy',$res->user_id)}}" style="display: inline;">
                                    @csrf
                                    {{ method_field('DELETE') }}
                                    <button type="submit" value="delete" class="btn m-1 p-0 border-0">
                                        <span id="boot-icon" class="bi bi-trash" style="font-size: 20px; color: rgb(165, 42, 42);"></span>
                                    </button>
                                </form>
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

