@extends('parent.parent_layout')

@section('parent_content')
    <style>
        .exam-result-container {
            padding:3%;
            font-size: 1.1em;
        }
        .mark-header{
            border-top: 2px solid #dee2e6;
            border-bottom: 2px solid #dee2e6;
            padding: 3%;
            font-weight: 600;
        }
        .mark-data{
            background: #eee;
            margin-top: 2%;
            padding: 3%;
            border-radius: 10px;
        }
    </style>
    <header>
        <div class="system-bar">
            <div class="left">
                <a href="{{ url('parent/student_profile/'.$student_id.'/exam-result') }}" class="back-button">Back</a>
            </div>
            <div class="centre">{{$exam_term->name}}</div>
            <div class="right">
                <!--<a href="#" class="plus-button"><i class="bi bi-plus-lg"></i></a>-->
            </div>
        </div>
    </header>
    <div class="page">
        @include('layouts.error')
        <div class="exam-result-container">
            <div class="row mark-header">
                <div class="col-6">
                    Subject
                </div>
                <div class="col-3">
                    Marks
                </div>
                <div class="col-3">
                    Grade
                </div>
            </div>
            @foreach ($exam_marks as $mark)
            <div class="row mark-data">
                <div class="col-6">
                    {{$mark->subject_name}}
                </div>
                <div class="col-3">
                    {{$mark->mark}}/100
                </div>
                <div class="col-3">
                    {{$mark->result}}
                </div>
            </div>
            @endforeach
        </div>        
    </div>
@endsection