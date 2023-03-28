@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
	<h1>Teacher Information</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
			<li class="breadcrumb-item active">Category</li>
			<li class="breadcrumb-item active">Teacher Information</li>
		</ol>
	</nav>
	@include('layouts.error')
</div><!-- End Page Title -->

<section class="card">
    <div class="card-body">
        <br />
        
        <div class="row g-4">
            <div class="col-md-11" style='color:#012970;'>
                <h4><b>Teacher List</b></h4>
            </div>
            <div class="col-md-1">
                <a class="btn btn-sm btn-primary" href="{{route('teacher_info.create')}}" id="form-header-btn"> Create</a>
            </div>
        </div>
        <br />

        <table cellpadding="0" cellspacing="0" border="0" class="datatable table table-striped table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Joined Date</th>
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
                        <td>{{ $res->email }}</td>
                        <td>{{ $res->phone }}</td>
                        <td>{{ $res->joined_date }}</td>
                        <td class="center">
                            <form method="post" action="{{route('teacher_info.destroy',$res->user_id)}}" style="display: inline;">
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
</section>


@endsection

