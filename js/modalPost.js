modalForumPost = document.getElementById("DOM__modal--newpost");
postTitle = document.getElementById("DOM__modal__newposttitle");
postmessage = document.getElementById("DOM__modal__newpostmessage");
submitpost = document.getElementById("DOM__modal__submitpost");
annuleerpostbtn =  document.getElementById('DOM__modal__annuleerpost');

newpostbtn = document.getElementById('DOM__new__post');




//New post modal
newpostbtn.onclick = function(){
modalForumPost.classList.remove("slideOutUp");
modalForumPost.classList.add("slideInDown");
modalForumPost.style.display = "flex"
}

annuleerpostbtn.onclick = function() {
  modalForumPost.classList.remove("slideInDown");
  modalForumPost.classList.add("slideOutUp");
  postTitle.value = "";
  postmessage.value = "";
}

submitpost.onclick = function() {

var ptitle = postTitle.value
var pmessage = postmessage.value

if(ptitle != "" && pmessage != ""){

    $.ajax({
        url:"../php/actionsHome.php",
        type:"POST",
        dataType:"json",
        data: {newpost:1,postmessage:pmessage,posttitle:ptitle},
        success: function(data){
          if(data.returnCode == 0) {
            modalForumPost.classList.remove("slideInDown");
            modalForumPost.classList.add("slideOutUp");
            postTitle.value = "";
            postmessage.value = "";

            destroyModals();
            home();
            //notify(); code voor message dat post is aangemaakt

          }else{
            notify(data.returnCode);
          }
        }
    })

 }else{
   //notify();
 }
}

function destroyModals() {
  modalForumPost.display = "none"
}
