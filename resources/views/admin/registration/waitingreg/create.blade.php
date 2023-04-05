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
            <div class="col-md-9" style='color:#012970;'>
                <h4><b>Create Waiting List Registration</b></h4>
            </div>
            <div class="col-md-1">
                <a class="btn btn-sm btn-primary" href="{{route('waitinglist_reg.index')}}" id="form-header-btn"> Back</a>
            </div>
            <div class="col-md-1"></div>
        </div>

        <br />
        <form method="POST" action="{{route('waitinglist_reg.store')}}" enctype="multipart/form-data">
            @csrf
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="name"><b>Student Name<span style="color:brown">*</span></b></label>
                        <div class="col-sm-10">
                            <input type="text" name="name" class="form-control" required>
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
                            <input type="text" name="phone" class="form-control" required>
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
                            <input type="text" name="email" class="form-control" required>
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
                                    <option  value="{{$a->id}}">{{$a->name}}</option>
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
                                    <option  value="{{$a->id}}">{{$a->name}}</option>
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
                            <input type="date" id="enquiry_date" name="enquiry_date" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="form-group col-md-10">
                    <div class="row col-md-10">
                        <div class='col-sm-4'>
                            <label for="waiting_number"><b>Waiting Student Number </b></label>
                        </div>
                        <div class='col-sm-1'>
                            :
                        </div>
                        <div class='col-sm-2'>
                            <span id='waiting_number'><b>{{$waiting_number}}</b></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="form-group col-md-2">
                    <div class="d-grid mt-4">
                        <input type="submit" value="Save" class="btn btn-primary">
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>
            <br />
        </form>
    </div>
</section>


@endsection