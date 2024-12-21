@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
    <h1>Update Driver Rotues</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item active">Driver</li>
            <li class="breadcrumb-item active">Driver Routes</li>
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
                <h4><b>Update Driver Routes</b></h4>
            </div>
            <div class="col-md-2">
                <a class="btn btn-sm btn-primary" href="{{url('admin/driver_routes/list')}}" id="form-header-btn"><i class="bi bi-skip-backward-fill"></i> Back</a>
            </div>
        </div>

        <br />
        <form method="POST" action="{{route('driver_routes.update',$result[0]->id)}}" enctype="multipart/form-data">
            @csrf
            <br />
            {{method_field('PUT')}}
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="track_no"><b>Track Number<span style="color:brown">*</span></b></label>
                        <div class="col-sm-10">
                            <select class="form-select" id="track_no" name="track_no" required>
                                @foreach($track_number_list as $key => $value)
                                    <option value="{{ $value }}"
                                        @if ($result[0]->track_no == $value) selected @endif>{{ $value }}
                                    </option>
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
                        <label for="day"><b>Day</b></label>
                        <div class="col-sm-10">
                            <select class="form-select" id="day" name="day" required>
                                @foreach($weekdays as $key => $value)
                                    <option value="{{ $key }}"
                                        @if ($result[0]->day == $key) selected @endif>{{ $value }}
                                    </option>
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
                        <label for=""><b>Start Time</b></label>
                        <div class="col-sm-10">
                            <input type="time" name="start_time" value="{{$result[0]->start_time}}" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for=""><b>End Time</b></label>
                        <div class="col-sm-10">
                            <input type="time" name="end_time" value="{{$result[0]->end_time}}" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for=""><b>Route Type</b></label>
                        <div class="col-sm-10">
                            <select class="form-select" id="type" name="type" required>
                                @foreach($route_types as $key => $value)
                                <option value="{{ $key }}"
                                    @if ($result[0]->type == $key) selected @endif>{{ $value }}
                                </option>
                                @endforeach
                            </select>
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