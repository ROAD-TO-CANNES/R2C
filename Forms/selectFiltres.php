<?php
  echo('
  <div class="divProg">
    <select id="selectProg" name="programme" form="formProg">
      <option class="default_value" value="">Programmes</option>
    ');
    foreach($progs as $i => $prog) {
      echo('<option value="'.$progs[$i]['idprog'].'">'.$progs[$i]['nomprog'].'</option>');
    }
    echo('
    </select>
    <h2>Sélectionnez les programmes</h2>
    <div class="selected-itemsDiv" id="selected-itemsProg"></div>
  </div>');
  if ($_SERVER['REQUEST_URI'] == "/Accueil/accueil.php") {
    echo ('
    <div class="divPhase">
      <select id="selectPhase" name="phase" form="formPhase">
        <option class="default_value" value="" disabled selected>Phases</option>
        ');
        foreach($phases as $i => $phase) {
          echo('<option value="'.$phases[$i]['idphase'].'">Phase '.$phases[$i]['idphase'].'</option>');
        }
      echo('
      </select>
      <h2>Sélectionnez les phases</h2>
      <div class="selected-itemsDiv" id="selected-itemsPhase"></div>
    </div>');
  }
  echo('
  <div class="divMotClef">
    <select id="selectMotClef" name="motClef" form="formMotClef">
      <option class="default_value" value="">Mots clefs</option>
    ');
    foreach($motsClefs as $i => $motClef) {
      echo('<option value="'.$motsClefs[$i]['idmotclef'].'">'.$motsClefs[$i]['motclef'].'</option>');
    }
    echo('
    </select>
    ');
    if ($_SERVER['REQUEST_URI'] == "/NewBP/newBP.php") {
      echo('
        <form id="formMotClef" action="../Forms/addMotClef.php" method="post">
          <button type="submit" id="addMotClef">Ajouter</button>
          <input type="text" id="newMotClef" name="newMotClef" placeholder="Ajouter un mot clef" required>
        </form>
      ');
    };
    echo('
    <h2>Sélectionnez les mots clefs</h2>
    <div class="selected-itemsDiv" id="selected-itemsMotClef"></div>
  </div>
  ');
?>
