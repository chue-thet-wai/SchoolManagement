@extends('layouts.dashboard')

@section('content')
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="{{ asset('js/payment.js') }}"></script>
<div class="pagetitle">
    <h1>Payment Registration</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item active">Registration</li>
            <li class="breadcrumb-item active">Payment Registration</li>
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
                <h4><b>Create Invoice</b></h4>
            </div>
            <div class="col-md-2">
                <a class="btn btn-sm btn-primary" href="{{url('admin/payment/list')}}" id="form-header-btn"><i class="bi bi-skip-backward-fill"></i> Back</a>
            </div>
        </div>
        <br />
        <form method="POST" action="{{route('payment.store')}}" enctype="multipart/form-data">
            @csrf
            <br />
            <input type="hidden" id="token" value="<?php echo csrf_token() ?>" />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="invoice_type"><b>Invoice Type :</b></label>
                        <div class="col-sm-10">
                            <div class='row ms-2'>
                                <div class="form-check col-sm-4">
                                    <input class="form-check-input" type="radio" id="single" name="invoice_type" value="0" checked>
                                    <label class="form-check-label" for="single">
                                        <b>Single</b>
                                    </label>
                                </div>
                                <div class="form-check col-sm-4">
                                    <input class="form-check-input" type="radio" id="branch" name="invoice_type" value="1">
                                    <label class="form-check-label" for="branch">
                                        <b>Branch</b>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="card col-md-10" >
                    <div class="card-title" style="color:#000;font-size:1rem">
                        <b>Payment Information</b>
                        <hr />
                    </div>
                    <div class="card-body">
                        <div class="row g-4" id="single-invoice">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class='row'>
                                        <label for="student_id"><b>Student ID<span style="color:brown">*</span></b></label>
                                        <div class="col-sm-10">
                                            <input type="text" id="student_id" name="student_id" class="form-control">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" name="registration_search" id="registration_search" class="btn btn-sm btn-primary mt-1">Search</button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <span name="registration_msg" id="registration_msg"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-4" id="branch-invoice" style="display:none;">
                            <div class="col-md-6">
                                <div class="form-group">                                    
                                    <label for=""><b>Branch</b></label>
                                    <div class="col-sm-10">
                                        <select class="form-select" id="branch_id" name="branch_id">
                                            <option value=''>--Select--</option>
                                            @foreach($branch_list as $key => $value)
                                                <option  value="{{$key}}" >{{$value}}</option>
                                            @endforeach
                                        </select>
                                    </div>                                    
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for=""><b>Class</b></label>
                                    <div class="col-sm-10">
                                        <select class="form-select" id="class_id" name="class_id">
                                            <option value=''>--Select--</option>
                                            @foreach($class_list as $key => $value)
                                                <option  value="{{$key}}">{{$value}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br />
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="grade_level"><b>Grade Level</b></label>
                                    <div class="col-sm-10">
                                        <input type="text" id="grade_level" name="grade_level" class="form-control" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="grade_level_fee"><b>Grade Level Fee</b></label>
                                    <div class="col-sm-10">
                                        <input type="text" id="grade_level_fee" name="grade_level_fee" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br />
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="payment_type"><b>Payment Type</b></label>
                                    <div class="col-sm-10">
                                        <div class='row'>
                                            <div class="col-sm-5">
                                                <input type="radio" id="monthly" name="payment_type" value="0" checked ><b> Monthly</b>
                                            </div>
                                            <div class="col-sm-5">
                                                <input type="radio" id="yearly" name="payment_type" value="1"><b> Yearly</b>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br />   
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pay_from_period"><b>Pay Period (From)</b><span style="color:brown">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="date" id="pay_from_period" name="pay_from_period" class="form-control" required>
                                        <input type="hidden" id="academic_start" name="academic_start" value="{{$academic_start}}" class="form-control" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pay_to_period"><b>Pay Period (To)</b><span style="color:brown">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="date" id="pay_to_period" name="pay_to_period" class="form-control" required>
                                        <input type="hidden" id="academic_end" name="academic_end" value="{{$academic_end}}" class="form-control" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br />  
                        <div class="row g-4">
                            <div class="card col-md-10 mt-5 ms-3 p-3">
                                <div class="card-header"><b>Additional Fee</b></div>
                                <div class="card-body">
                                    @foreach($additional_fee as $fee)
                                        <input type="checkbox" name="additionalFee[]" class="addfee_Checkbox" value="{{$fee->id .'|'. $fee->additional_amount}}"/> <b> {{$fee->name}} ({{$fee->additional_amount}}) </b>
                                        <br />
                                    @endforeach
                                </div>
                            </div>               
                        </div>  
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="total_amount"><b>Total Amount</b></label>
                                    <div class="col-sm-10">
                                        <input type="number" id="total_amount" name="total_amount" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="discount_percent"><b>Discount (%)</b></label>
                                    <div class="col-sm-10">
                                        <input type="number" id="discount_percent" name="discount_percent" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br />    
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="net_total"><b>Net Total</b></label>
                                    <div class="col-sm-10">
                                        <input type="number" id="net_total" name="net_total" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <br />
                    </div>   
                </div> 
            </div>
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="form-group col-md-2">
                    <div class="d-grid mt-4">
                        <input type="submit" value="Save" class="btn btn-primary">
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>
            <br />
        </form>
    </div>
</section>


@endsection