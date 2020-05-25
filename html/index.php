
<?php
session_start();
include 'dbh.php';
 ?>
<!doctype html>

<html lang="hu">
<head>
  <meta charset="utf-8">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://momentjs.com/downloads/moment-with-locales.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.0.1/mqttws31.js" type="text/javascript"></script>
  <link rel="stylesheet" type="text/css" href="css/index.css">
  <link rel="stylesheet" type="text/css" href="css/battery.css">
  <link rel="stylesheet" type="text/css" href="css/robot.php">
  <link rel="stylesheet" type="text/css" href="css/toggle-switch.css">
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

  <title>Beágy projekt</title>
  <script>
    $(document).ready(function() {
      toggleLiveDB();


    });
    var refreshIsOn = 0;
    var liveMqttIsOn = 0;

    var refreshInterval = null;
    function toggleLiveDB() {

      if (document.getElementById("input_dbauto").checked) {
        if(typeof(Storage) !== "undefined"){
          sessionStorage.livedb = "true";
        }


        $("#div_autoref").load("div_autoref.php", {Newref: sessionStorage.livedb});

        refreshInterval = setInterval(function() {
          $("#packagesDiv").load("load-packages-table.php");
          $("#robotsDiv").load("load-robots-table.php");
          $("#stationsDiv").load("load-stations-table.php");
          $("#logsDiv").load("load-logs-table.php");
          $("#div_palya").load("load-palya.php");
          $("#div_errorstate").load("div_errorstate.php");
        }, 1000);
      }
      else {
        if(typeof(Storage) !== "undefined"){
          sessionStorage.livedb = "false";
        }

        $("#div_autoref").load("div_autoref.php", {Newref: sessionStorage.livedb});
        clearInterval(refreshInterval);
      }
    }
  </script>

<!-- MQTT Message script -->
  <script>
    var address = "<?php echo getHostByName(getHostName()); ?>";
    var port = "8000";
    var clientName = "WebPageClient";
    // Create a client instance
    client = new Paho.MQTT.Client(address, Number(port), clientName);

    // set callback handlers
    client.onConnectionLost = onConnectionLost;
    client.onMessageArrived = onMessageArrived;

    // connect the client
    function connectToMqtt() {
      if(!client.isConnected()){
        client.connect({onSuccess:onConnect});
      }
      else{
        onConnect();
      }

      liveMqttIsOn = 1;
      $("#div_livemqtt").load("div_livemqtt.php", {newliveMQTT: liveMqttIsOn});
    }

    function disconnectToMqtt() {
      client.disconnect();
      liveMqttIsOn = 0;
      $("#div_livemqtt").load("div_livemqtt.php", {newliveMQTT: liveMqttIsOn});
    }

    function toggleLiveMqtt(){
      if(document.getElementById("input_mqtt_msg").checked){
        if(!client.isConnected()){
          client.connect({onSuccess:onConnect});
        }
        else{
          onConnect();
        }

        liveMqttIsOn = 1;
        $("#div_livemqtt").load("div_livemqtt.php", {newliveMQTT: liveMqttIsOn});
      }
      else{
        client.disconnect();
        liveMqttIsOn = 0;
        $("#div_livemqtt").load("div_livemqtt.php", {newliveMQTT: liveMqttIsOn});
      }
    }

    // called when the client connects
    function onConnect() {
      // Once a connection has been made, make a subscription and send a message.
      console.log("onConnect");
      client.subscribe("root/#");
      /*message = new Paho.MQTT.Message("Hello");
      message.destinationName = "root/error";
      client.send(message);*/
    }

    // called when the client loses its connection
    function onConnectionLost(responseObject) {
      if (responseObject.errorCode !== 0) {
        console.log("onConnectionLost:"+responseObject.errorMessage);
      }
    }

    function onMessageArrived(message) {
      console.log("onMessageArrived: " + message.payloadString);
      let d = new Date($.now());

      document.getElementById("table_msg_tbody").innerHTML += '<tr class="w3-hover-blue"><td>' + moment().format("YYYY-MM-DD HH:mm:ss") +'</td><td>' + message.destinationName + '  </td><td> ' + message.payloadString + '</td></tr>';
    }

	function toggleMqttTable() {
      $("#messages").toggle()
    }
    function clearMqttTable() {
      $("#table_msg_tbody")[0].innerHTML = "";
    }
  </script>
</head>

<body>
<div class="columns">
  <div id="leftMenu">
    <div id="status" class="panelStyle">
        <div id="statustext">
          <div id="dbConnStatus">
            <?php
              if($conn-> connect_error){
                echo "<p class='p_connection'>DB connection:</p> <span style='color:red;font-weight:bold;'>OFF</span>";
              }
              else{
                echo "<p class='p_connection'>DB connection:</p> <span style='color:green;font-weight:bold;'>ON</span>";
              }
              ?>

          </div>
          <div id="div_autoref_ctn">
            <div id="div_autoref">
                <?php include "div_autoref.php"; ?>
            </div>
            <label class="switch">
              <input id="input_dbauto" type="checkbox" onclick="toggleLiveDB()" >
              <span class="slider round"></span>
            </label>
            <script>
    			  document.getElementById("input_dbauto").checked = JSON.parse(sessionStorage.livedb);
    			  </script>
          </div>
          <div id="div_livemqtt_ctn">
            <div id="div_livemqtt">
                <?php include "div_livemqtt.php"; ?>
            </div>
            <label class="switch">
              <input id="input_mqtt_msg" type="checkbox" onclick="toggleLiveMqtt()">
              <span class="slider round"></span>
            </label>
          </div>
          <div id="div_errorstate_ctn">
            <div id="div_errorstate">
                <?php include "div_errorstate.php"; ?>
            </div>
          </div>
        </div>
    </div>

    <div id="div_sendMqttFunctions" class="panelStyle">
      <?php
      include "sendMqttFuntions.php";
      ?>
    </div>

    <div id="div_parkingFunctions" class="panelStyle">
      <?php
      include "parkingFunctions.php";
      ?>
    </div>

    <div id="div_errorFunctions" class="panelStyle">
      <?php
      include "errorFunctions.php";
      ?>
    </div>
  </div>

  <div id="middleMenu">
    <div id="div_database_ctn" class="panelStyle">

      <div id="div_databaseTables">
        <h1>Database tables</h1><br>
        <div id="packagesDiv">
          <?php  include "load-packages-table.php"; ?>
        </div>

        <div id="robotsDiv">
          <?php  include "load-robots-table.php"; ?>
        </div>

        <div id="logsDiv">
          <?php  include "load-logs-table.php"; ?>
        </div>

        <div id="stationsDiv">
          <?php  include "load-stations-table.php"; ?>
        </div>
      </div>
    </div>

    <div id="div_palya_ctn" class="panelStyle">
      <div id="toggle_names">
        <p class='p_connection'>Show names: </p>
        <label class="switch">
          <input id="btn_shownames" type="checkbox">
          <span class="slider round"></span>
        </label>
      </div>
      <div id="div_palya">
        <?php  include "load-palya.php"; ?>
      </div>
    </div>
  </div>

  <div id="rightMenu">
    <div id="div_mqttmessageWindow" class="panelStyle">
      <?php
      include "mqttMessageWindow.php";
      ?>
    </div>
  </div>
</div>
</body>
</html>
