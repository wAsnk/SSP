<script>
function error_stop(){
  var msgToSend = new Paho.MQTT.Message("stop server");
  msgToSend.destinationName = "root/error";
  client.send(msgToSend);
}

function error_continue(){
  var msgToSend = new Paho.MQTT.Message("continue");
  msgToSend.destinationName = "root/error";
  client.send(msgToSend);
}
</script>


<h1>Error functions</h1><br>
<input type="button" id="error_stop" name="error_stop" onclick="connectif(error_stop)" value="Stop">
<input type="button" id="error_continue" name="error_continue" onclick="connectif(error_continue)" value="Continue">
