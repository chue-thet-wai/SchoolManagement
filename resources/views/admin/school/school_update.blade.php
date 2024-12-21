@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
    <h1>School Registration</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item active">School Registration</li>
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
                <h4><b>Update School Registration</b></h4>
            </div>
            <div class="col-md-2">
                <a class="btn btn-sm btn-primary" href="{{ url('admin/school_registration/list') }}" id="form-header-btn"><i class="bi bi-skip-backward-fill"></i> Back</a>
            </div>
        </div>

        <br />
        <form method="POST" action="{{ url('admin/school_registration/update/'.$result[0]->id) }}" enctype="multipart/form-data">
            @csrf
            <br />
            {{method_field('POST')}}
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="name"><b>Name<span style="color:brown">*</span></b></label>
                        <div class="col-sm-10">
                            <input type="text" name="name" class="form-control" value="{{$result[0]->name}}" required>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="code"><b>Code<span style="color:brown">*</span></b></label>
                        <div class="col-sm-10">
                            <input type="text" name="code" class="form-control" value="{{$result[0]->code}}" required>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="url"><b>URL<span style="color:brown">*</span></b></label>
                        <div class="col-sm-10">
                            <input type="text" name="url" class="form-control" value="{{$result[0]->url}}" required>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="form-group col-md-5">
                    <label for="status"><b>Status</b></label>
                    <div class="col-sm-10">
                        <select class="form-select" id="status" name="status">
                            @foreach($status as $key => $value)
                                <option value="{{$key}}"
                                @if ($result[0]->status == $key)
                                    selected
                                @endif
                                >{{$value}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1">
                    <input type="hidden" id="previous_image" name="previous_image" value="{{$result[0]->logo}}">
                </div>
                <div class="form-group col-md-10">
                    <label for="profile"><b>Upload Logo</b></label>
                    <div class="image-preview-container">
                        <div class="preview">
                            <img id="preview-selected-image" src="{{asset('assets/school_logo/'.$result[0]->logo)}}" style='display: block;'/>
                        </div>
                        <label for="file-upload">Upload Image</label>
                        <input type="file" id="file-upload" name='school_logo' accept="image/*" onchange="previewImage(event);" />
                    </div>
                </div>               
                <div class="col-md-1"></div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="start_date"><b>Start Date<span style="color:brown">*</span></b></label>
                        <div class="col-sm-10">
                            <input type="date" name="start_date"
                                    @if ($result[0]->start_date != null) 
                                        value="{{date('Y-m-d',strtotime($result[0]->start_date))}}" 
                                    @endif 
                                    class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="end_date"><b>End Date</b></label>
                        <div class="col-sm-10">
                            <input type="date" name="end_date"
                                @if ($result[0]->end_date != null) 
                                    value="{{date('Y-m-d',strtotime($result[0]->end_date))}}" 
                                @endif 
                                class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="note"><b>Note</b></label>
                        <div class="col-sm-10">
                            <textarea name="note" class="form-control">{{$result[0]->note}}</textarea>
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
        </form>
        <br />
    </div>
</section>


@endsection