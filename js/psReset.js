$(document).ready(function() {

  $("#dom__form--resetPassword").submit(function(event) {
    event.preventDefault();
    var password1 = $("#dom__input--password1").val();
    var password2 = $("#dom__input--password2").val();


    if((password1 != "") && (password2 != "")) {
      if(password1 == password2) {
        $.ajax({
          url:"../php/login.php",
          type:"POST",
          dataType:"json",
          data: {passwordReset:1,password1:password1,password2:password2},
          success: function(data){
            if(data.returnCode == 0) {
              window.location.replace("../index.php");
            }else{
              notify(data.returnCode);
            }
          }
        })

      }else{
        notify(301);
      }
    }else{
       notify(701);
    }


  });

})
