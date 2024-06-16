// Send a POST request to enableDisableBP.php when a switch-case is clicked
$(".switch-case").on("click", function () {
  // Get the ID of the clicked switch-case
  var id = $(this).attr("id");

  // Send a POST request to enableDisableBP.php with the ID
  $.post("enableDisableBP.php", { id: id });
});
