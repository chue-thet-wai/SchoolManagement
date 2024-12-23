@extends('layouts.dashboard')

@section('content')

<script>
    function getGuardianData(){
        var ph = $("#guardian_phone").val();
        $.ajax({
           type:'POST',
           url:'/admin/student_registration/guardian_search',
           data:{
                _token :'<?php echo csrf_token() ?>',
                phone  : ph
            },
           
           success:function(data){
                if (data.msg == 'found') {
                    $("#guardian_msg").html('Guardian data already exist.');
                    $("#guardian_id").val(data.guardian_id);
                    $("#guardian_name").val(data.guardian_name);
                    $("#guardian_address").val(data.guardian_address);
                    $("#guardian_name").attr('disabled','disabled');
                    $("#guardian_address").attr('disabled','disabled');
                } else {
                    $("#guardian_msg").html('Guardian data not exist.Please Fill !');
                }             
            }
        });
     }
     function onchangeNewClass(value){
        $.ajax({
           type:'POST',
           url:'/admin/student_registration/class_search',
           data:{
                _token :'<?php echo csrf_token() ?>',
                class_id  : value
            },
           
           success:function(data){
                if (data.msg == 'found') {
                    $("#student_reg_academicyr").val(data.academic_year);
                    $("#student_limit").html(data.student_limit);
                    $("#current_student_limit").html(data.current_student_limit);
                } else {
                    $("#student_reg_academicyr").val('');
                    $("#student_limit").html('');
                    $("#current_student_limit").html('');
                }             
            }
        });
     }
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
                    $("#student_reg_name").html(data.name);
                    $("#student_reg_dob").html(data.date_of_birth);
                    $("#student_reg_fathername").html(data.father_name);
                    $("#student_reg_mothername").html(data.mother_name);
                    $('#old_class').val(data.old_class_id);

                    //$("#new_class option[value='" + data.old_class_id + "']").hide();
                    var newclass_data= data.new_class_data;

                    $("#new_class").empty();
                    newclass_data.forEach(function(element) {
                        var optionVal = element.id;                        
                        $("#new_class").append("<option value='"+optionVal+"'>"+element.name+"</option>");
                        
                    });

                    $('#student_reg_info').show();
                } else {
                    var oldClsId = $('#old_class').val();
                    $("#new_class option[value='" + oldClsId + "']").show();
                    $("#student_reg_name").html('');
                    $("#student_reg_dob").html('');
                    $("#student_reg_fathername").html('');
                    $("#student_reg_mothername").html('');
                    $('#old_class').val('');
                    $('#student_reg_info').hide();
                    $("#new_class").empty();
                }             
            }
        });
     }
    function cancelPreview() {
        $('#file-upload').val('');
        $('#preview-selected-image').attr('src', '');
        $('#preview-selected-image').hide();
        $('#cancel-image').hide();
    }
</script>

<div class="pagetitle">
    <h1>Student Registration</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item active">Registration</li>
            <li class="breadcrumb-item active">Student Registration</li>
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
                <h4><b>Student Registration</b></h4>
            </div>
            <div class="col-md-2">
                <a class="btn btn-sm btn-primary" href="{{route('student_reg.index')}}" id="form-header-btn"><i class="bi bi-skip-backward-fill"></i> Back</a>
            </div>
        </div>

        <br />
        <form id="studentRegForm" method="POST" action="{{route('student_reg.store')}}" enctype="multipart/form-data" onsubmit="saveFormData()">
            @csrf
            <br />
            <input type="hidden" name="registration_type" class="form-control" value="{{ $register_type == '' ? old('register_type') : $register_type }}" required>
            <input type="hidden" name="waiting_id" class="form-control" value="{{ $waiting_id == '' ? old('waiting_id') : $waiting_id }}" required>
            @if ($register_type == 1)
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    <!-- start step indicators -->
                    <div class="form-header d-flex mb-4">
                        <span class="stepIndicator">Student Information</span>
                        <span class="stepIndicator">Parent Information</span>
                        <span class="stepIndicator">Student Registration</span>
                    </div>
                    <!-- end step indicators -->

                    <!-- step one -->
                    <div class="step">
                        <p class="text-center mb-4">Create Student Information</p>
                        <div class="row g-4">
                            <div class="col-md-1"></div>
                            <div class="form-group col-md-5">
                                <label for="name"><b>Name<span style="color:brown">*</span></b></label>
                                <div class="col-sm-10">
                                    <input type="text" name="name" value="{{ $waiting_name == '' ? old('name') : $waiting_name }}" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group col-md-5">
                                <label for="name_mm"><b>Name(Myanmar)<span style="color:brown">*</span></b></label>
                                <div class="col-sm-10">
                                    <input type="text" name="name_mm" value="{{ old('name_mm') }}" class="form-control" required>
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
                                    <input type="text" name="religion" value="{{old('religion')}}" class="form-control">
                                </div>
                            </div>
                            <div class="form-group col-md-5">
                                <label for="nationality"><b>Nationality<span style="color:brown">*</span></b></label>
                                <div class="col-sm-10">
                                    <input type="text" name="nationality" value="{{old('nationality')}}" class="form-control" required>
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
                                            <option value="{{ $key }}" {{ $key == old('gender') ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-5">
                                <label for=""><b>Towhship</b></label>
                                <div class="col-sm-10">
                                    <select class="form-select" id="township" name="township">
                                        @foreach($township as $key => $value)
                                        <option value="{{$key}}" {{ $key == old('township') ? 'selected' : '' }}>{{$value}}</option>
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
                                    <input type="date" name="date_of_birth" class="form-control" value="{{old('date_of_birth')}}" required>
                                </div>
                            </div>
                            <div class="form-group col-md-5">
                                <label for="biography"><b>Add Biography</b></label>
                                <div class="col-sm-10">
                                    <input type="file" class="form-control" id="biography" name="biography" value="{{ old('biography') }}" />
                                </div>
                            </div>
                            <div class="col-md-1"></div>
                        </div>

                        <br />

                        <div class="row g-4">
                            <div class="col-md-1"></div>
                            <div class="form-group col-md-5">
                                <label for="student_profile"><b>Upload Profile</b></label>
                                <div class="image-preview-container" style='width:100%;'>
                                    <div class="preview">
                                        <img id="preview-selected-image" style='height:140px;' />
                                        <div class="cancel-image" id="cancel-image" onclick="cancelPreview()" style="display:none;">&#10006;</div>
                                    </div>
                                    <label for="file-upload">Upload Image</label>
                                    <input type="file" id="file-upload" name="student_profile" value="{{ old('student_profile') }}" accept="image/*" onchange="previewImage(event);" />
                                </div>
                            </div>
                            <div class="col-md-6"></div>
                        </div>

                        <br />
                        <br />

                        <div class="row g-4">
                            <div class="col-md-1"></div>
                            <fieldset class="form-group col-md-10 border m-2 p-3">
                                <legend for="reg_type" class="float-none w-auto" style='font-size:1.2em;'><b>Education History</b></legend>

                                <div class="form-group ">
                                    <div class="row col-md-10">
                                        <div class='col-sm-4'>
                                            <label for="old_school_name"><b>Old School Name</b></label>
                                        </div>
                                        <div class='col-sm-8'>
                                            <input type="text" name="old_school_name" value="{{ old('old_school_name') }}" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <br />
                                <div class="form-group ">
                                    <div class="row col-md-10">
                                        <div class='col-sm-4'>
                                            <label for="old_grade"><b>Old Grade</b></label>
                                        </div>
                                        <div class='col-sm-8'>
                                            <input type="text" name="old_grade" value="{{ old('old_grade') }}" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <br />
                                <div class="form-group ">
                                    <div class="row col-md-10">
                                        <div class='col-sm-4'>
                                            <label for="old_academic_year"><b>Old Academic Year</b></label>
                                        </div>
                                        <div class='col-sm-8'>
                                            <input type="text" name="old_academic_year" value="{{ old('old_academic_year') }}" class="form-control">
                                        </div>
                                    </div>
                                </div>

                            </fieldset>
                            <div class="col-md-1"></div>
                        </div>
                        <br />

                    </div>


                    <!-- step two -->
                    <div class="step">
                        <p class="text-center mb-4">Create Student Parent Information</p>
                        <div class="row g-4">
                            <div class="col-md-1"></div>
                            <div class="form-group col-md-5">
                                <label for="father_name"><b>Father Name<span style="color:brown">*</span></b></label>
                                <div class="col-sm-10">
                                    <input type="text" name="father_name" value="{{old('father_name')}}" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group col-md-5">
                                <label for="father_name_mm"><b>Father Name(Myanmar)<span style="color:brown">*</span></b></label>
                                <div class="col-sm-10">
                                    <input type="text" name="father_name_mm" value="{{old('father_name_mm')}}" class="form-control" required>
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
                                    <input type="text" name="mother_name" value="{{old('mother_name')}}" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group col-md-5">
                                <label for="mother_name_mm"><b>Mother Name(Myanmar)<span style="color:brown">*</span></b></label>
                                <div class="col-sm-10">
                                    <input type="text" name="mother_name_mm" value="{{old('mother_name_mm')}}" class="form-control" required>
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
                                    <input type="text" name="father_phone" value="{{old('father_phone')}}" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group col-md-5">
                                <label for="mother_phone"><b>Mother Phone<span style="color:brown">*</span></b></label>
                                <div class="col-sm-10">
                                    <input type="text" name="mother_phone" value="{{old('mother_phone')}}" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-1"></div>
                        </div>

                        <br />

                        <div class="row g-4">
                            <div class="col-md-1"></div>
                            <div class="form-group col-md-10">
                                <label for="address_1"><b>Address 1</b></label>
                                <div class="col-sm-12">
                                    <textarea name="address_1" class="form-control">{{old('address_1')}}</textarea>
                                </div>
                            </div>
                            <div class="col-md-1"></div>
                        </div>

                        <br />

                        <div class="row g-4">
                            <div class="col-md-1"></div>
                            <div class="form-group col-md-10">
                                <label for="address_2"><b>Address 2</b></label>
                                <div class="col-sm-12">
                                    <textarea name="address_2" class="form-control">{{old('address_2')}}</textarea>
                                </div>
                            </div>
                            <div class="col-md-1"></div>
                        </div>

                        <br />
                        <br />

                        <div class="row g-4">
                            <div class="col-md-1"></div>
                            <fieldset class="form-group col-md-10 border m-2 p-3">
                                <legend for="reg_type" class="float-none w-auto" style='font-size:1.2em;'><b>Guardian Information</b></legend>

                                <div class="form-group ">
                                    <div class="row col-md-10">
                                        <div class='col-sm-4'>
                                            <label for="guardian_phone"><b>Phone</b><span style="color:brown">*</span></label>
                                        </div>
                                        <div class='col-sm-6'>
                                            <input type="text" id="guardian_phone" name="guardian_phone" value="{{old('guardian_phone')}}" class="form-control" required>
                                        </div>
                                        <div class='col-sm-2'>
                                            <button type="button" name="guardian_search" id="guardian_search" class="btn btn-sm btn-primary" onclick="getGuardianData()">Search</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <div class="row col-md-10">
                                        <input type="hidden" id="guardian_id" name="guardian_id" value="{{old('guardian_id')}}" class="form-control"> 
                                        <span id="guardian_msg"></span>
                                    </div>
                                </div>
                                <br />
                                <div class="form-group ">
                                    <div class="row col-md-10">
                                        <div class='col-sm-4'>
                                            <label for="guardian_name"><b>Name</b><span style="color:brown">*</span></label>
                                        </div>
                                        <div class='col-sm-8'>
                                            <input type="text" id="guardian_name" name="guardian_name" value="{{old('guardian_name')}}" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                                <br />
                                <div class="form-group ">
                                    <div class="row col-md-10">
                                        <div class='col-sm-4'>
                                            <label for="guardian_address"><b>Address</b><span style="color:brown">*</span></label>
                                        </div>
                                        <div class='col-sm-8'>
                                            <textarea id="guardian_address" name="guardian_address" value="{{old('guardian_address')}}" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>

                            </fieldset>
                            <div class="col-md-1"></div>
                        </div>
                        <br />                       

                    </div>

                    <!-- step three -->
                    <div class="step">
                        <p class="text-center mb-4">Create Student Registration</p>

                        <div class="row g-4">
                            <div class="col-md-1"></div>
                            <div class="form-group col-md-8">
                                <div class="row col-md-10">
                                    <div class='col-sm-4'>
                                        <label for="new_class_select"><b>New Class</b><span style="color:brown">*</span></label>
                                    </div>
                                    <div class='col-sm-8'>
                                        <select class="form-select" id="new_class" name="new_class" onchange="onchangeNewClass(this.value);" required>
                                            <option value="99">select class</option>
                                            @foreach($class as $c)
                                            <option value="{{$c->id}}" {{ $c->id == old('new_class') ? 'selected' : '' }}>{{$c->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3"></div>
                        </div>

                        <br />

                        <div class="row g-4">
                            <div class="col-md-1"></div>
                            <div class="form-group col-md-8">
                                <div class="row col-md-10">
                                    <div class='col-sm-4'>
                                        <label for="student_reg_academicyr"><b>Academic Year</b></label>
                                    </div>
                                    <div class='col-sm-8'>
                                        <input type="text" id="student_reg_academicyr" name="student_reg_academicyr" value="{{old('student_reg_academicyr')}}" class="form-control" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3"></div>
                        </div>
                        <br />

                        <div class="row g-4">
                            <div class="col-md-1"></div>
                            <div class="form-group col-md-8">
                                <div class="row col-md-10">
                                    <div class='col-sm-4'>
                                        <label for="registration_date"><b>Registration Date</b><span style="color:brown">*</span></label>
                                    </div>
                                    <div class='col-sm-8'>
                                        <input type="date" id="registration_date" name="registration_date" value="{{old('registration_date')}}" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3"></div>
                        </div>
                        <br />
                        <div class="row g-4">
                            <div class="col-md-1"></div>
                            <div class="form-group col-md-8">
                                <div class="row col-md-10">
                                    <div class='col-sm-4'>
                                        <label for="card_id"><b>Card ID</b></label>
                                    </div>
                                    <div class='col-sm-8'>
                                        <input type="text" id="card_id" name="card_id" value="{{old('card_id')}}" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3"></div>
                        </div>
                        <br />

                        <div class="row g-4">
                            <div class="col-md-1"></div>
                            <div class="form-group col-md-10">
                                <div class="row col-md-10">
                                    <div class='col-sm-4'>
                                        <label for="student_limit"><b>Student Limit :</b></label>
                                    </div>
                                    <div class='col-sm-1'>
                                        :
                                    </div>
                                    <div class='col-sm-2'>
                                        <span id='student_limit' name="student_limit">{{old('student_limit')}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1"></div>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-1"></div>
                            <div class="form-group col-md-10">
                                <div class="row col-md-10">
                                    <div class='col-sm-4'>
                                        <label for="current_student_limit"><b>Current Student Limit </b></label>
                                    </div>
                                    <div class='col-sm-1'>
                                        :
                                    </div>
                                    <div class='col-sm-2'>
                                        <span id='current_student_limit' name="current_student_limit">{{old('current_student_limit')}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1"></div>
                        </div>

                        <br />

                    </div>

                    <!-- start previous / next buttons -->
                    <div class="form-footer d-flex" style="margin-left:8%;margin-right:6%;">
                        <button type="button" id="prevBtn" onclick="nextPrev(-1)">Previous</button>
                        <button type="button" id="nextBtn" onclick="nextPrev(1)">Next</button>
                    </div>
                    <!-- end previous / next buttons -->
                    </di>
                    <div class="col-md-1"></div>
                </div>
            </div>
            @else

                <div class="row g-4">
                    <div class="col-md-1"></div>
                    <div class="form-group col-md-8">
                        <div class="row col-md-10">
                            <div class='col-sm-4'>
                                <label for="student_id"><b>Student ID</b></label>
                            </div>
                            <div class='col-sm-6'>
                                <input type="text" id="student_id" name="student_id" class="form-control">
                            </div>
                            <div class='col-sm-2'>
                                <button type="button" name="student_searcg" id="student_search" class="btn btn-sm btn-primary" onclick="getStudentData()">Search</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1"></div>
                </div>
                <br />
                <!--start student info data-->
                <div class="row g-4">
                    <div class="col-md-1"></div>
                    <br />
                    <fieldset id='student_reg_info' class="form-group col-md-8 border m-2 p-3" style='display:none;'>
                        <legend for="" class="float-none w-auto" style='font-size:1.2em;'><b>Student Info</b></legend>
                   
                        <div class="form-group col-md-10">
                            <div class="row col-md-10">
                                <div class='col-sm-4'>
                                    <label for=""><b>Student Name </b></label>
                                </div>
                                <div class='col-sm-1'>
                                    :
                                </div>
                                <div class='col-sm-7'>
                                    <span id='student_reg_name'></span>
                                </div>
                            </div>
                            <div class="row col-md-10">
                                <div class='col-sm-4'>
                                    <label for=""><b>Date of Birth </b></label>
                                </div>
                                <div class='col-sm-1'>
                                    :
                                </div>
                                <div class='col-sm-7'>
                                    <span id='student_reg_dob'></span>
                                </div>
                            </div>
                            <div class="row col-md-10">
                                <div class='col-sm-4'>
                                    <label for=""><b>Father Name </b></label>
                                </div>
                                <div class='col-sm-1'>
                                    :
                                </div>
                                <div class='col-sm-7'>
                                    <span id='student_reg_fathername'></span>
                                </div>
                            </div>
                            <div class="row col-md-10">
                                <div class='col-sm-4'>
                                    <label for=""><b>Mother Name </b></label>
                                </div>
                                <div class='col-sm-1'>
                                    :
                                </div>
                                <div class='col-sm-7'>
                                    <span id='student_reg_mothername'></span>
                                </div>
                            </div>
                        </div>
                       
                    </fieldset>
                    <div class="col-md-1"></div>
                </div>
                <!--end student info data-->
                <br />
                <div class="row g-4">
                    <div class="col-md-1"></div>
                    <div class="form-group col-md-8">
                        <div class="row col-md-10">
                            <div class='col-sm-4'>
                                <label for="old_class_select"><b>Old Class</b></label>
                            </div>
                            <div class='col-sm-8'>
                                <select class="form-select" id="old_class" name="old_class" disabled>
                                    <option value="99">select class</option>
                                    @foreach($class as $c)
                                    <option value="{{$c->id}}">{{$c->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3"></div>
                </div>
            <br />
                <div class="row g-4">
                    <div class="col-md-1"></div>
                    <div class="form-group col-md-8">
                        <div class="row col-md-10">
                            <div class='col-sm-4'>
                                <label for="new_class_select"><b>New Class</b></label>
                            </div>
                            <div class='col-sm-8'>
                                <select class="form-select" id="new_class" name="new_class" onchange="onchangeNewClass(this.value);">
                                    <option value="99">select class</option>
                                    @foreach($class as $c)
                                    <option value="{{$c->id}}">{{$c->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3"></div>
                </div>

            <br />

                <div class="row g-4">
                    <div class="col-md-1"></div>
                    <div class="form-group col-md-8">
                        <div class="row col-md-10">
                            <div class='col-sm-4'>
                                <label for="student_reg_academicyr"><b>Academic Year</b></label>
                            </div>
                            <div class='col-sm-8'>
                                <input type="text" id="student_reg_academicyr" name="student_reg_academicyr" class="form-control" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3"></div>
                </div>
                <br />

                <div class="row g-4">
                    <div class="col-md-1"></div>
                    <div class="form-group col-md-8">
                        <div class="row col-md-10">
                            <div class='col-sm-4'>
                                <label for="registration_date"><b>Registration Date</b></label>
                            </div>
                            <div class='col-sm-8'>
                                <input type="date" id="registration_date" name="registration_date" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3"></div>
                </div>
                <br />

                <div class="row g-4">
                    <div class="col-md-1"></div>
                    <div class="form-group col-md-10">
                        <div class="row col-md-10">
                            <div class='col-sm-4'>
                                <label for="student_limit"><b>Student Limit :</b></label>
                            </div>
                            <div class='col-sm-1'>
                                :
                            </div>
                            <div class='col-sm-2'>
                                <span id='student_limit'></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1"></div>
                </div>

                <div class="row g-4">
                    <div class="col-md-1"></div>
                    <div class="form-group col-md-10">
                        <div class="row col-md-10">
                            <div class='col-sm-4'>
                                <label for="current_student_limit"><b>Current Student Limit </b></label>
                            </div>
                            <div class='col-sm-1'>
                                :
                            </div>
                            <div class='col-sm-2'>
                                <span id='current_student_limit'></span>
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
                        <input type="submit" value="Register" class="btn btn-primary">
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>
            @endif
        </form>
    </div>
</section>
<style>
    .image-preview-container {
      position: relative;
    }

    #preview-selected-image {
      height: 140px;
      position: relative;
    }

    .cancel-image {
      position: absolute;
      top: 0;
      right: 0;
      cursor: pointer;
      color: white;
      font-size: 20px;
      background-color: #8338ec;
      padding: 5px;
      border-top-right-radius: 5px;
    }
  </style>

@endsection