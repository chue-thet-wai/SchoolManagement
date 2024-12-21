@extends('parent.parent_layout')

@section('parent_content')
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        #examTab{
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
        .date-data{
            padding:2%;
            color: #fff;
            display: flex;
            align-items: left;
            justify-content: center;
            padding: 0px;
            margin: 1%;
        }
        .days-until{
            background:#2B7A4A;
        }
        .day-count{
            font-size: 1.3em;
            font-weight: 700;
        }
    </style>
    <header>
        <div class="system-bar">
            <div class="left">
                <a href="{{ url('parent/student_profile/'.$student_id) }}" class="back-button">Back</a>
            </div>
            <div class="centre">EXAM DATE</div>
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
                        <ul class="row nav nav-pills" id="examTab" role="tablist">
                            <li class="col-12 nav-item"><a class="nav-link active" id="future-tab" data-toggle="tab" href="#future" role="tab" aria-controls="future" aria-selected="true">Future</a></li>
                            <!--<li class="col-6 nav-item"><a class="nav-link" id="past-tab" data-toggle="tab" href="#past" role="tab" aria-controls="past" aria-selected="false">Past</a></li>-->
                        </ul>
                    </div>
                    <hr class="line-break" />
                    <div class="tab-content mb-4">
                        <div class="tab-pane fade show active" id="future" role="tabpanel" aria-labelledby="future-tab">
                            @php $i=1; 
                                $colorArray=array('#0F4D19','#35DB0B','#1F5F5B','#06373A','#FFC06E'); 
                            @endphp
                            @foreach($exam_terms_detail as $detail)
                                @php 
                                    $randomColorKey = array_rand($colorArray);
                                    $randomColor = $colorArray[$randomColorKey];
                                @endphp
                                <div class="row date-data p-1" style="background-color: {{ $randomColor }}">
                                    <div class="col-3">
                                        <img src="{{ asset('assets/subject_images/'.$detail['subject_image']) }}" width="50" class="center-image">
                                    </div>
                                    <div class="col-6 d-flex align-items-center">
                                        {{$detail['subject_name']}}
                                    </div>
                                    <div class="col-3 days-until">
                                        <div class="row day-count">
                                            <center>{{$detail['exam_date']}}</center>
                                        </div>
                                        <div class="row">
                                            <center>Days Until</center>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection