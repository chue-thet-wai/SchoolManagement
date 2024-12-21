@extends('driver.driver_layout')

@section('driver_content')
    <header>
        <div class="system-bar">
            <div class="row">
                <div class="col-3 left">
                    <a href="{{ url('driver/schedule') }}" class="back-button">
                        <img src="{{asset('driver/back.png')}}" alt="Image">
                    </a>
                </div>
                <div class="col-6 center">Route</div>
                <div class="col-3 right"></div>
            </div>
        </div>
    </header>
    <div class="page">
        <div class="route-container">
            @include('layouts.error')
            <div class="mt-3" id="student_routes">
                <table class="table custom-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Student Name</th>
                            <th>Township</th>
                            <th>Address</th>
                            <th>Contact</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    @php $student_count=0; @endphp
                        @foreach ($ferry_student as $student)
                        <tr>
                            <td>{{$student_count+1}}</td>
                            <td class="long-text" data-max-length="10">{{$student->name}}</td>
                            <td>{{$township[$student->township]}}</td>
                            <td class="long-text" data-max-length="10">{{$student->address}}</td>
                            <td>{{$student->phone}}</td>
                            <td>
                                <select id="route-status" data-route-id="{{ $route_id }}" data-student-id="{{ $student->student_id }}">
                                    @foreach ($route_status as $key => $value)
                                        @if (array_key_exists($student->student_id,$today_routes))
                                            <option value="{{$key}}"
                                                @if ($today_routes[$student->student_id] == $key) selected @endif
                                            >{{$value}}</option>
                                        @else 
                                            <option value="{{$key}}">{{$value}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table> 
            </div>
        </div>        
    </div>
    <div class="modal fade" id="cancelNoteModal" tabindex="-1" role="dialog" aria-labelledby="cancelNoteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelNoteModalLabel">Enter Reason for Cancellation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="cancelCancel" style="border:none;background:none;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <textarea id="cancelNote" class="form-control" rows="3" placeholder="Enter reason for cancellation"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="modelCancel">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmCancel" style="color:#ffffff;">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
        // Function to handle route status change
        $('#student_routes').on('change', '#route-status', function() {
            var studentId = $(this).data('student-id');
            var routeId = $(this).data('route-id');
            var newStatus = $(this).val();

            if (newStatus === '5') {
                $('#cancelNoteModal').modal('show');

                $('#confirmCancel').click(function() {
                    var cancelNote = $('#cancelNote').val();
                    if (cancelNote.trim() === '') {
                        alert('Please provide a reason for cancellation.');
                        return;
                    }

                    // Send AJAX request to update status
                    $.ajax({
                        url: '{{ url("driver/update_route_status") }}',
                        type: 'POST',
                        data: {
                            student_id: studentId,
                            route_id: routeId,
                            new_status: newStatus,
                            cancel_note: cancelNote,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            location.reload();
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                            location.reload();
                            //alert('Failed to update route status. Please try again.');
                        }
                    });

                    $('#cancelNoteModal').modal('hide');
                });

                $('#cancelCancel').click(function() {
                    location.reload();
                });

            } else {
                // Send AJAX request to update status
                $.ajax({
                    url: '{{ url("driver/update_route_status") }}',
                    type: 'POST',
                    data: {
                        student_id: studentId,
                        route_id: routeId,
                        new_status: newStatus,
                        cancel_note: '',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                        location.reload();
                        //alert('Failed to update route status. Please try again.');
                    }
                });
            }
        });
        $('.long-text').each(function() {
            var maxLength = $(this).data('max-length');
            var text = $(this).text();
            if (text.length > maxLength) {
                var newText = text.substring(0, maxLength) + '<br>' + text.substring(maxLength);
                $(this).html(newText);
            }
        });
        $('#modelCancel').click(function() {
            location.reload();
        });
    });
    </script>
@endsection