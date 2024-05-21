$(".infobtn").on("click", function () {
  var id = $(this).attr("id");
  $("#" + id + ".infopopup").css("display", "flex");
  $(".fond_delConfirm").css("display", "block");
});

$(".infopopup button[type=button]").on("click", function () {
  $(".infopopup").css("display", "none");
  $(".fond_delConfirm").css("display", "none");
});

$(".fond_delConfirm").on("click", function () {
  $(".infopopup").css("display", "none");
  $(".fond_delConfirm").css("display", "none");
});
