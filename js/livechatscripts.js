



function openchat(){
var body = document.getElementById("DOM__livechat__body--main");
var bodytitle = document.getElementById("DOM__livechat__title");
var bodymessages = document.getElementById("DOM__livechat__body");


body.style.height= "50%";

bodymessages.style.display = "inline";
bodymessages.style.visibility = "visible";

bodytitle.style.display= "none";
bodytitle.style.visibility= "hidden";

}


function closechat(){
  var body = document.getElementById("DOM__livechat__body--main");
  var bodytitle = document.getElementById("DOM__livechat__title");
  var bodymessages = document.getElementById("DOM__livechat__body");


  body.style.height= "7%";

  bodymessages.style.display = "none";
  bodymessages.style.visibility = "hidden";

  bodytitle.style.display= "inline";
  bodytitle.style.visibility= "visible";

  }
