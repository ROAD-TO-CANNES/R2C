<?php
include '/var/www/r2c.uca-project.com/bdd.php';

// verify if the user is already connected
$sql = "SELECT statutcon FROM USER WHERE login LIKE '$_SESSION[name]'";
$request = $BDD->prepare($sql);
$request->execute();
$statutcon = $request->fetchColumn();

// if the user is not connected, redirect to the login page
if ($statutcon == 0) {
  header('Location: ../index.php');
  exit;
}

include '/var/www/r2c.uca-project.com/timer.php';
