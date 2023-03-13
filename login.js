$(document).ready(function () {
  $("#login-form").validate();
  $("#alert").hide();

  $("#login").click(function (e) {
    console.log("login clicked");
    if (document.getElementById("login-form").checkValidity()) {
      console.log("im runing");
      e.preventDefault();
      $.ajax({
        url: "php/login.php",
        method: "post",
        data: $("#login-form").serialize() + "&action=login",
        success: function (response) {
          console.log(response);
          if (response != "Login Failed check your email and password !") {
            localStorage.setItem("Auth", response);
            window.location = "profile.html";
          } else {
            $("#alert").show();
            $("#result").html(response);
          }
        },
      });
    }
    return true;
  });
});
