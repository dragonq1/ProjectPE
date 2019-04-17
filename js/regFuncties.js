
  //Registratie form linken
  $(document).ready(function() {
    $('#dom__form--registratie').submit(function(event) {
        event.preventDefault();
        register();
    });
  });

  //Registratie
  function register() {

    //Variabelen ophalen
    var nicknameField = $("#dom__inputReg--nickname");
    var emailField = $("#dom__inputReg--email");
    var firstnameField = $("#dom__inputReg--voornaam");
    var lastnameField = $("#dom__inputReg--achternaam");
    var password1Field = $("#dom__inputReg--password1");
    var password2Field = $("#dom__inputReg--password2");

    var nickname = nicknameField.val();
    var email = emailField.val();
    var firstname = firstnameField.val();
    var lastname = lastnameField.val();
    var password1 = password1Field.val();
    var password2 = password2Field.val();

    if((nickname != "") && (email != "") &&( firstname != "") && (lastname != "") && (password1 != "") && (password2 != "")) {
      if(password1 == password2) {
        $.ajax({
          url:"../php/register.php",
          type:"POST",
          dataType:"json",
          data: {nickname:nickname,email:email,firstname:firstname,lastname:lastname,password1:password1,password2:password2},
          success: function(data){
            if(data.returnCode == 0) {
              notify(204);
              window.location.replace("../index.php");
            }else{
              notify(data.returnCode);
            }
          }
        })
      }else{
        notify(203);
      }
    }else{
      notify(701);
    }
  }

  //Functies voor wachtwoord sterkte meter
  var strength = {
     0: "Onaanvaardbaar",
     1: "Slecht",
     2: "Zwak",
     3: "Goed maar niet goed genoeg",
     4: "Sterk"
  }

  var password = document.getElementById('dom__inputReg--password1');
  var meter = document.getElementById('password_strength_meter');
  var text = document.getElementById('password_strength_text');
  var bt = document.getElementById('registratie__button');
  var text2 = document.getElementById('password_cracktime');

  password.addEventListener('input',function()
  {
    var val = password.value;
    var result = zxcvbn(val);

    //update password meter
    meter.value = result.score;
    //update text indicator


    if(val !== ""){
      text.innerHTML = "Sterkte:   " + strength[result.score];
      text2.innerHTML = "Mogelijke suggesties: " + JSON.stringify(result.feedback.suggestions);
      if(result.score < 3){
         bt.disabled = true;
      }else{
         bt.disabled = false;
      }
    }else{
      text.innerHTML = "";
    }
  });
