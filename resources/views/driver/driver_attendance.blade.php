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
                <div class="col-6 center">My Attendance</div>
                <div class="col-3 right"></div>
            </div>
        </div>
    </header>
    <div class="page">
        <div class="attendance-container">
            @include('layouts.error')
            <div class="m-2" id='calendar'></div>
            <div class="m-2" id="attendance">
                <h5>Today</h5>
                <div class="row mt-2 form-group">
                    <input type="date" class="col-5 ms-2 form-control" name="today_date" id="today_date" value="{{ date('Y-m-d') }}" readonly>
                    <button type="button" class="col-3 ms-2 btn" id="btn-checkin" @if($today_checkin) disabled @endif>Check In</button>
                    <button type="button" class="col-3 ms-2 btn" id="btn-checkout" @if($today_checkout) disabled @endif>Check Out</button> 
                </div>
            </div>
            <div class="m-2" id="history">
                <h5>Attendance History</h5>
                <hr id="line-break">
                <div class="mt-1" id="history-list">
                    @foreach ($driver_attendance as $att)
                        <div class="card mt-2 p-2">
                            <span>{{date('d M Y',strtotime($att->date))}}</span>
                            <span>Check In &nbsp;&nbsp; : @if($att->check_in != null) {{date('h/i A',strtotime($att->check_in . ' +6 hours 30 minutes'))}} @endif</span>
                            <span>Check Out : @if ($att->check_out != null) {{date('h/i A',strtotime($att->check_out. ' +6 hours 30 minutes'))}} @endif</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>        
    </div>


    <link href="{{ asset('css/calendar.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <script>
		$(document).ready(function() {
            var SITEURL = "{{ url('/') }}";			  
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var calendar = $('#calendar').fullCalendar({
                header: {
                    left: 'prev',
                    center: 'title',
                    right: 'next'
                },
                height: 350,
                events: SITEURL + "/driver/attendance",
                eventRender: function(event, element, view) {
                    if (event.status === 'leave') {
                        element.css('background-color', 'red'); // Change the background color of the event element to red for 'leave' status
                        $('.fc-day[data-date="' + event.date + '"]').css('background', 'red');
                    } else if (event.status === 'present') {
                        element.css('background-color', 'green'); // Change the background color of the event element to green for 'present' status
                        $('.fc-day[data-date="' + event.date + '"]').css('background', 'green');
                    }
                }
            });

            $('#btn-checkin').click(function() {
                $.ajax({
                    url: SITEURL + "/driver/checkin",
                    type: "POST",
                    data: {
                        date: $('#today_date').val(),
                        type: 'checkin'
                    },
                    success: function(response) {
                        //toastr.success(response.message);
                        location.reload();
                    }
                });
            });

            $('#btn-checkout').click(function() {
                $.ajax({
                    url: SITEURL + "/driver/checkout",
                    type: "POST",
                    data: {
                        date: $('#today_date').val(),
                        type: 'checkout'
                    },
                    success: function(response) {
                        //toastr.success(response.message);
                        location.reload();
                    }
                });
            });
        }); 

    </script>
@endsection
