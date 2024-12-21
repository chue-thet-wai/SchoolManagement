$(document).ready(function() {

    $("#class_id").change(function() {
        var class_id = $("#class_id").val();
        var token    = $("#token").val();
        $.ajax({
            type:'POST',
            url:'/admin/exam_marks/change_class',
            data:{
                 _token : token,
                 class_id  : class_id
             },
            
             success:function(data){
                if (data.msg == 'found') {

                    var student_data= data.student_data;
                    var exam_terms_data= data.exam_terms_data;
                    var subject_data= data.subject_data;

                    $("#student_id").empty();
                    $("#student_id").append("<option value=''>--Select--</option>");
                    student_data.forEach(function(element) {
                        $("#student_id").append("<option value='"+element.student_id+"'>"+element.name+"</option>");
                        
                    });

                    $("#exam_terms_id").empty();
                    $("#exam_terms_id").append("<option value=''>--Select--</option>");
                    exam_terms_data.forEach(function(element) {
                        $("#exam_terms_id").append("<option value='"+element.id+"'>"+element.name+"</option>");
                        
                    });

                    $("#subject_id").empty();
                    $("#subject_id").append("<option value=''>--Select--</option>");
                    subject_data.forEach(function(element) {
                        $("#subject_id").append("<option value='"+element.id+"'>"+element.name+"</option>");
                        
                    });
                    
                } else {
                    $("#student_id").empty();
                    $("#exam_terms_id").empty();  
                    $("#subject_id").empty();                             
                }         
            }
         });    
    });

    $("#exam_terms_id").change(function() {
        var exam_terms_id = $("#exam_terms_id").val();
        var token    = $("#token").val();
        $.ajax({
            type:'POST',
            url:'/admin/exam_marks/change_examterms',
            data:{
                 _token : token,
                 exam_terms_id  : exam_terms_id
             },
            
             success:function(data){
                if (data.msg == 'found') {

                    var subject_data= data.subject_data;

                    $("#subject_id").empty();
                    $("#subject_id").append("<option value=''>--Select--</option>");
                    subject_data.forEach(function(element) {
                        $("#subject_id").append("<option value='"+element.id+"'>"+element.name+"</option>");
                        
                    });
                    
                } else {
                    $("#subject_id").empty();                             
                }         
            }
         });    
    });

});
