$(".changepswuser").on("click", function () {
  $(".popupPsw").css("display", "block");
  $(".fondpsw").css("display", "block");
  document.body.classList.add("noScroll");
});

$("#annulerusr").on("click", function () {
  $("#err2usr").removeClass("act");
  $("#err3usr").removeClass("act");
  $("#err4usr").removeClass("act");
  $("#err5usr").removeClass("act");
  $("#err6usr").removeClass("act");
  $("#err7usr").removeClass("act");
  $(".popupPsw").css("display", "none");
  $(".fondpsw").css("display", "none");
  document.getElementById("change-password-form-user").reset();
  document.body.classList.remove("noScroll");
});

$("#validerusr").on("click", function () {
  $("#err2usr").removeClass("act");
  $("#err3usr").removeClass("act");
  $("#err4usr").removeClass("act");
  $("#err5usr").removeClass("act");
  $("#err6usr").removeClass("act");
  $("#err7usr").removeClass("act");
});

$("#okbtnusr").on("click", function () {
  $("#validusr").css("display", "none");
  $("#change-password-form-user").css("display", "flex");
  $(".popupPsw").css("display", "none");
  $(".fondpsw").css("display", "none");
  document.getElementById("change-password-form-user").reset();
  document.body.classList.remove("noScroll");
});
