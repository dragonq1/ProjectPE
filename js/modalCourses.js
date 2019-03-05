modalInviteUser = document.getElementById("dom__modal--inviteUser");
btnInviteUser = document.getElementById("dom__btn--inviteUser");
btnInviteUserClose = document.getElementById("dom__btn--inviteUserClose");

//Invite user modal
btnInviteUser.onclick = function() {
  modalInviteUser.classList.remove("slideOutUp");
  modalInviteUser.classList.add("slideInDown");
  modalInviteUser.style.display = "flex"
}

btnInviteUserClose.onclick = function() {
  modalInviteUser.classList.remove("slideInDown");
  modalInviteUser.classList.add("slideOutUp");
}
