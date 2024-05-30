<?php
  session_start();
  include '/var/www/r2c.uca-project.com/Forms/checkSession.php';

  if(isset($_POST['newMotClef']) && isset($_POST['idbp'])) {
    $newMotClef = htmlspecialchars($_POST['newMotClef']);
    $newMotClef_seq = $BDD->quote($newMotClef);
    $idbp = $_POST['idbp'];

    // Vérifier si le mot clé existe déjà dans la base de données
    $sql = "SELECT * FROM MOTSCLEF WHERE motclef LIKE $newMotClef_seq";
    $request = $BDD->prepare($sql);
    $request->execute();
    $result = $request->fetchAll();

    foreach($result as $row) {
      if($row['motclef'] == $newMotClef) {
        $exist = true;
      }
    }

    if($exist) {
      // Vérifier si le mot clé est déjà associé à la bonne pratique
      $sql = "SELECT idmotclef FROM BONNESPRATIQUES_MOTSCLEF WHERE idbp = $idbp AND idmotclef = $row[idmotclef]";
      $request = $BDD->prepare($sql);
      $request->execute();
      $result = $request->fetchAll();

      // Si le mot clé n'est pas associé à la bonne pratique, l'ajouter
      if (count($result) == 0) {
        $sql = "INSERT INTO BONNESPRATIQUES_MOTSCLEF (idbp, idmotclef) VALUES ($idbp, $row[idmotclef])";
        $request = $BDD->prepare($sql);
        $request->execute();
        //Log d'ajout de mot clef//
        $typelog = "Information";
        $desclog = 'Ajout du mot clef "'.$newMotClef.'" à la bonne pratique id='.$idbp;
        $loginlog = $_SESSION['name'];
        include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
        // Rediriger vers la page d'accueil
        header('Location: ../Accueil/accueil.php?info='.$idbp);
      } else {
        //Log d'erreur d'ajout de mot clef//
        $typelog = "Warning";
        $desclog = 'Erreur lors de l\'ajout du mot clef "'.$newMotClef.'" à la bonne pratique id='.$idbp.' le mot clef est déjà associé à la bonne pratique';
        $loginlog = $_SESSION['name'];
        include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
        // Rediriger vers la page d'accueil
        header('Location: ../Accueil/accueil.php?info='.$idbp);
      }
    } else { // Si le mot clé n'existe pas dans la base de données, l'ajouter
      $sql = "INSERT INTO MOTSCLEF (motclef) VALUES ($newMotClef_seq)";
      $request = $BDD->prepare($sql);
      $request->execute();
      //Log d'ajout de mot clef//
      $typelog = "Information";
      $desclog = 'Création du mot clef "'.$newMotClef.'"';
      $loginlog = $_SESSION['name'];
      include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
      
      // Récupérer l'id du mot clé ajouté
      $sql = "SELECT idmotclef FROM MOTSCLEF WHERE motclef LIKE $newMotClef_seq";
      $request = $BDD->prepare($sql);
      $request->execute();
      $idmotclef = $request->fetchColumn();

      // Ajouter le mot clé à la bonne pratique
      $sql = "INSERT INTO BONNESPRATIQUES_MOTSCLEF (idbp, idmotclef) VALUES ($idbp, $idmotclef)";
      $request = $BDD->prepare($sql);
      $request->execute();
      //Log d'ajout de mot clef//
      $typelog = "Information";
      $desclog = 'Ajout du mot clef "'.$newMotClef.'" à la bonne pratique id='.$idbp;
      $loginlog = $_SESSION['name'];
      include '/var/www/r2c.uca-project.com/Forms/addLogs.php';

      // Rediriger vers la page d'accueil
      header('Location: ../Accueil/accueil.php?info='.$idbp);
    }
  } else {
    //Log d'erreur d'ajout de mot clef//
    $typelog = "Warning";
    $desclog = 'Erreur lors de la création du mot clef certains parametres sont manquants';
    $loginlog = $_SESSION['name'];
    include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
    header('Location: ../Validation/validation.php?message=ecmc');
  }
?>