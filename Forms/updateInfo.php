<?php
session_start();
include '/var/www/r2c.uca-project.com/Forms/checkSession.php';

if (isset($_POST)) { // if POST is set
  $action = $_POST['action'];
  $idbp = $_POST['selectId'];
  $idbp = substr($idbp, 2);
  // Retrieve the name of the good practice
  $sql = 'SELECT nombp FROM BONNESPRATIQUES WHERE idbp = ' . $idbp;
  $request = $BDD->prepare($sql);
  $request->execute();
  $nombp = $request->fetchColumn();
  $element = $_POST['itemId'];

  if (substr($element, 0, 2) == 'PR') { // if element is a programme
    $idprog = substr($element, 2);
    // Retrieve the name of the programme
    $sql = 'SELECT nomprog FROM PROGRAMME WHERE idprog = ' . $idprog;
    $request = $BDD->prepare($sql);
    $request->execute();
    $nomprog = $request->fetchColumn();
  } elseif (substr($element, 0, 2) == 'MC') { // if element is a mot clef
    $idmotclef = substr($element, 2);
    // Retrieve the name of the mot clef
    $sql = 'SELECT motclef FROM MOTSCLEF WHERE idmotclef = ' . $idmotclef;
    $request = $BDD->prepare($sql);
    $request->execute();
    $nommotclef = $request->fetchColumn();
  }

  if ($action == 'add') { // if action is add
    if (!empty($idprog)) { // if element is a programme
      // Insert programme into BONNESPRATIQUES_PROGRAMME
      $sql = 'INSERT INTO BONNESPRATIQUES_PROGRAMME (idbp, idprog) VALUES (' . $idbp . ', ' . $idprog . ')';
      $request = $BDD->prepare($sql);
      $request->execute();

      // Log the addition of a programme
      $typelog = 'Information';
      $desclog = 'Ajout du programme "' . $nomprog . '" à la bonne pratique "' . $nombp . '"';
      $loginlog = $_SESSION['name'];
      include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
    } elseif (!empty($idmotclef)) { // if element is a mot clef
      // Insert mot clef into BONNESPRATIQUES_MOTSCLEF
      $sql = 'INSERT INTO BONNESPRATIQUES_MOTSCLEF (idbp, idmotclef) VALUES (' . $idbp . ', ' . $idmotclef . ')';
      $request = $BDD->prepare($sql);
      $request->execute();

      // Log the addition of a mot clef
      $typelog = 'Information';
      $desclog = 'Ajout du mot clef "' . $nommotclef . '" à la bonne pratique "' . $nombp . '"';
      $loginlog = $_SESSION['name'];
      include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
    }
  } elseif ($action == 'remove') { // if action is remove
    if (!empty($idprog)) { // if element is a programme
      // Delete programme from BONNESPRATIQUES_PROGRAMME
      $sql = 'DELETE FROM BONNESPRATIQUES_PROGRAMME WHERE idbp = ' . $idbp . ' AND idprog = ' . $idprog;
      $request = $BDD->prepare($sql);
      $request->execute();

      // logs de suppression de programme
      $typelog = 'Information';
      $desclog = 'Suppression du programme "' . $nomprog . '" de la bonne pratique "' . $nombp . '"';
      $loginlog = $_SESSION['name'];
      include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
    } elseif (!empty($idmotclef)) { // if element is a mot clef
      // Delete mot clef from BONNESPRATIQUES_MOTSCLEF
      $sql = 'DELETE FROM BONNESPRATIQUES_MOTSCLEF WHERE idbp = ' . $idbp . ' AND idmotclef = ' . $idmotclef;
      $request = $BDD->prepare($sql);
      $request->execute();

      // Log the deletion of a mot clef
      $typelog = 'Information';
      $desclog = 'Suppression du mot clef "' . $nommotclef . '" de la bonne pratique "' . $nombp . '"';
      $loginlog = $_SESSION['name'];
      include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
    }
  }
} else { // if no POST
  // Log the error
  $typelog = 'Warning';
  $desclog = 'Erreur lors de la modification d\'éléments de la bonne pratique';
  $loginlog = $_SESSION['name'];
  include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
}
