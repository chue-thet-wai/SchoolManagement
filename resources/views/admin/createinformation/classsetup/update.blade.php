@extends('layouts.dashboard')

@section('content')
<script>
    function changeBranch(){
        var branchID = $("#branch_id").val();
    
        $.ajax({
        type:'POST',
        url:'/admin/class_setup/get_room',
        data:{
                _token :'<?php echo csrf_token() ?>',
                branch_id  : branchID
            },           
            success:function(data){
                if (data.msg == 'found') {
                    var room_data= data.room_data;

                    $("#room_id").empty();
                    $("#room_id").append("<option value=''>--Select--</option>");
                    room_data.forEach(function(element) {
                        var optionVal = element.id;                        
                        $("#room_id").append("<option value='"+optionVal+"'>"+element.name+"</option>");
                        
                    });
                } else {
                    $("#room_id").empty();           
                }           
            }
        });
    };
</script>
<div class="pagetitle">
    <h1>Update Class Setup</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item active">Create Information</li>
            <li class="breadcrumb-item active">Class Setup</li>
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
                <h4><b>Update Class Setup</b></h4>
            </div>
            <div class="col-md-2">
                <a class="btn btn-sm btn-primary" href="{{url('admin/class_setup/list')}}" id="form-header-btn"><i class="bi bi-skip-backward-fill"></i> Back</a>
            </div>
        </div>

        <br />
        <form method="POST" action="{{route('class_setup.update',$result[0]->id)}}" enctype="multipart/form-data">
            @csrf
            <br />
            {{method_field('PUT')}}
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group col-md-12">
                        <label for=""><b>Branch</b></label>
                        <div class="col-sm-10">
                        <select class="form-select" id="branch_id" name="branch_id" onchange="changeBranch()">
                                <option  value="99">select branch</option>
                                @foreach($branch_list as $b)
                                    <option  value="{{$b->id}}"
                                    @if ($result[0]->branch_id == $b->id)
                                        selected
                                    @endif
                                    >{{$b->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for=""><b>Room</b></label>
                            <div class="col-sm-10">
                                <select class="form-select" id="room_id" name="room_id" >
                                    <option  value="99">select room</option>
                                    @foreach($room_list as $a)
                                        <option  value="{{$a->id}}"
                                        @if ($result[0]->room_id == $a->id)
                                            selected
                                        @endif
                                        >{{$a->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for=""><b>Grade</b></label>
                            <div class="col-sm-10">
                                <select class="form-select" id="grade_id" name="grade_id" >
                                    <option  value="99">select grade</option>
                                    @foreach($grade_list as $a)
                                        <option  value="{{$a->id}}"
                                        @if ($result[0]->grade_id == $a->id)
                                            selected
                                        @endif
                                        >{{$a->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                           
                </div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for=""><b>Section</b></label>
                            <div class="col-sm-10">
                                <select class="form-select" id="section_id" name="section_id" >
                                    <option  value="99">select section</option>
                                    @foreach($section_list as $a)
                                        <option  value="{{$a->id}}" 
                                        @if ($result[0]->section_id == $a->id)
                                            selected
                                        @endif
                                        >{{$a->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for=""><b>Academic Year</b></label>
                            <div class="col-sm-10">
                                <select class="form-select" id="academic_year_id" name="academic_year_id" >
                                    <option  value="99">select Academic Year</option>
                                    @foreach($academic_list as $a)
                                        <option  value="{{$a->id}}" 
                                        @if ($result[0]->academic_year_id == $a->id)
                                            selected
                                        @endif
                                        >{{$a->name}}</option>
                                    @endforeach
                                </select>
                            </div>
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