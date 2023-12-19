@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
    <h1>Waiting List Registration</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item active">Registration</li>
            <li class="breadcrumb-item active">Waiting List Registration</li>
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
                <h4><b>Update Waiting List Registration</b></h4>
            </div>
            <div class="col-md-2">
                <a class="btn btn-sm btn-primary" href="{{url('admin/waitinglist_reg/list')}}" id="form-header-btn"><i class="bi bi-skip-backward-fill"></i> Back</a>
            </div>
        </div>

        <br />
        <form method="POST" action="{{route('waitinglist_reg.update',$result[0]->id)}}" enctype="multipart/form-data">
            @csrf
            <br />
            {{method_field('PUT')}}
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="name"><b>Student Name<span style="color:brown">*</span></b></label>
                        <div class="col-sm-10">
                            <input type="text" name="name" value="{{$result[0]->name}}" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="phone"><b>Phone<span style="color:brown">*</span></b></label>
                        <div class="col-sm-10">
                            <input type="text" name="phone" value="{{$result[0]->phone}}" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="email"><b>Email<span style="color:brown">*</span></b></label>
                        <div class="col-sm-10">
                            <input type="text" name="email" value="{{$result[0]->email}}" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="name"><b>Grade<span style="color:brown">*</span></b></label>
                        <div class="col-sm-10">
                            <select class="form-select" id="grade_id" name="grade_id" >
                                <option  value="99">select grade</option>
                                @foreach($grade_list as $a)
                                    <option  value="{{$a->id}}"
                                        @if($a->id==$result[0]->grade_id)
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
                        <label for="name"><b>Academic Year<span style="color:brown">*</span></b></label>
                        <div class="col-sm-10">
                            <select class="form-select" id="academic_year_id" name="academic_year_id" >
                                <option  value="99">select Academic Year</option>
                                @foreach($academic_list as $a)
                                    <option  value="{{$a->id}}" 
                                        @if($a->id==$result[0]->academic_year_id)
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
                        <label for="enquiry_date"><b>Enquiry Date</span></b></label>
                        <div class="col-sm-10">
                            <input type="date" id="enquiry_date" name="enquiry_date" value="{{date('Y-m-d',strtotime($result[0]->enquiry_date))}}" class="form-control">
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
            </div>
            <br />
        </form>
        <br />
    </div>
</section>


@endsection