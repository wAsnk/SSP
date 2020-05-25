<?php
  include 'dbh.php';

  $divnewcount = $_POST['keydownNew'];

  echo "<table class='w3-table-all' id='table_packages'><thead><tr><th class='table_title' colspan='4'>Packages</th></tr><th>Package_RFID</th><th>Station_RFID</th><th>Robot_RFID</th><th>State</th></thead>";
  $sql = "SELECT * FROM Packages";
  $result = mysqli_query($conn, $sql);
  if(mysqli_num_rows($result) > 0){

    while ($row = mysqli_fetch_assoc($result)) {
      // code...
      echo "<tr class='w3-hover-blue'>";
      echo "<td>" . $row['Package_RFID'] . "</td>"
      . "<td>" . $row['Station_RFID'] . "</td>"
      . "<td>" . $row['Robot_RFID'] . "</td>"
      . "<td>" . $row['State'] . "</td>";
      echo "</tr>";
    }
    echo "</table>";
  }
  else{
    echo "There are no Packages!";
  }
?>
