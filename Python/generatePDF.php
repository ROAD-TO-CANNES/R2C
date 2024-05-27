<?php
  session_start();
  include '/var/www/r2c.uca-project.com/Form/checkSession.php';
  error_reporting(E_ALL);

  if(isset($_POST['generate_pdf'])) {
    $bps = json_decode($_POST['generate_pdf']);
    $param = '';
    foreach ($bps as $bp) {
      $param .= $bp . ' ';
    }
    $param = trim($param);
    $command = "/usr/bin/python3 /var/www/r2c.uca-project.com/Python/ProgToPDF.py $param 2>&1";
    shell_exec($command);
    $pdf_filename = "Bonnes_Pratiques.pdf";

    // Output the generated PDF content
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="'.$pdf_filename.'"');
    readfile('/var/www/r2c.uca-project.com/Python/Download/'.$pdf_filename);
    exit; // Stop further execution of the script
  }
?>