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


class Blacklist(Base):
    __tablename__ = 'blacklist'
    ipAddressuserIP = Column('ipAddress',String(20), primary_key=True)
    macAddress = Column('macAddress', String(20), nullable=True)
    blacklistedOn = Column('blacklistedOn',DateTime, nullable=True)


class Devices(Base):
    __tablename__ = 'devices'
    deviceID = Column('deviceID', Integer, primary_key=True)
    name = Column('name', String(45), nullable=False, unique=True)
    types = Column('types', String(45), nullable=True)
    ipv4 = Column('ipv4', String(15), nullable=True)
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
    
class Logs(Base):	
    __tablename__ = 'logs'	
    log_id = Column('log_id',Inetegre,Primary_key=True)
    switch_id = Column('switch_id', BigInteger,nullable=False)
    unixtimestamp = Column('unixtimestamp',Integer)		
    port_id = Column('port_id', String(20), default=None)
    tx_packets = Column('tx_packets', Integer, default=0)		
    rx_packets = Column('rx_packets', Integer, default=0)	
    tx_bytes = Column('tx_bytes', Integer, default=0)	
    rx_bytes = Column('rx_bytes', Integer, default=0)	
    tx_errors = Column('tx_errors', Integer, default=0)	
    rx_errors = Column('rx_errors', Integer, default=0)

class Policies(Base):
    __tablename__ = 'policies'
    policyID = Column('policyID', String(36), primary_key=True)
    deviceID = Column('deviceID', Integer, nullable=False)
    src_ip = Column('src_ip', String(45), default=None)
    dst_IP = Column('dst_ip', String(45), default=None)
    switch1 = Column('switch1', String(45), default=None)
    switch2 = Column('switch2', String(45), default=None)
    senderPort = Column('senderPort', Integer, default=None)
    receiverPort = Column('receiverPort', Integer, default=None)
    loaded = Column('loaded',Integer, default=None)


class Qvm(Base):
    __tablename__ = 'qvm'
    qvmUID = Column('qvmUID', String(100), primary_key=True)
    qvmName = Column('qvmName', String(255), nullable=True)
    qvmIP = Column('qvmIP', String(20), default=None)
    qvmStartTime = Column('qvmStartTime', DateTime, default=None)
    numberOfAttackers = Column('numberOfAttackers', Integer, default=None)
    currentlyActive = Column('currentlyActive', Integer, default=None)


class Servers(Base):
    __tablename__ = 'servers'
    serverUID = Column('serverUID', String(100), primary_key=True)
    serverName = Column('serverName', String(125), default=None)
    serverIP = Column('serverIP', String(20), default=None)
    serverCreatedOn = Column('serverCreatedOn', DateTime, default=None)
    reputationValue = Column('reputationValue', Numeric, default=None)
    bidValue = Column('bidValue', Numeric, default=None)


class SuspiciousnessScores(Base):
    __tablename__ = 'suspiciousness_scores'
    SSID = Column('sssID', Integer, primary_key=True, autoincrement=True)
    deviceID = Column('device_id', Integer)
    traceID = Column('traceID', Integer)
    ssscore_caluculated_time = Column('ssscore_caluculated_time', DateTime)
    name = Column('name', String(20), default=None)
    score = Column('score', Numeric, default=0)


class SuspiciousnessScoresByTime(Base):
    __tablename__ = 'suspiciousness_scores_by_time'
    SSIDByTime = Column('sssIDByTime', Integer, primary_key=True, autoincrement=True)
    deviceID = Column('device_id', Integer)
    traceID = Column('traceID', Integer)
    suspiciousness_caluculated_time = Column('suspiciousness_caluculated_time', DateTime)
    name = Column('name', String(20), default=None)
    score = Column('score', Numeric, default=0)


class SwitchDevices(Base):
    __tablename__ = 'switch_devices'
    switchID = Column('switchID', BigInteger, primary_key=True)
    deviceID = Column('deviceID', Integer, primary_key=True)
    port = Column('port', Integer, primary_key=True)


class Switches(Base):
    __tablename__ = 'switches'
    switchID = Column('switchID', BigInteger, nullable=False, primary_key=True)
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
    userUID = Column('userID', String(100), nullable=False, primary_key=True)
    username = Column('username', String(100), default=None)
    ipAddressuserIP = Column('ipAddress', String(20), default=None)
    connectionStartTime = Column('connectionStartTime', DateTime, default=None)
    connectionStopTime = Column('connectionStopTime', DateTime, default=None)


class Whitelist(Base):
    __tablename__ = 'whitelist'
    ipv4 = Column('ipv4', String(15), nullable=False, primary_key=True)



session = Session()
Base.metadata.create_all(engine)
session.commit()
