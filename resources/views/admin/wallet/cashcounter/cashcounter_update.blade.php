@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
    <h1>Cash Counter</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item active">Wallet</li>
            <li class="breadcrumb-item active">Cash Counter</li>
        </ol>
    </nav>
    @include('layouts.error')
</div><!-- End Page Title -->

<section class="card">
    @php
        $gradeList = [];
    @endphp
    <div class="card-body">
        <br />
        
        <div class="row g-4">
            <div class="col-md-1"></div>
            <div class="col-md-9" style='color:#012970;'>
                <h4><b>Update Cash Counter</b></h4>
            </div>
            <div class="col-md-2">
                <a class="btn btn-sm btn-primary" href="{{ url('admin/cash_counter/list') }}" id="form-header-btn"><i class="bi bi-skip-backward-fill"></i> Back</a>
            </div>
        </div>

        <br />
        <form method="POST" action="{{ url('admin/cash_counter/update/'.$result[0]->id) }}" enctype="multipart/form-data">
            @csrf
            <br />
            {{method_field('POST')}}
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="form-group col-md-5">
                    <div class='row'>
                        <label for="card_id"><b>Card ID<span style="color:brown">*</span></b></label>
                        <div class="col-sm-10">
                        <input type="hidden" id="card_id_hidden" name="card_id" value="{{$result[0]->card_id}}" class="form-control">
                            <input type="text" id="card_id" name="card_id" class="form-control" value="{{$result[0]->card_id}}" required disabled>
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
                            <input type="hidden" id="student_id_hidden" name="student_id" value="{{$result[0]->student_id}}" class="form-control">
                            <input type="text" id="student_id" value="{{$result[0]->student_id}}" class="form-control" disabled>
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
                            <input type="text" id="student_name" name="student_name" value="{{$result[0]->name}}" class="form-control" disabled>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">                    
                    <div class="form-group">
                        <label for=""><b>Amount<span style="color:brown">*</span></b></label>
                        <div class="col-sm-10">
                            <input type="number" id="amount" name="amount" value="{{$result[0]->amount}}" class="form-control" required>
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