//When the user clicks on the button "changer le mot de passe", the corresponding popup appears
document
  .getElementById("change-password-form")
  .addEventListener("submit", function (event) {
    event.preventDefault();
    fetch("../Forms/change_pswScript.php", {
      method: "POST",
      body: new FormData(event.target),
    })
      .then((response) => response.json())
      .then((data) => {
        switch (data) {
          case 0: // The password has been changed
            $("#change-password-form").css("display", "none");
            $(".valid").css("display", "flex");
            break;
          case 1: // The actual password is not correct
            $("#err1").addClass("act");
            break;
          case 2: // The password is not the same as the confirmation
            $("#err2").addClass("act");
            break;
          case 3: // The password is the same as the old one
            $("#err3").addClass("act");
            break;
          case 4: // The password is too short
            $("#err4").addClass("act");
            break;
          case 5: // The password contains few numbers
            $("#err5").addClass("act");
            break;
          case 6: // The password contains few special characters
            $("#err6").addClass("act");
            break;
          case 7: // The password contains few uppercase letters
            $("#err7").addClass("act");
            break;
          case 8: // The password does not contain the login
            $("#err8").addClass("act");
            break;
          case 9: // The password does not contain accented characters
            $("#err9").addClass("act");
            break;
          case 10: // The user is the SuperAdmin, redirect to an error page
            window.location.href =
              "../Validation/validation.php?message=epswsadmin";
            break;
          default:
            break;
        }
      });
  });

// When the user clicks on the button "annuler", all the error messages and the popup disappears
$(".annuler").on("click", function () {
  $("#err1").removeClass("act");
  $("#err2").removeClass("act");
  $("#err3").removeClass("act");
  $("#err4").removeClass("act");
  $("#err5").removeClass("act");
  $("#err6").removeClass("act");
  $("#err7").removeClass("act");
  $("#err8").removeClass("act");
  $("#err9").removeClass("act");
  $(".psw-popup").removeClass("active-fond");
  $(".fond_psw").removeClass("active-fond");
  document.getElementById("change-password-form").reset();
});

// When the user clicks on the button "valider", all the error messages disappear
$(".valider").on("click", function () {
  $("#err1").removeClass("act");
  $("#err2").removeClass("act");
  $("#err3").removeClass("act");
  $("#err4").removeClass("act");
  $("#err5").removeClass("act");
  $("#err6").removeClass("act");
  $("#err7").removeClass("act");
  $("#err8").removeClass("act");
  $("#err9").removeClass("act");
});

// When the user clicks on the button "ok", the popup disappears
$("#okbtn").on("click", function () {
  $(".valid").css("display", "none");
  $("#change-password-form").css("display", "flex");
  $(".psw-popup").removeClass("active-fond");
  $(".fond_psw").removeClass("active-fond");
  document.getElementById("change-password-form").reset();
});
