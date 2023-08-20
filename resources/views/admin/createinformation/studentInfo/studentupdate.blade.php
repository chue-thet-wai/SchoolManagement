@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
    <h1>Student Information</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item active">Create Information</li>
            <li class="breadcrumb-item active">Student Information</li>
        </ol>
    </nav>
    @include('layouts.error')
</div><!-- End Page Title -->

<section class="card">
    @php
        $gradeList = [];
    @endphp
    <div class="card-body">
        <br />
        
        <div class="row g-4">
            <div class="col-md-1"></div>
            <div class="col-md-9" style='color:#012970;'>
                <h4><b>Update Student Information</b></h4>
            </div>
            <div class="col-md-1">
                <a class="btn btn-sm btn-primary" href="{{ url('admin/student_info/list') }}" id="form-header-btn"> Back</a>
            </div>
            <div class="col-md-1"></div>
        </div>

        <br />
        <form method="POST" action="{{ url('admin/student_info/update/'.$result[0]->student_id) }}" enctype="multipart/form-data">
            @csrf
            <br />
            {{method_field('POST')}}
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="form-group col-md-5">
                    <label for="name"><b>Name<span style="color:brown">*</span></b></label>
                    <div class="col-sm-10">
                        <input type="text" name="name" value="{{$result[0]->name}}" class="form-control" required>
                    </div>
                </div>
                <div class="form-group col-md-5">
                    <label for="name_mm"><b>Name(Myanmar)<span style="color:brown">*</span></b></label>
                    <div class="col-sm-10">
                        <input type="text" name="name_mm" value="{{$result[0]->name_mm}}" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>

            <br />

            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="form-group col-md-5">
                    <label for="religion"><b>Religion<span style="color:brown">*</span></b></label>
                    <div class="col-sm-10">
                        <input type="text" name="religion" value="{{$result[0]->religion}}" class="form-control">
                    </div>
                </div>
                <div class="form-group col-md-5">
                    <label for="nationality"><b>Nationality<span style="color:brown">*</span></b></label>
                    <div class="col-sm-10">
                        <input type="text" name="nationality" value="{{$result[0]->nationality}}" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>

            <br />

            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="form-group col-md-5">
                    <label for=""><b>Gender</b></label>
                    <div class="col-sm-10">
                        <select class="form-select" id="gender" name="gender">
                            @foreach($gender as $key => $value)
                            <option value="{{$key}}"
                            @if ($result[0]->gender == $key)
                                selected
                            @endif
                            >{{$value}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group col-md-5">
                    <label for=""><b>Towhship</b></label>
                    <div class="col-sm-10">
                        <select class="form-select" id="township" name="township">
                            @foreach($township as $key => $value)
                            <option value="{{$key}}" 
                            @if ($result[0]->township == $key)
                                selected
                            @endif
                            >{{$value}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>

            <br />

            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="form-group col-md-5">
                    <label for="date_of_birth"><b>Date of Birth<span style="color:brown">*</span></b></label>
                    <div class="col-sm-10">
                        <input type="date" name="date_of_birth" value="{{date('Y-m-d',strtotime($result[0]->date_of_birth))}}" class="form-control" required>
                    </div>
                </div>
                <div class="form-group col-md-5">
                    <label for="card_id"><b>Card ID<span style="color:brown">*</span></b></label>
                    <div class="col-sm-10">
                        <input type="text" name="card_id" value="{{$result[0]->card_id}}" class="form-control" required disabled>
                    </div>
                </div>
            </div>

            <br />

            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="form-group col-md-5">
                    <label for="father_name"><b>Father Name<span style="color:brown">*</span></b></label>
                    <div class="col-sm-10">
                        <input type="text" name="father_name" value="{{$result[0]->father_name}}" class="form-control" required>
                    </div>
                </div>
                <div class="form-group col-md-5">
                    <label for="father_name_mm"><b>Father Name(Myanmar)<span style="color:brown">*</span></b></label>
                    <div class="col-sm-10">
                        <input type="text" name="father_name_mm" value="{{$result[0]->father_name_mm}}" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>

            <br />

            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="form-group col-md-5">
                    <label for="mother_name"><b>Mother Name<span style="color:brown">*</span></b></label>
                    <div class="col-sm-10">
                        <input type="text" name="mother_name" value="{{$result[0]->mother_name}}" class="form-control" required>
                    </div>
                </div>
                <div class="form-group col-md-5">
                    <label for="mother_name_mm"><b>Mother Name(Myanmar)<span style="color:brown">*</span></b></label>
                    <div class="col-sm-10">
                        <input type="text" name="mother_name_mm" value="{{$result[0]->mother_name_mm}}" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>

            <br />

            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="form-group col-md-5">
                    <label for="father_phone"><b>Father Phone<span style="color:brown">*</span></b></label>
                    <div class="col-sm-10">
                        <input type="text" name="father_phone" value="{{$result[0]->father_phone}}" class="form-control" required>
                    </div>
                </div>
                <div class="form-group col-md-5">
                    <label for="mother_phone"><b>Mother Phone<span style="color:brown">*</span></b></label>
                    <div class="col-sm-10">
                        <input type="text" name="mother_phone" value="{{$result[0]->mother_phone}}" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>

            <br />

            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="form-group col-md-10">
                    <label for="address_1"><b>Address 1</b></label>
                    <div class="col-sm-11">
                        <textarea name="address_1" class="form-control">{{$result[0]->address_1}}</textarea>
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>

            <br />

            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="form-group col-md-10">
                    <label for="address_2"><b>Address 2</b></label>
                    <div class="col-sm-11">
                        <textarea name="address_2" class="form-control">{{$result[0]->address_2}}</textarea>
                    </div>
                </div>
                <div class="col-md-1"></div>
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
        </form>
        <br />
    </div>
</section>


@endsection