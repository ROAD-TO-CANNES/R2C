<?php
  session_start();
  include '/var/www/r2c.uca-project.com/Forms/checkSession.php';

  // Verify that the new keyword and the good practice id are set in the POST array
  if(isset($_POST['newMotClef']) && isset($_POST['idbp'])) {
    // Sanitize the new keyword
    $newMotClef = htmlspecialchars($_POST['newMotClef']);
    $newMotClef_seq = $BDD->quote($newMotClef);
    $idbp = $_POST['idbp'];

    // Verify if the keyword already exists in the database
    $sql = "SELECT * FROM MOTSCLEF WHERE motclef LIKE $newMotClef_seq";
    $request = $BDD->prepare($sql);
    $request->execute();
    $result = $request->fetchAll();

    $exist = false;
    foreach($result as $row) {
      if($row['motclef'] == $newMotClef) {
        $exist = true;
      }
    }

    if($exist) {
      // Verify if the keyword is already associated with the good practice
      $sql = "SELECT idmotclef FROM BONNESPRATIQUES_MOTSCLEF WHERE idbp = $idbp AND idmotclef = $row[idmotclef]";
      $request = $BDD->prepare($sql);
      $request->execute();
      $result = $request->fetchAll();

      // If the keyword is not associated with the good practice, add it
      if (count($result) == 0) {
        $sql = "INSERT INTO BONNESPRATIQUES_MOTSCLEF (idbp, idmotclef) VALUES ($idbp, $row[idmotclef])";
        $request = $BDD->prepare($sql);
        $request->execute();
        // Log the addition of the keyword
        $typelog = "Information";
        $desclog = 'Ajout du mot clef "'.$newMotClef.'" à la bonne pratique id='.$idbp;
        $loginlog = $_SESSION['name'];
        include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
        // Redirect to the home page
        header('Location: ../Accueil/accueil.php?info='.$idbp);
      } else {
        // Log the error of adding the keyword
        $typelog = "Warning";
        $desclog = 'Erreur lors de l\'ajout du mot clef "'.$newMotClef.'" à la bonne pratique id='.$idbp.' le mot clef est déjà associé à la bonne pratique';
        $loginlog = $_SESSION['name'];
        include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
        // Redirect to the home page
        header('Location: ../Accueil/accueil.php?info='.$idbp);
      }
    } else { // If the keyword does not exist in the database add it
      $sql = "INSERT INTO MOTSCLEF (motclef) VALUES ($newMotClef_seq)";
      $request = $BDD->prepare($sql);
      $request->execute();
      // Log the creation of the keyword
      $typelog = "Information";
      $desclog = 'Création du mot clef "'.$newMotClef.'"';
      $loginlog = $_SESSION['name'];
      include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
      
      // Get the id of the newly created keyword
      $sql = "SELECT idmotclef FROM MOTSCLEF WHERE motclef LIKE $newMotClef_seq";
      $request = $BDD->prepare($sql);
      $request->execute();
      $idmotclef = $request->fetchColumn();

      // Associate the keyword with the good practice
      $sql = "INSERT INTO BONNESPRATIQUES_MOTSCLEF (idbp, idmotclef) VALUES ($idbp, $idmotclef)";
      $request = $BDD->prepare($sql);
      $request->execute();
      // Log the addition of the keyword
      $typelog = "Information";
      $desclog = 'Ajout du mot clef "'.$newMotClef.'" à la bonne pratique id='.$idbp;
      $loginlog = $_SESSION['name'];
      include '/var/www/r2c.uca-project.com/Forms/addLogs.php';

      // Redirect to the home page
      header('Location: ../Accueil/accueil.php?info='.$idbp);
    }
  } else {
    // Log the error of adding the keyword
    $typelog = "Warning";
    $desclog = 'Erreur lors de la création du mot clef certains parametres sont manquants';
    $loginlog = $_SESSION['name'];
    include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
    
    // Redirect to the validation page with the error message
    header('Location: ../Validation/validation.php?message=eckeyword');
  }
?>