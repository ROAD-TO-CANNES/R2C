<?php
session_start();
include '/var/www/r2c.uca-project.com/Form/checkSession.php';

if(isset($_POST['generate_pdf'])) {
  $bps = json_decode($_POST['generate_pdf']);
  $command = "python3 /var/www/r2c.uca-project.com/Python/pdf_output.py";
  $output = shell_exec($command);

  if (!empty($bps)) {
    $test = 'plein';
  } else {
    $test = 'vide';
  }

  // Set the appropriate headers for PDF download
  header('Content-Type: application/pdf');
  header('Content-Disposition: attachment; filename="'.$test.'.pdf"');

  // Output the generated PDF content
  echo $output;
  exit; // Stop further execution of the script
}
?>
<script>
  function generatePDF() {
    fetch('/home/tom/Bureau/Cours_BUT_RT1/S2/SAE/SAE-24_Thales_PHP/Site/Accueil/accueil.php', {
      method: 'POST',
      body: JSON.stringify({
        generate_pdf: true,
        bpsFiltered: <?php echo json_encode($bps); ?>
      })
    })
    .then(response => response.blob())
    .then(blob => {
      // Create a temporary URL for the generated PDF
      const url = URL.createObjectURL(blob);

      // Create a link element and simulate a click to trigger the download
      const link = document.createElement('a');
      link.href = url;
      link.download = 'generated_pdf.pdf';
      link.click();

      // Clean up the temporary URL
      URL.revokeObjectURL(url);
    })
    .catch(error => {
      // Handle the error
      console.error(error);
    });
  }
</script>