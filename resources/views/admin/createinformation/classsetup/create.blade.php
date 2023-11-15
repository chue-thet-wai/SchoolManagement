@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
    <h1>Class Setup</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item active">Create Information</li>
            <li class="breadcrumb-item active">Class Setup</li>
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
                <h4><b>Create Class Setup</b></h4>
            </div>
            <div class="col-md-1">
                <a class="btn btn-sm btn-primary" href="{{url('admin/class_setup/list')}}" id="form-header-btn"> Back</a>
            </div>
            <div class="col-md-1"></div>
        </div>

        <br />
        <form method="POST" action="{{route('class_setup.store')}}" enctype="multipart/form-data">
            @csrf
            <br />
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
                <div class="col-md-10">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for=""><b>Room</b></label>
                            <div class="col-sm-10">
                                <select class="form-select" id="room_id" name="room_id" >
                                    <option  value="99">select room</option>
                                    @foreach($room_list as $a)
                                        <option  value="{{$a->id}}">{{$a->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for=""><b>Grade</b></label>
                            <div class="col-sm-10">
                                <select class="form-select" id="grade_id" name="grade_id" >
                                    <option  value="99">select grade</option>
                                    @foreach($grade_list as $a)
                                        <option  value="{{$a->id}}">{{$a->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                           
                </div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for=""><b>Section</b></label>
                            <div class="col-sm-10">
                                <select class="form-select" id="section_id" name="section_id" >
                                    <option  value="99">select section</option>
                                    @foreach($section_list as $a)
                                        <option  value="{{$a->id}}">{{$a->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for=""><b>Academic Year</b></label>
                            <div class="col-sm-10">
                                <select class="form-select" id="academic_year_id" name="academic_year_id" >
                                    <option  value="99">select Academic Year</option>
                                    @foreach($academic_list as $a)
                                        <option  value="{{$a->id}}">{{$a->name}}</option>
                                    @endforeach
                                </select>
                            </div>
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
    </div>
</section>


@endsection