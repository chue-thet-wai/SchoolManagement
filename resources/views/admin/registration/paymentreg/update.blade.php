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
                <h4><b>Update Invoice</b></h4>
            </div>
            <div class="col-md-2">
                <a class="btn btn-sm btn-primary" href="{{url('admin/payment/list')}}" id="form-header-btn"><i class="bi bi-skip-backward-fill"></i> Back</a>
            </div>
        </div>

        <br />
        <form method="POST" action="{{route('payment.update',$result[0]->invoice_id)}}" enctype="multipart/form-data">
            @csrf
            <br />
            {{method_field('PUT')}}

            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <div class='row'>
                            <label for="student_id"><b>Student ID<span style="color:brown">*</span></b></label>
                            <div class="col-sm-10">
                                <input type="text" id="reg_no" name="reg_no" value="{{$result[0]->student_id}}" class="form-control" required disabled>
                                <input type="hidden" id="student_id" name="student_id" value="{{$result[0]->student_id}}" />
                            </div>
                            <!--<div class="col-md-2">
                                <button type="button" name="registration_search" id="registration_search" class="btn btn-sm btn-primary mt-1" >Search</button>
                            </div>-->
                        </div>
                        <div class="row">
                            <span name="registration_msg" id="registration_msg"></span>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="grade_level"><b>Grade Level</b></label>
                        <div class="col-sm-10">
                            <input type="text" id="grade_level" name="grade_level" value="{{$result[0]->grade_name}}" class="form-control" disabled>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="grade_level_fee"><b>Grade Level Fee</b></label>
                        <div class="col-sm-10">
                            <input type="text" id="grade_level_fee" name="grade_level_fee" value="{{$result[0]->grade_level_fee}}" class="form-control" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <br />   
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="payment_type"><b>Payment Type</b></label>
                        <div class="col-sm-10">
                            <div class='row'>                                
                                @foreach ($payment_type as $key=> $value)
                                    <div class="col-sm-4">
                                        <input type="radio" id="monthly" name="payment_type" value="{{$key}}" 
                                        @if ($result[0]->payment_type == $key)
                                            checked
                                        @endif
                                        ><b> {{$value}}</b>
                                    </div>
                                @endforeach                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br />   
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="pay_from_period"><b>Pay Period (From)</b><span style="color:brown">*</span></label>
                        <div class="col-sm-10">
                            <input type="date" id="pay_from_period" name="pay_from_period" value="{{date('Y-m-d',strtotime($result[0]->pay_from_period))}}" class="form-control" required>
                            <input type="hidden" id="academic_start" name="academic_start" value="{{$academic_start}}" class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="pay_to_period"><b>Pay Period (To)</b><span style="color:brown">*</span></label>
                        <div class="col-sm-10">
                            <input type="date" id="pay_to_period" name="pay_to_period" value="{{date('Y-m-d',strtotime($result[0]->pay_to_period))}}" class="form-control" required>
                            <input type="hidden" id="academic_end" name="academic_end" value="{{$academic_end}}" class="form-control" />
                        </div>
                    </div>
                </div>
            </div>
            <br /> 
            <div class="row g-4">
                <div class="col-md-1"></div>
                <fieldset class="form-group col-md-9 border m-3 p-3">
                    <legend for="reg_type" class="float-none w-auto" style='font-size:1.2em;'><b>Additional Fee</b></legend>
                    <div class="col-sm-10">
                        @foreach($addition_fee_list as $fee)
                            <input type="checkbox" name="additionalFee[]" class="addfee_Checkbox" value="{{$fee->id .'|'. $fee->additional_amount}}"  
                            @if (in_array($fee->id,$payment_additional_fee))
                                checked
                            @endif
                            /> <b> {{$fee->name}} ({{$fee->additional_amount}}) </b>
                            <br />
                        @endforeach
                    </div>
                </fieldset>               
                <div class="col-md-2"></div>
            </div>  
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="total_amount"><b>Total Amount</b></label>
                        <div class="col-sm-10">
                            <input type="number" id="total_amount" name="total_amount" value="{{$result[0]->total_amount}}" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="discount_percent"><b>Discount (%)</b></label>
                        <div class="col-sm-10">
                            <input type="number" id="discount_percent" name="discount_percent" value="{{$result[0]->discount_percent}}" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <br />    
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="net_total"><b>Net Total</b></label>
                        <div class="col-sm-10">
                            <input type="number" id="net_total" name="net_total" value="{{$result[0]->net_total}}" class="form-control" readonly>
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
            <br />
        </form>
        <br />
    </div>
</section>


@endsection