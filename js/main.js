function home(){
  $.ajax({
  url:"../php/actionsHome.php",
  type:"POST",
  datatype:"text",
  data: {homeMenu:1},
  success: function(data){
    $("#dom__interactive").html(data);
    }
  })
}

function courses(groupID){
  destroyModals();
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
