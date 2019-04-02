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
          $("#groups-mainbox").append(data.output);
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

//stijl voor notificaties
function notifiStyles() {
  $.notify.addStyle('success', {
  html: "<div>✔ <span data-notify-text/></div>",
  classes: {
    base: {
      "background-color": "#2e7d32",
      "padding": "10px",
      "color": "white",
      "border-radius": "3px"
    }
  }
  });
  $.notify.addStyle('error', {
  html: "<div>✕ <span data-notify-text/></div>",
  classes: {
    base: {
      "background-color": "#c63f17",
      "padding": "10px",
      "color": "white",
      "border-radius": "3px"
    }
  }
  });

  $.notify.addStyle('warning', {
  html: "<div>✕ <span data-notify-text/></div>",
  classes: {
    base: {
      "background-color": "#f9a825",
      "padding": "10px",
      "color": "white",
      "border-radius": "3px"
    }
  }
  });
}

function notify(returnCode) {

  switch(returnCode) {
    case 401:
      $.notify("Er ging iets fout! #401", {
        style: "error",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 402:
      $.notify("Er ging iets fout! #402", {
        style: "error",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 501:
      $.notify("Er ging iets fout! #501", {
        style: "error",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 502:
      $.notify("Er ging iets fout! #502", {
        style: "error",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 503:
      $.notify("Uitnodiging geaccepteerd!", {
        style: "success",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 504:
      $.notify("Je kan geen uitnodiging naar jezelf sturen!", {
        style: "error",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 505:
      $.notify("Deze gebruiker zit al in deze groep!", {
        style: "error",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 506:
      $.notify("Deze gebruiker heeft al uitnodiging!", {
        style: "error",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 507:
      $.notify("Deze gebruiker bestaat niet!", {
        style: "error",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 508:
      $.notify("Uitnodiging verstuurd!", {
        style: "success",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 509:
      $.notify("Uitnodiging geweigerd!", {
        style: "success",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 601:
      $.notify("Er ging iets fout! #601", {
        style: "warning",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 700:
      $.notify("Je hebt niet genoeg rechten!", {
        style: "warning",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 701:
      $.notify("Vul alle velden in!", {
        style: "warning",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 801:
      $.notify("Er ging iets fout! #801", {
        style: "warning",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 802:
      $.notify("Er ging iets fout! #802", {
        style: "error",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 803:
      $.notify("Een bestand met dezelfde naam bestaat al!", {
        style: "warning",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 804:
      $.notify("Het bestand is te groot. De max. grote is 20MB!", {
        style: "warning",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 805:
      $.notify("Het bestand is geüpload!", {
        style: "success",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 806:
      $.notify("Het bestand werd verwijderd!", {
        style: "success",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 901:
      $.notify("Deze gebruiker bestaat niet of zit niet in de groep!", {
        style: "warning",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 902:
      $.notify("Je kan jezelf niet verwijderen uit de groep!", {
        style: "warning",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 903:
      $.notify("Fout bij ophalen van uw rechten!", {
        style: "error",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 904:
      $.notify("Je kan de groep niet verlaten als eigenaar!", {
        style: "warning",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 905:
      $.notify("Er ging iets fout! #905", {
        style: "error",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 906:
      $.notify("Je hebt de groep verlaten.", {
        style: "success",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 907:
      $.notify("Gebruiker is verwijderd uit de groep!", {
        style: "success",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 908:
      $.notify("Groep verwijderd!", {
        style: "success",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 909:
      $.notify("Vak aangemaakt!", {
        style: "success",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 910:
      $.notify("Groep aangemaakt!", {
        style: "success",
        autoHide: true,
        clickToHide: true
      });
      break;
    // TODO:
    //VERANDEREN NAAR STANDAARD BERICHT BIJ RELEASE -> ERROR GAAN ANDERS DOOR NAAR FRONTEND
    default:
      alert(returnCode);
      break;
  }

}
