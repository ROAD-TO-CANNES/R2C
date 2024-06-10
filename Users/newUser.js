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
          case 0:
            $("#errusr0").css("display", "block");
            break;
          case 1:
            $("#errusr1").css("display", "block");
            break;
          case 2:
            $("#errusr2").css("display", "block");
            break;
          case 3:
            $("#errusr3").css("display", "block");
            break;
          case 4:
            $("#errusr4").css("display", "block");
            break;
          case 5:
            $("#errusr5").css("display", "block");
            break;
          case 6:
            $("#errusr6").css("display", "block");
            break;
          case 7:
            document.getElementById("newUser-form").reset();
            window.location.href = "../Users/users.php";
          default:
            break;
        }
      });
  });

// Change password popup
$(".annulerUser").on("click", function () {
  $("#errusr0").css("display", "none");
  $("#errusr1").css("display", "none");
  $("#errusr2").css("display", "none");
  $("#errusr3").css("display", "none");
  $("#errusr4").css("display", "none");
  $("#errusr5").css("display", "none");
  $("#errusr6").css("display", "none");
  document.getElementById("newUser-form").reset();
});

$("#enregistrerUser").on("click", function () {
  $("#errusr0").css("display", "none");
  $("#errusr1").css("display", "none");
  $("#errusr2").css("display", "none");
  $("#errusr3").css("display", "none");
  $("#errusr4").css("display", "none");
  $("#errusr5").css("display", "none");
  $("#errusr6").css("display", "none");
});
