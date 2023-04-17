@extends('layouts.dashboard')

@section('content')
<script>
    function getRegistrationData(){
        var regNo = $("#registration_no").val();
        $.ajax({
           type:'POST',
           url:'/admin/payment/paymentreg_search',
           data:{
                _token :'<?php echo csrf_token() ?>',
                registration_no  : regNo
            },
           
            success:function(data){
                if (data.msg == 'found') {
                    $("#registration_msg").html('Registration data found!.');
                    $("#grade_level").val(data.grade_level);
                    $("#grade_level_fee").val(data.grade_level_fee);
                } else {
                    $("#registration_msg").html('Registration data not found!.');
                    $("#grade_level").val('');
                    $("#grade_level_fee").val('');
                }  
                changeTotalAmount();           
            }
        });
    }
    function changeTotalAmount() {
        var totalAmt = 0;
        //Add grade level fee
        if ($("#grade_level_fee").val() !=''){
            totalAmt += parseFloat($("#grade_level_fee").val());
        } 
        
        //Add additional fee
        var arr = $('.addfee_Checkbox:checked').map(function(){
            return this.value;
        }).get();
        if (arr.length != 0) {
            for (let i = 0; i < arr.length; i++) {
                var fee = arr[i];
                var ret = fee.split("|");
                totalAmt += parseFloat(ret[1]);
            }
        }
        $("#total_amount").val(totalAmt);

        var netAmt = totalAmt;
        //Calculate Discount
        if ($("#discount_percent").val() !=''){
            var dis = parseFloat($("#discount_percent").val());
            var disAmt  = totalAmt * (dis/100);
            netAmt     -= disAmt;
        } 
        $("#net_total").val(netAmt);
    }
</script>
<div class="pagetitle">
    <h1>Payment</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item active">Registration</li>
            <li class="breadcrumb-item active">Payment</li>
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
                <h4><b>Create Payment Registration</b></h4>
            </div>
            <div class="col-md-1">
                <a class="btn btn-sm btn-primary" href="{{route('payment.index')}}" id="form-header-btn"> Back</a>
            </div>
            <div class="col-md-1"></div>
        </div>

        <br />
        <form method="POST" action="{{route('payment.store')}}" enctype="multipart/form-data">
            @csrf
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <div class='row'>
                            <label for="registration_no"><b>Registration Number<span style="color:brown">*</span></b></label>
                            <div class="col-sm-10">
                                <input type="text" id="registration_no" name="registration_no" class="form-control" required>
                            </div>
                            <div class="col-md-2">
                                <button type="button" name="registration_search" id="registration_search" class="btn btn-sm btn-primary mt-1" onclick="getRegistrationData()">Search</button>
                            </div>
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
                            <input type="text" id="grade_level" name="grade_level" class="form-control" disabled>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="pay_date"><b>Pay Date</b></label>
                        <div class="col-sm-10">
                            <input type="date" id="pay_date" name="pay_date" class="form-control" required>
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
                                <div class="col-sm-4">
                                    <input type="radio" id="monthly" name="payment_type" value="0" checked><b> Monthly</b>
                                </div>
                                <div class="col-sm-4">
                                    <input type="radio" id="yearly" name="payment_type" value="1"><b> Yearly</b>
                                </div>
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
                        <label for="pay_from_period"><b>Pay Period (From)</b></label>
                        <div class="col-sm-10">
                            <input type="date" id="pay_from_period" name="pay_from_period" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="pay_to_period"><b>Pay Period (To)</b></label>
                        <div class="col-sm-10">
                            <input type="date" id="pay_to_period" name="pay_to_period" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>
            <br /> 
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
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
                <div class="col-md-1"></div>
                <fieldset class="form-group col-md-9 border m-3 p-3">
                    <legend for="reg_type" class="float-none w-auto" style='font-size:1.2em;'><b>Additional Fee</b></legend>
                    <div class="col-sm-10">
                        @foreach($additional_fee as $fee)
                            <input type="checkbox" name="additionalFee[]" class="addfee_Checkbox" value="{{$fee->id .'|'. $fee->additional_amount}}" onclick="changeTotalAmount()" /> <b> {{$fee->name}} ({{$fee->additional_amount}}) </b>
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
                            <input type="number" id="total_amount" name="total_amount" class="form-control" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <br />   
            <div class="row g-4">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="discount_percent"><b>Discount (%)</b></label>
                        <div class="col-sm-10">
                            <input type="number" id="discount_percent" name="discount_percent" onchange="changeTotalAmount()" class="form-control">
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
                            <input type="number" id="net_total" name="net_total" class="form-control" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <br />       
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