// Modals
modalNewGroup = document.getElementById("dom__modal--newgroup");

btnNewGroup = document.getElementById("dom__btn--newgroup");
btnNewGroupClose = document.getElementById("dom__btn--newGroupClose");
btnNewGroupSubmit = document.getElementById("dom__submit--newGroup");



//New group modal

btnNewGroup.onclick = function() {
  modalNewGroup.classList.remove("slideOutUp");
  modalNewGroup.classList.add("slideInDown");
  modalNewGroup.style.display = "flex"
}

  btnNewGroupClose.onclick = function() {
  modalNewGroup.classList.remove("slideInDown");
  modalNewGroup.classList.add("slideOutUp");
}

btnNewGroupSubmit.onclick = function() {

  var grName = document.getElementById("grName").value;
  var grDescription = document.getElementById("grDescription").value;

  if(grName != "" && grDescription != "") {
    $.ajax({
        url:"../php/actionsHome.php",
        type:"POST",
        dataType:"json",
        data: {grName:grName,grDescription:grDescription},
        success: function(data){
          if(data.returnCode == 0) {
            destroyModals();
            home();
          }else{
            alert(data.returnCode);
          }
        }
    })
  }else{
  alert("Voer alle velden in!")
  }



}

function destroyModals() {
  modalNewGroup.style.display = "none"
}
