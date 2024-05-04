$("#filtre").on("click", function () {
  // Add "active-popup" class to select-popup element
  $(".select-popup").addClass("active-popup");
  // Add "active-fond" class to select-fond_popup element
  $(".select-fond_popup").addClass("active-fond");
});

$(".select-fond_popup").on("click", function () {
  // Remove "active-popup" class from select-popup element
  $(".select-popup").removeClass("active-popup");
  // Remove "active-fond" class from select-fond_popup element
  $(".select-fond_popup").removeClass("active-fond");
});

// Cancel selection
$("#cancelBtn").on("click", function () {
  // Remove all object divs
  $(".object").remove();
  // Remove "active-popup" class from select-popup element
  $(".select-popup").removeClass("active-popup");
  // Remove "active-fond" class from select-fond_popup element
  $(".select-fond_popup").removeClass("active-fond");
  // Clear the divIds array
  divIds = [];
});

// Validate selection
$("#validBtn").on("click", function () {
  // Set a cookie with the name "filtres" and the value of the stringified divIds array
  document.cookie = "filtres=" + JSON.stringify(divIds) + ";path=/";
  // Reload the page
  location.reload();
});
