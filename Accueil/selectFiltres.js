// Already display first items
$(document).ready(function () {
  // When selectProg, selectPhase, or selectMotClef changes
  $("#selectProg, #selectPhase, #selectMotClef").change(function () {
    // Remove focus from the select element
    $(this).blur();
    // Select the first option
    $(this).find("option:first").prop("selected", true);
  });
});

// Select multiple items
let divIds = [];
// Element input avec l'ID "generate_pdf-phase"
let pdfInputPhase = document.getElementById("generate_pdf-phase");
// Element input avec l'ID "generate_csv-phase"
let csvInputPhase = document.getElementById("generate_csv-phase");
// Element input avec l'ID "generate_pdf-keyword"
let pdfInputKeyword = document.getElementById("generate_pdf-keyword");
// Element input avec l'ID "generate_csv-keyword"
let csvInputKeyword = document.getElementById("generate_csv-keyword");
// Element input avec l'ID "generate_pdf-prog"
let pdfInputProg = document.getElementById("generate_pdf-prog");
// Element input avec l'ID "generate_csv-prog"
let csvInputProg = document.getElementById("generate_csv-prog");

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
    case "selectPhase":
      prefix = "PH";
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
    if (
      document.getElementById(prefix + option.value) &&
      document
        .getElementById(prefix + option.value)
        .classList.contains("object")
    ) {
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

        // Update the input values
        switch (prefix) {
          case "PH":
            pdfInputPhase.value = pdfInputPhase.value.replace(
              " " + option.value,
              ""
            );
            csvInputPhase.value = csvInputPhase.value.replace(
              " " + option.value,
              ""
            );
            break;
          case "PR":
            pdfInputProg.value = pdfInputProg.value.replace(
              " " + option.value,
              ""
            );
            csvInputProg.value = csvInputProg.value.replace(
              " " + option.value,
              ""
            );
            break;
          case "MC":
            pdfInputKeyword.value = pdfInputKeyword.value.replace(
              " " + option.value,
              ""
            );
            csvInputKeyword.value = csvInputKeyword.value.replace(
              " " + option.value,
              ""
            );
            break;
          default:
            break;
        }
      });

      itemDiv.appendChild(removeButton);
      selectedItemsDiv.appendChild(itemDiv);

      // Update the input values
      switch (prefix) {
        case "PH":
          pdfInputPhase.value += " " + option.value;
          csvInputPhase.value += " " + option.value;
          break;
        case "PR":
          pdfInputProg.value += " " + option.value;
          csvInputProg.value += " " + option.value;
          break;
        case "MC":
          pdfInputKeyword.value += " " + option.value;
          csvInputKeyword.value += " " + option.value;
          break;
        default:
          break;
      }
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

// Listen for change event on selectPhase
document.getElementById("selectPhase").addEventListener("change", () => {
  updateSelectedItems(
    "selectPhase",
    document.getElementById("selected-itemsPhase")
  );
});

// Listen for change event on selectMotClef
document.getElementById("selectMotClef").addEventListener("change", () => {
  updateSelectedItems(
    "selectMotClef",
    document.getElementById("selected-itemsMotClef")
  );
});

// On page load, retrieve selected items from the cookie
$(document).ready(function () {
  // Find the cookie with the name "filtres"
  const cookie = document.cookie
    .split("; ")
    .find((row) => row.startsWith("filtres="));
  if (cookie) {
    // Parse the selected items from the cookie
    const selectedItems = JSON.parse(cookie.split("=")[1]);
    // Iterate over each selected item
    selectedItems.forEach((itemId) => {
      // Determine the prefix of the item
      const prefix = itemId.substring(0, 2);
      // Determine the selectId and selectedItemsDivId based on the prefix
      const selectId =
        prefix === "PR"
          ? "selectProg"
          : prefix === "PH"
          ? "selectPhase"
          : "selectMotClef";
      const selectedItemsDivId =
        prefix === "PR"
          ? "selected-itemsProg"
          : prefix === "PH"
          ? "selected-itemsPhase"
          : "selected-itemsMotClef";
      // Get the selectedItemsDiv element
      const selectedItemsDiv = document.getElementById(selectedItemsDivId);
      // Get the option value from the itemId
      const option = itemId.substring(2);

      // Call the updateSelectedItems function with the appropriate parameters
      updateSelectedItems(selectId, selectedItemsDiv, option);
    });
  }
});
