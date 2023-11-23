@extends('layouts.dashboard')

@section('content')
<script>
    function getStudentData(){
        var cardNo = $("#card_id").val();
        $.ajax({
           type:'POST',
           url:'/admin/cash_counter/card_data',
           data:{
                _token :'<?php echo csrf_token() ?>',
                card_id  : cardNo
            },
           
           success:function(data){
                if (data.msg == 'found') {
                    $("#student_data_msg").html('Student data found!.');
                    $("#student_id").val(data.student_id);
                    $("#student_id_hidden").val(data.student_id);
                    $("#student_name").val(data.student_name);
                } else {
                    $("#student_data_msg").html('Student data not found!.');
                    $("#student_id").val('');
                    $("#student_name").val('');
                }             
            }
        });
    }
</script>
@section('content')
<div class="pagetitle">
    <h1>Cash Counter</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item active">Wallet</li>
            <li class="breadcrumb-item active">Cash Counter</li>
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
                <h4><b>Create Cash Counter</b></h4>
            </div>
            <div class="col-md-2">
                <a class="btn btn-sm btn-primary" href="{{url('admin/cash_counter/list')}}" id="form-header-btn"><i class="bi bi-skip-backward-fill"></i> Back</a>
            </div>
        </div>

        <br />
        <form method="POST" action="{{url('admin/cash_counter/save')}}" enctype="multipart/form-data">
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
                <div class="col-md-5">                    
                    <div class="form-group">
                        <label for=""><b>Amount<span style="color:brown">*</span></b></label>
                        <div class="col-sm-10">
                            <input type="number" id="amount" name="amount" class="form-control" required>
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