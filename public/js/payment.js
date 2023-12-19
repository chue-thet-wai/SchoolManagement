$(document).ready(function() {
    // Handle click event for an element with the ID "myButton"
    $("#registration_search").click(function() {
        var studentNo = $("#student_id").val();
        var token    = $("#token").val();
        $.ajax({
        type:'POST',
        url:'/admin/payment/paymentreg_search',
        data:{
                _token : token,
                student_id  : studentNo
            },
        
            success:function(data){
                if (data.msg == 'found') {
                    $("#registration_msg").html('Student data found!.');
                    $("#grade_level").val(data.grade_level);
                    $("#grade_level_fee").val(data.grade_level_fee);
                    if (data.fee_type == '1') {
                        $('#onetime').prop('checked', true);
                        $('input[name="payment_type"]').prop('disabled', true);
                        $('#payment_type_choose').val($('input[name="payment_type"]:checked').val());

                    } else {
                        $('#monthly').prop('checked', true);
                        $('input[name="payment_type"]').prop('disabled', false);
                        $('#payment_type_choose').val($('input[name="payment_type"]:checked').val());
                    }
                    $('#pay_from_period').attr('min', data.academic_year_start);
                    $('#pay_from_period').attr('max', data.academic_year_end);
                    $('#pay_to_period').attr('min', data.academic_year_start);
                    $('#pay_to_period').attr('max', data.academic_year_end);
                    $('#academic_start').val(data.academic_year_start);
                    $('#academic_end').val(data.academic_year_end);
                } else {
                    $("#registration_msg").html('Student data not found!.');
                    $("#grade_level").val('');
                    $("#grade_level_fee").val('');
                    $('#monthly').prop('checked', false);
                    $('input[name="payment_type"]').prop('disabled', false);
                    $('#payment_type_choose').val($('input[name="payment_type"]:checked').val());
                    $('#pay_from_period').removeAttr('min');
                    $('#pay_from_period').removeAttr('max');
                    $('#pay_to_period').removeAttr('min');
                    $('#pay_to_period').removeAttr('max');
                    $('#academic_start').val('');
                    $('#academic_end').val('');
                }  
                changeTotalAmount();           
            }
        });
    });

    $("#branch_id").change(function() {
        var branchID = $("#branch_id").val();
        var token    = $("#token").val();

        $.ajax({
        type:'POST',
        url:'/admin/payment/get_class_data',
        data:{
                _token : token,
                branch_id  : branchID
            },           
            success:function(data){
                if (data.msg == 'found') {
                    var class_data= data.class_data;

                    $("#class_id").empty();
                    $("#class_id").append("<option value=''>--Select--</option>");
                    class_data.forEach(function(element) {
                        var optionVal =
                                element.class_id + "/" +
                                element.grade_level_amount + "/" +
                                element.fee_type + "/" +
                                formatDate(new Date(element.academic_year_start)) + "/" +
                                formatDate(new Date(element.academic_year_end))+ "/" +
                                element.grade_level;
                        
                        $("#class_id").append("<option value="+optionVal+">"+element.class_name+"</option>");
                        
                    });
                    $("#grade_level").val('');
                    $("#grade_level_fee").val('');
                } else {
                    $("#class_id").empty();
                    $("#grade_level").val('');
                    $("#grade_level_fee").val('');             
                }  
                changeTotalAmount();           
            }
        });
    });

    $("input[name='invoice_type']").change(function() {
        var selectedValue = $('input[name="invoice_type"]:checked').val();
        var single_div = $('#single-invoice');
        var branch_div = $('#branch-invoice');        
        if (selectedValue == 1) {
            single_div.hide();
            branch_div.show();
        } else {
            single_div.show();
            branch_div.hide();
        }
        originValue();
    });

    $("input[name='payment_type']").change(function() {
        var selectedValue = $('input[name="payment_type"]:checked').val();
        if (selectedValue==1) {
            $("#pay_from_period").val($("#academic_start").val());
            $("#pay_to_period").val($("#academic_end").val());
        } else {
            $("#pay_from_period").val(null);
            $("#pay_to_period").val(null);
        }
        $('#payment_type_choose').val($('input[name="payment_type"]:checked').val());
        changeTotalAmount();
    });

    $("#class_id").change(function() {
        var classID = $("#class_id").val();
        if (classID.length === 0) {
            $("#grade_level").val('');
            $("#grade_level_fee").val(''); 
        } else {
            var classIDStr = classID.split('/');
            var amount   = classIDStr[1];
            var feetype  = classIDStr[2];
            var astart   = classIDStr[3];
            var aend     = classIDStr[4];
            var grade    = classIDStr[5];

            
            $("#grade_level").val(grade);
            $("#grade_level_fee").val(amount); 
            if (feetype == '1') {
                $('#onetime').prop('checked', true);
                $('input[name="payment_type"]').prop('disabled', true);
                $('#payment_type_choose').val($('input[name="payment_type"]:checked').val());
            } else {
                $('#monthly').prop('checked', true);
                $('input[name="payment_type"]').prop('disabled', false);
                $('#payment_type_choose').val($('input[name="payment_type"]:checked').val());
            }
            $('#pay_from_period').attr('min', astart);
            $('#pay_from_period').attr('max', aend);
            $('#pay_to_period').attr('min', astart);
            $('#pay_to_period').attr('max',aend);
            $('#academic_start').val(astart);
            $('#academic_end').val(aend);
        } 
        changeTotalAmount();       
    });

    $("#pay_from_period").change(function() {
        changeTotalAmount();    
    });
    $("#pay_to_period").change(function() {
        changeTotalAmount();    
    });
    $('.addfee_Checkbox').click(function() {
        changeTotalAmount();
    });
    $("#discount_percent").change(function() {
        changeTotalAmount();    
    });

    function originValue() {
        $("#student_id").val('');
        $("#grade_level").val('');
        $("#branch_id").val('');
        $("#class_id").val('')
        $("#registration_msg").html('');
        $("#grade_level_fee").val('');
        $("#pay_from_period").val(null);
        $("#pay_to_period").val(null);
        $('input[name="payment_type"]').filter('[value="0"]').prop('checked', true);
        $('#payment_type_choose').val($('input[name="payment_type"]:checked').val());
        $('.addfee_Checkbox').prop('checked', false);

        $("#discount_percent").val('');
        $("#total_amount").val('');
        $("#net_total").val('');
        $('input[name="payment_type"]').prop('disabled', false);
    }

    function changeTotalAmount() {
        var totalAmt = 0;
    
        var pay_from_period = $("#pay_from_period").val();
        var pay_to_period = $("#pay_to_period").val();
    
        var calMonth =0;
        var paymentType = $('input[name="payment_type"]:checked').val();
        
        if (pay_from_period && pay_to_period) {
            if (paymentType == 2) {
                calMonth = 1;
            } else {
                // Parse the date strings to Date objects
                var fromDate = new Date(pay_from_period);
                var toDate = new Date(pay_to_period);
        
                // Calculate the difference in months
                var monthDifference = (toDate.getFullYear() - fromDate.getFullYear()) * 12 + toDate.getMonth() - fromDate.getMonth();
                
                calMonth = monthDifference + 1; 
            } 
        }   
    
        //Add grade level fee
        if ($("#grade_level_fee").val() !=''){
            totalAmt += parseFloat($("#grade_level_fee").val()) * calMonth;
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

    function formatDate(date) {
        var year = date.getFullYear();
        var month = ('0' + (date.getMonth() + 1)).slice(-2);
        var day = ('0' + date.getDate()).slice(-2);
        return year + '-' + month + '-' + day;
    }

});
