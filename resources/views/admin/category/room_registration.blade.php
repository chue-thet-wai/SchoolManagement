@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
	<h1>Room</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
			<li class="breadcrumb-item active">Category</li>
			<li class="breadcrumb-item active">Room</li>
		</ol>
	</nav>
	@include('layouts.error')
</div><!-- End Page Title -->

<section class="card">
    <div class="card-body">
        <br />
        <div class="row g-4">
            <div class="col-md-1"></div>
            <div class="col-md-9 content-title">
            @if ($action == 'Add')
                <h4><b>Create Room</b></h4>
            @else
                <h4><b>Update Room</b></h4>
            @endif
            </div>
            <div class="col-md-2">
                <a class="btn btn-sm btn-primary" href="{{url('admin/room/list')}}" id="form-header-btn"><i class="bi bi-skip-backward-fill"></i> Back</a>
            </div>
        </div>

        <br />
        @if ($action == 'Add')
            <form method="POST" action="{{route('room.store')}}" enctype="multipart/form-data">
                @csrf
                <div class="row g-4">
                    <div class="col-md-1"></div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="name"><b>Name<span style="color:brown">*</span></b></label>
                            <div class="col-sm-10">
                                <input type="text" name="name" class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>
                <br />
                <div class="row g-4">
                    <div class="col-md-1"></div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="name"><b>Capacity<span style="color:brown">*</span></b></label>
                            <div class="col-sm-10">
                                <input type="number" name="capacity" class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>
                <br />
                <div class="row g-4">
                    <div class="col-md-1"></div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for=""><b>Branch</b></label>
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
                    </div>
                </div>
                <br />
                <div class="row g-4">
                    <div class="col-md-1"></div>
                    <div class="form-group col-md-2">
                        <div class="d-grid mt-4">
                            <input type="submit" value="Add" class="btn btn-primary">
                        </div>
                    </div>
                    <div class="col-md-1"></div>
                </div>
                <br />
            </form>
        @else
		    <form method="POST" action="{{route('room.update',$result[0]->id)}}" enctype="multipart/form-data">
            @csrf
            {{method_field('PUT')}}
                <div class="row g-4">
                    <div class="col-md-1"></div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="name"><b>Name<span style="color:brown">*</span></b></label>
                            <div class="col-sm-10">
                                <input type="text" name="name" value="{{$result[0]->name}}" class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>
                <br />
                <div class="row g-4">
                    <div class="col-md-1"></div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="name"><b>Capacity<span style="color:brown">*</span></b></label>
                            <div class="col-sm-10">
                                <input type="number" name="capacity" value="{{$result[0]->capacity}}" class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>
                <br />
                <div class="row g-4">
                    <div class="col-md-1"></div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for=""><b>Branch</b></label>
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
                    </div>
                </div>
                <br />
                <div class="row g-4">
                    <div class="col-md-1"></div>
                    <div class="form-group col-md-2">
                        <div class="d-grid mt-4">
                            <input type="submit" value="Update" class="btn btn-primary">
                        </div>
                    </div>
                    <div class="col-md-1"></div>
                </div>
            </form>
            <br />
        @endif
    </div>
</section>
@endsection