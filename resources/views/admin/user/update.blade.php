@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
    <h1>User Management</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item active">Category</li>
            <li class="breadcrumb-item active">User Management</li>
        </ol>
    </nav>
    @include('layouts.error')
</div><!-- End Page Title -->

<section class="card">
    @php
        $deptList = [];
    @endphp
    <div class="card-body">
        <br />
        
        <div class="row g-4">
            <div class="col-md-1"></div>
            <div class="col-md-9" style='color:#012970;'>
                <h4><b>Create User Information</b></h4>
            </div>
            <div class="col-md-1">
                <a class="btn btn-sm btn-primary" href="{{route('user.index')}}" id="form-header-btn"> Back</a>
            </div>
            <div class="col-md-1"></div>
        </div>

        <br />
        <form method="POST" action="{{route('user.update',$result[0]->user_id)}}" enctype="multipart/form-data">
            @csrf
            <br />
            {{method_field('PUT')}}
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="form-group col-md-5">
                    <label for="name"><b>Name<span style="color:brown">*</span></b></label>
                    <div class="col-sm-10">
                        <input type="text" name="name" class="form-control" value="{{$result[0]->name}}" required>
                    </div>
                </div>
                <div class="form-group col-md-5">
                    <label for="login_name"><b>Login Name<span style="color:brown">*</span></b></label>
                    <div class="col-sm-10">
                        <input type="text" name="login_name" class="form-control" value="{{$result[0]->login_name}}" required>
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="form-group col-md-5">
                    <label for="email"><b>Email<span style="color:brown">*</span></b></label>
                    <div class="col-sm-10">
                        <input type="text" name="email" class="form-control" value="{{$result[0]->email}}" required>
                    </div>
                </div>
                <div class="form-group col-md-5">
                    <label for="password"><b>Password<span style="color:brown">*</span></b></label>
                    <div class="col-sm-10">
                        <input type="password" name="password" class="form-control">
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="form-group col-md-5">
                    <label for="contact_no"><b>Contact No</b></label>
                    <div class="col-sm-10">
                        <input type="text" name="contact_no" class="form-control" value="{{$result[0]->contact_number}}" required>
                    </div>
                </div>
                <div class="form-group col-md-5">
                    <label for=""><b>Role</b></label>
                    <div class="col-sm-10">
                        <select class="form-select" id="department_id" name="department_id" >
                            <option value="99">select department</option>
                            @foreach($department_list as $g)
                                <option value="{{$g['id']}}"
                                    @if($g['id'] == $result[0]->department_id)
                                        selected
                                    @endif
                                >{{$g['name']}}</option>
                                @php  $deptList[$g['id']] = $g['name']; @endphp
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
                    <label for="startworking_date"><b>Start Working Date<span style="color:brown">*</span></b></label>
                    <div class="col-sm-10">
                        <input type="date" name="startworking_date" class="form-control" value="{{date('Y-m-d',strtotime($result[0]->startworking_date))}}" required>
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="form-group col-md-10">
                    <label for="address"><b>Address</b></label>
                    <div class="col-sm-10">
                        <textarea name="address" class="form-control" >{{$result[0]->address}}</textarea>
                    </div>
                </div>               
                <div class="col-md-1"></div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="form-group col-md-10">
                    <label for="remark"><b>Remark</b></label>
                    <div class="col-sm-10">
                        <textarea name="remark" class="form-control">{{$result[0]->remark}}</textarea>
                    </div>
                </div>               
                <div class="col-md-1"></div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="form-group col-md-10">
                    <label for="status"><b>Status</b></label>
                    <div class="col-sm-10">
                        <input type="radio" id="inactive" name="status" value="0" 
                            @if($result[0]->resign_status != "1")
                                checked
                            @endif
                            ><b> Inactive</b>
                        <input type="radio" id="active" name="status" value="1"
                            @if($result[0]->resign_status == "1")
                                checked
                            @endif
                            ><b> Active</b>
                    </div>
                </div>               
                <div class="col-md-1"></div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1">
                    <input type="hidden" id="previous_image" name="previous_image" value="{{$result[0]->profile_image}}">
                </div>
                <div class="form-group col-md-10">
                    <label for="profile"><b>Upload Profile</b></label>
                    <div class="image-preview-container">
                        <div class="preview">
                            <img id="preview-selected-image" src="{{asset('assets/user_images/'.$result[0]->profile_image)}}" style='display: block;'/>
                        </div>
                        <label for="file-upload">Upload Image</label>
                        <input type="file" id="file-upload" name='user_profile' accept="image/*" onchange="previewImage(event);" />
                    </div>
                </div>               
                <div class="col-md-1"></div>
            </div>
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="form-group col-md-2">
                    <div class="d-grid mt-4">
                        <input type="submit" value="Add" class="btn btn-primary">
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>
        </form>
    </div>
</section>


@endsection