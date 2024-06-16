// Create a div for each selected item
let checkedValues = [];
// Get all input elements with the name "checkedBp"
let inputs = document.querySelectorAll('input[name="checkedBp"]');
// Get the input elements for the PDF and CSV generation
let pdfInput = document.getElementById("generate_pdf");
let csvInput = document.getElementById("generate_csv");

// When the DOM is fully loaded
document.addEventListener("DOMContentLoaded", () => {
  // Loop through each input element
  inputs.forEach((input) => {
    // If the input is checked, add its value to the checkedValues array
    if (input.checked) {
      checkedValues.push(input.value);
    }
    // When the input changes
    input.addEventListener("change", () => {
      // If the input is checked, add its value to the checkedValues array
      if (input.checked) {
        checkedValues.push(input.value);
      } else {
        // If the input is unchecked, remove its value from the checkedValues array
        let index = checkedValues.indexOf(input.value);
        if (index > -1) {
          checkedValues.splice(index, 1);
        }
      }
      // Update the input values when the input changes
      pdfInput.value = JSON.stringify(checkedValues);
      csvInput.value = JSON.stringify(checkedValues);
    });
  });
  // Update the input values when the DOM is fully loaded
  pdfInput.value = JSON.stringify(checkedValues);
  csvInput.value = JSON.stringify(checkedValues);
});
