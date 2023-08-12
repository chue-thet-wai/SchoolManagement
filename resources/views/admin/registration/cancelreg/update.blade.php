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
                    $("#student_id_hidden").val('');
                    $("#grade").val('');
                }             
            }
        });
    }
</script>
<div class="pagetitle">
    <h1>Update Cancel Registration</h1>
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
                <h4><b>Update Cancel Registration</b></h4>
            </div>
            <div class="col-md-1">
                <a class="btn btn-sm btn-primary" href="{{route('cancel_reg.index')}}" id="form-header-btn"> Back</a>
            </div>
            <div class="col-md-1"></div>
        </div>

        <br />
        <form method="POST" action="{{route('cancel_reg.update',$result[0]->id)}}" enctype="multipart/form-data">
            @csrf
            <br />
            {{method_field('PUT')}}

            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <div class='row'>
                            <label for="registration_no"><b>Registration Number<span style="color:brown">*</span></b></label>
                            <div class="col-sm-10">
                                <input type="text" id="registration_no" name="registration_no" value="{{$result[0]->registration_no}}"  class="form-control" required>
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
                            <input type="hidden" id="student_id_hidden" value="{{$result[0]->student_id}}"  name="student_id" class="form-control">
                            <input type="text" id="student_id" class="form-control" value="{{$result[0]->student_id}}"  disabled>
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
                            <input type="text" id="student_name" name="student_name" value="{{$result[0]->student_name}}"  class="form-control" disabled>
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
                            <input type="text" id="grade" name="grade" value="{{$grade_name}}"  class="form-control" disabled>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="cancel_date"><b>Cancel Date</b><span style="color:brown">*</span></label>
                        <div class="col-sm-10">
                            <input type="date" id="cancel_date" name="cancel_date" value="{{date('Y-m-d',strtotime($result[0]->cancel_date))}}" class="form-control" required>
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
                            <input type="amount" id="refund_amount" name="refund_amount" value="{{$result[0]->refund_amount}}" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="remark"><b>Remark</b><span style="color:brown">*</span></label>
                        <div class="col-sm-10">
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