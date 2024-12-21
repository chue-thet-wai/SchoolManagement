$(document).ready(function() {

    $("#class_id").change(function() {
        var class_id = $("#class_id").val();
        var token    = $("#token").val();
        $.ajax({
            type:'POST',
            url:'/admin/student_request/student_search',
            data:{
                 _token : token,
                 class_id  : class_id
             },
            
             success:function(data){
                if (data.msg == 'found') {

                    var student_data= data.student_data;

                    $("#student_id").empty();
                    $("#student_id").append("<option value=''>--Select--</option>");
                    student_data.forEach(function(element) {
                        $("#student_id").append("<option value='"+element.student_id+"'>"+element.name+"</option>");
                        
                    });
                    
                } else {
                    $("#student_id").empty();
                             
                }         
            }
         });    
    });

});
