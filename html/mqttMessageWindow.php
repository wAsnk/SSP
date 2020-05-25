<div id="message_ctn">
  <h1>Live MQTT</h1><br>
  <!--Live root/# MQTT message window:
  <button id="btn_liveMqtt" onclick="connectToMqtt()">Connect</button>
  <button id="btn_liveMqtt" onclick="disconnectToMqtt()">Disconnect</button>-->

  <button id="btn_liveMqtt" onclick="toggleMqttTable()">Toggle show</button>
  <button onclick="clearMqttTable()">Clear messages</button><br>
  <div id="messages">

    <table id="table_msg" class="w3-table-all">
      <thead>
        <tr>
          <th class="timestamp">Timestamp</th>
          <th class="topic">Topic</th>
          <th class="message">Message</th>
        </tr>
      </thead>
      <tbody id="table_msg_tbody">
      </tbody>
    </table>
  </div>
</div>
