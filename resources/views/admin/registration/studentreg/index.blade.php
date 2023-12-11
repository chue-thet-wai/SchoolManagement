@extends('layouts.dashboard')

@section('content')
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
            <div class="col-md-11" style='color:#012970;'>
                <h4><b>Student Register</b></h4>
            </div>
            <form method="GET" action="{{route('student_reg.create')}}">
                @csrf
                <br />
                <div class="row g-4">
                    <div class="col-md-1"></div>
                    <fieldset class="form-group col-md-6 border p-3">
                        <legend for="reg_type" class="float-none w-auto" style='font-size:1.2em;'><b>Choose Register Type</b></legend>
                        <div class="col-sm-10">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="reg_type" id="new_radio" value="1" checked>
                                <label class="form-check-label" for="new_radio">
                                    New
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="reg_type" id="existing_radio" value="2">
                                <label class="form-check-label" for="existing_radio">
                                    Existing
                                </label>
                            </div>
                        </div>
                    </fieldset>               
                    <div class="col-md-5"></div>
                </div>
                <div class="row g-4">
                    <div class="col-md-1"></div>
                    <div class="form-group col-md-2">
                        <div class="d-grid mt-4">
                            <input type="submit" value="Register" class="btn btn-primary">
                        </div>
                    </div>
                    <div class="col-md-1"></div>
                </div>
            </form>    
        </div>
    </div>
</section>


@endsection

