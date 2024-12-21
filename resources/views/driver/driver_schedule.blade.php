@extends('driver.driver_layout')

@section('driver_content')
    <header>
        <div class="system-bar">
            <div class="row">
                <div class="col-3 left">
                    <a href="{{ url('driver/home') }}" class="back-button">
                        <img src="{{asset('driver/back.png')}}" alt="Image">
                    </a>
                </div>
                <div class="col-6 center">My Schedule</div>
                <div class="col-3 right"></div>
            </div>
        </div>
    </header>
    <div class="page">
        <div class="schedule-container">
            @include('layouts.error')
            <div class="row mt-2 form-group">
                <div class="col-4 schedule_date"><label for="schedule_date"><b>Date</b></label></div>
                <div class="col-8">
                    <input type="date" name="schedule_date" id="schedule_date" value="{{ date('Y-m-d') }}" class="form-control" readonly>
                </div>
            </div>
            <div class="mt-3" id="today_routes">
                <h5>Today Routes</h5>
                <table class="table custom-table">
                    <thead>
                        <tr>
                            <th>Route</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Number of Student</th>
                            <th>Type</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    @php $route_count=0; @endphp
                    @foreach ($driver_routes as $route)
                        <tr>
                            <td>{{$route_count+1}}</td>
                            <td>{{$route->start_time}}</td>
                            <td>{{$route->end_time}}</td>
                            <td>{{$student_number}}</td>
                            <td>{{$route_types[$route->type]}}</td>
                            <td><a href="/driver/route/{{$route->id}}" id="route_drive">Drive</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table> 
            </div>
        </div>        
    </div>
@endsection