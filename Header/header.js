// Auth popup
$(".connected_pp").on("click", function () {
  $(".auth-popup").addClass("active");
  $(".fond_popup").addClass("active-fond");
});

$(".auth-popup .close").on("click", function () {
  $(".auth-popup").removeClass("active");
  $(".fond_popup").removeClass("active-fond");
});

$(".fond_popup").on("click", function () {
  $(".auth-popup").removeClass("active");
  $(".fond_popup").removeClass("active-fond");
});

// Change password popup
$(".changepsw").on("click", function () {
  $(".psw-popup").addClass("active-fond");
  $(".fond_psw").addClass("active-fond");
  document.body.classList.add("noScroll");
});

$(".annuler").on("click", function () {
  document.body.classList.remove("noScroll");
});

$("#okbtn").on("click", function () {
  document.body.classList.remove("noScroll");
});

// Logo redirect to acceuil
$(".logo").on("click", function () {
  window.location.href = "../index.php";
});
