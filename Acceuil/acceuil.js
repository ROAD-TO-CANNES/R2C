$("#filtre").on("click", function () {
  $(".select-popup").addClass("active-popup");
  $(".select-fond_popup").addClass("active-fond");
});

$(".select-fond_popup").on("click", function () {
  $(".select-popup").removeClass("active-popup");
  $(".select-fond_popup").removeClass("active-fond");
});

// Already dysplay first items
$(document).ready(function () {
  $("#selectProg, #selectPhase, #selectMotClef").change(function () {
    $(this).blur();
    $(this).find("option:first").prop("selected", true);
  });
});

// Select multiple items
let divIds = [];

function updateSelectedItems(selectId, selectedItemsDiv) {
  const select = document.getElementById(selectId);
  const selectedOptions = Array.from(select.selectedOptions);

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

  // Affiche les éléments sélectionnés dans la div
  selectedOptions.forEach((option) => {
    // Vérifie si une div avec le même id existe déjà
    const itemId = prefix + option.value;
    if (document.getElementById(prefix + option.value)) {
      return;
    } else {
      // Crée une div pour chaque élément sélectionné
      const itemDiv = document.createElement("div");
      itemDiv.className = "object";
      itemDiv.id = itemId;
      itemDiv.textContent = option.textContent;

      // Ajoute l'ID de la div au tableau
      divIds.push(itemId);

      // Ajoute un bouton pour retirer l'élément
      const removeButton = document.createElement("button");
      removeButton.textContent = "✘";
      removeButton.addEventListener("click", () => {
        select.options[option.index].selected = false;
        itemDiv.remove();

        // Retire l'ID de la div du tableau
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

// Écoute l'événement de changement de sélection pour chaque liste déroulante
document.getElementById("selectProg").addEventListener("change", () => {
  updateSelectedItems(
    "selectProg",
    document.getElementById("selected-itemsProg")
  );
});

document.getElementById("selectPhase").addEventListener("change", () => {
  updateSelectedItems(
    "selectPhase",
    document.getElementById("selected-itemsPhase")
  );
});

document.getElementById("selectMotClef").addEventListener("change", () => {
  updateSelectedItems(
    "selectMotClef",
    document.getElementById("selected-itemsMotClef")
  );
});

// Annuler la sélection
$("#cancelBtn").on("click", function () {
  $(".object").remove();
  $(".select-popup").removeClass("active-popup");
  $(".select-fond_popup").removeClass("active-fond");
  divIds = [];
});

// Valider la sélection
$("#validBtn").on("click", function () {
  $("#divIdsInput").val(JSON.stringify(divIds));
  $("#hiddenForm").submit();
});
