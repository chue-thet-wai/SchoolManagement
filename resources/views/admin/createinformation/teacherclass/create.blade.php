@extends('layouts.dashboard')

@section('content')
    <div class="pagetitle">
        <h1>Teacher Class</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
                <li class="breadcrumb-item active">Create Teacher Class</li>
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
                    <h4><b>Create Teacher Class</b></h4>
                </div>
                <div class="col-md-2">
                    <a class="btn btn-sm btn-primary" href="{{ url('admin/teacher_class/list') }}" id="form-header-btn"><i
                            class="bi bi-skip-backward-fill"></i> Back</a>
                </div>
            </div>

            <br />
            <form method="POST" action="{{ route('teacher_class.store') }}" enctype="multipart/form-data">
                @csrf
                <br />
                <div class="row g-4">
                    <div class="col-md-1"></div>
                    <div class="form-group col-md-5">
                        <label for=""><b>Teacher</b><span style="color:brown">*</span></label>
                        <div class="col-sm-10">
                            <select class="form-select" id="teacher_id" name="teacher_id">
                                <option value="99">select Teacher</option>
                                @foreach ($teacher_list as $key=>$value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                    @php  $teacher_list[$key] = $value; @endphp
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
                                <option value="99">select class</option>
                                @foreach ($class_list as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                    @php  $class_list[$c->id] = $c->name; @endphp
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
                            <textarea name="remark" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="col-md-1"></div>
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
            </form>
        </div>
    </section>
@endsection
