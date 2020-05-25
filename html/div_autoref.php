<?php
session_start();
  echo "<p class='p_connection'>Live Data: </p>";

  if (isset($_POST['Newref'])) {
    $_SESSION['newonoroff'] = $_POST['Newref'];
  }

  if ($_SESSION['newonoroff'] == "true") {
    echo "<span style='color:green;font-weight:bold;'> ON</span>";
  }
  else {
    echo "<span style='color:red;font-weight:bold;'> OFF</span>";
  }
?>
