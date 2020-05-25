<script>
function connectif(func){
    if (!client.isConnected()) {
      client.connect({onSuccess: func});

    }
    else {
      func();
    }
}

function parkRobot(){
  if ($("#robot").val() != "") {
    var msgToSend = new Paho.MQTT.Message("park " + $("#robots_list").val());
    msgToSend.destinationName = "root/parking";
    client.send(msgToSend);
  }
}

function parkAllRobot(){
  var msgToSend = new Paho.MQTT.Message("park all");
  msgToSend.destinationName = "root/parking";
  client.send(msgToSend);
}

function noparkAllRobot(){
  var msgToSend = new Paho.MQTT.Message("denied");
  msgToSend.destinationName = "root/parking";
  client.send(msgToSend);
}

function toggleparkAllRobot(){
  if(document.getElementById("input_parkRobots").checked){
    var msgToSend = new Paho.MQTT.Message("park all");
    msgToSend.destinationName = "root/parking";
    client.send(msgToSend);
  }
  else {
    var msgToSend = new Paho.MQTT.Message("denied");
    msgToSend.destinationName = "root/parking";
    client.send(msgToSend);
  }
}
</script>


<h1>Parking functions</h1><br>
<form id="form_parking" name="form_parking">
<label for="robots_list">Choose robot(s) to park:</label>
<br>
<div id="robots_list_ctn">
  <select id="robots_list" size="5" multiple>
    <?php
    include dbh.php;
    $sql = "SELECT * FROM Robots ORDER BY Owner ASC";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){
      while ($row = mysqli_fetch_assoc($result)) {
        echo "<option value='". $row['Robot_ID'] ."'>". $row['Owner'] ."</option>";
      }
    }
    ?>
  </select>
  <input type="button" id="parkrobot" name="parkrobot" onclick="connectif(parkRobot)" value="Send">
</div>
</form>
<br>
<label for="topic">Park everyone: </label>
<!--<label class="switch">
  <input id="input_parkRobots" type="checkbox" onclick="connectif(toggleparkAllRobot)">
  <span class="slider round"></span>
</label>-->
<input type="button" id="parkAll" name="parkAll" onclick="connectif(parkAllRobot)" value="ON">
<input type="button" id="noparkAll" name="noparkAll" onclick="connectif(noparkAllRobot)" value="OFF">
