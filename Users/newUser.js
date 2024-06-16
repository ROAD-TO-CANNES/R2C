// When the form is submitted, do the right action
document
  .getElementById("newUser-form")
  .addEventListener("submit", function (event) {
    event.preventDefault();
    fetch("../Forms/newUserScript.php", {
      method: "POST",
      body: new FormData(event.target),
    })
      .then((response) => response.json())
      .then((data) => {
        switch (data) {
          case 0: //The password does not match the confirmation
            $("#errusr0").css("display", "block");
            break;
          case 1: // Password too short
            $("#errusr1").css("display", "block");
            break;
          case 2: // The password contains few numbers
            $("#errusr2").css("display", "block");
            break;
          case 3: // The password contains few special characters
            $("#errusr3").css("display", "block");
            break;
          case 4: // The password contains few uppercase letters
            $("#errusr4").css("display", "block");
            break;
          case 5: // The password does not contain the login
            $("#errusr5").css("display", "block");
            break;
          case 6: // The password does not contain accented characters
            $("#errusr6").css("display", "block");
            break;
          case 7: // The user has been created redirect to the users page
            document.getElementById("newUser-form").reset();
            window.location.href = "../Users/users.php";
            break;
          case 8: // The user already exists
            $("#errusr7").css("display", "block");
            break;
          case 9: // The username contains special characters or spaces
            $("#errusr8").css("display", "block");
            break;
          default:
            break;
        }
      });
  });

//When the user clicks on the button "annuler", all the error messages and the popup disappears
$(".annulerUser").on("click", function () {
  $("#errusr0").css("display", "none");
  $("#errusr1").css("display", "none");
  $("#errusr2").css("display", "none");
  $("#errusr3").css("display", "none");
  $("#errusr4").css("display", "none");
  $("#errusr5").css("display", "none");
  $("#errusr6").css("display", "none");
  $("#errusr7").css("display", "none");
  $("#errusr8").css("display", "none");
  document.getElementById("newUser-form").reset();
});

//When the user clicks on the button "enregistrer", all the error messages disappear
$("#enregistrerUser").on("click", function () {
  $("#errusr0").css("display", "none");
  $("#errusr1").css("display", "none");
  $("#errusr2").css("display", "none");
  $("#errusr3").css("display", "none");
  $("#errusr4").css("display", "none");
  $("#errusr5").css("display", "none");
  $("#errusr6").css("display", "none");
  $("#errusr7").css("display", "none");
  $("#errusr8").css("display", "none");
});
