@extends('parent.parent_layout')

@section('parent_content')
    <header>
        <div class="system-bar">
            <div class="left">
                <a href="{{ url('parent/student_profile/'.$student_id) }}" class="back-button">Back</a>
            </div>
            <div class="centre">HOMEWORK</div>
            <div class="right">
                <!--<a href="#" class="plus-button"><i class="bi bi-plus-lg"></i></a>-->
            </div>
        </div>
    </header>
    <div class="page">
        @include('layouts.error')
        <div class='student-data'>
            <h5><strong> {{$student_data->name}} </strong></h5>
            <p> Class - {{$student_data->class_name}}</p>
        </div>
        <hr />
        <div class="container mb-5">
            @foreach ($homework as $hw)
            <div class="card">
                <div class="card-body" id="parent-card">
                    <div class="row">
                        <div class="col-6">
                            <div>Assigned Date</div>
                            <div>{{date('Y/m/d',strtotime($hw->created_at))}}</div>
                            <div class="mt-2">{{$hw->title}}</div>
                            <p>Subject : {{$hw->subject_name}}</p>
                        </div>
                        <div class="col-6">
                            <div>Due Date</div>
                            <div>{{date('Y/m/d',strtotime($hw->due_date))}}</div>
                            <div>
                                <a class="btn btn-labeled btn-info" href="{{asset('assets/homework_files/'.$hw->homework_file)}}" download> 
                                    <span id="boot-icon" class="bi bi-download" style="font-size: 20px; color: rgb(58 69 207); margin:2px;"></span>{{$hw->homework_file}}
                                </a>
                            </div>
                        </div>
                    </div>
                    <hr class="m-0" />
                    <div class="row p-2">{{$hw->description}}</div>
                </div>
            </div>
            <br />
            @endforeach
        </div>
    </div>
@endsection