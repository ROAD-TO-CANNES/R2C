<?php
// Connection to the database
try { // try to connect to the database
  $BDD = new PDO(
    'mysql:host=localhost;dbname=project;charset=utf8',
    'tomh',
    'HJ34!5r&*'
  );
} catch (Exception $e) { // if the connection failed display an error message
  echo ('Erreur : ' . $e->getMessage());
}
