@extends('layouts.dashboard')

@section('content')
    <div class="pagetitle">
        <h1>Schedule</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
                <li class="breadcrumb-item active">Create Information</li>
                <li class="breadcrumb-item active">Schedule</li>
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
                    <h4><b>Create Schedule</b></h4>
                </div>
                <div class="col-md-2">
                    <a class="btn btn-sm btn-primary" href="{{ url('admin/schedule/list') }}" id="form-header-btn"><i
                            class="bi bi-skip-backward-fill"></i> Back</a>
                </div>
            </div>

            <br />
            <form method="POST" action="{{ route('schedule.store') }}" enctype="multipart/form-data">
                @csrf
                <br />
                <div class="row g-4">
                    <div class="col-md-1"></div>
                    <div class="col-md-10">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for=""><b>Class</b></label>
                                <div class="col-sm-10">
                                    <select class="form-select" id="class_id" name="class_id">
                                        <option value="99">select Class</option>
                                        @foreach ($classes as $a)
                                            <option value="{{ $a->id }}">{{ $a->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for=""><b>Teacher</b></label>
                                <div class="col-sm-10">
                                    <select class="form-select" id="teacher_id" name="teacher_id">
                                        <option value="99">select Teacher</option>
                                        @foreach ($teacher_list as $a)
                                            <option value="{{ $a->user_id }}">{{ $a->name }}</option>
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
                                <label for=""><b>Subject</b></label>
                                <div class="col-sm-10">
                                    <select class="form-select" id="subject_id" name="subject_id">
                                        <option value="99">select subject</option>
                                        @foreach ($subjects as $a)
                                            <option value="{{ $a->id }}">{{ $a->name }} ({{ $a->grade->name }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for=""><b>Weekday</b></label>
                                <div class="col-sm-10">
                                    <select class="form-select" id="weekdays" name="weekdays">
                                        @foreach ($weekdays as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
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
                                <label for=""><b>Start Time</b></label>
                                <div class="col-sm-10">
                                    <input type="time" name="start_time" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for=""><b>End Time</b></label>
                                <div class="col-sm-10">
                                    <input type="time" name="end_time" class="form-control" required>
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
