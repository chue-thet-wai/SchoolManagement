@extends('parent.parent_layout')

@section('parent_content')
<link href="{{ asset('css/home.css') }}" rel="stylesheet">
<script src="{{ asset('js/home.js') }}" defer></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<style>
        #app>div.page {
            top: 20px;
        }
        .curriculum-container {
            padding: 0% 3%;
            font-size: 1.1em;
        }
        .day-header {
            border-top: 2px solid #dee2e6;
            border-bottom: 2px solid #dee2e6;
            padding: 2%;
            font-weight: 600;
            display: flex;
            flex-wrap: wrap; 
            justify-content: space-evenly; 
        }
        .day-header .col{
            width:13%;
        }
        .day-link {
            text-decoration: none;
            color: #a6a8ab;
            padding: 5px 10px; 
            margin: 5px; 
        }
        .active-day {
            border: 1px solid #0F4D19;
            border-radius: 5px;
            padding: 5px 10px;
        }
        .today-btn {
            color: #ffffff;
            background: #0F4D19;
            padding: 2px 15px;
            border-radius: 20px;
            margin-bottom: 2%;
        }
        
        .day-data{
            display: flex;
            align-items: stretch;
            background: #eee;
            margin-top: 2%;
            padding: 1%;
            border-radius: 10px;
        }
        .vertical-linebreak {
            border-right: 2px solid #bbb1b1;
            margin: 2px;
        }

        /* Add additional styling for smaller screens */
        @media only screen and (max-width: 768px) {
            #app>div.page {
                top: 50px;
            }
            .day-header {
                padding: 2% 0%;
            }
            .day-link,
            .active-day,
            .today-btn {
                padding: 1px 10px;
                margin: 2px;
            }
        }
    </style>
    <header>
        <div class="system-bar">
            <div class="left">
                <a href="{{ url('parent/student_profile/'.$student_id) }}" class="back-button">Back</a>
            </div>
            <div class="centre">CURRICULUM</div>
            <div class="right">
                <!--<a href="#" class="plus-button"><i class="bi bi-plus-lg"></i></a>-->
            </div>
        </div>
    </header>
    <div class="page">
        @include('layouts.error')
        <div class="curriculum-container mb-2">
            <div class="row day-header mb-2">
                @for ($i = 1; $i <= 7; $i++)
                    <div class="col">
                        <a class="day-link @if ($dateNumber == $i) active-day @endif" href="{{ url('parent/student_profile/' . $student_id . '/curriculum/' . $i) }}">
                            {{ date('D', strtotime("Sunday +{$i} days")) }}
                        </a>
                    </div>
                @endfor
            </div>
            <div class="row mb-3">
                <b>{{$dayName}}</b>
                <b>{{$dateName}}</b>
            </div>
            @if ($isCurrentDay === true)
                <div class="row">
                    <div class="col-8"></div>
                    <div class="col-3">
                        <button class="btn btn-sm today-btn">Today</button>
                    </div>
                    <div class="col-1"></div>
                </div>
            @endif
            @foreach ($schedules as $schedule)
                <div class="row day-data m-2">
                    <div class="col-2">
                        {{$schedule->start_time}}
                        <br >
                        {{$schedule->end_time}}
                    </div>
                    <div class="col-1 vertical-linebreak"></div>
                    <div class="col-8 mt-2">
                        {{$schedule->subject_name}}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection