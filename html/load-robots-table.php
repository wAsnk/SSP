<?php
    include 'dbh.php';
    echo "<table class='w3-table-all' id='table_robots'><thead><tr><th class='table_title' colspan='4'>Robots</th></tr>"
      ."<th>Robot_RFID</th>"
      ."<th>Owner</th>"
      ."<th>Battery_state</th>"
      ."<th>Location</th></thead>";
    $sql = "SELECT * FROM Robots";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){

      while ($row = mysqli_fetch_assoc($result)) {
        // code...
        echo "<tr class='w3-hover-blue'>";
        echo "<td>" . $row['Robot_ID'] . "</td>"
        . "<td>" . $row['Owner'] . "</td>"
        . "<td>"
        . ($row['Battery_state'] != null ? ($row['Battery_state'] < 50
          ? ($row['Battery_state'] < 25
            ? '<div class="battery"><div class="normal">' . $row['Battery_state'] . '%</div><div class="battery-level alert" style="width:' . $row['Battery_state'] . '%;"></div></div>'
            : '<div class="battery"><div class="normal">' . $row['Battery_state'] . '%</div><div class="battery-level warn" style="width:' . $row['Battery_state'] . '%;"></div></div>')
        : '<div class="battery"><div class="normal">' . $row['Battery_state'] . '%</div><div class="battery-level" style="width:' . $row['Battery_state'] . '%;"></div></div>' )
        : '<div class="battery"><div class="normal">N/A</div><div class="battery-level" style="width:0%"></div></div>')
        . "</td>"
        . "<td>" . $row['Location'] . "</td>";
        echo "</tr>";
      }

    }
    else{
      echo "<tr><td colspan='4'>No Robots were found in the database!</td></tr>";
    }

    echo "</table>";
  ?>
