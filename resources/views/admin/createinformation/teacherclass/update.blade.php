@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
    <h1>Teacher Class</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item active">Create Information</li>
            <li class="breadcrumb-item active">Teacher Class</li>
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
                <h4><b>Update Teacher Class</b></h4>
            </div>
            <div class="col-md-2">
                <a class="btn btn-sm btn-primary" href="{{url('admin/teacher_class/list')}}" id="form-header-btn"><i class="bi bi-skip-backward-fill"></i> Back</a>
            </div>
        </div>

        <br />
        <form method="POST" action="{{route('teacher_class.update',$result[0]->id)}}" enctype="multipart/form-data">
            @csrf
            <br />
            {{method_field('PUT')}}
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="form-group col-md-5">
                    <label for=""><b>Teacher</b><span style="color:brown">*</span></label>
                    <div class="col-sm-10">
                        <select class="form-select" id="teacher_id" name="teacher_id">
                            @foreach($teacher_list as $key => $value)
                            <option value="{{$key}}"
                            @if ($result[0]->teacher_id == $key)
                                selected
                            @endif
                            >{{$value}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="form-group col-md-5">
                    <label for=""><b>Class</b><span style="color:brown">*</span></label>
                    <div class="col-sm-10">
                        <select class="form-select" id="class_id" name="class_id">
                            @foreach($class_list as $key => $value)
                            <option value="{{$key}}"
                            @if ($result[0]->class_id == $key)
                                selected
                            @endif
                            >{{$value}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <br />  
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="form-group col-md-5">
                    <label for="remark"><b>Remark</b></label>
                    <div class="col-sm-10">
                        <textarea name="remark" class="form-control">{{$result[0]->remark}}</textarea>
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>
            <br />         
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="form-group col-md-5">
                    <div class="d-grid mt-4">
                        <input type="submit" value="Update" class="btn btn-primary">
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>
        </form>
    </div>
</section>


@endsection