@extends('parent.parent_layout')

@section('parent_content')
    <style>
        #leave-request-form {
            color:#0F4D19;
        }
        #leave-request-form input {
            background: #ede2e2;
            color:#0F4D19;
        }
        #leave-request-form textarea {
            background: #ede2e2;
            color:#0F4D19;
        }
        .save-btn {
            background: #0F4D19 !important;
            border: 1px solid #0F4D19;
            color: #fff !important;
        }
    </style>
    <header>
        <div class="system-bar">
            <div class="left">
                <a href="{{ url('parent/student_profile/'.$student_id.'/attendance') }}" class="back-button">Back</a>
            </div>
            <div class="centre">LEAVE REQUEST</div>
            <div class="right">
                <!--<a href="#" class="plus-button"><i class="bi bi-plus-lg"></i></a>-->
            </div>
        </div>
    </header>
    <div class="page">
        @include('layouts.error')
        <div class="leave-request-container">
            <div class='student-data'>
                <h5><strong> {{$student_data->name}} </strong></h5>
                <p> Class - {{$student_data->class_name}}</p>
            </div>
            <hr />
            <form method="POST" id="leave-request-form" action="{{url('parent/student_profile/'.$student_id.'/attendance/leave_request_submit')}}" enctype="multipart/form-data">
                @csrf
                <div class="row g-4 mb-2">
                    <div class="col-md-1"></div>
                    <div class="col-md-10">
                        <div class="form-group">
                            <div class="col-sm-11">
                                <input type="text" name="title" class="form-control" value="" placeholder="Title" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-4 mb-2">
                    <div class="col-md-1"></div>
                    <div class="col-md-10">
                        <div class="form-group">
                            <div class="col-sm-11">
                                <textarea name="description" class="form-control" placeholder="description" required></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-4 mb-2">
                    <div class="col-md-1"></div>
                    <div class="col-md-10">
                        <div class="form-group row">
                            <div class="col-sm-5">
                                <label for="from_date" class="col-form-label">From Date:</label>
                                <input type="date" id="from-date" name="from_date" class="form-control" required>
                            </div>
                            <div class="col-sm-1"></div>
                            <div class="col-sm-5">
                                <label for="to_date" class="col-form-label">To Date:</label>
                                <input type="date" id="to-date" name="to_date" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1"></div>
                </div>
                <br />
                <div class="row g-4 mb-2">
                    <div class="col-md-1"></div>
                    <div class="form-group col-md-2 mx-auto">
                        <div class="d-grid">
                            <input type="submit" value="Save" class="btn save-btn">
                        </div>
                    </div>
                    <div class="col-md-1"></div>
                </div>
                <br />
            </form>
        </div>
    </div>
@endsection