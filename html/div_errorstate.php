<?php
  include 'dbh.php';
  echo "<p class='p_connection'>Error state: </p>";
  $sql = "SELECT * FROM System";
  $result = mysqli_query($conn, $sql);
  if(mysqli_num_rows($result) > 0){
    while($row = mysqli_fetch_assoc($result)){
      if($row['Error_State'] == "stop"){
        echo "<span style='color:red;font-weight:bold;'> ERROR</span>";
      }
      else if($row['Error_State'] == "continue"){
        echo "<span style='color:green;font-weight:bold;'> CONTINUE</span>";
      }
    }
  }
  else {
    echo "<span style='color:grey;font-weight:bold;'> UNKNOWN</span>";
  }
?>
