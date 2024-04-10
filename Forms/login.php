<?php 
  session_start();

//Récuperation des données//
  $name = htmlspecialchars($_POST['userName']);
  $password = ($_POST['password']);
//----------Récuperation des données--------------//

include '../bdd.php';

//Connexion au site//

  //Récuperation des droits//
  $sql = "SELECT droits FROM USER WHERE login LIKE '$name'";
  $request = $BDD->prepare($sql);
  $request->execute();
  $droits = $request->fetchColumn();

  //Si connexion du SuperAdmin//
  if ($droits == 2) {
    //Sécurisez les entrées//
    $name_seq = $BDD->quote($name);
    
    //Recuperation du mot de passe//
    $sql = "SELECT mdp FROM USER WHERE login=$name_seq";
    $request = $BDD->prepare($sql);
    $request->execute();
    $result = $request->fetchColumn();

    //Verification du mot de passe//
    if (password_verify($password, $result)) {
      //Deconnexion des autres utilisateurs//
      $sql = "UPDATE USER SET statutcon=0";
      $request = $BDD->prepare($sql);
      $request->execute();

      //Attribution du nom d'utilisateur//
      $_SESSION['name'] = $name;

      //Attribution des droits//
      $_SESSION['droits'] = $droits;
      
      //Mise a jours du status de la connexion//
      $sql = "UPDATE USER SET statutcon=1 WHERE login LIKE '$_SESSION[name]'";
      $request = $BDD->prepare($sql);
      $request->execute();

      //Remise a 0 de l'inactivité//
      $_SESSION['derniereActivite'] = time();
      setcookie('m1', 0, 0, "/");

      //Redirection//
      header('Location: ../Acceuil/acceuil.php');
    } else {
    
      //Envoie d'erreur//
      $error = "e1"; //nom d'utilisateur ou mot de passe incorrect
      $error = urlencode($error);
      header('Location: ../index.php?error='.$error);
    };  
  } else { //Si connexion d'un utilisateur ou administrateur//

    //Verrification de la disponibilité de connexion//
    $sql = "SELECT statutcon FROM USER";
    $request = $BDD->prepare($sql);
    $request->execute();
    $statusconns = $request->fetchAll();
    $connexcount = 0;
    foreach ($statusconns as $statusconn) {
      if ($statusconn['statutcon'] == 1) {
        $connexcount++;
      }
    };

    if ($connexcount > 0) {
      //Envoie d'erreur//
      $error = "e3"; //un utilisateur est déjà connecté
      $error = urlencode($error);
      header('Location: ../index.php?error='.$error);
    } else {
      //Sécurisez les entrées//
      $name_seq = $BDD->quote($name);

      //Vérification des tentatives de connexion//
      $sql = "SELECT tentativedelogin FROM USER WHERE login LIKE $name_seq";
      $request = $BDD->prepare($sql);
      $request->execute();
      $tentative = $request->fetchColumn();

      if ($tentative < 3) {
        //Recuperation du mot de passe//
        $sql = "SELECT mdp FROM USER WHERE login=$name_seq";
        $request = $BDD->prepare($sql);
        $request->execute();
        $result = $request->fetchColumn();
        
        if (password_verify($password, $result)) {
          //Attribution du nom d'utilisateur//
          $_SESSION['name'] = $name;

          //Remise a 0 des tentatives de connexion//
          $sql = "UPDATE USER SET tentativedelogin=0 WHERE login LIKE '$_SESSION[name]'";
          $request = $BDD->prepare($sql);
          $request->execute();
          
          //Attribution des droits//
          $_SESSION['droits'] = $droits;
          
          //Mise a jours du status de la connexion//
          $sql = "UPDATE USER SET statutcon=1 WHERE login LIKE '$_SESSION[name]'";
          $request = $BDD->prepare($sql);
          $request->execute();

          //Remise a 0 de l'inactivité//
          $_SESSION['derniereActivite'] = time();
          setcookie('m1', 0, 0, "/");

          //Redirection//
          header('Location: ../Acceuil/acceuil.php');
        } else {
          //+1 tentative de connexion echouée//
          $sql = "UPDATE USER SET tentativedelogin=tentativedelogin+1 WHERE login LIKE $name_seq";
          $request = $BDD->prepare($sql);
          $request->execute();
          
          //Envoie d'erreur//
          $error = "e1"; //nom d'utilisateur ou mot de passe incorrect
          $error = urlencode($error);
          header('Location: ../index.php?error='.$error);
        };  
      } else {
        //Envoie d'erreur//
        $error = "e2"; //compte bloqué
        $error = urlencode($error);
        header('Location: ../index.php?error='.$error);
      };
    };
  };
//----------Connexion au site----------------//
?>  