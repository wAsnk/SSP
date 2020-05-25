<script>
$(document).ready(function() {
  if (typeof(Storage) !== "undefined") {
    if(!sessionStorage.robot_hover){
      sessionStorage.robot_hover = "none";
      $(".robot_hover").css("display", "none");
      $("#btn_shownames").prop("checked", false);
    }
    else{
      if (sessionStorage.robot_hover == "hover") {
        console.log("hover");
        $(".robot_hover").css("display", "block");
        $("#btn_shownames").prop("checked", true);
      }
      else if (sessionStorage.robot_hover == "none") {
        console.log("none");
        $(".robot_hover").css("display", "none");
        $("#btn_shownames").prop("checked", false);
      }
    }
  }

});
$(function() {
  $("#btn_shownames").click(function() {
    if (typeof(Storage) !== "undefined") {
      if(sessionStorage.robot_hover){
        if (sessionStorage.robot_hover == "hover") {
          sessionStorage.robot_hover = "none";
          $(".robot_hover").css("display", "none");
        }
        else if (sessionStorage.robot_hover == "none") {
          sessionStorage.robot_hover = "hover";
          $(".robot_hover").css("display", "block");
        }
      }
      else{
        sessionStorage.robot_hover = "hover";
        $(".robot_hover").css("display", "block");
      }
    }
    else{
      console.log("else");
    }
  });
});


</script>
<img src="img/palya.png" width="100%">
<!--<input type="button" id="btn_shownamess" value="Toggle Names">-->
<?php
    include 'dbh.php';
    $S1 = "S1";

    $sql = "SELECT * FROM Robots";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){
      $pc = 0;
      $parking = 0;
      $s1pos = 0;

      while ($row = mysqli_fetch_assoc($result)) {
        // code...
        $rand_color = "#".substr(md5($row['Owner']), 0, 6);
        $pc = $pc + 1;
        if ($row['Location'] == "parking") {
          echo '<div id="R'. $pc .'" class="robot parking" style="--pos:'. $parking .'%; --bg-color: '. $rand_color .';">
          <div class="robot_hover parking">'. $row['Owner'] .'</div>
          </div>';
          $parking = $parking + 5;
        }
        else if ($row['Location'] == $S1) {
          echo '<div id="R'. $pc .'" class="robot '. $S1 .'" style="--pos:'. $s1pos .'%; --bg-color: '. $rand_color .';">
          <div class="robot_hover '. $S1 .'">' . $row['Owner'] .'</div>
          </div>';
          $s1pos = $s1pos + 5;
        }
      }

    }
  ?>
