function home(){
if(typeof destroyCourseModals === "function"){
  destroyCourseModals();
}
  $.ajax({
  url:"../php/actionsHome.php",
  type:"POST",
  datatype:"text",
  data: {homeMenu:1},
  success: function(data){
    $("#dom__interactive").html(data);
    $.ajax({
    url:"../php/actionsHome.php",
    type:"POST",
    datatype:"text",
    data: {homeSidebar:1,homeMenu:1},
    success: function(data){
      $("#dom__sidebar--groups").html(data);
      }
    })
    }
  })
}

function courses(groupID){
  destroyModals();
  if(typeof destroyCourseModals === "function"){
    destroyCourseModals();
  }
  $.ajax({
      url:"../php/actionsHome.php",
      type:"POST",
      datatype:"text",
      data: {group:1,groupID:groupID},
      success: function(data){
      if(data == "403") {
        // TODO: Notificatie systeem
        alert("You don't have access to this group!");
      }else{
        $("#dom__interactive").html(data);
      }

      }
  })
}

function course(courseID){
  destroyModals();
  if(typeof destroyCourseModals === "function"){
    destroyCourseModals();
  }
  $.ajax({
      url:"../php/actionsHome.php",
      type:"POST",
      datatype:"text",
      data: {course:1,courseID:courseID},
      success: function(data){
      if(data == "403") {
        // TODO: Notificatie systeem
        alert("You don't have access to this course!");
      }else{
        $("#groups-mainbox").html(data);
      }

      }
  })
}

//file uploader voor vakken
function uploadFileCourse() {
  if(typeof destroyCourseModals === "function"){
    destroyCourseModals();
  }
  var file = $("#fileInputCourses").prop("files")[0];
  if(typeof file == "object") {
    if(file.size < 20971520) {
      var form_data = new FormData();
      form_data.append('file', file);
      $.ajax({
        url:"../php/fileUploader.php",
        type:"POST",
        datatype:"text",
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        success: function(data){
          course(data);
        }
      })
    }else{
      alert("Het bestand is te groot. \nBestanden mogen maximum 20Mb groot zijn.")
    }

  }
}

//Functie om account in te laden

function account() {
  destroyModals();
  if(typeof destroyCourseModals === "function"){
    destroyCourseModals();
  }
  $.ajax({
    url:"../php/Account.php",
    type:"POST",
    datatype:"text",
    data: {homeMenu:1},
    success: function(data){
      $("#dom__interactive").html(data);
    }
  })
}


function acceptInvite(inviteID){
  $.ajax({
  url:"../php/actionsHome.php",
  type:"POST",
  datatype:"text",
  data: {acceptInvite:1,inviteID:inviteID},
  success: function(data){
    home();
    }
  })
}

function declineInvite(inviteID){
  $.ajax({
  url:"../php/actionsHome.php",
  type:"POST",
  datatype:"text",
  data: {declineInvite:1,inviteID:inviteID},
  success: function(data){
    home();
    }
  })
}

function leaveGroup(){
  destroyCourseModals();
  $.ajax({
  url:"../php/actionsHome.php",
  type:"POST",
  datatype:"text",
  data: {leaveGroup:1},
  success: function(){
    home();
    }
  })
}

function getGroupMembers(){
  $.ajax({
  url:"../php/actionsHome.php",
  type:"POST",
  datatype:"text",
  data: {getGroupMembers:1},
  success: function(data){
    $("#dom__groupMembers").html(data);
    }
  })
}
