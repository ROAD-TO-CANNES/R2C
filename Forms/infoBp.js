// Already display first items
$(document).ready(function () {
  // When selectProg or selectMotClef changes
  $(".selectProgInfo, .selectMotClefInfo").change(function () {
    // Remove focus from the select element
    $(this).blur();
    // Select the first option
    $(this).find("option:first").prop("selected", true);
  });
});

document.addEventListener("DOMContentLoaded", (event) => {
  // Get all selectProgInfo elements
  const selectProgInfos = document.querySelectorAll(".selectProgInfo");
  // Loop over each selectProgInfo element
  selectProgInfos.forEach((selectProgInfo) => {
    // Add change event listener to each selectProgInfo
    selectProgInfo.addEventListener("change", () => {
      updateItem(selectProgInfo, "PR");
    });
  });

  // Get all selectMotClefInfo elements
  const selectMotClefInfos = document.querySelectorAll(".selectMotClefInfo");
  // Loop over each selectMotClefInfo element
  selectMotClefInfos.forEach((selectMotClefInfo) => {
    // Add change event listener to each selectMotClefInfo
    selectMotClefInfo.addEventListener("change", () => {
      updateItem(selectMotClefInfo, "MC");
    });
  });
});

function updateItem(select, prefix, optionValue = null) {
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

  // Display selected items in the div
  selectedOptions.forEach((option) => {
    // Check if a div with the same id already exists
    const itemId = prefix + option.value;
    let selectedItemsDiv;
    if (prefix === "PR") {
      selectedItemsDiv = document.querySelector(
        `.selected-itemsDivProgInfo[id='${select.id}']`
      );
    } else if (prefix === "MC") {
      selectedItemsDiv = document.querySelector(
        `.selected-itemsDivMcInfo[id='${select.id}']`
      );
    }
    if (selectedItemsDiv.querySelector("#" + itemId)) {
      return;
    } else {
      // Create a div for each selected item
      const itemDiv = document.createElement("div");
      itemDiv.className = "objectInfo";
      itemDiv.id = itemId;
      itemDiv.textContent = option.textContent;

      // Add a button to remove the item
      const removeButton = document.createElement("button");
      removeButton.textContent = "✘";
      removeButton.addEventListener("click", () => {
        // Deselect the option
        select.options[option.index].selected = false;
        // Remove the div
        itemDiv.remove();

        // Send data to updateInfo.php
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "../Forms/updateInfo.php", true);
        xhr.setRequestHeader(
          "Content-Type",
          "application/x-www-form-urlencoded"
        );
        xhr.send(`selectId=${select.id}&itemId=${itemId}&action=remove`);
      });

      itemDiv.appendChild(removeButton);
      // Find the selectedItemsDiv with the same id as the select
      const matchingSelectedItemsDiv = document.querySelector(
        `.${selectedItemsDiv.className}[id='${select.id}']`
      );
      matchingSelectedItemsDiv.appendChild(itemDiv);

      // Send data to updateInfo.php
      const xhr = new XMLHttpRequest();
      xhr.open("POST", "../Forms/updateInfo.php", true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.send(`selectId=${select.id}&itemId=${itemId}&action=add`);
    }
  });
}

window.onload = function () {
  // Select all delete buttons in .objectInfo divs
  var deleteButtons = document.querySelectorAll(".objectInfo .delete-item");

  // Add event listener to each button
  deleteButtons.forEach(function (button) {
    button.addEventListener("click", function () {
      // Send data to updateInfo.php
      const xhr = new XMLHttpRequest();
      xhr.open("POST", "../Forms/updateInfo.php", true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.send(
        `selectId=${this.parentNode.parentNode.id}&itemId=${this.parentNode.id}&action=remove`
      );
      // Remove the parent .objectInfo div when the button is clicked
      this.parentNode.remove();
    });
  });
};
