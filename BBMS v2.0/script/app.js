//checkbox - toggle papssword visibility
function myFunction(id) {
  var x = document.getElementById(id);
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}

//counter - animating numbers in home page
$(document).ready(function(){
  $(".counter").counterUp({
    delay: 10,
    time: 3000
  });
});
