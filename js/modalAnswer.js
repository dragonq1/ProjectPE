var modalpostanswer = document.getElementById("DOM__modal--newanswer");
var answermsg = document.getElementById("DOM__modal__newanswermessage");
var submitanswer = document.getElementById("DOM__modal__submitanswer");
var annuleeranswerbtn =  document.getElementById('DOM__modal__annuleeranswer');

var newanswerbtn = document.getElementById('DOM__new__answer');




//New post modal
newanswerbtn.onclick = function(){
modalpostanswer.classList.remove("slideOutUp");
modalpostanswer.classList.add("slideInDown");
modalpostanswer.style.display = "flex"
}

annuleeranswerbtn.onclick = function() {
  modalpostanswer.classList.remove("slideInDown");
  modalpostanswer.classList.add("slideOutUp");
  answermsg.value = "";
}
submitanswer.onclick = function() {

var answermessage = answermsg.value

if(answermessage != ""){

    $.ajax({
        url:"../php/actionsHome.php",
        type:"POST",
        dataType:"json",
        data: {newanswer:1,answermessage:answermessage},
        success: function(data){
          if(data.returnCode == 0) {
            modalpostanswer.classList.remove("slideInDown");
            modalpostanswer.classList.add("slideOutUp");
            answermsg.value = "";
            destroyModals();
            //notify(); code voor message dat post is aangemaakt
          }else{
            notify(data.returnCode);
          }
        }
    })

 }else{
   //notify();
 }
}

function destroyModals() {
  modalpostanswer.display = "none"
}
