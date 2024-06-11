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
          case 0:
            $("#change-password-form-user").css("display", "none");
            $("#validusr").css("display", "flex");
            break;
          case 2:
            $("#err2usr").addClass("act");
            break;
          case 3:
            $("#err3usr").addClass("act");
            break;
          case 4:
            $("#err4usr").addClass("act");
            break;
          case 5:
            $("#err5usr").addClass("act");
            break;
          case 6:
            $("#err6usr").addClass("act");
            break;
          case 7:
            $("#err7usr").addClass("act");
            break;
          case 8:
            $("#err8usr").addClass("act");
            break;
          case 9:
            $("#err9usr").addClass("act");
            break;
          case 10:
            window.location.href =
              "../Validation/validation.php?message=epswsadmin";
            break;
          case 11:
            window.location.href =
              "../Validation/validation.php?message=epswuserinexist";
            break;
          default:
            break;
        }
      });
  });
