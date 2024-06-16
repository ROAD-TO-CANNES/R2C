// Save the state of the form in the local storage after reloading the page
$("#formMotClef").on("submit", function () {
  $("input, select, textarea").each(function () {
    var inputType =
      this.tagName.toUpperCase() === "INPUT" ? this.type.toUpperCase() : "";
    if (
      inputType !== "CHECKBOX" &&
      inputType !== "RADIO" &&
      inputType !== "FILE"
    ) {
      localStorage.setItem(this.name, this.value);
    } else if (inputType === "CHECKBOX" || inputType === "RADIO") {
      localStorage.setItem(this.name, this.checked);
    }
  });
  localStorage.removeItem("newMotClef"); // delete the previous value of the newMotClef
});

// Restore the state of the form after reloading the page
$(document).ready(function () {
  $("input, select, textarea").each(function () {
    var value = localStorage.getItem(this.name);
    if (value !== null) {
      if (
        this.type.toUpperCase() === "CHECKBOX" ||
        this.type.toUpperCase() === "RADIO"
      ) {
        this.checked = value === "true";
      } else {
        this.value = value;
      }
    }
  });
});

// On page load, retrieve selected items from the cookie
$(document).ready(function () {
  // Find the cookie with the name "selection"
  const cookie = document.cookie
    .split("; ")
    .find((row) => row.startsWith("selection="));
  if (cookie) {
    // Parse the selected items from the cookie
    const selectedItems = JSON.parse(decodeURIComponent(cookie.split("=")[1]));
    // Iterate over each selected item
    selectedItems.forEach((itemId) => {
      // Determine the prefix of the item
      const prefix = itemId.substring(0, 2);
      // Determine the selectId and selectedItemsDivId based on the prefix
      const selectId = prefix === "PR" ? "selectProg" : "selectMotClef";
      const selectedItemsDivId =
        prefix === "PR" ? "selected-itemsProg" : "selected-itemsMotClef";
      // Get the selectedItemsDiv element
      const selectedItemsDiv = document.getElementById(selectedItemsDivId);
      // Get the option value from the itemId
      const option = itemId.substring(2);

      // Call the updateSelectedItems function with the appropriate parameters
      updateSelectedItems(selectId, selectedItemsDiv, option);
    });
  }
});
// Delete a cookie
function deleteCookie(name) {
  document.cookie = name + "=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;";
}

// Delete the cookie "selection" after submitting the form
$("#newBP-form").on("submit", function () {
  localStorage.clear();
  deleteCookie("selection");
});

// Delete the cookie "selection" after clicking on the cancel button or the logo
$(".annulerBP, .logo").on("click", function () {
  localStorage.clear();
  deleteCookie("selection");
});

// Save the selected items in the cookie "selection" after clicking on the add button
$("#addMotClef").on("click", function () {
  document.cookie = "selection=" + JSON.stringify(divIds) + ";path=/";
});
