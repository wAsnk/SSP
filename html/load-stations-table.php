<?php
    include 'dbh.php';
    echo "<table class='w3-table-all' id='table_stations'><thead><tr><th class='table_title' colspan='2'>Stations</th></tr>"
    ."<th>Station_ID</th>"
    ."<th>Battery_state</th></thead>";
    $sql = "SELECT * FROM Stations";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){

      while ($row = mysqli_fetch_assoc($result)) {
        // code...
        echo "<tr class='w3-hover-blue'>";
        echo "<td>" . $row['Station_ID'] . "</td>"
        . "<td>"
        . ($row['Battery_state'] != null ? ($row['Battery_state'] < 50
          ? ($row['Battery_state'] < 25
            ? '<div class="battery"><div class="normal">' . $row['Battery_state'] . '%</div><div class="battery-level alert" style="width:' . $row['Battery_state'] . '%;"></div></div>'
            : '<div class="battery"><div class="normal">' . $row['Battery_state'] . '%</div><div class="battery-level warn" style="width:' . $row['Battery_state'] . '%;"></div></div>')
        : '<div class="battery"><div class="normal">' . $row['Battery_state'] . '%</div><div class="battery-level" style="width:' . $row['Battery_state'] . '%;"></div></div>' )
        : '<div class="battery"><div class="normal">N/A</div><div class="battery-level" style="width:0%"></div></div>')
        . "</td>";
        echo "</tr>";
      }

    }
    else{
      echo "<tr><td colspan='2'>No Stations were found in the database!</td></tr>";
    }
    echo "</table>";
  ?>
