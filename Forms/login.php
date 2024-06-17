<?php
session_start();
// Reset the cookie of inactivity
setcookie('m1', 1, time() - 3600, "/");

// Get the username and password from the form
$name = htmlspecialchars($_POST['userName']);
$password = ($_POST['password']);

include '/var/www/r2c.uca-project.com/bdd.php';

// Encote the username
$name_encote = $BDD->quote($name);

// Check if the username exists in the database
$sql = "SELECT login FROM USER WHERE login LIKE $name_encote";
$request = $BDD->prepare($sql);
$request->execute();
$result = $request->fetchColumn();

if ($result == NULL) { // If the username does not exist
  // Log the error of connection
  $typelog = "Information";
  $desclog = "Connexion échouée utilisateur inconnu";
  $loginlog = $name;
  include '/var/www/r2c.uca-project.com/Forms/addLogs.php';

  $error = "e1"; // Username incorrect
} else { // If the username exists
  // Get the rights of the user
  $sql = "SELECT droits FROM USER WHERE login LIKE '$name'";
  $request = $BDD->prepare($sql);
  $request->execute();
  $droits = $request->fetchColumn();

  // If the user is the super administrator
  if ($droits == 2) {
    // Get the password of the user
    $sql = "SELECT mdp FROM USER WHERE login=$name_encote";
    $request = $BDD->prepare($sql);
    $request->execute();
    $result = $request->fetchColumn();

    // Verify the password
    if (password_verify($password, $result)) {

      // Get the status of the users
      $sql = "SELECT login, statutcon FROM USER";
      $request = $BDD->prepare($sql);
      $request->execute();
      $statususer = $request->fetchAll();

      foreach ($statususer as $user) {
        if ($user['statutcon'] == 1) {
          // Disconect connected users
          $sql = "UPDATE USER SET statutcon=0";
          $request = $BDD->prepare($sql);
          $request->execute();

          // Log the disconnection
          $typelog = "Information";
          $desclog = "Déconnexion forcée par le SuperAdministrateur";
          $loginlog = $user['login'];
          include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
        }
      }

      // Set the username
      $_SESSION['name'] = $name;

      // Log the connection
      $typelog = "Information";
      $desclog = "Connexion réussie";
      $loginlog = $name;
      include '/var/www/r2c.uca-project.com/Forms/addLogs.php';

      // Set the rights
      $_SESSION['droits'] = $droits;

      // Set the connection status
      $sql = "UPDATE USER SET statutcon=1 WHERE login LIKE '$_SESSION[name]'";
      $request = $BDD->prepare($sql);
      $request->execute();

      // Reset the inactivity
      $_SESSION['derniereActivite'] = time();

      // Redirect to the home page
      header('Location: ../Accueil/accueil.php');
      exit(); // Exit the script
    } else { // If the password is incorrect

      // Log the error of connection
      $typelog = "Warning";
      $desclog = "Connexion échouée mot de passe incorrect";
      $loginlog = $name;
      include '/var/www/r2c.uca-project.com/Forms/addLogs.php';

      $error = "e1"; // Username or password incorrect
    };
  } else { // If the user is not the super administrator

    // Get the connected users
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

    // If a user is already connected
    if ($connexcount > 10) {
      // Log the error of connection
      $typelog = "Warning";
      $desclog = 'Connexion échouée l\'utilisateur "' . $userconected . '" est déjà connecté';
      $loginlog = $name;
      include '/var/www/r2c.uca-project.com/Forms/addLogs.php';

      $error = "e3"; // User already connected
    } else { // If no user is connected
      // Get the number of connection attempts
      $sql = "SELECT tentativedelogin FROM USER WHERE login LIKE $name_encote";
      $request = $BDD->prepare($sql);
      $request->execute();
      $tentative = $request->fetchColumn();

      if ($tentative < 3) { // If the number of attempts is less than 3
        // Get the password of the user
        $sql = "SELECT mdp FROM USER WHERE login=$name_encote";
        $request = $BDD->prepare($sql);
        $request->execute();
        $result = $request->fetchColumn();

        // Verify the password
        if (password_verify($password, $result)) {
          // Set the username
          $_SESSION['name'] = $name;

          // Log the connection
          $typelog = "Information";
          $desclog = "Connexion réussie";
          $loginlog = $name;
          include '/var/www/r2c.uca-project.com/Forms/addLogs.php';

          // Reset the number of connection attempts
          $sql = "UPDATE USER SET tentativedelogin=0 WHERE login LIKE '$_SESSION[name]'";
          $request = $BDD->prepare($sql);
          $request->execute();

          // Set the rights
          $_SESSION['droits'] = $droits;

          // Set the connection status
          $sql = "UPDATE USER SET statutcon=1 WHERE login LIKE '$_SESSION[name]'";
          $request = $BDD->prepare($sql);
          $request->execute();

          // Reset the inactivity
          $_SESSION['derniereActivite'] = time();
          setcookie('m1', 0, 0, "/");

          // Redirect to the home page
          header('Location: ../Accueil/accueil.php');
          exit(); // Exit the script
        } else { // If the password is incorrect
          // Log the error of connection
          $typelog = "Warning";
          $desclog = "Connexion échouée mot de passe incorrect";
          $loginlog = $name;
          include '/var/www/r2c.uca-project.com/Forms/addLogs.php';

          // Update the number of connection attempts +1
          $sql = "UPDATE USER SET tentativedelogin=tentativedelogin+1 WHERE login LIKE $name_encote";
          $request = $BDD->prepare($sql);
          $request->execute();

          // Get the number of connection attempts
          $sql = "SELECT tentativedelogin FROM USER WHERE login LIKE $name_encote";
          $request = $BDD->prepare($sql);
          $request->execute();
          $tentative = $request->fetchColumn();

          if ($tentative == 3) { // If the number of attempts is equal to 3
            // Block the account
            $typelog = "Alert";
            $desclog = "Blocage du compte suite à 3 tentatives de connexion échouées";
            $loginlog = $name;
            include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
          }

          $error = "e1"; // Username or password incorrect
        };
      } else { // If the number of attempts is equal to 3
        // Log the error of connection
        $typelog = "Warning";
        $desclog = "Connexion échouée le compte est bloqué";
        $loginlog = $name;
        include '/var/www/r2c.uca-project.com/Forms/addLogs.php';

        $error = "e2"; // Account blocked
      };
    };
  };
};
$error = urlencode($error);
header('Location: ../index.php?error=' . $error); // Redirect to the index page with the error message
