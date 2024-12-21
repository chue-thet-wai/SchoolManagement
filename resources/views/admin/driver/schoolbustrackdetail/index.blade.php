@extends('layouts.dashboard')

@section('content')
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="{{ asset('js/ferrypayment.js') }}"></script>
<script>
    function selectAll(){
        if($('#select_all').is(':checked')){
            $('.ferrystudent_check').each(function(){
                this.checked = true;
            });
        }else{
             $('.ferrystudent_check').each(function(){
                this.checked = false;
            });
        }
    }

    function unselectAll() {
        if($('.ferrystudent_check:checked').length == $('.ferrystudent_check').length){
            $('#select_all').prop('checked',true);
        }else{
            $('#select_all').prop('checked',false);
        }
    }    
    
</script>
<div class="pagetitle">
	<h1>School Bus Track Detail</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
			<li class="breadcrumb-item active">Driver</li>
			<li class="breadcrumb-item active">School Bus Track / Detail</li>
		</ol>
	</nav>
	@include('layouts.error')
</div><!-- End Page Title -->

<section class="card">
    <div class="card-body">        
        <br />
        <div class="row g-4">
            <div class="col-md-10 content-title">
                <div class="exam-term-data m-2">
                    <h5><b>School Bus Track Data</b></h5>
                    <table class="table table-bordered">
                        <tr>
                            <td><b>Driver ID</b></td>
                            <td>{{$school_bus_track_data['driver_id']}}</td>
                        </tr>
                        <tr>
                            <td><b>Driver Name</b></td>
                            <td>{{$school_bus_track_data['name']}}</td>
                        </tr>
                        <tr>
                            <td><b>Driver Phone</b></td>
                            <td>{{$school_bus_track_data['phone']}}</td>
                        </tr>
                        <tr>
                            <td><b>Township</b></td>
                            <td>{{$township[$school_bus_track_data['township']]}}</td>
                        </tr>
                        <tr>
                            <td><b>Track No.</b></td>
                            <td>{{$school_bus_track_data['track_no']}}</td>
                        </tr>
                        <tr>
                            <td><b>Car Type</b></td>
                            <td>{{$school_bus_track_data['car_type']}}</td>
                        </tr>
                        <tr>
                            <td><b>Car No.</b></td>
                            <td>{{$school_bus_track_data['car_no']}}</td>
                        </tr>
                        <tr>
                            <td><b>Available Seat</b></td>
                            <td>{{$school_bus_track_data['arrive_student_no']}}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="col-md-2">
                <a class="btn btn-sm btn-primary" href="{{ url('admin/school_bus_track/list') }}" id="form-header-btn"><i
                        class="bi bi-skip-backward-fill"></i> Back</a>
            </div>
        </div>
        <br />
        <div class="card">
            <h5 class="m-2"><b>Ferry Student List</b></h5>
            <div class="row g-4 m-2" style="display: flex;overflow-x: auto;">
                <table cellpadding="0" cellspacing="0" class="datatable table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Registration No</th>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($detail_data) && $detail_data->count())
                            @php $i=1;@endphp
                            @foreach($detail_data as $res)
                            <tr>
                                <td>@php echo $i;$i++; @endphp</td>
                                <td>{{$res->registration_no }}</td>
                                <td>{{$res->student_id }}</td>
                                <td>{{$res->name }}</td>
                                <td>{{$status_list[$res->status] }}</td>
                                <td> 
                                    @if ($res->status != 2)
                                        <a href="#" data-toggle="modal" data-target="#myModalFerryPaid{{$res->id}}">Make Payment</a>
                                    @endif                               
                                </td>
                                <!-- Paid Modal -->
                                <div class="modal" id="myModalFerryPaid{{$res->id}}">
                                    <div class="modal-dialog">
                                        <form class="row g-4" method="POST" action="{{ url('admin/school_bus_track_detail/paid') }}" enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-content">
                                                <!-- Modal Header -->
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Paid</h4>
                                                </div>

                                                <!-- Modal Body -->
                                                <div class="modal-body">
                                                    <div class='row g-4'>
                                                        <div class="col-md-1"></div>
                                                        <div class="form-group col-md-10">
                                                            <label for="track_number"><b>Track Number</b></label>
                                                            <div class="col-sm-10">
                                                                <input type="text" name="track_number" class="form-control" value="{{$school_bus_track_data['track_no']}}" readonly>
                                                                <input type="hidden" name="two_way_amount" id="two_way_amount" value="{{$school_bus_track_data['two_way_amount']}}">
                                                                <input type="hidden" name="oneway_pickup_amount" id="oneway_pickup_amount" value="{{$school_bus_track_data['oneway_pickup_amount']}}">
                                                                <input type="hidden" name="oneway_back_amount" id="oneway_back_amount" value="{{$school_bus_track_data['oneway_back_amount']}}">
                                                                <input type="hidden" name="school_bus_track_id" id="school_bus_track_id" value="{{$school_bus_track_data['id']}}" />
                                                                <input type="hidden" name="ferry_student_id" id="ferry_student_id" value="{{$res->id}}" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <br />
                                                    <div class='row g-4'>
                                                        <div class="col-md-1"></div>
                                                        <div class="form-group col-md-10">
                                                            <label for="paid_studentid"><b>Student ID</b></label>
                                                            <div class="col-sm-10">
                                                                <input type="text" name="paid_studentid" class="form-control" value="{{ $res->student_id }}" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <br />
                                                    <div class='row g-4'>
                                                        <div class="col-md-1"></div>
                                                        <div class="form-group col-md-10">
                                                            <label for="paid_registrationid"><b>Registration No.</b></label>
                                                            <div class="col-sm-10">
                                                                <input type="text" name="paid_registrationid" class="form-control" value="{{ $res->registration_no }}" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <br />
                                                    <div class='row g-4'>
                                                        <div class="col-md-1"></div>
                                                        <div class="form-group col-md-10">
                                                            <label for="paid_registrationno"><b>Ferry Way</b></label>
                                                            <div class="col-sm-10">
                                                                <input type="text" name="paid_ferryway" class="form-control" value="{{ $ferry_way[$res->way] }}" readonly>
                                                                <input type="hidden" name="paid_ferryway_id" id="paid_ferryway_id" value="{{$res->way}}" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <br />
                                                    <div class='row g-4'>
                                                        <div class="col-md-1"></div>
                                                        <div class="form-group col-md-10">
                                                            <label for="paid_paiddate_from"><b>Paid Date(From)</b><span style="color:brown">*</span></label>
                                                            <div class="col-sm-10">
                                                                <input type="date" id="paid_paiddate_from" name="paid_paiddate_from" class="form-control" required>
                                                            </div>
                                                        </div>
                                                    </div>	
                                                    <br />	
                                                    <div class='row g-4'>
                                                        <div class="col-md-1"></div>
                                                        <div class="form-group col-md-10">
                                                            <label for="paid_paiddate_to"><b>Paid Date(To)</b><span style="color:brown">*</span></label>
                                                            <div class="col-sm-10">
                                                                <input type="date" id="paid_paiddate_to" name="paid_paiddate_to" class="form-control" required>
                                                            </div>
                                                        </div>
                                                    </div>	
                                                    <br />	
                                                    <div class='row g-4'>
                                                        <div class="col-md-1"></div>
                                                        <div class="form-group col-md-10">
                                                            <label for="paid_nettotal"><b>Net Total</b></label>
                                                            <div class="col-sm-10">
                                                                <input type="text" name="paid_nettotal" id="paid_nettotal" class="form-control" value="" readonly>
                                                            </div>
                                                        </div>
                                                    </div>	
                                                    <br />
                                                    <div class='row g-4'>
                                                        <div class="col-md-1"></div>
                                                        <div class="form-group col-md-10">
                                                            <label for="paid_paidtype"><b>Paid Type</b></label>
                                                            <div class="col-sm-10">
                                                                <select class="form-select" id="paid_paidtype" name="paid_paidtype">
                                                                    @foreach($paid_type as $key => $value)
                                                                        <option  value="{{$key}}" >{{$value}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <br />
                                                    <div class='row g-4'>
                                                        <div class="col-md-1"></div>
                                                        <div class="form-group col-md-10">
                                                            <label for="paid_paiddate"><b>Paid Date</b><span style="color:brown">*</span></label>
                                                            <div class="col-sm-10">
                                                                <input type="date" id="paid_paiddate" name="paid_paiddate" class="form-control" required>
                                                            </div>
                                                        </div>
                                                    </div>	
                                                    <br />	
                                                    <div class='row g-4'>
                                                        <div class="col-md-1"></div>
                                                        <div class="form-group col-md-10">
                                                            <label for="paid_remark"><b>Remark</b></label>
                                                            <div class="col-sm-10">
                                                                <textarea class="form-control" id="paid_remark" name="paid_remark"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>	
                                                    <div class="row g-4">
                                                        <div class="col-md-1"></div>
                                                        <div class="form-group col-md-3">
                                                            <div class="d-grid mt-4">
                                                                <input type="submit" value="Paid" class="btn btn-primary">
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <div class="d-grid mt-4">
                                                                <input type="submit" value="Close" class="btn btn-primary" data-dismiss="modal">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1"></div>
                                                    </div>
                                                    <br />
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <!-- End Paid Modal-->
                            </tr>
                            @endforeach
                        @else
                        <tr>
                            <td colspan="8">There are no data.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="d-flex">
                {!! $detail_data->links() !!}
            </div>
        </div>
        <div class="card">
            <h5 class="m-2"><b>Add to Ferry Student List </b>( Available Students - {{$school_bus_track_data['arrive_student_no']-$detail_data->count()}} )</h5>
            <form class="row g-4 m-2" style="display: flex;overflow-x: auto;" method="POST" action="{{ url('admin/school_bus_track_detail/add') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="available_students" id="available_students" value="{{$school_bus_track_data['arrive_student_no']-$detail_data->count()}}" />
                <div class="table-wrapper-scroll-y my-custom-scrollbar">
                    <input type="hidden" name="school_bus_track_id" class="form-control" value="{{$school_bus_track_data['id']}}">
                    <table cellpadding="0" cellspacing="0" border="0" class="datatable table table-striped table-bordered" id="attendance-table">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" class="check-all" id="select_all" onclick="selectAll()">
                                </th>
                                <th>Registration No</th>
                                <th>Student ID</th>
                                <th>Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($ferry_students) && $ferry_students->count())
                                @php $i=1;@endphp
                                @foreach($ferry_students as $res)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="ferrystudent_check" name="checkFerryStudent[]"  value="{{$res->id}}" onclick="unselectAll()">
                                    </td>
                                    <td>{{$res->registration_no }}</td>
                                    <td>{{$res->student_id }}</td>
                                    <td>{{$res->name }}</td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5">There are no data.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class='row g-4'>
                    <div class="col-md-10"></div>
                    <div class="form-group col-md-2">
                        <div class="d-grid">
                            <input type="submit" value="Add" class="btn btn-primary">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>


@endsection

