@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
    <h1>Food Order</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item active">Shop</li>
            <li class="breadcrumb-item active">Food Order</li>
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
                <h4><b>Create Food Order</b></h4>
            </div>
            <div class="col-md-1">
                <a class="btn btn-sm btn-primary" href="{{url('admin/food_order/list')}}" id="form-header-btn"> Back</a>
            </div>
            <div class="col-md-1"></div>
        </div>

        <br />
        <form method="POST" action="{{url('admin/food_order/save')}}" enctype="multipart/form-data">
            @csrf
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="form-group col-md-5">
                    <div class='row'>
                        <label for="card_id"><b>Card ID<span style="color:brown">*</span></b></label>
                        <div class="col-sm-10">
                            <input type="text" id="card_id" name="card_id" class="form-control" required>
                        </div>
                        <div class="col-md-2">
                            <button type="button" name="cardid_search" id="cardid_search" class="btn btn-sm btn-primary mt-1" onclick="getStudentData()">Search</button>
                        </div>
                    </div>
                    <div class="row">
                        <span name="student_data_msg" id="student_data_msg"></span>
                    </div>
                </div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="student_id"><b>Student ID<span style="color:brown">*</span></b></label>
                        <div class="col-sm-10">
                            <input type="hidden" id="student_id_hidden" name="student_id" class="form-control">
                            <input type="text" id="student_id" class="form-control" disabled>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="student_name"><b>Student Name<span style="color:brown">*</span></b></label>
                        <div class="col-sm-10">
                            <input type="text" id="student_name" name="student_name" class="form-control" disabled>
                        </div>
                    </div>
                </div>
            </div>
            <br />
           
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="form-group col-md-2">
                    <div class="d-grid mt-4">
                        <input type="submit" value="Add" class="btn btn-primary">
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>
            <br />
        </form>
    </div>
</section>


@endsection