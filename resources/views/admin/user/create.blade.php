@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
    <h1>User</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item active">Category</li>
            <li class="breadcrumb-item active">User</li>
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
                <h4><b>Create User</b></h4>
            </div>
            <div class="col-md-1">
                <a class="btn btn-sm btn-primary" href="{{route('user.index')}}" id="form-header-btn"> Back</a>
            </div>
            <div class="col-md-1"></div>
        </div>

        <br />
        <form method="POST" action="{{route('teacher_info.store')}}" enctype="multipart/form-data">
            @csrf
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="form-group col-md-5">
                    <label for="name"><b>Name<span style="color:brown">*</span></b></label>
                    <div class="col-sm-10">
                        <input type="text" name="name" class="form-control" required>
                    </div>
                </div>
                <div class="form-group col-md-5">
                    <label for="email"><b>Email<span style="color:brown">*</span></b></label>
                    <div class="col-sm-10">
                        <input type="text" name="email" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="form-group col-md-5">
                    <label for="phone"><b>Phone</b></label>
                    <div class="col-sm-10">
                        <input type="text" name="phone" class="form-control" required>
                    </div>
                </div>
                <div class="form-group col-md-5">
                    <label for="nrc"><b>NRC</b></label>
                    <div class="col-sm-10">
                        <input type="text" name="nrc" class="form-control" required>
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
                        <input type="date" name="date_of_birth" class="form-control" required>
                    </div>
                </div>
                <div class="form-group col-md-5">
                    <label for="joined_date"><b>Joined Date<span style="color:brown">*</span></b></label>
                    <div class="col-sm-10">
                        <input type="date" name="joined_date" class="form-control" required>
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
                        <textarea name="address" class="form-control" required></textarea>
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
                        <br />
                        <input type="radio" id="inactive" name="status" value="0" checked><b> Inactive</b>
                        <input type="radio" id="active" name="status" value="1"><b> Active</b>
                    </div>
                </div>               
                <div class="col-md-1"></div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="form-group col-md-10">
                    <label for="profile"><b>Upload Profile</b></label>
                    <div class="image-preview-container">
                        <div class="preview">
                            <img id="preview-selected-image" />
                        </div>
                        <label for="file-upload">Upload Image</label>
                        <input type="file" id="file-upload" name='teacher_profile' accept="image/*" onchange="previewImage(event);" />
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