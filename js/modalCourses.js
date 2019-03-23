modalInviteUser = document.getElementById("dom__modal--inviteUser");
btnInviteUser = document.getElementById("dom__btn--inviteUser");
btnSubmitInviteUser = document.getElementById("dom__submit--inviteUser");
btnInviteUserClose = document.getElementById("dom__btn--inviteUserClose");

modalkickUser = document.getElementById("dom__modal--kickUser");
btnkickUser = document.getElementById("dom__btn--kickUser");
btnSubmitkickUser = document.getElementById("dom__submit--kickUser");
btnkickUserClose = document.getElementById("dom__btn--kickUserClose");

modalLeaveGroup = document.getElementById("dom__modal--leaveGroup");
btnLeaveGroup = document.getElementById("dom__btn--leaveGroup");
btnLeaveGroupClose = document.getElementById("dom__btn--leaveGroupClose");

modalDeleteGroup = document.getElementById("dom__modal--deleteGroup");
btnDeleteGroup = document.getElementById("dom__btn--deleteGroup");
btnSubmitDeleteGroup = document.getElementById("dom__submit--deleteGroup");
btnDeleteGroupClose = document.getElementById("dom__btn--deleteGroupClose");

modalDeleteFile = document.getElementById("dom__modal--deleteFile");

modalMembers = document.getElementById("dom__modal--members");
btnMembers = document.getElementById("dom__btn--members");
btnMembersClose = document.getElementById("dom__btn--membersClose");

modalNewCourse = document.getElementById("dom__modal--newCourse");
btnNewCourse = document.getElementById("dom__btn--newCourse");
btnsubmitCr = document.getElementById("dom__submit--newCourse");
btnNewCourseClose = document.getElementById("dom__btn--newCourseClose");

//Bestand verwijderen modal
function loadDeleteButtons() {
  $(".dom__fileManager--deleteButton").on('click', function() {
    var file = $(this).parent().find("a").text().trim();
    modalDeleteFile.classList.remove("slideOutUp");
    modalDeleteFile.classList.add("slideInDown");
    modalDeleteFile.style.display = "flex"
    $("#dom__btn--deleteFileClose").on('click', function() {
      modalDeleteFile.classList.remove("slideInDown");
      modalDeleteFile.classList.add("slideOutUp");
      $("#dom__submit--deleteFile").unbind();
    });
    $("#dom__submit--deleteFile").on('click', function() {
      modalDeleteFile.classList.remove("slideInDown");
      modalDeleteFile.classList.add("slideOutUp");
      $("#dom__submit--deleteFile").unbind();
      $.ajax({
          url:"../php/actionsHome.php",
          type:"POST",
          datatype:"text",
          data: {deleteFile:1,file:file},
          success: function(data){
            if(data == 701) {
              alert("Er ging iets fout bij het verwijderen!");
            }else if(data == 403){
              alert("U heeft niet de juiste rechten om bestanden te verwijderen!");
            }else if(data == 501){
              alert("Er ging iets fout bij het ophalen van uw account gegevens!");
            }else{
              course(data);
            }
          }
      })
    });
  });
}


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

btnSubmitInviteUser.onclick = function() {

  var nickname = document.getElementById("dom__inviteUser--nickname").value;

  if(nickname != "") {
    $.ajax({
        url:"../php/actionsHome.php",
        type:"POST",
        datatype:"text",
        data: {nickname:nickname,inviteUser:1},
        success: function(){
          destroyCourseModals();
        }
    })
  }else{
  alert("Voer alle velden in!")
  }
}

//Delete user modal
btnkickUser.onclick = function() {
  modalkickUser.classList.remove("slideOutUp");
  modalkickUser.classList.add("slideInDown");
  modalkickUser.style.display = "flex"
}

btnkickUserClose.onclick = function() {
  modalkickUser.classList.remove("slideInDown");
  modalkickUser.classList.add("slideOutUp");
}

btnSubmitkickUser.onclick = function() {

  var nickname = document.getElementById("dom__kickUser--nickname").value;

  if(nickname != "") {
    $.ajax({
        url:"../php/actionsHome.php",
        type:"POST",
        datatype:"text",
        data: {nickname:nickname,deleteUser:1},
        success: function(){
          destroyCourseModals();
        }
    })
  }else{
  alert("Voer alle velden in!")
  }
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

//Delete group modal

btnDeleteGroup.onclick = function() {
  modalDeleteGroup.classList.remove("slideOutUp");
  modalDeleteGroup.classList.add("slideInDown");
  modalDeleteGroup.style.display = "flex"
}

btnDeleteGroupClose.onclick = function() {
  modalDeleteGroup.classList.remove("slideInDown");
  modalDeleteGroup.classList.add("slideOutUp");
}

btnSubmitDeleteGroup.onclick = function() {

    $.ajax({
        url:"../php/actionsHome.php",
        type:"POST",
        datatype:"text",
        data: {deleteGroup:1},
        success: function(data){
          destroyCourseModals();
          if(data == 200) {
            home();
          }else{
            alert(data);
          }
        }
    })
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



btnsubmitCr.onclick = function() {

  var crName = document.getElementById("crName").value;
  var crDescription = document.getElementById("crDescription").value;

  if(crName != "" && crDescription != "") {
    $.ajax({
        url:"../php/actionsHome.php",
        type:"POST",
        datatype:"text",
        data: {crName:crName,crDescription:crDescription},
        success: function(data){
          destroyCourseModals();
          courses(data);
        }
    })
  }else{
  alert("Voer alle velden in!")
  }
}



function destroyCourseModals() {
  modalLeaveGroup.style.display = "none";
  modalInviteUser.style.display = "none";
  modalMembers.style.display = "none";
  modalNewCourse.style.display = "none"
  modalkickUser.style.display = "none"
  modalDeleteGroup.style.display = "none"
}
