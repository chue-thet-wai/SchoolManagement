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
        .exam-term-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-decoration: none;
            color: #0F4D19;
        }
    </style>
    <header>
        <div class="system-bar">
            <div class="left">
                <a href="{{ url('parent/student_profile/'.$student_id) }}" class="back-button">Back</a>
            </div>
            <div class="centre">EXAM RESULT</div>
            <div class="right">
                <!--<a href="#" class="plus-button"><i class="bi bi-plus-lg"></i></a>-->
            </div>
        </div>
    </header>
    <div class="page">
        @include('layouts.error')
        <div class="exam-result-container">
            @foreach ($exam_terms as $term)
            <div class="row mark-data">
                <div class="col-12">
                    <a href="{{ url('parent/student_profile/'.$student_id.'/exam-result/'.$term->id) }}" class="exam-term-link">
                        {{$term->name}} <i class="bi bi-chevron-right"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>        
    </div>
@endsection