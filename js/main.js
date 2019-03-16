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
      $("#dom__interactive").html(data);
      }
  })
}

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
