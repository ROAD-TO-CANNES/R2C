<?php
  session_start();
  include '/var/www/r2c.uca-project.com/Forms/checkSession.php';

  if (isset($_POST)) {
    $action = $_POST['action'];
    $idbp = $_POST['selectId'];
    $idbp = substr($idbp, 2);
    $element = $_POST['itemId'];
    if (substr($element, 0, 2) == 'PR') {
      $idprog = substr($element, 2);
    } elseif (substr($element, 0, 2) == 'MC') {
      $idmotclef = substr($element, 2);
    }

    if ($action == 'add') {
      if (!empty($idprog)) {
        $sql = 'INSERT INTO BONNESPRATIQUES_PROGRAMME (idbp, idprog) VALUES ('.$idbp.', '.$idprog.')';
        $request = $BDD->prepare($sql);
        $request->execute();

        // logs d'ajout de programme
        $typelog = 'Information';
        $desclog = 'Ajout du programme '.$idprog.' à la bonne pratique '.$idbp;
        $loginlog = $_SESSION['name'];
        include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
      } elseif (!empty($idmotclef)) {
        $sql = 'INSERT INTO BONNESPRATIQUES_MOTSCLEF (idbp, idmotclef) VALUES ('.$idbp.', '.$idmotclef.')';
        $request = $BDD->prepare($sql);
        $request->execute();

        // logs d'ajout de mot clef
        $typelog = 'Information';
        $desclog = 'Ajout du mot clef '.$idmotclef.' à la bonne pratique '.$idbp;
        $loginlog = $_SESSION['name'];
        include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
      }
    } elseif ($action == 'remove') {
      if (!empty($idprog)) {
        $sql = 'DELETE FROM BONNESPRATIQUES_PROGRAMME WHERE idbp = '.$idbp.' AND idprog = '.$idprog;
        $request = $BDD->prepare($sql);
        $request->execute();

        // logs de suppression de programme
        $typelog = 'Information';
        $desclog = 'Suppression du programme '.$idprog.' de la bonne pratique '.$idbp;
        $loginlog = $_SESSION['name'];
        include '/var/www/r2c.uca-project.com/Forms/addLogs.php';

      } elseif (!empty($idmotclef)) {
        $sql = 'DELETE FROM BONNESPRATIQUES_MOTSCLEF WHERE idbp = '.$idbp.' AND idmotclef = '.$idmotclef;
        $request = $BDD->prepare($sql);
        $request->execute();

        // logs de suppression de mot clef
        $typelog = 'Information';
        $desclog = 'Suppression du mot clef '.$idmotclef.' de la bonne pratique '.$idbp;
        $loginlog = $_SESSION['name'];
        include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
      }
    }
  } else {
    echo 'No POST';
    // logs d'erreur
    $typelog = 'Warning';
    $desclog = 'Erreur lors de la modification d\'éléments de la bonne pratique';
    $loginlog = $_SESSION['name'];
    include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
  }
?>