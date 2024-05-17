<?php 

  session_start(); 
  include '/home/r2c/R2C/Forms/checkSession.php';

  //Recuperation des specs du mot de passe
  $sql = "SELECT * FROM SPECSPSW";
  $request = $BDD->prepare($sql);
  $request->execute();
  $specspsw = $request->fetch();

  if(isset($_POST['old_psw']) && isset($_POST['new_psw']) && isset($_POST['new_psw2'])){
    $old_psw = $_POST['old_psw'];
    $new_psw = $_POST['new_psw'];
    $new_psw_hash = password_hash($new_psw, PASSWORD_DEFAULT);
    $new_psw2 = $_POST['new_psw2'];

    if($new_psw == $new_psw2){
      $size = strlen($new_psw);//Taille du mot de passe
      if ($size >= $specspsw['size']) {
        $nbnumber = preg_match_all("/[0-9]/", $new_psw);//Nombre de chiffres
        if ($nbnumber >= $specspsw['number']) {
          $nbspecial = preg_match_all("/[^a-zA-Z0-9]/", $new_psw);//Nombre de caractères spéciaux
          if ($nbspecial >= $specspsw['specialchar']) {
            $nbupper = preg_match_all("/[A-Z]/", $new_psw);//Nombre de majuscules
            if ($nbupper >= $specspsw['uppercase']) {
              if($new_psw != $old_psw) {
                //Sécurisez les entrées//
                $new_psw_seq = $BDD->quote($new_psw_hash);
                //---------Sécurisez les entrées---------//

                //Requete SQL//
                $sql = "SELECT mdp FROM USER WHERE login LIKE '".$_SESSION['name']."'";
                $request = $BDD->prepare($sql);
                $request->execute();
                $result = $request->fetch();


                if(password_verify($old_psw, $result['mdp'])){
                  $sql = "UPDATE USER SET mdp=$new_psw_seq WHERE login LIKE '".$_SESSION['name']."'";
                  $request = $BDD->prepare($sql);
                  $request->execute();
                  //log de changement de mot de passe//
                  $typelog = "Réussite";
                  $desclog = "Changement de mot de passe réussi";
                  $loginlog = $_SESSION['name'];
                  include '/home/r2c/R2C/Forms/addLogs.php';
                  //Envoie de confirmation//
                  $response = 0;
                } else {
                  //log de tentative de changement de mot de passe//
                  $typelog = "Erreur";
                  $desclog = "Tentative de changement de mot de passe échouée mot de passe incorrect";
                  $loginlog = $_SESSION['name'];
                  include '/home/r2c/R2C/Forms/addLogs.php';
                  //Envoie d'erreur//
                  $response = 1; //Mot de passe incorrect
                };
                //----------Requete SQL------------//
              } else {
                //log de tentative de changement de mot de passe//
                $typelog = "Erreur";
                $desclog = "Tentative de changement de mot de passe échouée le nouveau mot de passe doit être différent de l'ancien";
                $loginlog = $_SESSION['name'];
                include '/home/r2c/R2C/Forms/addLogs.php';
                //Envoie d'erreur//
                $response = 3;//Le nouveau mot de passe doit être différent de l'ancien
              }
            } else {
              //log de tentative de changement de mot de passe//
              $typelog = "Erreur";
              $desclog = "Tentative de changement de mot de passe échouée le mot de passe ne contient pas assez de majuscules";
              $loginlog = $_SESSION['name'];
              include '/home/r2c/R2C/Forms/addLogs.php';
              //Envoie d'erreur//
              $response = 7;//Le mot de passe doit contenir au moins $specspsw['uppercase'] majuscule 
            }
          } else {
            //log de tentative de changement de mot de passe//
            $typelog = "Erreur";
            $desclog = "Tentative de changement de mot de passe échouée le mot de passe ne contient pas assez de caractères spéciaux";
            $loginlog = $_SESSION['name'];
            include '/home/r2c/R2C/Forms/addLogs.php';
            //Envoie d'erreur//
            $response = 6;//Le mot de passe doit contenir au moins $specspsw['specialchar'] caractères spéciaux 
          }
        } else {
          //log de tentative de changement de mot de passe//
          $typelog = "Erreur";
          $desclog = "Tentative de changement de mot de passe échouée le mot de passe ne contient pas assez de chiffres";
          $loginlog = $_SESSION['name'];
          include '/home/r2c/R2C/Forms/addLogs.php';
          //Envoie d'erreur//
          $response = 5;//Le mot de passe doit contenir au moins $specspsw['number'] chiffres 
        }
      } else {
        //log de tentative de changement de mot de passe//
        $typelog = "Erreur";
        $desclog = "Tentative de changement de mot de passe échouée le mot de passe est trop court";
        $loginlog = $_SESSION['name'];
        include '/home/r2c/R2C/Forms/addLogs.php';
        //Envoie d'erreur//
        $response = 4;//Le mot de passe doit faire aux moins $specspsw['size'] caractères 
      }
    } else {
      //log de tentative de changement de mot de passe//
      $typelog = "Erreur";
      $desclog = "Tentative de changement de mot de passe échouée les mots de passe ne correspondent pas";
      $loginlog = $_SESSION['name'];
      include '/home/r2c/R2C/Forms/addLogs.php';
      //Envoie d'erreur//
      $response = 2;//Les mots de passe ne correspondent pas 
    };
    echo json_encode($response);
  };
?>