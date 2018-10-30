
$startTimeafter30Min = date('Y-m-d H:i:s', strtotime("+30 minutes"));


# inserting in Devices tables
objects = [
    Devices(deviceID="1", name='user1', types='user', ipv4='10.0.0.107', ipv6='10.0.0.107', mac='0283ea6e1fe0'),
    Devices(deviceID="2", name='user2', types='user', ipv4='10.0.0.109', ipv6='10.0.0.109', mac='0245dbc7d81f'),
    Devices(deviceID="3", name='attacker1', types='user', ipv4='10.0.0.108', ipv6='10.0.0.108', mac='024089e25896'),
    Devices(deviceID="4", name='attacker2', types='user', ipv4='10.0.0.110', ipv6='10.0.0.110', mac='0243b69c46be'),
    Devices(deviceID="5", name='attacker3', types='user', ipv4='10.0.0.106', ipv6='10.0.0.106', mac='026c160681b5'),
    Devices(deviceID="6", name='qvm', types='qvm', ipv4='10.0.0.105', ipv6='10.0.0.105', mac='02744a0ec85d')
]
session.bulk_save_objects(objects)
session.commit()

# inserting in Users tables
Users(userID="1", username='user1', ipAddress='10.0.0.107','connectionStartTime'=current_date_timestamp),
    Users(userID="2", username='user2', ipAddress='10.0.0.109,'connectionStartTime'=$startTimeafter30Min),
    Users(userID="3", username='attacker1', ipAddress='10.0.0.108','connectionStartTime'=current_date_timestamp),
    Users(userID="4", username='attacker2', ipAddress='10.0.0.110','connectionStartTime'=$startTimeafter30Min),
    Users(userID="5", username='attacker3', ipAddress='10.0.0.106','connectionStartTime'=current_date_timestamp),
    Users(userID="6", username='qvm', ipAddress='10.0.0.105','connectionStartTime'=$startTimeafter30Min)
session.bulk_save_objects(objects)
session.commit()


# inserting into Qvm

objects = [
    Qvm(qvmUID="1", qvmName='Qvm', qvmIP='10.0.0.105')

]
session.bulk_save_objects(objects)
session.commit()

# inserting server

objects = [
    Servers(serverUID="11", serverName='server1', serverIP='10.0.0.102', serverCreatedOn=current_date_timestamp, reputationValue='10', bidValue='5'),
    Servers(serverUID="12", serverName='server2', serverIP='10.0.0.103', serverCreatedOn=current_date_timestamp, reputationValue='8', bidValue='4'),
    Servers(serverUID="13", serverName='server3', serverIP='10.0.0.104', serverCreatedOn=current_date_timestamp, reputationValue='7', bidValue='6')
  ]
session.bulk_save_objects(objects)
session.commit()

# inserting into SwitchDevices
objects = [
    SwitchDevices(switchID="196040413341508", deviceID='11', port='2'),
    SwitchDevices(switchID="196040413341508", deviceID='12', port='3'),
    SwitchDevices(switchID="196040413341508", deviceID='13', port='4'),

    SwitchDevices(switchID="77043891114308", deviceID='1', port='7'),
    SwitchDevices(switchID="77043891114308", deviceID='2', port='4'),
    SwitchDevices(switchID="77043891114308", deviceID='3', port='3'),
    SwitchDevices(switchID="77043891114308", deviceID='4', port='2'),
    SwitchDevices(switchID="77043891114308", deviceID='5', port='6'),
    SwitchDevices(switchID="77043891114308", deviceID='6', port='5'),

  ]
session.bulk_save_objects(objects)
session.commit()

# inserting Switches

objects = [
    Switches(switchID="196040413341508", name='root-switch', totalPorts='4', score='4'),
    Switches(switchID="77043891114308", name='slave-switch', totalPorts='7', score='7')
  ]
session.bulk_save_objects(objects)
session.commit()


# ad_rule = Rules(rule="Rule 3",loaded=3)
# session.add(ad_rule)
    #session.execute("Alter table suspiciousness_scores add name String(20);")

