@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
    <h1>Exam Terms</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item active">Exam</li>
            <li class="breadcrumb-item active">Exam Terms</li>
        </ol>
    </nav>
    @include('layouts.error')
</div><!-- End Page Title -->

<section class="card">
    @php
        $gradeList = [];
    @endphp
    <div class="card-body">
        <br />
        
        <div class="row g-4">
            <div class="col-md-1"></div>
            <div class="col-md-9" style='color:#012970;'>
                <h4><b>Update Exam Terms</b></h4>
            </div>
            <div class="col-md-2">
                <a class="btn btn-sm btn-primary" href="{{ url('admin/exam_terms/list') }}" id="form-header-btn"><i class="bi bi-skip-backward-fill"></i> Back</a>
            </div>
        </div>

        <br />
        <form method="POST" action="{{ url('admin/exam_terms/update/'.$result[0]->id) }}" enctype="multipart/form-data">
            @csrf
            <br />
            {{method_field('POST')}}
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="name"><b>Name<span style="color:brown">*</span></b></label>
                        <div class="col-sm-10">
                            <input type="text" name="name" class="form-control" value="{{$result[0]->name}}" required>
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
                            <label for=""><b>Grade</b></label>
                            <div class="col-sm-10">
                                <select class="form-select" id="grade_id" name="grade_id" >
                                    <option  value="99">select grade</option>
                                    @foreach($grade_list as $a)
                                        <option  value="{{$a->id}}"
                                        @if ($result[0]->grade_id == $a->id)
                                            selected
                                        @endif
                                        >{{$a->name}}</option>
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
                                        <option  value="{{$a->id}}" 
                                        @if ($result[0]->academic_year_id == $a->id)
                                            selected
                                        @endif
                                        >{{$a->name}}</option>
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
                        <input type="submit" value="Update" class="btn btn-primary">
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>
        </form>
        <br />
    </div>
</section>


@endsection