from sqlalchemy import Column, ForeignKey, Integer, String, Boolean, Numeric, DateTime, BigInteger
from settings import Session, engine, Base
import datetime
import time
# buildDB.sql scripts automated in sql alchemy
timestamp = time.time()
current_date_timestamp = datetime.datetime.fromtimestamp(timestamp).strftime('%Y-%m-%d %H:%M:%S')
# attackhistory table

class Attackhistory(Base):
    __tablename__ = 'attackhistory'
    attacker_id = Column('attacker_id',String(100), primary_key=True)
    source_IP = Column('source_IP', String(20), nullable=True)
    destination_IP = Column('destination_IP', String(20), nullable=True)
    attackStartTime = Column(DateTime, nullable=True)
    attackStopTime = Column(DateTime, nullable=True)
    numberOfPackets = Column('numberOfPackets', Integer, nullable=True)


class Blacklist(Base):
    __tablename__ = 'blacklist'
    ipAddressuserIP = Column('ipAddress',String(20), primary_key=True)
    macAddress = Column('macAddress', String(20), nullable=True)
    blacklistedOn = Column('blacklistedOn',DateTime, nullable=True)


class Devices(Base):
    __tablename__ = 'devices'
    deviceID = Column('deviceID', Integer, primary_key=True)
    name = Column('name', String(45), nullable=False, unique=True)
    type = Column('type', Integer, nullable=True)
    ipv4 = Column('ipv4',String(15), nullable=True)
    ipv6 = Column('ipv6', String(45), nullable=True)
    mac = Column('mac', String(45), nullable=True)


class Login(Base):
    __tablename__ = 'login'
    username = Column('username', String(255), primary_key=True, nullable=False)
    password = Column('password', String(255), nullable=False)
    remember_token = Column('remember_token', Integer, nullable=True)
    created_at = Column('created_at', DateTime, nullable=True)
    updated_at = Column('updated_at', DateTime, nullable=True)


class PacketLogs(Base):
    __tablename__ = 'packet_logs'
    switch_id = Column('switch_id', BigInteger, primary_key=True, nullable=False)
    trace_id = Column('trace_id', Integer, primary_key=True, nullable=False)
    frame_number = Column('frame_number', Integer, primary_key=True, nullable=False)
    frame_time = Column('frame_time', Integer, default=None)
    frame_time_relative = Column('frame_time_relative', Numeric(30), default=None)
    frame_protocols = Column('frame_protocols', String(50), default=None)
    frame_len = Column('frame_len', Integer, default=None)
    eth_src = Column('eth_src', String(20), default=None)
    eth_dst = Column('eth_dst', String(20), default=None)
    eth_type = Column('eth_type', String(10), default=None)
    ip_proto = Column('ip_proto', Integer, default=None)
    ip_src = Column('ip_src', String(15), default=None)
    ip_dst = Column('ip_dst', String(15), default=None)

class Policies(Base):
    __tablename__ = 'policies'
    policyID = Column('policyID', String(36), primary_key=True)
    deviceID = Column('deviceID', Integer, ForeignKey("devices.deviceID"), nullable=False)
    policy = Column('policy', String(40), nullable=False)
    loaded = Column('loaded',Integer, default=None)
    ipv6 = Column('ipv6', String(45), default=None)
    mac = Column('mac', String(45), default=None)


class Qvm(Base):
    __tablename__ = 'qvm'
    qvmUID = Column('qvmUID', String(100), primary_key=True)
    qvmName = Column('qvmName', String(255), nullable=True)
    qvmIP = Column('qvmIP', String(20), default=None)
    qvmStartTime = Column('qvmStartTime',DateTime, default=None)
    numberOfAttackers = Column('numberOfAttackers', Integer, default=None)
    currentlyActive = Column('currentlyActive', Integer, default=None)


class Servers(Base):
    __tablename__ = 'servers'
    serverUID = Column('serverUID', String(100), primary_key=True)
    serverName = Column('serverName', String(1255), default=None)
    serverIP = Column('serverIP', String(20), default=None)
    serverCreatedOn = Column('serverCreatedOn', DateTime, default=None)
    reputationValue = Column('reputationValue', Numeric, default=None)
    bidValue = Column('bidValue', Numeric, default=None)


class SuspiciousnessScores(Base):
    __tablename__ = 'suspiciousness_scores'
    deviceID = Column('device_id', Integer, primary_key=True)
    traceID = Column('traceID', Integer, primary_key=True)
    ssscore_caluculated_time = Column('ssscore_caluculated_time', DateTime, primary_key=True)
    score = Column('score', Numeric, default=0)


class SuspiciousnessScoresByTime(Base):
    __tablename__ = 'suspiciousness_scores_by_time'
    deviceID = Column('device_id', Integer, primary_key=True)
    traceID = Column('traceID', Integer, primary_key=True)
    frame_time = Column('frame_time', Integer, primary_key=True)
    score = Column('score', Numeric, default=0)
    suspiciousness_caluculated_time = Column('suspiciousness_caluculated_time', DateTime, primary_key=True)


class SwitchDevices(Base):
    __tablename__ = 'switch_devices'
    switchID = Column('switchID', BigInteger, nullable=False)
    deviceID = Column('deviceID', Integer, nullable=False)
    port = Column('port', Integer, primary_key=True)


class Switches(Base):
    __tablename__ = 'switches'
    switchID = Column('switchID', Integer, nullable=False, primary_key=True)
    name = Column('name', String(45), nullable=False, unique=True)
    totalPorts = Column('totalPorts', Integer, default=0)
    score = Column('score', Numeric, primary_key=True)


class UserMigration(Base):
    __tablename__ = 'usermigration'
    userMigrationUID = Column('userMigrationUID', String(100), nullable=False, primary_key=True)
    userIP = Column('userIP', String(45), nullable=True)
    originalServerIP = Column('originalServerIP', String(45), default=None)
    migratedServerIP = Column('migratedServerIP', String(45), default=None)
    migrationStartTime = Column('migrationStartTime', DateTime, default=None)
    migrationStopTime = Column('migrationStopTime', DateTime, default=None)


class Users(Base):
    __tablename__ = 'users'
    userUID = Column('userMigrationUID', String(100), nullable=False, primary_key=True)
    username = Column('username', String(100), default=None)
    ipAddressuserIP = Column('ipAddress', String(20), default=None)
    serverIP = Column('serverIP', String(20), default=None)
    connectionStartTime = Column('connectionStartTime', DateTime, default=None)
    connectionStopTime = Column('connectionStopTime', DateTime, default=None)


class Whitelist(Base):
    __tablename__ = 'whitelist'
    ipv4 = Column('ipv4', String(15), nullable=False, primary_key=True)



session = Session()
Base.metadata.create_all(engine)
session.commit()

# inserting data here

# inserting in Devices tables
objects = [
    Devices(deviceID="1", name='user1', type='user', ipv4='10.0.0.107', ipv6='10.0.0.107', mac='0283ea6e1fe0'),
    Devices(deviceID="2", name='user2', type='user', ipv4='10.0.0.109', ipv6='10.0.0.109', mac='0245dbc7d81f'),
    Devices(deviceID="3", name='attacker1', type='user', ipv4='10.0.0.108', ipv6='10.0.0.108', mac='024089e25896'),
    Devices(deviceID="4", name='attacker2', type='user', ipv4='10.0.0.110', ipv6='10.0.0.110', mac='0243b69c46be'),
    Devices(deviceID="5", name='attacker3', type='user', ipv4='10.0.0.106', ipv6='10.0.0.106', mac='026c160681b5'),
    Devices(deviceID="6", name='qvm', type='qvm', ipv4='10.0.0.105', ipv6='10.0.0.105', mac='02744a0ec85d')
]
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
    SwitchDevices(switchID="129860609480772", deviceID='11', port='2'),
    SwitchDevices(switchID="129860609480772", deviceID='12', port='3'),
    SwitchDevices(switchID="129860609480772", deviceID='13', port='4'),

    SwitchDevices(switchID="178505423650629", deviceID='1', port='7'),
    SwitchDevices(switchID="178505423650629", deviceID='2', port='4'),
    SwitchDevices(switchID="178505423650629", deviceID='3', port='3'),
    SwitchDevices(switchID="178505423650629", deviceID='4', port='2'),
    SwitchDevices(switchID="178505423650629", deviceID='5', port='6'),
    SwitchDevices(switchID="178505423650629", deviceID='6', port='5'),

  ]
session.bulk_save_objects(objects)
session.commit()

# inserting Switches

objects = [
    Switches(switchID="129860609480772", name='root-switch', totalPorts='4', score='4'),
    Switches(switchID="178505423650629", name='slave-switch', totalPorts='7', score='7')
  ]
session.bulk_save_objects(objects)
session.commit()


# ad_rule = Rules(rule="Rule 3",loaded=3)
# session.add(ad_rule)
#session.execute("Alter table products add rating Integer;")
