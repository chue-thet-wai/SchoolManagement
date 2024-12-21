@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
    <h1>Guardian Information</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item active">Create Information</li>
            <li class="breadcrumb-item active">Guardian Information</li>
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
                <h4><b>Update Guardian Information</b></h4>
            </div>
            <div class="col-md-2">
                <a class="btn btn-sm btn-primary" href="{{ url('admin/guardian_info/list') }}" id="form-header-btn"><i class="bi bi-skip-backward-fill"></i> Back</a>
            </div>
        </div>

        <br />
        <form method="POST" action="{{ url('admin/guardian_info/update/'.$result[0]->id) }}" enctype="multipart/form-data">
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
                <div class="col-md-1"></div>
            </div>
            <div class="row g-4 mt-1">
                <div class="col-md-1"></div>
                <div class="form-group col-md-5">
                    <label for="phone"><b>Phone<span style="color:brown">*</span></b></label>
                    <div class="col-sm-10">
                        <input type="text" name="phone" value="{{$result[0]->phone}}" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>
            <div class="row g-4 mt-1">
                <div class="col-md-1"></div>
                <div class="form-group col-md-5">
                    <label for="secondary_phone"><b>Secondary Phone</b></label>
                    <div class="col-sm-10">
                        <input type="text" name="secondary_phone" value="{{$result[0]->secondary_phone}}" class="form-control">
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>
            <div class="row g-4 mt-1">
                <div class="col-md-1"></div>
                <div class="form-group col-md-5">
                    <label for="email"><b>email</b></label>
                    <div class="col-sm-10">
                        <input type="text" name="email" value="{{$result[0]->email}}" class="form-control">
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>
            <div class="row g-4 mt-1">
                <div class="col-md-1"></div>
                <div class="form-group col-md-5">
                    <label for="nrc"><b>NRC</b></label>
                    <div class="col-sm-10">
                        <input type="text" name="nrc" value="{{$result[0]->nrc}}" class="form-control">
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>
            <div class="row g-4 mt-1">
                <div class="col-md-1"></div>
                <div class="form-group col-md-5">
                    <label for="password"><b>Password<span style="color:brown">*</span></b></label>
                    <div class="col-sm-10">
                        <input type="text" name="password" value="{{$result[0]->password}}" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>
            <div class="row g-4 mt-1">
                <div class="col-md-1">
                    <input type="hidden" id="previous_image" name="previous_image" value="{{$result[0]->photo}}">
                </div>
                <div class="form-group col-md-10">
                    <label for="profile"><b>Upload Profile</b></label>
                    <div class="image-preview-container">
                        <div class="preview">
                            <img id="preview-selected-image" src="{{asset('assets/guardian_images/'.$result[0]->photo)}}" style='display: block;'/>
                        </div>
                        <label for="file-upload">Upload Image</label>
                        <input type="file" id="file-upload" name='guardian_photo' accept="image/*" onchange="previewImage(event);" />
                    </div>
                </div>               
                <div class="col-md-1"></div>
            </div>
            <div class="row g-4 mt-1">
                <div class="col-md-1"></div>
                <div class="form-group col-md-5">
                    <label for="address"><b>Address</b></label>
                    <div class="col-sm-11">
                        <textarea name="address" class="form-control">{{$result[0]->address}}</textarea>
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>
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