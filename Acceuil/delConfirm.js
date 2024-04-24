$(".corbeille").on("click", function () {
  var id = $(this).attr("id");
  $("#" + id + ".delConfirm").css("display", "flex");
  $(".fond_delConfirm").css("display", "block");
});

$(".delConfirm button[type=button]").on("click", function () {
  $(".delConfirm").css("display", "none");
  $(".fond_delConfirm").css("display", "none");
});

$(".fond_delConfirm").on("click", function () {
  $(".delConfirm").css("display", "none");
  $(".fond_delConfirm").css("display", "none");
});
