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

function newGroup() {
  alert("Script voor nieuwe groep");
}
