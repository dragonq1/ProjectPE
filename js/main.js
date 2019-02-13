  function courses(){
    $.ajax({
        url:"../php/courses.php",
        type:"POST",
        datatype:"text",
        success: function(data){
          $("#dom__interactive").html(data);
        }


    })



  }
