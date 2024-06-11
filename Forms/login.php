<?php 
  session_start();
  setcookie('m1', 1, time()-3600, "/");

  //Récuperation des données//
  $name = htmlspecialchars($_POST['userName']);
  $password = ($_POST['password']);

  include '/var/www/r2c.uca-project.com/bdd.php';

  //Sécurisez les entrées//
  $name_encote = $BDD->quote($name);

  //Verrification de l'userName//
  $sql = "SELECT login FROM USER WHERE login LIKE $name_encote";
  $request = $BDD->prepare($sql);
  $request->execute();
  $result = $request->fetchColumn();

  if ($result == NULL) {
    //Log d'érreure de connexion//
    $typelog = "Information";
    $desclog = "Connexion échouée utilisateur inconnu";
    $loginlog = $name;
    include '/var/www/r2c.uca-project.com/Forms/addLogs.php';

    //Envoie d'erreur//
    $error = "e1"; //nom d'utilisateur ou mot de passe incorrect
    $error = urlencode($error);
    header('Location: ../index.php?error='.$error);
  } else {
    //Récuperation des droits//
    $sql = "SELECT droits FROM USER WHERE login LIKE '$name'";
    $request = $BDD->prepare($sql);
    $request->execute();
    $droits = $request->fetchColumn();

    //Si connexion du SuperAdmin//
    if ($droits == 2) {    
      //Recuperation du mot de passe//
      $sql = "SELECT mdp FROM USER WHERE login=$name_encote";
      $request = $BDD->prepare($sql);
      $request->execute();
      $result = $request->fetchColumn();

      //Verification du mot de passe//
      if (password_verify($password, $result)) {

        //Récuperation des utilisateurs connectés//
        $sql = "SELECT login, statutcon FROM USER";
        $request = $BDD->prepare($sql);
        $request->execute();
        $statususer = $request->fetchAll();

        foreach ($statususer as $user) {
          if ($user['statutcon'] == 1) {
            //Deconnexion des utilisateurs//
            $sql = "UPDATE USER SET statutcon=0";
            $request = $BDD->prepare($sql);
            $request->execute();

            //Log de déconnexion//
            $typelog = "Information";
            $desclog = "Déconnexion forcée par le SuperAdministrateur";
            $loginlog = $user['login'];
            include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
          }
        }

        //Attribution du nom d'utilisateur//
        $_SESSION['name'] = $name;

        //Log de connexion//
        $typelog = "Information";
        $desclog = "Connexion réussie";
        $loginlog = $name;
        include '/var/www/r2c.uca-project.com/Forms/addLogs.php';

        //Attribution des droits//
        $_SESSION['droits'] = $droits;
        
        //Mise a jours du status de la connexion//
        $sql = "UPDATE USER SET statutcon=1 WHERE login LIKE '$_SESSION[name]'";
        $request = $BDD->prepare($sql);
        $request->execute();

        //Remise a 0 de l'inactivité//
        $_SESSION['derniereActivite'] = time();

        //Redirection//
        header('Location: ../Accueil/accueil.php');
      } else {

        //Log d'érreure de connexion//
        $typelog = "Warning";
        $desclog = "Connexion échouée mot de passe incorrect";
        $loginlog = $name;
        include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
      
        //Envoie d'erreur//
        $error = "e1"; //nom d'utilisateur ou mot de passe incorrect
        $error = urlencode($error);
        header('Location: ../index.php?error='.$error);
      };  
    } else { //Si connexion d'un utilisateur ou administrateur//

      //Verrification de la disponibilité de connexion//
      $sql = "SELECT login, statutcon FROM USER";
      $request = $BDD->prepare($sql);
      $request->execute();
      $statusconns = $request->fetchAll();
      $connexcount = 0;
      foreach ($statusconns as $statusconn) {
        if ($statusconn['statutcon'] == 1) {
          $connexcount++;
          $userconected = $statusconn['login'];
        };
      };

      if ($connexcount > 10) {
        //Log d'érreure de connexion//
        $typelog = "Warning";
        $desclog = 'Connexion échouée l\'utilisateur "'.$userconected.'" est déjà connecté';
        $loginlog = $name;
        include '/var/www/r2c.uca-project.com/Forms/addLogs.php';

        //Envoie d'erreur//
        $error = "e3"; //un utilisateur est déjà connecté
        $error = urlencode($error);
        header('Location: ../index.php?error='.$error);
      } else {
        //Vérification des tentatives de connexion//
        $sql = "SELECT tentativedelogin FROM USER WHERE login LIKE $name_encote";
        $request = $BDD->prepare($sql);
        $request->execute();
        $tentative = $request->fetchColumn();

        if ($tentative < 3) {
          //Recuperation du mot de passe//
          $sql = "SELECT mdp FROM USER WHERE login=$name_encote";
          $request = $BDD->prepare($sql);
          $request->execute();
          $result = $request->fetchColumn();
          
          if (password_verify($password, $result)) {
            //Attribution du nom d'utilisateur//
            $_SESSION['name'] = $name;

            //Log de connexion//
            $typelog = "Information";
            $desclog = "Connexion réussie";
            $loginlog = $name;
            include '/var/www/r2c.uca-project.com/Forms/addLogs.php';

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
            header('Location: ../Accueil/accueil.php');
          } else {
            //Log d'érreure de connexion//
            $typelog = "Warning";
            $desclog = "Connexion échouée mot de passe incorrect";
            $loginlog = $name;
            include '/var/www/r2c.uca-project.com/Forms/addLogs.php';

            //+1 tentative de connexion echouée//
            $sql = "UPDATE USER SET tentativedelogin=tentativedelogin+1 WHERE login LIKE $name_encote";
            $request = $BDD->prepare($sql);
            $request->execute();

            //Vérification des tentatives de connexion//
            $sql = "SELECT tentativedelogin FROM USER WHERE login LIKE $name_encote";
            $request = $BDD->prepare($sql);
            $request->execute();
            $tentative = $request->fetchColumn();

            if ($tentative == 3) {
              //Log de blocage du compte//
              $typelog = "Alert";
              $desclog = "Blocage du compte suite à 3 tentatives de connexion échouées";
              $loginlog = $name;
              include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
            }
            
            //Envoie d'erreur//
            $error = "e1"; //nom d'utilisateur ou mot de passe incorrect
            $error = urlencode($error);
            header('Location: ../index.php?error='.$error);
          };  
        } else {
          //Log d'érreure de connexion//
          $typelog = "Warning";
          $desclog = "Connexion échouée le compte est bloqué";
          $loginlog = $name;
          include '/var/www/r2c.uca-project.com/Forms/addLogs.php';

          //Envoie d'erreur//
          $error = "e2"; //compte bloqué
          $error = urlencode($error);
          header('Location: ../index.php?error='.$error);
        };
      };
    };
  };
?>  