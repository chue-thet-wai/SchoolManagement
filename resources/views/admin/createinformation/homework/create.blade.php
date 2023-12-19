@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
    <h1>Homework</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item active">Create Information</li>
            <li class="breadcrumb-item active">Homework</li>
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
                <h4><b>Create Homework</b></h4>
            </div>
            <div class="col-md-2">
                <a class="btn btn-sm btn-primary" href="{{url('admin/homework/list')}}" id="form-header-btn"><i class="bi bi-skip-backward-fill"></i> Back</a>
            </div>
        </div>

        <br />
        <form method="POST" action="{{route('homework.store')}}" enctype="multipart/form-data">
            @csrf
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="title"><b>Title</b></label>
                        <div class="col-sm-10">
                            <input type="text" name="title" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="homework_file"><b>File</b></label>
                        <div class="col-sm-10">
                            <input type="file" class="form-control" id="homework_file" name='homework_file' />  
                        </div>
                    </div> 
                </div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for=""><b>Class</b></label>
                        <div class="col-sm-10">
                            <select class="form-select" id="class_id" name="class_id" >
                                <option  value="99">-- select --</option>
                                <option  value="0">All</option>
                                @foreach($classes as $a)
                                    <option  value="{{$a->id}}">{{$a->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for=""><b>Academic Year</b></label>
                        <div class="col-sm-10">
                            <select class="form-select" id="academic_year_id" name="academic_year_id" >
                                <option  value="99">-- select --</option>
                                @foreach($academic as $a)
                                    <option  value="{{$a->id}}">{{$a->name}}</option>
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
                        <label for=""><b>Subject</b></label>
                        <div class="col-sm-10">
                            <select class="form-select" id="subject_id" name="subject_id" >
                                <option  value="99">-- select --</option>
                                @foreach($subject as $a)
                                    <option  value="{{$a->id}}">{{$a->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="due_date"><b>Due Date</b></label>
                        <div class="col-sm-10">
                            <input type="date" id="due_date" name="due_date" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    <div class="form-group">
                        <label for="description"><b>Description</b></label>
                        <div class="col-sm-11">
                            <textarea name="description" class="form-control" required></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    <div class="form-group">
                        <label for="remark"><b>Remark</b></label>
                        <div class="col-sm-11">
                            <textarea name="remark" class="form-control" required></textarea>
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