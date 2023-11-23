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

<div class="container">
    <h5><b>@php echo 'Today - ' .date("Y/m/d"); @endphp</b></h5>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <!--event start-->
            <div class="row p-1">
                <div class="accordion" id="accordionExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                 <label><i class="bi bi-star-fill" style="color:#3490dc;"></i> Current and Coming Soon Events</label>
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                            <div class="accordion-body event-container">
                                <ul class="cards">
                                    @if(!empty($event_list) && $event_list->count())
                                        @php $i=1;@endphp
                                        @foreach($event_list as $res)
                                            <li class="card">
                                                <div>
                                                    <div class="card-title">{{$res->title}}</div>
                                                    <div class="card-content">                                                
                                                        <p>{{$res->description}}</p>
                                                    </div>
                                                </div>
                                                <div class="card-link-wrapper">
                                                    <div class="event-grade"><p style="font-size:0.6rem;"><b>Grade &nbsp;:</b> {{$grade[$res->grade_id]}}</p></div>
                                                    <div class="event-date"><p style="font-size:0.6rem;margin-top:-5%;"><b>Date &nbsp;&nbsp;&nbsp;:</b> {{date('Y-m-d',strtotime($res->event_from_date))}} ~ {{date('Y-m-d',strtotime($res->event_to_date))}} </p></div>
                                                </div>
                                            </li>
                                        @endforeach
                                    @else 
                                        <p> There is no event yet ! </p>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- End Event--> 
            <br />
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
            <div class="row">
                <div class="col-xxl-4 col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title p-0">Income</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-cash-stack"></i>
                                </div>
                                <div class="ps-4 count"><b>{{$income}}</b></div>
                            </div>
                        </div>
                    </div>
                </div><!-- End Income Card -->

                <div class="col-xxl-4 col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title p-0">Expense</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-cash-coin"></i>
                                </div>
                                <div class="ps-4 count"><b>{{$expense}}</b></div>
                            </div>
                        </div>
                    </div>
                </div><!-- End Expense Card -->
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
    font-size: 20px;
    color: #012970;
    font-weight: 600;
</style>

@endsection
