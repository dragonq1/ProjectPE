// Modals
modalNewGroup = document.getElementById("dom__modal--newgroup");

btnNewGroup = document.getElementById("dom__btn--newgroup");
btnNewGroupClose = document.getElementById("dom__btn--newGroupClose");



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




function destroyModals() {
  modalNewGroup.style.display = "none"
}
