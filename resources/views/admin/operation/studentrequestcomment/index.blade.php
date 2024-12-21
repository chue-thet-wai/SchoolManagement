@extends('layouts.dashboard')

@section('content')
<script>
    $(document).ready(function() {
       
        // Function to send AJAX request and save last read time
        function saveLastReadTime() {
            var studentRequestId = $("#student_request_id").val();

            $.ajax({
                type: 'POST',
                url: '/admin/student_request/save_last_read_time', // URL to your server endpoint to save last read time
                data: {
                    _token :'<?php echo csrf_token() ?>',
                    student_request_id: studentRequestId
                },
                success: function(response) {
                    console.log("Last read time saved successfully.");
                },
                error: function(xhr, status, error) {
                    console.error("Error occurred while saving last read time:", error);
                }
            });
        }

        // Event listener for beforeunload event
        $(window).on('beforeunload', function() {
            saveLastReadTime();
        });
    });
</script>
<div class="pagetitle">
    <h1>Student Request Comment</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item active">Operation</li>
            <li class="breadcrumb-item active">Student Request/ Comment</li>
        </ol>
    </nav>
    @include('layouts.error')
</div><!-- End Page Title -->

<section class="card">
    <div class="card-body">
        <br />
        <div class="row g-4">
            <div class="col-md-1"></div>
            <div class="col-md-9 content-title">
                <h5><b>Student Request Data</b></h5>
                <table class="table table-bordered">
                    <tr>
                        <td><b>Photo</b></td>
                        <td><img src="{{asset('assets/studentrequest_images/'.$student_request_data['photo'])}}" alt="" height=50 width=50></img></td>
                    </tr>
                    <tr>
                        <td><b>Message</b></td>
                        <td>{{$student_request_data['message']}}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-2">
                <a class="btn btn-sm btn-primary" href="{{url('admin/student_request/list')}}" id="form-header-btn"><i class="bi bi-skip-backward-fill"></i> Back</a>
            </div>
        </div>
        <br />
        <div class="row mt-2">
            <div class="col-md-1"></div>
            <div class="col-md-10 content-title">
                <h5><b>Comment List</b></h5>
            </div>
        </div>
        <div class="row m-2">
            <div class="col-md-1"></div>
            <form class="col-md-10" method="POST" action="{{ url('admin/student_request/comment/store') }}" enctype="multipart/form-data">
                @csrf
                <br />
                <input type="hidden" id="student_request_id" name="student_request_id" value="{{$student_request_data['id']}}" />
                <div class="row">
                    <div class="form-group col-md-10">
                        <label for="comment"><b>Comment</b></label>
                        <div class="col-sm-12">
                            <input type="text" id="comment" name="comment" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group col-md-2">
                        <div class="d-grid mt-4">
                            <input type="submit" value="Add" class="btn btn-primary">
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="row g-4 m-2" style="display: flex;overflow-x: auto;">
            <div class="col-md-1"></div>
            <div class="col-md-10">
                <table cellpadding="0" cellspacing="0" class="datatable table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Comment</th>
                            <th>Created By</th>
                            <th>Created Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($list_result) && $list_result->count())
                            @foreach($list_result as $res)
                            <tr>
                                <td>{{$res->comment }}</td>
                                <td>@if ($res->comment_by_parent != null) {{"Parent"}} @else {{"School"}} @endif</td>
                                <td>{{date('Y-m-d H:i:s',strtotime($res->created_at))}}</td>
                                <td class="center">
                                    <form method="post" action="{{ url('admin/special_request_comment/delete/'.$res->id) }}" style="display: inline;">
                                        @csrf
                                        {{ method_field('DELETE') }}
                                        <button type="submit" value="delete" class="btn m-1 p-0 border-0">
                                            <span id="boot-icon" class="bi bi-trash" style="font-size: 20px; color: rgb(165, 42, 42);"></span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        @else
                        <tr>
                            <td colspan="8">There are no data.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="d-flex">
            {!! $list_result->links() !!}
        </div>
    </div>
</section>

@endsection

