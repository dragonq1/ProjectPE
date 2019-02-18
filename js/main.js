  function courses(){
    $.ajax({
        url:"../php/courses.php",
        type:"POST",
        datatype:"text",
        success: function(data){
          $("#dom__interactive").html(data);
        }


    })



  }


  function home(userID){
    $.ajax({
    url:"../php/actionsHome.php",
    type:"POST",
    datatype:"text",
    data: {userID:userID, homeMenu:1},
    success: function(data){
      $("#dom__interactive").html(data);
      }
    })
  }

  function groups(userID){
    $.ajax({
    url:"../php/getGroups.php",
    type:"POST",
    datatype:"text",
    data: {userid:userID},
    success: function(data){
      // $("#dom__interactive--groups").html(data);
      }
    })
  }

function newGroup() {
  alert("Dialog box!");
}
