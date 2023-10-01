@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
    <h1>Book Register</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item active">Library</li>
            <li class="breadcrumb-item active">Book Register</li>
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
                <h4><b>Update Book Register</b></h4>
            </div>
            <div class="col-md-1">
                <a class="btn btn-sm btn-primary" href="{{ url('admin/book_category/list') }}" id="form-header-btn"> Back</a>
            </div>
            <div class="col-md-1"></div>
        </div>

        <br />
        <form method="POST" action="{{ url('admin/book_category/update/'.$result[0]->id) }}" enctype="multipart/form-data">
            @csrf
            <br />
            {{method_field('POST')}}
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="book_category"><b>Book Category<span style="color:brown">*</span></b></label>
                        <div class="col-sm-10">
                            <select class="form-select" id="book_category" name="student_id" >
                                <option  value="99">--Select Category--</option>
                                @foreach($bookcategory_list as $a)
                                    <option  value="{{$a->id}}">{{$a->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="title"><b>Title<span style="color:brown">*</span></b></label>
                        <div class="col-sm-10">
                            <input type="text" name="title" class="form-control" value="{{$result[0]->title)}}" required>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="author"><b>Author<span style="color:brown">*</span></b></label>
                        <div class="col-sm-10">
                            <input type="text" name="author" class="form-control" value="{{$result[0]->author)}}" required>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="quantity"><b>Quantity<span style="color:brown">*</span></b></label>
                        <div class="col-sm-10">
                            <input type="number" name="quantity" class="form-control" value="{{$result[0]->quantity)}}" required>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="name"><b>Description</b></label>
                        <div class="col-sm-10">
                            <textarea type="text" name="description" class="form-control">{{$result[0]->description)}}</textarea>
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