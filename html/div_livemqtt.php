<?php
  echo "<p class='p_connection'>Live MQTT: </p>";

  $liveMQTT = $_POST['newliveMQTT'];
    if ($liveMQTT == 1) {
      echo "<span style='color:green;font-weight:bold;'> ON</span>";
    }
    else {
      echo "<span style='color:red;font-weight:bold;'> OFF</span>";
    }
?>
