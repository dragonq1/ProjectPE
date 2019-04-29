
$(document).ready(function(){

$("#easterbtn").click(function(){
  console.log("test");
    $("#draak").animate(
    {
      height: "99%",
      width: "150%",
      display: "inherit"},2000,function(){$("#draak").hide()});
    })
});
