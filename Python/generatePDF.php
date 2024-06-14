<?php
  session_start();
  include '/var/www/r2c.uca-project.com/Forms/checkSession.php';

  // Verify if generate_pdf is set in POST array
  if(isset($_POST['generate_pdf'])) {
    $bps = json_decode($_POST['generate_pdf']);// Decode the JSON array of good practices
    // Format the parameters for the Python script
    $phase = '"ph['.substr($_POST['generate_pdf-phase'], 1).']"';
    $prog = '"pr['.substr($_POST['generate_pdf-prog'], 1).']"';
    $keyword = '"kw['.substr($_POST['generate_pdf-keyword'], 1.).']"';
    $listebp = '"bp[';
    foreach ($bps as $bp) {
      $listebp .= $bp . ' ';
    }
    $listebp = trim($listebp);
    $listebp .= ']"';
    $user = ucfirst(strtolower($_SESSION['name']));// Put the first letter in uppercase
    $date = date('d-m-Y');

    // Execute the Python script
    $param = $listebp . ' ' . $phase . ' ' . $prog . ' ' . $keyword.' "user['.$user.']"';
    $command = "/usr/bin/python3 /var/www/r2c.uca-project.com/Python/ProgToPDF.py $param 2>&1";
    shell_exec($command);

    // Log the generation of the PDF
    $typelog = "Information";
    $desclog = 'Génération d\'un fichier PDF des bonnes pratiques '.$listebp;
    $loginlog = $_SESSION['name'];
    include '/var/www/r2c.uca-project.com/Forms/addLogs.php';

    // Output the generated PDF content
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="Bonnes_pratiques_'.$user.'_'.$date.'.pdf"');// Name it Bonnes_pratiques_username_date.pdf
    readfile('/var/www/r2c.uca-project.com/Python/Download/Bonnes_Pratiques.pdf');
    exit; // Stop further execution of the script
  } else {
    // Log the error of generating the PDF
    $typelog = "Warning";
    $desclog = 'Erreur lors de la génération du fichier PDF certains parametres sont manquants';
    $loginlog = $_SESSION['name'];
    include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
    // Redirect to the validation page with the good message
    header('Location: ../Validation/validation.php?message=ecpdf');
  }
?>