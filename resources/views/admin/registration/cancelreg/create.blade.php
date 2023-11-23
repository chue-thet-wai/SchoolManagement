@extends('layouts.dashboard')

@section('content')
<script>
    function getRegistrationData(){
        var regNo = $("#registration_no").val();
        $.ajax({
           type:'POST',
           url:'/admin/cancel_reg/registration_search',
           data:{
                _token :'<?php echo csrf_token() ?>',
                registration_no  : regNo
            },
           
           success:function(data){
                if (data.msg == 'found') {
                    $("#registration_msg").html('Registration data found!.');
                    $("#student_id").val(data.student_id);
                    $("#student_id_hidden").val(data.student_id);
                    $("#student_name").val(data.student_name);
                    $("#grade").val(data.grade);
                } else {
                    $("#registration_msg").html('Registration data not found!.');
                    $("#student_id").val('');
                    $("#student_name").val('');
                    $("#grade").val('');
                }             
            }
        });
    }
</script>
<div class="pagetitle">
    <h1>Cancel Registration</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item active">Registration</li>
            <li class="breadcrumb-item active">Cancel Registration</li>
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
                <h4><b>Create Cancel Registration</b></h4>
            </div>
            <div class="col-md-2">
                <a class="btn btn-sm btn-primary" href="{{url('admin/cancel_reg/list')}}" id="form-header-btn"><i class="bi bi-skip-backward-fill"></i> Back</a>
            </div>
        </div>

        <br />
        <form method="POST" action="{{route('cancel_reg.store')}}" enctype="multipart/form-data">
            @csrf
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <div class='row'>
                            <label for="registration_no"><b>Registration Number<span style="color:brown">*</span></b></label>
                            <div class="col-sm-10">
                                <input type="text" id="registration_no" name="registration_no" class="form-control" required>
                            </div>
                            <div class="col-md-2">
                                <button type="button" name="registration_search" id="registration_search" class="btn btn-sm btn-primary mt-1" onclick="getRegistrationData()">Search</button>
                            </div>
                        </div>
                        <div class="row">
                            <span name="registration_msg" id="registration_msg"></span>
                        </div>
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
                        <label for="grade"><b>Grade Level<span style="color:brown">*</span></b></label>
                        <div class="col-sm-10">
                            <input type="text" id="grade" name="grade" class="form-control" disabled>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="cancel_date"><b>Cancel Date</b></label>
                        <div class="col-sm-10">
                            <input type="date" id="cancel_date" name="cancel_date" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="refund_amount"><b>Refund Amount<span style="color:brown">*</span></b></label>
                        <div class="col-sm-10">
                            <input type="amount" id="refund_amount" name="refund_amount" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="remark"><b>Remark</b></label>
                        <div class="col-sm-10">
                            <textarea name="remark" class="form-control" required></textarea>
                        </div>
                    </div>
                </div>
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