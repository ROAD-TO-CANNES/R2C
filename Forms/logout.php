<?php
session_start();
include '/var/www/r2c.uca-project.com/bdd.php';

// Verify if the user is already connected
$sql = "SELECT statutcon FROM USER WHERE login LIKE '$_SESSION[name]'";
$request = $BDD->prepare($sql);
$request->execute();
$statutcon = $request->fetchColumn();

// If the user is not connected, redirect to the login page
if ($statutcon == 0) {
  header('Location: ../index.php');
  exit;
}
// Reset the cookie filters
setcookie('filtres', '', time() - 3600, "/");

// Reset the connection status
$sql = "UPDATE USER SET statutcon=0 WHERE login LIKE '$_SESSION[name]'";
$request = $BDD->prepare($sql);
$request->execute();

// Log the disconnection
$typelog = "Information";
if (!isset($desclog)) { //If $desclog is not set the script is called manually with the logout button
  $desclog = "Déconnexion manuelle réussie";
}
$loginlog = $_SESSION['name'];
include '/var/www/r2c.uca-project.com/Forms/addLogs.php';

// Reset the session
session_unset();
session_destroy();
header('Location: ../index.php'); // Redirect to the login page
