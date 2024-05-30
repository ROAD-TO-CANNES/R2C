<?php
  if (isset($typelog) && isset($desclog) && isset($loginlog)) {
    $encote_typelog = $BDD->quote($typelog);
    $encote_desclog = $BDD->quote($desclog);
    $encote_loginlog = $BDD->quote($loginlog);
    date_default_timezone_set('Europe/Paris');
    $datelog = date('Y-m-d H:i:s');

    $sql = "INSERT INTO LOGS (type, datea, desca, login) VALUES ($encote_typelog, '$datelog', $encote_desclog, $encote_loginlog)";
    $request = $BDD->prepare($sql);
    $request->execute();
  }
?>