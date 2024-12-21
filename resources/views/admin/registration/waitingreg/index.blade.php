@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
	<h1>Waiting List Registration</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
			<li class="breadcrumb-item active">Registration</li>
			<li class="breadcrumb-item active">Waiting List Registration</li>
		</ol>
	</nav>
	@include('layouts.error')
</div><!-- End Page Title -->

<section class="card">
    <div class="card-body">
        <div class="row g-4">
            <div class="col-md-10 content-title">
                <h4><b>Waiting List Registration List</b></h4>
            </div>
            <div class="col-md-2">
                <a class="btn btn-sm btn-primary" href="{{route('waitinglist_reg.create')}}" id="form-header-btn"><span class="bi bi-plus"></span> Create</a>
            </div>
        </div>
        <br />
        <form class="row g-4" method="POST" action="{{ url('admin/waitinglist_reg/list') }}" enctype="multipart/form-data">
			@csrf
			<div class='row g-4'>
				<div class="form-group col-md-3">
					<label for="waitinglist_name"><b>Name</b></label>
					<div class="col-sm-10">
						<input type="text" name="waitinglist_name" class="form-control" value="{{ request()->input('waitinglist_name') }}">
					</div>
				</div>
				<div class="form-group col-md-3">
					<label for="waitinglist_email"><b>Email</b></label>
					<div class="col-sm-10">
						<input type="text" name="waitinglist_email" class="form-control" value="{{ request()->input('waitinglist_email') }}">
					</div>
				</div>
				<div class="form-group col-md-3">
					<label for="waitinglist_phone"><b>Phone</b></label>
					<div class="col-sm-10">
						<input type="text" name="waitinglist_phone" class="form-control" value="{{ request()->input('waitinglist_phone') }}">
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
                        <th>Grade</th>
                        <th>Academic Year</th>
                        <th>Change</th>
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
                            <td>{{ $res->phone }}</td>
                            <td>{{ $res->email }}</td>
                            <td>{{ $grade_list[$res->grade_id] }}</td>
                            <td>{{ $academic_list[$res->academic_year_id] }}</td>
                            <td class="center">
                                @if ($res->status == '1')
                                <form class="col-sm-1 d-inline" method="GET" action="{{ route('student_reg.create') }}">
                                    @csrf
                                    <input type="hidden" name="reg_type" value="1" />
                                    <input type="hidden" name="waiting_id" value="{{ $res->id }}" />
                                    <input type="submit" value="Register" class="btn btn-sm btn-primary">
                                </form>
                                @endif
                            </td>
                            <td class="center">
                                <a href="{{route('waitinglist_reg.edit',$res->id)}}">
                                    <button type="submit" value="delete" class="btn m-1 p-0 border-0">
                                        <span id="boot-icon" class="bi bi-pencil-square" style="font-size: 20px; color:rgb(58 69 207);"></span>
                                    </button>                            
                                </a>
                                <form method="post" action="{{route('waitinglist_reg.destroy',$res->id)}}" style="display: inline;">
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
                        <td colspan="7">There are no data.</td>
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

