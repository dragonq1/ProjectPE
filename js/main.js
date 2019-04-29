function home(){
if(typeof destroyCourseModals === "function"){
  destroyCourseModals();
}
  $.ajax({
    url:"../php/actionsHome.php",
    type:"POST",
    dataType:"json",
    data: {homeMenu:1},
    success: function(data){
      if(data.returnCode == 0) {
        $("#dom__interactive").html(data.output);
      }else{
        notify(data.returnCode);
      }
    }
  })

  $.ajax({
    url:"../php/actionsHome.php",
    type:"POST",
    dataType:"json",
    data: {homeSidebar:1,homeMenu:1},
    success: function(data){
      if(data.returnCode == 0) {
        $("#dom__sidebar--groups").html(data.output);
      }else{
        notify(data.returnCode);
      }
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
      dataType:"json",
      data: {group:1,groupID:groupID},
      success: function(data){
        if(data.returnCode == 0) {
          $("#dom__interactive").html(data.output);
        }else{
          notify(data.returnCode);
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
      dataType:"json",
      data: {course:1,courseID:courseID},
      success: function(data){
        if(data.returnCode == 0) {
          $("#groups-mainbox").html(data.output);
          loadDeleteButtons();
        }else{
          notify(data.returnCode);
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
  $("#fileInputCourses").val("");
  if(typeof file == "object") {
    if(file.size < 20971520) {
      var form_data = new FormData();
      form_data.append('file', file);
      $.ajax({
        url:"../php/fileUploader.php",
        type:"POST",
        dataType:"json",
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        success: function(data){
          if(data.returnCode == 0) {
            course(data.output);
            notify(805);
          }else{
            notify(data.returnCode);
          }
        }
      })
    }else{
      notify(804);
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
    url:"../php/actionsHome.php",
    type:"POST",
    dataType:"json",
    data: {account:1},
    success: function(data){
      if(data.returnCode == 0) {
        $("#dom__interactive").html(data.output);
      }else{
        notify(data.returnCode);
      }
    }
  })
}


function acceptInvite(inviteID){
  $.ajax({
  url:"../php/actionsHome.php",
  type:"POST",
  dataType:"json",
  data: {acceptInvite:1,inviteID:inviteID},
  success: function(data){
    if(data.returnCode == 0) {
      notify(503);
      home();
    }else{
      notify(data.returnCode);
    }
    }
  })
}

function declineInvite(inviteID){
    $.ajax({
    url:"../php/actionsHome.php",
    type:"POST",
    dataType:"json",
    data: {declineInvite:1,inviteID:inviteID},
    success: function(data){
      if(data.returnCode == 0) {
        home();
        notify(509);
      }else{
        notify(data.returnCode);
      }
    }
  })
}

function leaveGroup(){
  destroyCourseModals();
  $.ajax({
    url:"../php/actionsHome.php",
    type:"POST",
    dataType:"json",
    data: {leaveGroup:1},
    success: function(data){
      if(data.returnCode == 0) {
        home();
        notify(906);
      }else{
        notify(data.returnCode);
      }
    }
  })
}

function getGroupMembers(){
    $.ajax({
    url:"../php/actionsHome.php",
    type:"POST",
    dataType:"json",
    data: {getGroupMembers:1},
    success: function(data){
      if(data.returnCode == 0) {
      $("#dom__groupMembers").html(data.output);
      }else{
        notify(data.returnCode);
      }
    }
  })
}
//forum inladen

function forum() {
  destroyModals();
  if(typeof destroyCourseModals === "function"){
    destroyCourseModals();
  }
  $.ajax({
    url:"../php/actionsHome.php",
    type:"POST",
    dataType:"json",
    data: {forum:1},
    success: function(data){
      if(data.returnCode == 0) {
        $("#dom__interactive").html(data.output);
      }else{
        notify(data.returnCode);
      }
    }
  })
}
//subcategorien inladen
function forum_subcat(){
  destroyModals();
  if(typeof destroyCourseModals === "function"){
    destroyCourseModals();
  }
  $.ajax({
    url:"../php/actionsHome.php",
    type:"POST",
    dataType:"json",
    data: {forumsub:1},
    success: function(data){
      if(data.returnCode == 0) {
        $("#dom__interactive").html(data.output);
      }else{
        notify(data.returnCode);
      }
    }
  })
}
//Uitloggen
function logout() {
  $.ajax({
    url:"../php/logout.php",
    type:"POST",
    dataType:"json",
    success: function(data){
      if(data.returnCode == 0) {
        window.location.href = "../index.php";
      }else{
        notify(data.returnCode);
      }
    }
  })
}
