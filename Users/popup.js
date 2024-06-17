// When the user clicks on the cancel button, all the error messages and the popup disappears
$("#annulerusr").on("click", function () {
  $("#err2usr").removeClass("act");
  $("#err3usr").removeClass("act");
  $("#err4usr").removeClass("act");
  $("#err5usr").removeClass("act");
  $("#err6usr").removeClass("act");
  $("#err7usr").removeClass("act");
  $("#err8usr").removeClass("act");
  $("#err9usr").removeClass("act");
  $(".popupPsw").css("display", "none");
  $(".fondpsw").css("display", "none");
  document.getElementById("change-password-form-user").reset();
  document.body.classList.remove("noScroll");
});

// When the user clicks on the button "validate", all the error messages disappear
$("#validerusr").on("click", function () {
  $("#err2usr").removeClass("act");
  $("#err3usr").removeClass("act");
  $("#err4usr").removeClass("act");
  $("#err5usr").removeClass("act");
  $("#err6usr").removeClass("act");
  $("#err7usr").removeClass("act");
  $("#err8usr").removeClass("act");
  $("#err9usr").removeClass("act");
});

// When the user clicks on the button "ok", the popup disappears
$("#okbtnusr").on("click", function () {
  $("#validusr").css("display", "none");
  $("#change-password-form-user").css("display", "flex");
  $(".popupPsw").css("display", "none");
  $(".fondpsw").css("display", "none");
  document.getElementById("change-password-form-user").reset();
  document.body.classList.remove("noScroll");
});
