@extends('parent.parent_layout')

@section('parent_content')
    <header>
        <div class="row system-bar">
            <div class="col-3 left">
                <!--<a href="#" class="back-button">Back</a>-->
            </div>
            <div class="col-6 centre">ANNOUANCEMENT</div>
            <div class="col-3 right">
                <!--<a href="#" class="plus-button"><i class="bi bi-plus-lg"></i></a>-->
            </div>
        </div>
    </header>
    <div class="page">
        @include('layouts.error')
        @if (count($today_events) > 0)
            <div><strong>Today</div></strong>
            <div class="mt-2 today-annouancement-list">
                @foreach ($today_events as $event)
                    <div class="card" id="parent-card">
                        <div class="p-2"> 
                            <p>{{$event['title']}}</p>                                               
                            <p>
                                {{$event['description']}}
                            </p>
                        </div>
                        <div class="ps-2 annouce-date">
                            {{date('Y-m-d',strtotime($event['event_from_date']))}}-
                            {{date('Y-m-d',strtotime($event['event_to_date']))}}
                        </div>
                    </div>
                    <br />
                @endforeach
            </div>
        <br />
        @endif
        @if (count($early_events) > 0)
            <div><strong>Earlier this year</strong></div>
            <div class="mt-2 earlier-annouancement-list">
                @foreach ($early_events as $event)
                    <div class="card" id="parent-card">
                        <div class="p-2"> 
                            <p>{{$event['title']}}</p>                                               
                            <p>
                                {{$event['description']}}
                            </p>
                        </div>
                        <div class="ps-2 annouce-date">
                            {{date('Y-m-d',strtotime($event['event_from_date']))}}-
                            {{date('Y-m-d',strtotime($event['event_to_date']))}}
                        </div>
                    </div>
                    <br />
                @endforeach                
            </div>
        @endif
    </div>
@endsection