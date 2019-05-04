var autoscroll = false, interval, btnAutscroll;

//Chatbox openen
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
      dataType: "json",
      success: function(data){
        if(data.returnCode == 0) {
          ophalen();
        }else{
          notify(data.returnCode);
        }
      }
  });
  $('#DOM__livechat__text').val('');
});

function toggleAutoscroll() {
  if(autoscroll) {
    autoscroll = false;
    btnAutscroll.removeClass('livechat__submitbtn--active');
  }else{
    btnAutscroll.addClass('livechat__submitbtn--active');
    autoscroll = true;
  }
}


$(document).ready(function() {
  load_chat();
  btnAutscroll = $('#DOM__livechat__autoscroll');
  toggleAutoscroll();
  btnAutscroll.on('click', function() {
    toggleAutoscroll();
  });
});

window.addEventListener("beforeunload", function(event) {
  if(typeof logout === 'function') {
    logout();
  }
});
