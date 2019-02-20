
    var strength = {
       0: "Onaanvaardbaar",
       1: "Slecht",
       2: "Zwak",
       3: "Goed",
       4: "Sterk"
    }

  var password = document.getElementById('psw');
  var meter = document.getElementById('password_strength_meter');
  var text = document.getElementById('password_strength_text');

  password.addEventListener('input',function()
  {
    var val = password.value;
    var result = zxcvbn(val);

    //update password meter
    meter.value = result.score;
    //update text indicator

    if(val !== "")
      {
      text.innerHTML = "Sterkte:   " + strength[result.score];

      }else
       {
         text.innerHTML = "";
       }
  });





  
