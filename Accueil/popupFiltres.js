// When filter button is clicked display the popup and the background
$(".filtre").on("click", function () {
  $(".select-popup").addClass("active-popup");
  $(".select-fond_popup").addClass("active-fond");
});

// When the user clicks outside of the popup, it disappears
$(".select-fond_popup").on("click", function () {
  $(".select-popup").removeClass("active-popup");
  $(".select-fond_popup").removeClass("active-fond");
});

// When the user clicks on the clear filter button, remove all objects and clear the divIds array
$("#rmFiltreBtn").on("click", function () {
  $(".object").remove();
  // Clear the divIds array
  divIds = [];
});

// When the user clicks on the cancel button, the popup disappears
$("#cancelBtn").on("click", function () {
  $(".select-popup").removeClass("active-popup");
  $(".select-fond_popup").removeClass("active-fond");
});

// When the user clicks on the valid button, set a cookie with the divIds array and reload the page
$("#validBtn").on("click", function () {
  document.cookie = "filtres=" + JSON.stringify(divIds) + ";path=/";
  location.reload();
});
