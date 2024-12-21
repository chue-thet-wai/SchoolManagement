@extends('parent.parent_layout')

@section('parent_content')
    <style>
        .profile-card{
            background:#ede2e2;
            color:#00C314;
            border-radius:10px;
            text-decoration:none;
            font-weight: 700;
        }
        .profile-card .card{
            background:#ede2e2;
        }
        .center-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            margin-top: 10%;
        }

        .center-image {
            width:40%;
        }

    </style>
    <header>
        <div class="system-bar">
            <div class="left">
                <a href="{{ url('parent/home') }}" class="back-button">Back</a>
            </div>
            <div class="centre">STUDENT PROFILE</div>
            <div class="right">
                <!--<a href="#" class="plus-button"><i class="bi bi-plus-lg"></i></a>-->
            </div>
        </div>
    </header>
    <div class="page">
        @include('layouts.error')
        <div class="row">
            <div class="col-1"></div>
            <div class="col-5">
                <a href="{{ url('parent/student_profile/' . $student_id . '/exam-date') }}" class="profile-card">
                    <div class="card">
                        <div class="center-content">
                            <img src="{{ asset('parent/exam-date.png') }}" width="50" class="center-image">
                            <div class="mt-2 mb-2 center-text">Exam Date</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-5">
                <a href="{{ url('parent/student_profile/' . $student_id . '/homework') }}" class="profile-card">
                    <div class="card">
                        <div class="center-content">
                            <img src="{{ asset('parent/homework.png') }}" width="50" class="center-image">
                            <div class="mt-2 mb-2 center-text">Homework</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-1"></div>
        </div>
        <br />
        <div class="row">
            <div class="col-1"></div>
            <div class="col-5">
                <a href="{{ url('parent/student_profile/' . $student_id . '/attendance') }}" class="profile-card">
                    <div class="card">
                        <div class="center-content">
                            <img src="{{ asset('parent/attendance.png') }}" width="50" class="center-image">
                            <div class="mt-2 mb-2 center-text">Attendance</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-5">
                <a href="{{ url('parent/student_profile/' . $student_id . '/exam-result') }}" class="profile-card">
                    <div class="card">
                        <div class="center-content">
                            <img src="{{ asset('parent/exam-result.png') }}" width="50" class="center-image">
                            <div class="mt-2 mb-2 center-text">Exam Result</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-1"></div>
        </div>
        <br />
        <div class="row">
            <div class="col-1"></div>
            <div class="col-5">
                <a href="{{ url('parent/student_profile/' . $student_id . '/curriculum') }}" class="profile-card">
                    <div class="card">
                        <div class="center-content">
                            <img src="{{ asset('parent/curriculum.png') }}" width="50" class="center-image">
                            <div class="mt-2 mb-2 center-text">Curriculum</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-5">
                <a href="{{ url('parent/student_profile/' . $student_id . '/messages') }}" class="profile-card">
                    <div class="card">
                        <div class="center-content">
                            <img src="{{ asset('parent/message.png') }}" width="50" class="center-image">
                            <div class="mt-2 mb-2 center-text">Messages</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-1"></div>
        </div>
        <br />
        <div class="row">
            <div class="col-1"></div>
            <div class="col-5">
                <a href="{{ url('parent/student_profile/' . $student_id . '/event') }}" class="profile-card">
                    <div class="card">
                        <div class="center-content">
                            <img src="{{ asset('parent/event.png') }}" width="50" class="center-image">
                            <div class="mt-2 mb-2 center-text">Event</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-5">
                <a href="{{ url('parent/student_profile/' . $student_id . '/billing') }}" class="profile-card">
                    <div class="card">
                        <div class="center-content">
                            <img src="{{ asset('parent/billing.png') }}" width="50" class="center-image">
                            <div class="mt-2 mb-2 center-text">Billing</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-1"></div>
        </div>
    </div>
@endsection