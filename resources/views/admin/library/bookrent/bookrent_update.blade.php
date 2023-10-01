@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
    <h1>Book Rent</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item active">Library</li>
            <li class="breadcrumb-item active">Book Rent</li>
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
                <h4><b>Update Book Rent</b></h4>
            </div>
            <div class="col-md-1">
                <a class="btn btn-sm btn-primary" href="{{ url('admin/book_rent/list') }}" id="form-header-btn"> Back</a>
            </div>
            <div class="col-md-1"></div>
        </div>

        <br />
        <form method="POST" action="{{ url('admin/book_rent/update/'.$result[0]->id) }}" enctype="multipart/form-data">
            @csrf
            <br />
            {{method_field('POST')}}
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="book_category"><b>Books<span style="color:brown">*</span></b></label>
                        <div class="col-sm-10">
                            <select class="form-select" id="book_category" name="student_id" >
                                <option  value="99">--Select Books--</option>
                                @foreach($books_list as $a)
                                    <option  value="{{$a->id}}">{{$a->title}}</option>
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
                        <label for="book_category"><b>Studnet</I><span style="color:brown">*</span></b></label>
                        <div class="col-sm-10">
                            <select class="form-select" id="book_category" name="student_id" >
                                <option  value="99">--Select Student--</option>
                                @foreach($student_list as $a)
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
                        <label for="rent_date"><b>Rent Date<span style="color:brown">*</span></b></label>
                        <div class="col-sm-10">
                            <input type="date" name="rent_date" value="{{date('Y-m-d',strtotime($result[0]->rent_date))}}" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="return_date"><b>Return Date<span style="color:brown">*</span></b></label>
                        <div class="col-sm-10">
                            <input type="date" name="return_date" value="{{date('Y-m-d',strtotime($result[0]->return_date))}}" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="actural_return_date"><b>Actual Return Date<span style="color:brown">*</span></b></label>
                        <div class="col-sm-10">
                            <input type="date" name="actural_return_date" value="{{date('Y-m-d',strtotime($result[0]->actual_return_date))}}" class="form-control" required>
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