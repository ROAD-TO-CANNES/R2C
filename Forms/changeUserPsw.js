//When the user clicks on the button "changer le mot de passe", the corresponding popup appears
document
  .getElementById("change-password-form-user")
  .addEventListener("submit", function (event) {
    event.preventDefault();
    fetch("../Forms/change_pswScript.php", {
      method: "POST",
      body: new FormData(event.target),
    })
      .then((response) => response.json())
      .then((data) => {
        console.log(data);
        switch (data) {
          case 0: // The password has been changed
            $("#change-password-form-user").css("display", "none");
            $("#validusr").css("display", "flex");
            break;
          case 2: // The password is not the same as the confirmation
            $("#err2usr").addClass("act");
            break;
          case 3: // The password is the same as the old one
            $("#err3usr").addClass("act");
            break;
          case 4: // The password is too short
            $("#err4usr").addClass("act");
            break;
          case 5: // The password contains few numbers
            $("#err5usr").addClass("act");
            break;
          case 6: // The password contains few special characters
            $("#err6usr").addClass("act");
            break;
          case 7: // The password contains few uppercase letters
            $("#err7usr").addClass("act");
            break;
          case 8: // The password does not contain the login
            $("#err8usr").addClass("act");
            break;
          case 9: // The password does not contain accented characters
            $("#err9usr").addClass("act");
            break;
          case 10: // The user is the SuperAdmin, redirect to an error page
            window.location.href =
              "../Validation/validation.php?message=epswsadmin";
            break;
          case 11: // The user is not in the database, redirect to an error page
            window.location.href =
              "../Validation/validation.php?message=epswuserinexist";
            break;
          default:
            break;
        }
      });
  });
