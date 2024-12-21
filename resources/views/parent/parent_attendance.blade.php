@extends('parent.parent_layout')

@section('parent_content')
<link href="{{ asset('css/home.css') }}" rel="stylesheet">
<script src="{{ asset('js/home.js') }}" defer></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        #attendanceTab{
            background: #eee;
        }
        .nav-pills .nav-item .nav-link {
            color: #2B7A4A;
            border-radius: 0;
        }

        .nav-pills .nav-item .nav-link.active {
            background-color: #2B7A4A;
            color: #00C314;
        }
        .nav-item{
            padding: 0px;
        }
        .line-break{
            background: #adb5bd;
        }
        body{
            margin-top:1%;
        }
        .exam-date-container {
            padding: 0px;
        }
        .attendance-btn-container {
            display: flex;
            justify-content: center;
            margin-top: 15px; 
        }
        .attendance-btn {
            width: 100%;
            margin-right: 10px; 
            color:#ffffff;
        }
        .submit-leave ,
        .submit-leave:hover {
            width: 100%;
            background: #E8982F;
            color:#ffffff;
            border-radius:10px;
        }
        @media only screen and (min-width: 200px) and (max-width: 370px) {
            .attendance-btn {
                width: 150%;
                margin-right: 10px; 
                color:#ffffff;
                font-size: 0.8em;
            }
            .submit-leave ,
            .submit-leave:hover {
                width: 200%;
                background: #E8982F;
                color:#ffffff;
                border-radius:10px;
                font-size: 0.8em;
            }
        }
        @media only screen and (min-width: 371px) and (max-width: 600px) {
            .attendance-btn {
                width: 100%;
                margin-right: 10px; 
                color:#ffffff;
                font-size: 0.8em;
            }
            .submit-leave ,
            .submit-leave:hover {
                width: 130%;
                background: #E8982F;
                color:#ffffff;
                border-radius:10px;
                font-size: 0.8em;
            }
        }
    </style>
    <header>
        <div class="system-bar">
            <div class="left">
                <a href="{{ url('parent/student_profile/'.$student_id) }}" class="back-button">Back</a>
            </div>
            <div class="centre">ATTENDANCE</div>
            <div class="right">
                <!--<a href="#" class="plus-button"><i class="bi bi-plus-lg"></i></a>-->
            </div>
        </div>
    </header>
    <div class="page">
        @include('layouts.error')
        <div class="exam-date-container">
            <div class="row container d-flex justify-content-center">
                <div class="col-12">
                    <div class="mb-3">
                        <ul class="row nav nav-pills" id="attendanceTab" role="tablist">
                            <li class="col-6 nav-item"><a class="nav-link active" id="monthly-tab" data-toggle="tab" href="#monthly" role="tab" aria-controls="monthly" aria-selected="true">Monthly</a></li>
                            <li class="col-6 nav-item"><a class="nav-link" id="report-tab" data-toggle="tab" href="#report" role="tab" aria-controls="report" aria-selected="false">Report</a></li>
                        </ul>
                    </div>
                    <div class="tab-content mb-4">
                        <div class="tab-pane fade show active" id="monthly" role="tabpanel" aria-labelledby="monthly-tab">
                            <div class="calendar calendar-first" id="calendar_first">
                                <div class="calendar_header">
                                    <button class="switch-month switch-left"> <i class="bi bi-caret-left-fill"></i></button>
                                    <h2></h2>
                                    <button class="switch-month switch-right"> <i class="bi bi-caret-right-fill"></i></button>
                                </div>
                                <hr class="line-break" />
                                <div class="calendar_weekdays"></div>
                                <div class="calendar_content"></div>
                            </div>   
                            <hr class="line-break" />
                            <div class="row attendance-btn-container">
                                <div class="col-4"><button class="btn btn-sm attendance-btn" style="background:#0F4D19;">PRESENT</button></div>
                                <div class="col-4"><button class="btn btn-sm attendance-btn" style="background:#F90000;">ABSENT</button></div>
                                <div class="col-4"><button class="btn btn-sm attendance-btn" style="background:#8B8E0D;">LEAVE</button></div>
                            </div>
                            <div class="row mt-5">
                                <div class="col-5 mx-auto text-center">
                                    <a href="{{ url('parent/student_profile/'.$student_id.'/attendance/leave_request') }}" class="btn submit-leave">SUBMIT LEAVE<i class="bi bi-chevron-right"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="report" role="tabpanel" aria-labelledby="report-tab">
                            <p> Reporting </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection