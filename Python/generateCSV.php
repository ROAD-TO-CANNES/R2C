<?php
session_start();
include '/var/www/r2c.uca-project.com/Forms/checkSession.php';

// Verify if generate_csv is set in POST array
if (isset($_POST['generate_csv'])) {
  $bps = json_decode($_POST['generate_csv']); // Decode the JSON array of good practices
  // Format the parameters for the Python script
  $phase = '"ph[' . substr($_POST['generate_csv-phase'], 1) . ']"';
  $prog = '"pr[' . substr($_POST['generate_csv-prog'], 1) . ']"';
  $keyword = '"kw[' . substr($_POST['generate_csv-keyword'], 1.) . ']"';
  $listebp = '"bp[';
  foreach ($bps as $bp) {
    $listebp .= $bp . ' ';
  }
  $listebp = trim($listebp);
  $listebp .= ']"';
  $user = ucfirst(strtolower($_SESSION['name'])); // Put the first letter in uppercase 
  $date = date('d-m-Y');

  // Execute the Python script
  $param = $listebp . ' ' . $phase . ' ' . $prog . ' ' . $keyword . ' "user[' . $user . ']"';
  $command = "/usr/bin/python3 /var/www/r2c.uca-project.com/Python/ProgToCSV.py $param 2>&1";
  shell_exec($command);

  // Log the generation of the CSV
  $typelog = "Information";
  $desclog = 'Génération d\'un fichier CSV des bonnes pratiques ' . $listebp;
  $loginlog = $_SESSION['name'];
  include '/var/www/r2c.uca-project.com/Forms/addLogs.php';

  // Output the generated PDF content
  header('Content-Type: text/csv');
  header('Content-Disposition: attachment; filename="Bonnes_pratiques_' . $user . '_' . $date . '.csv"'); // Name it Bonnes_pratiques_username_date.csv
  readfile('/var/www/r2c.uca-project.com/Python/Download/Bonnes_Pratiques.csv');
  exit; // Stop further execution of the script
} else {
  // Log the error of generating the CSV
  $typelog = "Warning";
  $desclog = 'Erreur lors de la génération du fichier CSV certains parametres sont manquants';
  $loginlog = $_SESSION['name'];
  include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
  // Redirect to the validation page with the good message
  header('Location: ../Validation/validation.php?message=eccsv');
}
