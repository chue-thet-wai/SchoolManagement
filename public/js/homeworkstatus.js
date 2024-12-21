$(document).ready(function() {

    $("#class_id").change(function() {
        var class_id = $("#class_id").val();
        var token    = $("#token").val();
        $.ajax({
            type:'POST',
            url:'/admin/homework_status/homework_search',
            data:{
                 _token : token,
                 class_id  : class_id
             },
            
             success:function(data){
                if (data.msg == 'found') {
                    var homework_data= data.homework_data;

                    $("#homework_id").empty();
                    $("#homework_id").append("<option value=''>--Select--</option>");
                    homework_data.forEach(function(element) {
                        $("#homework_id").append("<option value='"+element.id+"'>"+element.title+"</option>");
                        
                    });

                    var student_data= data.student_data;

                    $("#student_id").empty();
                    $("#student_id").append("<option value=''>--Select--</option>");
                    student_data.forEach(function(element) {
                        $("#student_id").append("<option value='"+element.student_id+"'>"+element.name+"</option>");
                        
                    });
                    
                } else {
                    $("#homework_id").empty();
                    $("#student_id").empty();
                             
                }         
            }
         });    
    });

});
