@extends('layouts.dashboard')

@section('content')
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="{{ asset('js/dailyactivity.js') }}"></script>
<div class="pagetitle">
    <h1>Daily Activity</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item active">Operation</li>
            <li class="breadcrumb-item active">Daily Activity</li>
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
                <h4><b>Create Daily Activity</b></h4>
            </div>
            <div class="col-md-2">
                <a class="btn btn-sm btn-primary" href="{{url('admin/daily_activity/list')}}" id="form-header-btn"><i class="bi bi-skip-backward-fill"></i> Back</a>
            </div>
        </div>
        <br />
        <form method="POST" action="{{route('daily_activity.store')}}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="token" value="<?php echo csrf_token(); ?>" />
            <div class="row g-4 mt-1">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <div class='col-sm-4'>
                            <label for="class_id"><b>Class</b><span style="color:brown">*</span></label>
                        </div>
                        <div class='col-sm-8'>
                            <select class="form-select" id="class_id" name="class_id" required>
                                <option value="">--Select--</option>
                                @foreach($class_list as $key => $value)
                                <option value="{{$key}}">{{$value}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-4 mt-1">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <div class='col-sm-4'>
                            <label for="student_id"><b>Student</b><span style="color:brown">*</span></label>
                        </div>
                        <div class='col-sm-8'>
                            <select class="form-select" id="student_id" name="student_id" required>
                                <option value="">--Select--</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-4 mt-1">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <div class='col-sm-4'>
                            <label for="activity_id"><b>Activity</b><span style="color:brown">*</span></label>
                        </div>
                        <div class='col-sm-8'>
                            <select class="form-select" id="activity_id" name="activity_id" required>
                                @foreach($activity_list as $key => $value)
                                <option value="{{$key}}">{{$value}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-4 mt-1">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="activity_date"><b>Date</b></label>
                        <div class="col-sm-8">
                            <input type="date" id="activity_date" name="activity_date" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-4 mt-1">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <div class='col-sm-4'>
                            <label for="rate"><b>Rate</b><span style="color:brown">*</span></label>
                        </div>
                        <div class='col-sm-8'>
                            <select class="form-select" id="rate" name="rate" required>
                                @foreach($rate_list as $key => $value)
                                <option value="{{$key}}">{{$value}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-4 mt-1">
                <div class="col-md-1"></div>
                <div class="form-group col-md-2">
                    <div class="d-grid mt-4">
                        <input type="submit" value="Save" class="btn btn-primary">
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>
            <br />
        </form>
    </div>
</section>


@endsection