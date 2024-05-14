// Sauvegarder l'état du formulaire dans le stockage local avant de recharger la page
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
      localStorage.setItem(this.name, this.checked); // Sauvegarder l'état de la checkbox
    }
  });
  localStorage.removeItem("newMotClef"); // Supprimer la valeur de 'newMotClef' du stockage local
});

// Restaurer l'état du formulaire à partir du stockage local après le rechargement de la page
$(document).ready(function () {
  $("input, select, textarea").each(function () {
    var value = localStorage.getItem(this.name);
    if (value !== null) {
      if (
        this.type.toUpperCase() === "CHECKBOX" ||
        this.type.toUpperCase() === "RADIO"
      ) {
        this.checked = value === "true"; // Restaurer l'état de la checkbox
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
// Supprimer un cookies
function deleteCookie(name) {
  document.cookie = name + "=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;";
}

// Supprimer les éléments du stockage local après la soumission du formulaire
$("#newBP-form").on("submit", function () {
  localStorage.clear();
  deleteCookie("selection");
});

// Supprimer les éléments du stockage local après l'annulation du formulaire
$(".annulerBP, .logo").on("click", function () {
  localStorage.clear();
  deleteCookie("selection");
});

// Sauvegarder les divs sélectionnés dans le cookies "selection"
$("#addMotClef").on("click", function () {
  document.cookie = "selection=" + JSON.stringify(divIds) + ";path=/";
});
