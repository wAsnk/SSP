# SSP
Smart Storage Project with MQTT

In this project we've created a server which handles MQTT messages sent by robots or stations. It was made for a university group project, which involved the whole class. Some made robots and others made stations. Me and my class mate made the server which solves the communication between everyone.

The robots follow lines which simulates the path in a storage, they are equiped with NodeMCU to send messages via WIFI. 
Each of them has a package moving mechanism on top. They have ultrasound distance sensor and light sensor also which prevents collision.
On the bottom they have an RFID reader which reads the RFID tags which are on the floor next to the lines. The RFID tags specifies the position.

The stations are responsible to forward the packages to the robots and also to take them off.

# Requirements:
* Apache
* MySQL
* Python 3.6
* Eclipse Paho Python MQTT client
* MQTT broker with websocket support (e.g. HiveMQ)

The client-connect python file should be pasted into the Eclipse Paho Python MQTT client folder.
The html folder contains the GUI to handle the system. 

Full size picture of the GUI (background tear is only there because of the fullscreen screenshot):
![Image of theGUI](https://raw.githubusercontent.com/wAsnk/SSP/master/images/screenshot.png)
