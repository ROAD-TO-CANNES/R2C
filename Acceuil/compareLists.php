<?php
  function compare_lists($list1, $list2, $list3, $tableau4) {
        $resultat = array();

        foreach ($tableau4 as $element) {
          $progMatch = empty($list1) || in_array($element['prog'], $list1);
          $phaseMatch = empty($list2) || in_array($element['phase'], $list2);
          $motclefMatch = empty($list3) || in_array($element['motclef'], $list3);

          if ($progMatch && $phaseMatch && $motclefMatch) {
            $resultat[] = $element;
          }
        }

        return $resultat;
  }
?>