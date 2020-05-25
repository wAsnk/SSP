<script>

function sendmqttmsg(){
  if ($("#topic").val() != "" && $("#msg").val() != "") {
    var msgToSend = new Paho.MQTT.Message($("#msg").val());
    msgToSend.destinationName = $("#topic").val();
    client.send(msgToSend);
  }
}

</script>


<h1>MQTT Console</h1><br>
<form id="mqttsender" name="mqttsender">
<label for="topic">Topic: </label>
<br>
<input type="text" id="topic" name="topic">
<br>
<label for="msg">Message: </label>
<br>
<input type="text" id="msg" name="msg">
<br><input type="button" id="submit" name="submit" value="Send" onclick="connectif(sendmqttmsg)">
</form>
