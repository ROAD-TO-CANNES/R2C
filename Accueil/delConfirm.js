// When the user clicks on the corbeille icon, a confirmation message appears
$(".corbeille").on("click", function () {
  var id = $(this).attr("id");
  $("#" + id + ".delConfirm").css("display", "flex");
  $(".fond_delConfirm").css("display", "block");
});

// When the user clicks on the Annuler button, the confirmation message disappears
$(".delConfirm button[type=button]").on("click", function () {
  $(".delConfirm").css("display", "none");
  $(".fond_delConfirm").css("display", "none");
});

// When the user clicks outside the confirmation message, it disappears
$(".fond_delConfirm").on("click", function () {
  $(".delConfirm").css("display", "none");
  $(".fond_delConfirm").css("display", "none");
});
