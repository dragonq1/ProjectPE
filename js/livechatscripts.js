

$('#DOM__livechat__form').submit(function(event) {
  event.preventDefault();
    // information to be sent to the server
    var message = $('#DOM__livechat__text').val();
    var messageGroupID = ;
    var messagerUserID = "<?php echo $_SESSION[\"userID\"] ?>" ;

    $.ajax({
        type: "POST",
        url: '../php/livechatsend.php',
        data: {message},
        dataType: "text"
    });
});



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

  bodytitle.style.display= "block";
  bodytitle.style.visibility= "visible";

  }
