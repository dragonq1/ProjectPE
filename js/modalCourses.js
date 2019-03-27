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
          dataType:"json",
          data: {deleteFile:1,file:file},
          success: function(data){
            if(data.returnCode == 0) {
              course(data.output);
            }else{
              alert(data.returnCode);
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

  var inputNickname = document.getElementById("dom__inviteUser--nickname");
  var nickname = inputNickname.value;

  if(nickname != "") {
    $.ajax({
        url:"../php/actionsHome.php",
        type:"POST",
        dataType:"json",
        data: {nickname:nickname,inviteUser:1},
        success: function(data){
          if(data.returnCode == 0) {
            destroyCourseModals();
          }else{
            alert(data.returnCode);
          }
        }
    })
  }else{
  alert("Voer alle velden in!")
  }

  inputNickname.value = "";

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

  var inputNickname = document.getElementById("dom__kickUser--nickname");
  var nickname = inputNickname.value;

  if(nickname != "") {
    $.ajax({
        url:"../php/actionsHome.php",
        type:"POST",
        dataType:"json",
        data: {nickname:nickname,deleteUser:1},
        success: function(data){
          if(data.returnCode == 0) {
            destroyCourseModals();
          }else{
            alert(data.returnCode);
          }
        }
    })
  }else{
  alert("Voer alle velden in!")
  }

  inputNickname.value = "";

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
        dataType:"json",
        data: {deleteGroup:1},
        success: function(data){
          if(data.returnCode == 0) {
            destroyCourseModals();
            home();
          }else{
            destroyCourseModals();
            alert(data.returnCode);
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

  var crNameField = document.getElementById("crName");
  var crDescriptionField = document.getElementById("crDescription");

  var crName = crNameField.value;
  var crDescription = crDescriptionField.value;


  if(crName != "" && crDescription != "") {
    $.ajax({
        url:"../php/actionsHome.php",
        type:"POST",
        dataType:"json",
        data: {crName:crName,crDescription:crDescription},
        success: function(data){
          if(data.returnCode == 0) {
            courses(data.output);
          }else{
            alert(data.returnCode);
          }
          destroyCourseModals();
        }
    })
  }else{
    alert("Voer alle velden in!")
    // TODO: Van alert notificatie maken
  }

  crNameField.value = "";
  crDescription.value = "";

}



function destroyCourseModals() {
  modalLeaveGroup.style.display = "none";
  modalInviteUser.style.display = "none";
  modalMembers.style.display = "none";
  modalNewCourse.style.display = "none"
  modalkickUser.style.display = "none"
  modalDeleteGroup.style.display = "none"
}
