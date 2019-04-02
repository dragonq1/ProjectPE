
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

  $("#DOM__livechat__form").submit(function(event) {
  event.preventDefault();
    // information to be sent to the server
    var livechatmessage = $('#DOM__livechat__text').val();

    $.ajax({
        type: 'POST',
        url: '../php/actionsHome.php',
        data: {livechat__text:livechatmessage},
        dataType: "text",
        success: function(data){
          //alert(data);
        }
    });

$('#DOM__livechat__text').val(' ');


  });



  $("#DOM__livechat__body--messages").ready(function() {

  var poll = 1;

    $.ajax({
        type: 'POST',
        url: '../php/actionsHome.php',
        data: {pollchat:poll},
        dataType: "text",
        success: function(data){
          alert(data[0]);
        }
    });
  });
