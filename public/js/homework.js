$(document).ready(function() {

    $("#class_id").change(function() {
        var class_id = $("#class_id").val();
        var token    = $("#token").val();
        $.ajax({
            type:'POST',
            url:'/admin/homework/class_change',
            data:{
                 _token : token,
                 class_id  : class_id
             },
            
             success:function(data){
                if (data.msg == 'found') {

                    var subject_data= data.subject_data;

                    $("#subject_id").empty();
                    //$("#subject_id").append("<option value=''>--Select--</option>");
                    subject_data.forEach(function(element) {
                        $("#subject_id").append("<option value='"+element.id+"'>"+element.name+"("+element.grade_name+")</option>");
                        
                    });

                    var academic_data= data.academic_data;

                    $("#academic_year_id").empty();
                    //$("#academic_year_id").append("<option value=''>--Select--</option>");
                    academic_data.forEach(function(element) {
                        $("#academic_year_id").append("<option value='"+element.id+"'>"+element.name+"</option>");
                        
                    });
                    
                } else {
                    $("#subject_id").empty();
                    $("#academic_year_id").empty();                      
                }         
            }
         });    
    });

});
