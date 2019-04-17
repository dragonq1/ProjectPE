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
        $('#dom__form--login').submit(function(event) {
            event.preventDefault();
            login();
        });
      }else{
        notify(data.returnCode);
      }

    }
  })
}

//Inloggen
function login() {
  var emailField = $("#dom__inputLogin--email");
  var passwordField = $("#dom__inputLogin--password");
  var email = emailField.val();
  var password = passwordField.val();

  if(email != "" && password != "") {
    $.ajax({
      url:"../php/login.php",
      type:"POST",
      dataType:"json",
      data: {login:1,password:password,email:email},
      success: function(data){
        if(data.returnCode == 0) {
          window.location.replace("../home.php");
        }else{
          notify(data.returnCode);
        }
      }
    })
  }else{
    notify(701);
  }

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
