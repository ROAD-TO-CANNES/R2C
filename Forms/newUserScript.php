<?php
  session_start();
  include '/var/www/r2c.uca-project.com/Forms/checkSession.php';

  if ($_SESSION['droits'] < 1) {
    header('Location: ../Accueil/accueil.php');
  }

  //Recuperation des specs du mot de passe
  $sql = "SELECT * FROM SPECSPSW";
  $request = $BDD->prepare($sql);
  $request->execute();
  $specspsw = $request->fetch();

  if (isset($_POST['username']) && isset($_POST['newuserpsw']) && isset($_POST['newuserpsw2']) && isset($_POST['role'])) {
    $username = $_POST['username'];
    $newuserpsw = $_POST['newuserpsw'];
    $newuserpsw2 = $_POST['newuserpsw2'];
    $role = $_POST['role'];
    $date = date('Y-m-d H:i:s');

    if ($newuserpsw == $newuserpsw2) {
      $size = strlen($newuserpsw);//Taille du mot de passe
      if ($size >= $specspsw['size']) {
        $nbnumber = preg_match_all("/[0-9]/", $newuserpsw);//Nombre de chiffres
        if ($nbnumber >= $specspsw['number']) {
          $nbspecial = preg_match_all("/[^a-zA-Z0-9]/", $newuserpsw);//Nombre de caractères spéciaux
          if ($nbspecial >= $specspsw['specialchar']) {
            $nbupper = preg_match_all("/[A-Z]/", $newuserpsw);//Nombre de majuscules
            if ($nbupper >= $specspsw['uppercase']) {
              $newuserpsw_hash = password_hash($newuserpsw, PASSWORD_DEFAULT);
              $sql = "INSERT INTO USER (login, mdp, droits, dateus) VALUES ('$username', '$newuserpsw_hash', $role, '$date')";
              $request = $BDD->prepare($sql);
              $request->execute();
              //logs de création d'utilisateur
              $typelog = "Information";
              $desclog = "Création du nouvel utilisateur '".$username."' réussie";
              $loginlog = $_SESSION['name'];
              include '/var/www/r2c.uca-project.com/Forms/addLogs.php';

              $response = 5;//Redirection
              echo json_encode($response);
            } else {
              //logs d'erreur
              $typelog = "Warning";
              $desclog = "Erreur lors de la création de l'utilisateur '".$username."' le mot de passe ne contien pas suffisament de majuscules";
              $loginlog = $_SESSION['name'];
              include '/var/www/r2c.uca-project.com/Forms/addLogs.php';

              $response = 4;//Nombre de majuscules insuffisant
              echo json_encode($response);
              exit();
            }
          } else {
            //logs d'erreur
            $typelog = "Warning";
            $desclog = "Erreur lors de la création de l'utilisateur '".$username."' le mot de passe ne contien pas suffisament de caractères spéciaux";
            $loginlog = $_SESSION['name'];
            include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
            
            $response = 3;//Nombre de caractères spéciaux insuffisant
            echo json_encode($response);
            exit();
          }
        } else {
          //logs d'erreur
          $typelog = "Warning";
          $desclog = "Erreur lors de la création de l'utilisateur '".$username."' le mot de passe ne contien pas suffisament de chiffres";
          $loginlog = $_SESSION['name'];
          include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
          
          $response = 2;//Nombre de chiffres insuffisant
          echo json_encode($response);
          exit();
        }
      } else {
        //logs d'erreur
        $typelog = "Warning";
        $desclog = "Erreur lors de la création de l'utilisateur '".$username."' le mot de passe est trop court";
        $loginlog = $_SESSION['name'];
        include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
        
        $response = 1;//Taille du mot de passe insuffisante
        echo json_encode($response);
        exit();
      }
    } else {
      //logs d'erreur
      $typelog = "Warning";
      $desclog = "Erreur lors de la création de l'utilisateur '".$username."' les mots de passe ne correspondent pas";
      $loginlog = $_SESSION['name'];
      include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
      
      $response = 0;//Les mots de passe ne correspondent pas
      echo json_encode($response);
      exit();
    }
  } else {
    //logs d'erreur
    $typelog = "Warning";
    $desclog = "Erreur lors de la création d'un utilisateur certain paramètres sont manquant";
    $loginlog = $_SESSION['name'];
    include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
  }
?>