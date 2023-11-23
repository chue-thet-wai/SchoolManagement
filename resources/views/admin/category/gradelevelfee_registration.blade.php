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
    <div class="card-body">
        <br />
        <div class="row g-4">
            <div class="col-md-1"></div>
            <div class="col-md-9" style='color:#012970;'>
            @if ($action == 'Add')
                <h4><b>Create Grade Level Fee</b></h4>
            @else
                <h4><b>Update Grade Level Fee</b></h4>
            @endif
            </div>
            <div class="col-md-2">
                <a class="btn btn-sm btn-primary" href="{{url('admin/grade_level_fee/list')}}" id="form-header-btn"><i class="bi bi-skip-backward-fill"></i> Back</a>
            </div>
        </div>

        <br />
        @if ($action == 'Add')
            <form method="POST" action="{{route('grade_level_fee.store')}}" enctype="multipart/form-data">
                @csrf
                <div class="row g-4">
                    <div class="col-md-1"></div>
                    <div class="col-md-5">
                        <div class="form-group">
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
                    </div>
                </div>
                <br />
                <div class="row g-4">
                    <div class="col-md-1"></div>
                    <div class="col-md-5">
                        <div class="form-group">
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
                </div>
                <br />
                <div class="row g-4">
                    <div class="col-md-1"></div>
                    <div class="col-md-5">
                        <div class="form-group">
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
                    </div>
                </div>
                <br />
                <div class="row g-4">
                    <div class="col-md-1"></div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for=""><b>Amount<span style="color:brown">*</span></b></label>
                            <div class="col-sm-10">
                                <input type="number" name="amount" class="form-control" required>
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
		    <form method="POST" action="{{route('grade_level_fee.update',$result[0]->id)}}" enctype="multipart/form-data">
            @csrf
            {{method_field('PUT')}}
                <div class="row g-4">
                    <div class="col-md-1"></div>
                    <div class="col-md-5">
                        <div class="form-group">
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
                    </div>
                </div>
                <br />
                <div class="row g-4">
                    <div class="col-md-1"></div>
                    <div class="col-md-5">
                        <div class="form-group">
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
                </div>
                <br />
                <div class="row g-4">
                    <div class="col-md-1"></div>
                    <div class="col-md-5">
                        <div class="form-group">
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
                    </div>
                </div>
                <br />
                <div class="row g-4">
                    <div class="col-md-1"></div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for=""><b>Amount<span style="color:brown">*</span></b></label>
                            <div class="col-sm-10">
                                <input type="number" name="amount" value="{{$result[0]->grade_level_amount}}" class="form-control" required>
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