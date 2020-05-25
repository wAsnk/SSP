<?php
    include 'dbh.php';
    echo "<table class='w3-table-all' id='table_logs'><thead><tr><th class='table_title' colspan='4'>Logs</th></tr>"
      ."<th>ID</th>"
      ."<th>Timestamp</th>"
      ."<th>Topic</th>"
      ."<th>Message</th></thead>";
    //$sql = "SELECT * FROM Logs ORDER BY ID DESC LIMIT 10";
	$sql = "SELECT * FROM Logs ORDER BY ID DESC LIMIT 100";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){

      while ($row = mysqli_fetch_assoc($result)) {
        // code...
        echo "<tr class='w3-hover-blue'>";
        echo "<td>" . $row['ID'] . "</td>"
        . "<td>" . $row['Timestamp'] . "</td>"
        . "<td>" . $row['Topic'] . "</td>"
        . "<td>" . $row['Message'] . "</td>";
        echo "</tr>";
      }

    }
    else{
      echo "<tr><td colspan='4'>No Logs were found in the database!</td></tr>";
    }
    echo "</table>";
  ?>
