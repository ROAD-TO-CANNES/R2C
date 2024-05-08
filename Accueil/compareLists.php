<?php
  function compare_lists($list1, $list2, $list3, $tableau, $bpsProg, $bpsMC) {
    $resultat = array(); // Initialize an empty array to store the result

    foreach ($tableau as $element) { // Iterate through each element in the $tableau array
      $element['idprog'] = array(); // Initialize an empty array to store the matching program IDs for the current element

      foreach ($bpsProg as $prog) { // Iterate through each program in the $bpsProg array
        if ($element['idbp'] == $prog['idbp']) { // Check if the current element's 'idbp' matches the program's 'idbp'
          $element['idprog'][] = $prog['idprog']; // Add the program's 'idprog' to the current element's 'idprog' array
        }
      }

      $progMatch = empty($list1) || !empty(array_intersect($element['idprog'], $list1)); // Check if the current element's 'idprog' array has any intersection with $list1

      $element['idmotclef'] = array(); // Initialize an empty array to store the matching keyword IDs for the current element

      foreach ($bpsMC as $mc) { // Iterate through each keyword in the $bpsMC array
        if ($element['idbp'] == $mc['idbp']) { // Check if the current element's 'idbp' matches the keyword's 'idbp'
          $element['idmotclef'][] = $mc['idmotclef']; // Add the keyword's 'idmotclef' to the current element's 'idmotclef' array
        }
      }

      $motclefMatch = empty($list3) || !empty(array_intersect($element['idmotclef'], $list3)); // Check if the current element's 'idmotclef' array has any intersection with $list3

      $phaseMatch = empty($list2) || in_array($element['phase'], $list2); // Check if the current element's 'phase' is present in $list2

      if ($progMatch && $phaseMatch && $motclefMatch) { // Check if all the conditions for a match are met
        $resultat[] = $element; // Add the current element to the result array
      }
    }
    
    return $resultat; // Return the final result array
  }
?>
