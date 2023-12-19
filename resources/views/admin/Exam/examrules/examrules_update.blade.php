@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
    <h1>Exam Rules</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item active">Exam</li>
            <li class="breadcrumb-item active">Exam Rules</li>
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
                <h4><b>Update Rules Terms</b></h4>
            </div>
            <div class="col-md-2">
                <a class="btn btn-sm btn-primary" href="{{ url('admin/exam_rules/list') }}" id="form-header-btn"><i class="bi bi-skip-backward-fill"></i> Back</a>
            </div>
        </div>

        <br />
        <form method="POST" action="{{ url('admin/exam_rules/update/'.$result[0]->id) }}" enctype="multipart/form-data">
            @csrf
            <br />
            {{method_field('POST')}}
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="title"><b>Title<span style="color:brown">*</span></b></label>
                        <div class="col-sm-10">
                            <input type="text" name="title" class="form-control" value="{{$result[0]->title}}" required>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="mark_range_from"><b>Mark Range(From)<span style="color:brown">*</span></b></label>
                        <div class="col-sm-10">
                            <input type="text" name="mark_range_from" class="form-control" value="{{$result[0]->mark_range_from}}" required>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="mark_range_to"><b>Mark Range(To)<span style="color:brown">*</span></b></label>
                        <div class="col-sm-10">
                            <input type="text" name="mark_range_to" class="form-control" value="{{$result[0]->mark_range_to}}" required>
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