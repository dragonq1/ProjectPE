// Alles gerelateerd tot login pagina

$(document).ready(function() {
  loadLogin();
});



//login box
function loadLogin() {
  $.ajax({
    url:"../php/login.php",
    type:"POST",
    dataType:"json",
    data: {loginBody:1},
    success: function(data){
      if(data.returnCode == 0) {
        $("#dom__loginBox").html(data.output);
        $('#dom__link--reset').click(function() {
            loadPSReset();
        });
      }else{
        notify(data.returnCode);
      }

    }
  })
}

//Wachtwoord vergeten box
function loadPSReset() {
  $.ajax({
    url:"../php/login.php",
    type:"POST",
    dataType:"json",
    data: {passwordResetPage:1},
    success: function(data){
      if(data.returnCode == 0) {
        //Box inladen
        $("#dom__loginBox").html(data.output);
        //Login link koppellen aan login box functue
        $('#dom__link--login').click(function() {
            loadLogin();
        });
        //Form linken
        $('#dom__form--passwordReset').submit(function(event) {
            event.preventDefault();
            psReset();
        });
      }else{
        notify(data.returnCode);
      }

    }
  })
}

function psReset() {
  var emailBox = $("#dom__reset--email");
  var email = emailBox.val();

  if(email == "") {
    notify(701);
  }

  emailBox.val("");
  $.ajax({
    url:"../php/login.php",
    type:"POST",
    dataType:"json",
    data: {passwordReset:1, email:email},
    success: function(data){
      if(data.returnCode == 0) {
        notify(304);
      }else{
        notify(data.returnCode);
      }
    }
  })

}
