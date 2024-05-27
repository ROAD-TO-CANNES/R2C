// Créez un tableau vide pour stocker les valeurs
let checkedValues = [];
// Obtenez tous les éléments input avec le nom "checkedBp"
let inputs = document.querySelectorAll('input[name="checkedBp"]');
// Element input avec l'ID "generate_pdf"
let pdfInput = document.getElementById("generate_pdf");
// Element input avec l'ID "generate_csv"
let csvInput = document.getElementById("generate_csv");

// Exécutez le script lorsque le DOM est chargé
document.addEventListener("DOMContentLoaded", () => {
  // Parcourez tous les éléments input
  inputs.forEach((input) => {
    // Si l'input est coché, ajoutez sa valeur au tableau
    if (input.checked) {
      checkedValues.push(input.value);
    }
    // Ajoutez un écouteur d'événement "change" à chaque input
    input.addEventListener("change", () => {
      // Si l'input est coché, ajoutez sa valeur au tableau
      if (input.checked) {
        checkedValues.push(input.value);
      } else {
        // Si l'input est décoché, retirez sa valeur du tableau
        let index = checkedValues.indexOf(input.value);
        if (index > -1) {
          checkedValues.splice(index, 1);
        }
      }
      // Mettez à jour la valeur de generate_pdf avec le tableau checkedValues
      pdfInput.value = JSON.stringify(checkedValues);
      csvInput.value = JSON.stringify(checkedValues);
    });
  });
  pdfInput.value = JSON.stringify(checkedValues);
  csvInput.value = JSON.stringify(checkedValues);
});
