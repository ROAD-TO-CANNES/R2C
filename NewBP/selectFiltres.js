// Already display first items
$(document).ready(function () {
  // When selectProg or selectMotClef changes
  $("#selectProg, #selectMotClef").change(function () {
    // Remove focus from the select element
    $(this).blur();
    // Select the first option
    $(this).find("option:first").prop("selected", true);
  });
});

// Select multiple items
let divIds = [];

function updateSelectedItems(selectId, selectedItemsDiv, optionValue = null) {
  const select = document.getElementById(selectId);
  let selectedOptions;

  if (optionValue) {
    // Filter options based on optionValue
    selectedOptions = Array.from(select.options).filter(
      (option) => option.value === optionValue
    );
  } else {
    // Get selected options
    selectedOptions = Array.from(select.selectedOptions);
  }

  // Determine the prefix based on the selectId
  let prefix;
  switch (selectId) {
    case "selectProg":
      prefix = "PR";
      break;
    case "selectMotClef":
      prefix = "MC";
      break;
    default:
      prefix = "error";
  }

  // Display selected items in the div
  selectedOptions.forEach((option) => {
    // Check if a div with the same id already exists
    const itemId = prefix + option.value;
    if (document.getElementById(prefix + option.value)) {
      return;
    } else {
      // Create a div for each selected item
      const itemDiv = document.createElement("div");
      itemDiv.className = "object";
      itemDiv.id = itemId;
      itemDiv.textContent = option.textContent;

      // Add the div's ID to the array
      divIds.push(itemId);

      // Add a button to remove the item
      const removeButton = document.createElement("button");
      removeButton.textContent = "✘";
      removeButton.addEventListener("click", () => {
        // Deselect the option
        select.options[option.index].selected = false;
        // Remove the div
        itemDiv.remove();

        // Remove the div's ID from the array
        const index = divIds.indexOf(itemId);
        if (index > -1) {
          divIds.splice(index, 1);
        }
      });

      itemDiv.appendChild(removeButton);
      selectedItemsDiv.appendChild(itemDiv);
    }
  });
}

// Listen for change event on selectProg
document.getElementById("selectProg").addEventListener("change", () => {
  updateSelectedItems(
    "selectProg",
    document.getElementById("selected-itemsProg")
  );
});

// Listen for change event on selectMotClef
document.getElementById("selectMotClef").addEventListener("change", () => {
  updateSelectedItems(
    "selectMotClef",
    document.getElementById("selected-itemsMotClef")
  );
});

$("#newBP-form").submit(function () {
  // Convertir le tableau divIds en une chaîne de caractères
  var divIdsString = JSON.stringify(divIds);
  // Stocker la chaîne dans le champ caché
  $("#divIds").val(divIdsString);
});
