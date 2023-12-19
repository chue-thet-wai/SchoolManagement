@extends('layouts.dashboard')

@section('content')
    <div class="pagetitle">
        <h1>Schedule</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
                <li class="breadcrumb-item active">Create Information</li>
                <li class="breadcrumb-item active">Schedule</li>
            </ol>
        </nav>
        @include('layouts.error')
    </div><!-- End Page Title -->

    <section class="card">
        <div class="card-body">
            <br />

            <div class="row g-4">
                <div class="col-md-10 content-title">
                    <h4><b>Schedule List</b></h4>
                </div>
                <div class="col-md-2">
                    <a class="btn btn-sm btn-primary" href="{{ route('schedule.create') }}" id="form-header-btn"><span
                            class="bi bi-plus"></span> Create</a>
                </div>
            </div>
            <br />
            <form class="row g-4" method="POST" action="{{ url('admin/schedule/list') }}" enctype="multipart/form-data">
                @csrf
                <div class='row g-4'>
                    <div class="form-group col-md-3">
                        <label for="schedule_classid"><b>Class</b></label>
                        <div class="col-sm-10">
                            <select class="form-select" id="schedule_classid" name="schedule_classid">
                                <option value=''>--Select--</option>
                                @foreach ($classes as $key => $value)
                                    <option value="{{ $key }}" @if (request()->input('schedule_classid') == $key) selected @endif>
                                        {{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="schedule_teacher"><b>Teacher</b></label>
                        <div class="col-sm-10">
                            <select class="form-select" id="schedule_teacher" name="schedule_teacher">
                                <option value=''>--Select--</option>
                                @foreach ($teacher_list as $key => $value)
                                    <option value="{{ $key }}" @if (request()->input('schedule_teacher') == $key) selected @endif>
                                        {{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="schedule_weekday"><b>Weekday</b></label>
                        <div class="col-sm-10">
                            <select class="form-select" id="schedule_weekday" name="schedule_weekday">
                                <option value=''>--Select--</option>
                                @foreach ($weekdays as $key => $value)
                                    <option value="{{ $key }}" @if (request()->input('schedule_weekday') == $key) selected @endif>
                                        {{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class='row p-3'>
                    <div class="form-group col-sm-1 p-2">
                        <div class="d-grid mt-2">
                            <button type="submit" name="action" value="search"
                                class="btn btn-sm btn-primary">Search</button>
                        </div>
                    </div>
                    <div class="form-group col-sm-1 p-2">
                        <div class="d-grid mt-2">
                            <button type="submit" name="action" value="reset"
                                class="btn btn-sm btn-primary">Reset</button>
                        </div>
                    </div>
                </div>
            </form>
            <br />
            <div class="row g-4 m-2" style="display: flex;overflow-x: auto;">
                <table cellpadding="0" cellspacing="0" class="datatable table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Class</th>
                            <th>Teacher</th>
                            <th>Subject</th>
                            <th>Weekday</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!empty($list_result) && $list_result->count())
                            @php $i=1;@endphp
                            @foreach ($list_result as $res)
                                <tr>
                                    <td>@php
                                        echo $i;
                                        $i++;
                                    @endphp</td>
                                    <td>{{ $classes[$res->class_id] }}</td>
                                    <td>{{ $teacher_list[$res->teacher_id] }}</td>
                                    <td>{{ $subjects[$res->subject_id] }}</td>
                                    <td>{{ $weekdays[$res->weekdays] }}</td>
                                    <td>{{ $res->start_time }}</td>
                                    <td>{{ $res->end_time }}</td>
                                    <td class="center">
                                        <a href="{{ route('schedule.edit', $res->id) }}">
                                            <button type="submit" value="delete" class="btn m-1 p-0 border-0">
                                                <span id="boot-icon" class="bi bi-pencil-square"
                                                    style="font-size: 20px; color:rgb(58 69 207);"></span>
                                            </button>
                                        </a>
                                        <form method="post" action="{{ route('schedule.destroy', $res->id) }}"
                                            style="display: inline;">
                                            @csrf
                                            {{ method_field('DELETE') }}
                                            <button type="submit" value="delete" class="btn m-1 p-0 border-0">
                                                <span id="boot-icon" class="bi bi-trash"
                                                    style="font-size: 20px; color: rgb(165, 42, 42);"></span>
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
            <div class="d-flex">
                {!! $list_result->links() !!}
            </div>
        </div>
    </section>


@endsection
