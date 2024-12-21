@extends('parent.parent_layout')

@section('parent_content')
<link href="{{ asset('css/home.css') }}" rel="stylesheet">
<script src="{{ asset('js/home.js') }}" defer></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<style>
    body{
        margin-top:1%;
    }
    .calendar, .calendar_weekdays, .calendar_content {
        max-width: 100%;
    }
    .event-container {
        padding: 0px;
    }
</style>
    <header>
        <div class="system-bar">
            <div class="left">
                <a href="{{ url('parent/student_profile/'.$student_id) }}" class="back-button">Back</a>
            </div>
            <div class="centre">EVENT</div>
            <div class="right">
                <!--<a href="#" class="plus-button"><i class="bi bi-plus-lg"></i></a>-->
            </div>
        </div>
    </header>
    <div class="page">
        @include('layouts.error')
        <div class="event-container mb-5">
            <div class="row">
                <div>
                    <div class="calendar calendar-first" id="calendar_first">
                        <div class="calendar_weekdays"></div>
                        <div class="calendar_content"></div>
                    </div>   
                </div>                   
            </div>

            <div class="row mt-3 mb-3" style="background:#e9ecef;">
                <h5 class="p-2">Event Date</h5>
            </div>
            <div class="row ms-2">
                <div class="event-list">
                    @foreach ($event as $e)
                    <div class="card" id="parent-card">
                        <div class="event-date" style="background-color:#2B7A4A;">
                            @php
                                $fromDate = date('d/l/Y', strtotime($e->event_from_date));
                                $toDate = date('d/l/Y', strtotime($e->event_to_date));
                            @endphp

                            {{ $fromDate . ' to ' . $toDate }}
                        </div>
                        <div class="event-description p-2">                                                
                            <p class="mb-1">
                                <strong>{{$e->title}}</strong>
                            </p>
                            <p>
                                {{$e->description}}
                            </p>
                        </div>
                    </div>
                    <br />
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection