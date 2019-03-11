modalInviteUser = document.getElementById("dom__modal--inviteUser");
btnInviteUser = document.getElementById("dom__btn--inviteUser");
btnInviteUserClose = document.getElementById("dom__btn--inviteUserClose");

modalLeaveGroup = document.getElementById("dom__modal--leaveGroup");
btnLeaveGroup = document.getElementById("dom__btn--leaveGroup");
btnLeaveGroupClose = document.getElementById("dom__btn--leaveGroupClose");

modalMembers = document.getElementById("dom__modal--members");
btnMembers = document.getElementById("dom__btn--members");
btnMembersClose = document.getElementById("dom__btn--membersClose");

modalNewCourse = document.getElementById("dom__modal--newCourse");
btnNewCourse = document.getElementById("dom__btn--newCourse");
btnNewCourseClose = document.getElementById("dom__btn--newCourseClose");

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

//Leave group modal

btnLeaveGroup.onclick = function() {
  modalLeaveGroup.classList.remove("slideOutUp");
  modalLeaveGroup.classList.add("slideInDown");
  modalLeaveGroup.style.display = "flex"
}

btnLeaveGroupClose.onclick = function() {
  modalLeaveGroup.classList.remove("slideInDown");
  modalLeaveGroup.classList.add("slideOutUp");
}

//Member list

btnMembers.onclick = function() {
  modalMembers.classList.remove("slideOutUp");
  modalMembers.classList.add("slideInDown");
  modalMembers.style.display = "flex"
  getGroupMembers();
}

btnMembersClose.onclick = function() {
  modalMembers.classList.remove("slideInDown");
  modalMembers.classList.add("slideOutUp");
}

//New course

btnNewCourse.onclick = function() {
  modalNewCourse.classList.remove("slideOutUp");
  modalNewCourse.classList.add("slideInDown");
  modalNewCourse.style.display = "flex"
}

btnNewCourseClose.onclick = function() {
  modalNewCourse.classList.remove("slideInDown");
  modalNewCourse.classList.add("slideOutUp");
}



function destroyCourseModals() {
  modalLeaveGroup.style.display = "none";
  modalInviteUser.style.display = "none";
  modalMembers.style.display = "none";
  modalNewCourse.style.display = "none"
}
