<?php
  echo('
  <h1>Informations de la bonne pratique : "'.$nombp.'"</h1>
  <h1>Statut : '.$statutAff.'</h1> 
  <div class="divProg">
    <select id="selectProgInfo'.$idbp.'" name="programme" form="formProg">
      <option class="default_value" value="">Programmes</option>
    ');
    foreach($progs as $i => $prog) {
      echo('<option value="'.$progs[$i]['idprog'].'">'.$progs[$i]['nomprog'].'</option>');
    }
    echo('
    </select>
    <h2>Sélectionnez les programmes</h2>
    <div class="selected-itemsDiv" id="selected-itemsProgInfo'.$idbp.'">
    ');
      foreach ($progsOfBp as $progOfBp) {
        $sql = 'SELECT nomprog FROM PROGRAMME WHERE idprog = '.$progOfBp['idprog'];
        $request = $BDD->prepare($sql);
        $request->execute();
        $nomprog = $request->fetchColumn();
        echo('<div class="object" id="PR'.$progOfBp['idprog'].'">'.$nomprog.'<span id="'.$progOfBp['idprog'].'" class="delete-item">✘</span></div>');
      }
    echo('
    </div>
  </div>');
  echo('
  <div class="divMotClef">
    <select id="selectMotClefInfo'.$idbp.'" name="motClef" form="formMotClef">
      <option class="default_value" value="">Mots clefs</option>
    ');
    foreach($motsClefs as $i => $motClef) {
      echo('<option value="'.$motsClefs[$i]['idmotclef'].'">'.$motsClefs[$i]['motclef'].'</option>');
    }
    echo('
    </select>
    <form id="formMotClefInfo'.$idbp.'" action="../Forms/addMotClef.php" method="post">
      <button type="submit" id="addMotClefInfo'.$idbp.'">Ajouter</button>
      <input type="text" id="newMotClefInfo'.$idbp.'" name="newMotClef" placeholder="Ajouter un mot clef" required>
    </form>
    <h2>Sélectionnez les mots clefs</h2>
    <div class="selected-itemsDiv" id="selected-itemsMotClefInfo'.$idbp.'">
    ');
      foreach ($motsClefsOfBp as $motClefOfBp) {
        $sql = 'SELECT motclef FROM MOTSCLEF WHERE idmotclef = '.$motClefOfBp['idmotclef'];
        $request = $BDD->prepare($sql);
        $request->execute();
        $motclef = $request->fetchColumn();
        echo('<div class="object" id="MC'.$motClefOfBp['idmotclef'].'">'.$motclef.'<span id="'.$motClefOfBp['idmotclef'].'" class="delete-item">✘</span></div>');
      }
    echo('
    </div>
  </div>
  ');
?>
<script src="../Forms/infoBp.js"></script>
