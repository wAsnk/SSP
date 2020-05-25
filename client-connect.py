#!python3
import paho.mqtt.client as mqtt
import time
import mysql.connector
import random

#mysql database details
mydb = mysql.connector.connect(
	host="localhost",
	user="root",
	passwd="123",
	database="beagy_labor"
)
mycursor = mydb.cursor()
broker="localhost"
client = mqtt.Client("ServerClient")

queues={}


############################ Functions #################################
def on_log(client, userdata, level, buf):
	print("log: "+buf)

def on_connect(client, userdata, flags, rc):
	if rc==0:
		print("Connected OK")
		client.subscribe("root/#")
		InitQueues()
	else:
		print("Not connected, RC=", rc)

def on_disconnect(client, userdata, flags, rc=0):
	print("Disconnected result code "+str(rc))

def on_message(client, userdata, msg):
	topic=msg.topic
	m_decode=str(msg.payload.decode("utf-8"))
	print("Topic: "+ topic + " Message: "+ m_decode + " QOS: " + str(msg.qos))
	
	
	LogMessage(m_decode, topic)
	
	#print("|" + topic + "|")
	
	if(topic == "root/db/packages/request"):
		PackageRequestHandle(m_decode)
	elif(topic == "root/db/packages/state"):
		PackageStateHandle(m_decode)
	elif(topic == "root/logs/robots"):
		LogRobotsHandle(m_decode)
	elif(topic == "root/logs/stations"):
		LogStationsHandle(m_decode)
	elif("root/nodeafter/" in topic and "queue" in topic):
		QueueHandle(m_decode, topic)
	elif(topic == "root/where"):
		WhereToGoHandle(m_decode)
	elif(topic == "root/parking"):
		ParkingHandle(m_decode)
	

#TODO: 
#DONE root/logs/robots 
#DONE root/logs/stations
#DONE root/nodeafter/<állomás RFID>/queue add <robot RFID>  vagy del <robot RFID>
#DONE root/nodeafter/<állomás RFID>/next
#
#
#DOC
#PA mint Parking RFID
#root/where
#root/go

def InitQueues():
	sql = "SELECT `Station_ID` FROM `Stations`"
	mycursor.execute(sql)
	myresult = mycursor.fetchall()
	print("Loading current stations.....")
	print("Current station RFIDs:")
	for row in myresult:
		q = list()
		queues[row[0]] = q
		print(row[0])

#works - But not needed beacuse the function only logs the message it has nothing to do with it
def ErrorHandle(msg):
	#message example
	#stop <RFID>
	#pl stop R00000023
	message = msg.split()
	if(message[0] == "stop" and len(message) == 2):
		#eltarolja a LOGS tablaba
		sql = f"INSERT INTO Logs ('Timestamp', 'Topic', 'Message') VALUES (NOW(), 'root/error', '{message[1]}')"
		mycursor.execute(sql)
		mydb.commit()
	#elif (message[0] == "continue" and len(message) == 1):
		#eltarolja a LOGS tablaba

#works - Package request handling function
def PackageRequestHandle(msg):
	#message example
	#req <csomag RFID> <robot RFID>
	#pl: req P003272531 R223275121
	message = msg.split()
	if(message[0] == "req" and  len(message) == 3):		
		sql = f"SELECT *  FROM Packages WHERE Package_RFID = '{message[1]}'"
		#print(sql)
		mycursor.execute(sql)
		myresult = mycursor.fetchone()
		#print(myresult)
		#pack <csomag RFID> to <allomas RFID> for <robot RFID>
		#root/db/packages/response
		publishmsg = "pack "+myresult[0]+" to "+myresult[1]+" for "+ message[2]
		#print(publishmsg)
		client.publish("root/db/packages/response", publishmsg)
		sql = f"UPDATE Packages SET Robot_RFID = '{message[2]}' WHERE Package_RFID = '{myresult[0]}'"
		mycursor.execute(sql)
		mydb.commit()		
		#print("done")


#works - Update package state in db
def PackageStateHandle(msg):
	#root/db/packages/state <- state <csomag RFID> <state>
	#pl: state P003272531 01
	message = msg.split()
	if (message[0] == "state" and len(message) == 3):
		sql = f"UPDATE Packages SET State = '{message[2]}' WHERE Package_RFID = '{message[1]}'"
		mycursor.execute(sql)
		mydb.commit()



def LogMessage(msg, topic):    
	#print("Logmessage started")
	sql = f"INSERT INTO `Logs`(`Timestamp`, `Topic`, `Message`) VALUES (NOW(), '{topic}' , '{msg}')"
	#print(sql)
	mycursor.execute(sql)
	mydb.commit()
	#print("Logmessage saved")


def QueueHandle(msg, topic):
	#msg = add R1
	#topic root/nodeafter/S1/queue
	message = msg.split()
	topic = topic.split('/')
	if (len(message) == 2):		
		if(message[0] == "add"):
			queues[topic[2]].append(message[1])
			#print(len(queues[topic[2]]))			
			if(len(queues[topic[2]]) == 1 ):
				#he comes next
				mqttMsg = "go " + message[1]
				mqttTopic = f"root/nodeafter/{topic[2]}/next"
				client.publish(mqttTopic, mqttMsg)				
				#print("Next")
		elif(message[0] == "del" ):
			#delete			
			#print(queues[topic[2]].pop(0))
			
			#then send the next if there is
			if (len(queues[topic[2]]) >= 1):				
				mqttMsg = "go " + queues[topic[2]][0]
				mqttTopic = f"root/nodeafter/{topic[2]}/next"
				client.publish(mqttTopic, mqttMsg)				


def LogRobotsHandle(msg):
	#root/logs/robots RobotRFID 90 parking
	message = msg.split()
	if (len(message) == 3):
		sql = f"UPDATE Robots SET Battery_state = '{message[1]}', Location= '{message[2]}' WHERE Robot_ID = '{message[0]}'"
		mycursor.execute(sql)
		mydb.commit()

def LogStationsHandle(msg):
	#root/logs/stations StationRFID 90
	message = msg.split()
	if (len(message) == 2):
		sql = f"UPDATE Stations SET Battery_state = '{message[1]}' WHERE Station_ID = '{message[0]}'"
		mycursor.execute(sql)
		mydb.commit()

def WhereToGoHandle(msg):
	#in
	#root/where where R1 scannedRFID
	#
	#out
	#root/go
	#go R1 left or ahead
	message = msg.split()
	if(message[0] == "where" and len(message) == 3):
		sql = f"SELECT *  FROM Packages WHERE Robot_RFID = '{message[1]}'"
		mycursor.execute(sql)
		myresult = mycursor.fetchall()
		for row in myresult:
			#search for an ongoing package which state should be 01
			if (row[3] == "01"):
				#if the station RFID is equel to the scanned one then go ahead
				if (row[1] == message[2]):
					client.publish("root/go", f"go {message[1]} ahead")
				#If it's a PA (parking) RFID, then check if it is allowed to park
				elif ( "PA" in message[2]):
					
					sql = f"SELECT * FROM System WHERE ID = '1'"
					mycursor.execute(sql)
					myresult = mycursor.fetchone()
					#if parking is allowed go ahead, else go left
					if (myresult[3] == "allowed"):
						client.publish("root/go", f"go {message[1]} ahead")
					elif (message[1] in myresult[3]):
						client.publish("root/go", f"go {message[1]} ahead")
					elif (myresult[3] == "denied"):
						client.publish("root/go", f"go {message[1]} left")
					else:
						client.publish("root/go", f"go {message[1]} left")
				#if it's not a station rfid and a parking rfid then it should be a gate rfid where it is random where to go
				else:
					directionList = ["ahead", "left"]
					mqttMsg = "go " + message[1] + " " + random.choice(directionList)
					client.publish("root/go", mqttMsg )


def ParkingHandle(msg):
	message = msg.split()
	
	if (len(message) == 2):		
		if (message[1] == "all"):
			sql = "UPDATE System SET Parking_State = 'allowed' WHERE ID = '1'"
			mycursor.execute(sql)
			mydb.commit()		
		else:
			print(message[1])
			sql = f"UPDATE System SET Parking_State = 'allowed for {message[1]}' WHERE ID = '1'"
			print(sql)
			mycursor.execute(sql)
			mydb.commit()
			print("done")
	elif (message[0] == "denied"):
		print("denied")
		sql = "UPDATE System SET Parking_State = 'denied' WHERE ID = '1'"
		mycursor.execute(sql)
		mydb.commit()

############################ Functions end #################################


client.on_connect=on_connect
client.on_disconnect=on_disconnect
client.on_message=on_message
#client.on_log=on_log

print("Connecting to broker ", broker)
client.connect(broker, port=1883)
client.loop_forever()
############################# Main loop ##################################### 
#client.loop_start()
#while True:
	#client.publish("root/error", "continue")
	#time.sleep(1)

	# root/parking
	# root/db/packages/response
	# root/nodeafter/<allrfid>/next

