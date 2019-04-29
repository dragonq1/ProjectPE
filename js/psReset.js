$(document).ready(function() {

  var password1 = $("#dom__input--password1");
  var password2 = $("#dom__input--password2");
  var suggestiesText = $("#dom__suggesties");
  var meter = $("#dom__meter--sterkte");
  var sterkteText = $("#dom__text--sterkte");

  $("#dom__form--resetPassword").submit(function(event) {
    event.preventDefault();
    if((password1.val() != "") && (password2.val() != "")) {
      if(password1.val() == password2.val()) {
        $.ajax({
          url:"../php/login.php",
          type:"POST",
          dataType:"json",
          data: {passwordReset:1,password1:password1.val(),password2:password2.val()},
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

  password1.on('input', function() {

    var strength = {
       0: "Onaanvaardbaar",
       1: "Slecht",
       2: "Zwak",
       3: "Goed maar niet goed genoeg",
       4: "Sterk"
    }

    var val = password1.val();
    var result = zxcvbn(val);

    //update password meter
    meter.val(result.score);
    //update text indicator
    if(val !== ""){
      //Meter
      sterkteText.html("Sterkte: " + strength[result.score]);
      var suggestions = result.feedback.suggestions
      //Er zijn suggesties, door loopen en in lijst zetten
      if(suggestions.length > 0) {
        suggestiesText.show();
        //Veld resetten om oude suggesties weg te halen
        suggestiesText.html('');
        suggestiesText.append('<p>Suggesties voor een sterker wachtwoord:<p><ul>');
        suggestions.forEach((item) => {
          suggestiesText.append('<li>' + item + '</li>');
        });
        suggestiesText.append('</ul>');
      //Geen suggesties, suggesties hiden
      }else{
        suggestiesText.hide();
      }
    }else{
      suggestiesText.innerHTML = "";
    }
  });

})
