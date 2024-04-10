document
  .getElementById("change-password-form")
  .addEventListener("submit", function (event) {
    event.preventDefault();
    fetch("../Forms/change_psw.php", {
      method: "POST",
      body: new FormData(event.target),
    })
      .then((response) => response.json())
      .then((data) => {
        switch (data) {
          case 0:
            $("#change-password-form").css("display", "none");
            $(".valid").css("display", "flex");
            break;
          case 1:
            $("#err1").addClass("act");
            break;
          case 2:
            $("#err2").addClass("act");
            break;
          case 3:
            $("#err3").addClass("act");
            break;
          case 4:
            $("#err4").addClass("act");
            break;
          case 5:
            $("#err5").addClass("act");
            break;
          case 6:
            $("#err6").addClass("act");
            break;
          case 7:
            $("#err7").addClass("act");
            break;
          default:
            break;
        }
      });
  });

// Change password popup
$(".annuler").on("click", function () {
  $("#err1").removeClass("act");
  $("#err2").removeClass("act");
  $("#err3").removeClass("act");
  $("#err4").removeClass("act");
  $("#err5").removeClass("act");
  $("#err6").removeClass("act");
  $("#err7").removeClass("act");
  $(".psw-popup").removeClass("active-fond");
  $(".fond_psw").removeClass("active-fond");
  document.getElementById("change-password-form").reset();
});

$(".valider").on("click", function () {
  $("#err1").removeClass("act");
  $("#err2").removeClass("act");
  $("#err3").removeClass("act");
  $("#err4").removeClass("act");
  $("#err5").removeClass("act");
  $("#err6").removeClass("act");
  $("#err7").removeClass("act");
});

$("#okbtn").on("click", function () {
  $(".valid").css("display", "none");
  $("#change-password-form").css("display", "flex");
  $(".psw-popup").removeClass("active-fond");
  $(".fond_psw").removeClass("active-fond");
  document.getElementById("change-password-form").reset();
});
