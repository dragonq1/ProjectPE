var strength = {
   0: "Onaanvaardbaar",
   1: "Slecht",
   2: "Zwak",
   3: "Goed maar niet goed genoeg",
   4: "Sterk"
}

$(document).ready(function() {
  var passwordOld = $('#passwordOld');
  var formPsReset = $('#DOM__form--psReset');
  var password1 = $('#password1');
  var password2 = $('#password2');
  var meter = $('#password_strength_meter');
  var textStrength = $('#password_strength_text');
  var btnReset = $('#account__button');
  var divSuggestions = $('#password_suggestions');
  //Submit button
  formPsReset.on('submit', function() {
    //passwoorden Nakijken
    event.preventDefault();
    if(passwordOld.val() == '' || password1.val() == '' || password2.val() == '') {
      notify(701);
      return;
    }
    if(password1.val() != password2.val()) {
      notify(703);
      return;
    }

    $.ajax({
      url:"../php/actionsHome.php",
      type:"POST",
      dataType:"json",
      data: {psChange:1,psOld:passwordOld.val(),password1:password1.val(),password2:password2.val()},
      success: function(data){
        if(data.returnCode == 0) {
          notify(453);
          logout();
        }else{
          notify(data.returnCode);
        }
      }
    })
  });

  //Password sterkte nakijken
  password1.on('input',function() {
    var val = password1.val();
    var result = zxcvbn(val);

    //update password meter
    meter.val(result.score);
    //update text indicator
    if(val !== ""){
      //Meter
      textStrength.html("Sterkte: " + strength[result.score]);
      var suggestions = result.feedback.suggestions
      //Er zijn suggesties, door loopen en in lijst zetten
      if(suggestions.length > 0) {
        divSuggestions.show();
        //Veld resetten om oude suggesties weg te halen
        divSuggestions.html('');
        divSuggestions.append('<p>Suggesties voor een sterker wachtwoord:<p><ul>');
        suggestions.forEach((item) => {
          divSuggestions.append('<li>' + item + '</li>');
        });
        divSuggestions.append('</ul>');
      //Geen suggesties, suggesties hiden
      }else{
        divSuggestions.hide();
      }
    }else{
      divSuggestions.innerHTML = "";
    }
  });
})
