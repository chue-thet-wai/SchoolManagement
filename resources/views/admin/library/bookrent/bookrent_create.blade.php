@extends('layouts.dashboard')

@section('content')
<script>
    function getStudentData(){
        var stID = $("#student_id").val();
        $.ajax({
           type:'POST',
           url:'/admin/student_registration/student_search',
           data:{
                _token :'<?php echo csrf_token() ?>',
                student_id  : stID
            },
           
           success:function(data){
                if (data.msg == 'found') {
                    $("#student_name").val(data.name);
                    $('#student_search_result').html('Student data found !');
                } else {
                    $("#student_name").val('');
                    $('#student_search_result').html('Student data not found !');
                }             
            }
        });
    }
</script>
<div class="pagetitle">
    <h1>Book Rent</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item active">Create Book Rent</li>
            <li class="breadcrumb-item active">Book Rent</li>
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
                <h4><b>Create Book Rent</b></h4>
            </div>
            <div class="col-md-2">
                <a class="btn btn-sm btn-primary" href="{{url('admin/book_rent/list')}}" id="form-header-btn"><i class="bi bi-skip-backward-fill"></i> Back</a>
            </div>
        </div>

        <br />
        <form method="POST" action="{{url('admin/book_rent/save')}}" enctype="multipart/form-data">
            @csrf
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="student_id"><b>Studnet ID</I><span style="color:brown">*</span></b></label>
                        <div class="col-sm-10">
                            <!--<select class="form-select" id="book_category" name="student_id" >
                                <option  value="99">--Select Student--</option>
                                @foreach($student_list as $a)
                                    <option  value="{{$a->student_id}}">{{$a->name}}</option>
                                @endforeach
                            </select>-->
                            <div class="row">
                                <div class='col-sm-10'>
                                    <input type="text" id="student_id" name="student_id" class="form-control">
                                    <br />
                                    <span id="student_search_result"></span>
                                </div>
                                <div class='col-sm-2'>
                                    <button type="button" name="student_search" id="student_search" class="btn btn-sm btn-primary" onclick="getStudentData()">Search</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="book_category"><b>Studnet Name</b></label>
                        <div class="col-sm-10">
                            <input type="text" name="student_name" id="student_name" class="form-control" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="book_name"><b>Book Title<span style="color:brown">*</span></b></label>
                        <div class="col-sm-10">
                            <!--<select class="form-select" id="book_name" name="book_id" >
                                <option  value="99">--Select Books--</option>
                                @foreach($book_list as $a)
                                    <option  value="{{$a->id}}">{{$a->title}}</option>
                                @endforeach
                            </select>-->
                            <input type="text" name="book_title" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="rent_date"><b>Rent Date<span style="color:brown">*</span></b></label>
                        <div class="col-sm-10">
                            <input type="date" name="rent_date" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="return_date"><b>Return Date<span style="color:brown">*</span></b></label>
                        <div class="col-sm-10">
                            <input type="date" name="return_date" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="actual_return_date"><b>Actual Return Date</b></label>
                        <div class="col-sm-10">
                            <input type="date" name="actual_return_date" class="form-control">
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