@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
    <h1>Update Event</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item active">Create Information</li>
            <li class="breadcrumb-item active">Event</li>
        </ol>
    </nav>
    @include('layouts.error')
</div><!-- End Page Title -->

<section class="card">
    <div class="card-body">
        <br />
        
        <div class="row g-4">
            <div class="col-md-1"></div>
            <div class="col-md-9" style='color:#012970;'>
                <h4><b>Update Event</b></h4>
            </div>
            <div class="col-md-2">
                <a class="btn btn-sm btn-primary" href="{{url('admin/event/list')}}" id="form-header-btn"><i class="bi bi-skip-backward-fill"></i> Back</a>
            </div>
        </div>

        <br />
        <form method="POST" action="{{route('event.update',$result[0]->id)}}" enctype="multipart/form-data">
            @csrf
            <br />
            {{method_field('PUT')}}
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    <div class="form-group">
                        <label for="title"><b>Title</b></label>
                        <div class="col-sm-11">
                            <input type="text" name="title" class="form-control" value="{{$result[0]->title}}" required>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for=""><b>Grade</b></label>
                        <div class="col-sm-10">
                            <select class="form-select" id="grade_id" name="grade_id" >
                                <option  value="99">-- select --</option>
                                <option  value="0" 
                                    @if ($result[0]->grade_id == '0')
                                        selected
                                    @endif
                                >All</option>
                                @foreach($grade as $a)
                                    <option  value="{{$a->id}}"
                                    @if ($result[0]->grade_id == $a->id)
                                        selected
                                    @endif
                                    >{{$a->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for=""><b>Academic Year</b></label>
                        <div class="col-sm-10">
                            <select class="form-select" id="academic_year_id" name="academic_year_id" >
                                <option  value="99">-- select --</option>
                                @foreach($academic as $a)
                                    <option  value="{{$a->id}}"
                                    @if ($result[0]->academic_year_id == $a->id)
                                        selected
                                    @endif
                                    >{{$a->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="event_from_date"><b>From Date</b></label>
                        <div class="col-sm-10">
                            <input type="date" id="event_from_date" name="event_from_date" value="{{date('Y-m-d',strtotime($result[0]->event_from_date))}}" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="event_to_date"><b>To Date</b></label>
                        <div class="col-sm-10">
                            <input type="date" id="event_to_date" name="event_to_date" value="{{date('Y-m-d',strtotime($result[0]->event_to_date))}}" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    <div class="form-group">
                        <label for="description"><b>Description</b></label>
                        <div class="col-sm-11">
                            <textarea name="description" class="form-control" required>{{$result[0]->description}}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    <div class="form-group">
                        <label for="remark"><b>Remark</b></label>
                        <div class="col-sm-11">
                            <textarea name="remark" class="form-control" required>{{$result[0]->remark}}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="form-group col-md-2">
                    <div class="d-grid mt-4">
                        <input type="submit" value="Update" class="btn btn-primary">
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>
            <br />
        </form>
        <br />
    </div>
</section>


@endsection