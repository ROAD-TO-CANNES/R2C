// When the user clicks on the button "voir la bonne pratique", the corresponding popup appears
$(".infobtn").on("click", function () {
  var id = $(this).attr("id");
  $("#" + id + ".infopopup").css("display", "flex");
  $(".fond_delConfirm").css("display", "block");
});

// When the user clicks on the button "fermer", the popup disappears
$(".closeInfoBp").on("click", function () {
  $(".infopopup").css("display", "none");
  $(".fond_delConfirm").css("display", "none");
});

// When the user clicks outside the popup, it disappears
$(".fond_delConfirm").on("click", function () {
  $(".infopopup").css("display", "none");
  $(".fond_delConfirm").css("display", "none");
});
