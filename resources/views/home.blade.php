@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
	<h1>Dashboard</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
			<li class="breadcrumb-item active">Dashboard</li>
		</ol>
	</nav>
	@include('layouts.error')
</div><!-- End Page Title -->
<br />
<br />
<div class="container">
    <h5><b>@php echo 'Today - ' .date("Y/m/d"); @endphp</b></h5>
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="row">
                <div class="col-xxl-4 col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title p-0">Students</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="ps-4 count"><b>{{$student_count}}</b></div>
                            </div>
                        </div>
                    </div>
                </div><!-- End Student Card -->

                <div class="col-xxl-4 col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title p-0">Teachers</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="ps-4 count"><b>{{$teacher_count}}</b></div>
                            </div>
                        </div>
                    </div>
                </div><!-- End Teacher Card -->
                <div class="col-xxl-4 col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title p-0">Drivers</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="ps-4 count"><b>{{$driver_count}}</b></div>
                            </div>
                        </div>
                    </div>
                </div><!-- End Driver Card -->
            </div>
        </div>
    </div>
</div>

<style>
.card-icon {
    font-size: 30px;
    line-height: 0;
    width: 64px;
    height: 64px;
    flex-shrink: 0;
    flex-grow: 0;
    color: #b3b315;
    background-color: #f3ebeb;
}
.rounded-circle {
    border-radius: 50%!important;
}
.align-items-center {
    align-items: center!important;
}
.count {
    font-size: 25px;
    color: #012970;
    font-weight: 600;
</style>

@endsection
