<?php
session_start();
include '/var/www/r2c.uca-project.com/Form/checkSession.php';

if(isset($_POST['generate_csv'])) {
  $bpsFiltered = $_POST['bpsFiltered'];
  $command = "python3 /var/www/r2c.uca-project.com/Python/ProgToCSV.py";
  $output = shell_exec($command);

  // Set the appropriate headers for CSV download
  header('Content-Type: text/csv');
  header('Content-Disposition: attachment; filename="Bonnes-pratiques.csv"');

  // Output the generated CSV content
  echo $output;
  exit; // Stop further execution of the script
}
?>
<script>
  function generateCSV() {
    fetch('/home/tom/Bureau/Cours_BUT_RT1/S2/SAE/SAE-24_Thales_PHP/Site/Accueil/accueil.php', {
      method: 'POST',
      body: JSON.stringify({
        generate_csv: true,
        bpsFiltered: <?php echo json_encode($bpsFiltered); ?>
      })
    })
    .then(response => response.blob())
    .then(blob => {
      // Create a temporary URL for the generated CSV
      const url = URL.createObjectURL(blob);

      // Create a link element and simulate a click to trigger the download
      const link = document.createElement('a');
      link.href = url;
      link.download = 'generated_csv.csv';
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