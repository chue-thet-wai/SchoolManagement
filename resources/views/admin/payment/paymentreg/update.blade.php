@extends('layouts.dashboard')

@section('content')
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="{{ asset('js/payment.js') }}"></script>
<div class="pagetitle">
    <h1>Invoice Creation</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item active">Payment</li>
            <li class="breadcrumb-item active">Invoice Creation</li>
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
                <h4><b>Update Invoice</b></h4>
            </div>
            <div class="col-md-2">
                <a class="btn btn-sm btn-primary" href="{{url('admin/invoice/list')}}" id="form-header-btn"><i class="bi bi-skip-backward-fill"></i> Back</a>
            </div>
        </div>

        <br />
        <form method="POST" action="{{route('invoice.update',$result[0]->invoice_id)}}" enctype="multipart/form-data">
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
                        <input type="hidden" id="payment_type_choose" name="payment_type_choose" value="{{$result[0]->payment_type}}" />
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
                            <input type="date" id="pay_from_period" name="pay_from_period" value="@if ($result[0]->pay_from_period != null) date('Y-m-d',strtotime($result[0]->pay_from_period)) @endif" class="form-control">
                            <input type="hidden" id="academic_start" name="academic_start" value="{{$academic_start}}" class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="pay_to_period"><b>Pay Period (To)</b><span style="color:brown">*</span></label>
                        <div class="col-sm-10">
                            <input type="date" id="pay_to_period" name="pay_to_period" value="@if ($result[0]->pay_to_period != null ) date('Y-m-d',strtotime($result[0]->pay_to_period)) @endif" class="form-control">
                            <input type="hidden" id="academic_end" name="academic_end" value="{{$academic_end}}" class="form-control" />
                        </div>
                    </div>
                </div>
            </div>
            <br /> 
            <div class="row g-4 ms-5">
                <div class="card col-md-10 mt-5 ms-4 p-3">
                    <div class="card-header"><b>Additional Fee</b></div>
                    <div class="card-body" id="additional_fee_card">
                        @foreach($addition_fee_list as $fee)
                            <input type="checkbox" name="additionalFee[]" class="addfee_Checkbox" value="{{$fee->id .'|'. $fee->additional_amount}}"  
                            @if (in_array($fee->id,$payment_additional_fee))
                                checked
                            @endif
                            /> <label class="additional_fee_label"> {{$fee->name}} ({{$fee->additional_amount}}) </label>
                            <br class="additional_fee_br" />
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