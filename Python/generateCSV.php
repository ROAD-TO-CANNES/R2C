<?php
  session_start();
  include '/var/www/r2c.uca-project.com/Form/checkSession.php';

  if(isset($_POST['generate_csv'])) {
    $bps = json_decode($_POST['generate_csv']);
    $param = '';
    foreach ($bps as $bp) {
      $param .= $bp . ' ';
    }
    $param = trim($param);
    $command = "/usr/bin/python3 /var/www/r2c.uca-project.com/Python/ProgToCSV.py $param";
    shell_exec($command);
    $csv_filename = "Bonnes_Pratiques.csv";

    // Output the generated PDF content
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="'.$csv_filename.'"');
    readfile('/var/www/r2c.uca-project.com/Python/Download/'.$csv_filename);
    exit; // Stop further execution of the script
  }
?>