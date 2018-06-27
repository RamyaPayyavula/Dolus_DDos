from sqlalchemy import Column, ForeignKey, Integer, String, Boolean, Numeric, DateTime
from python.settings import Session, engine, Base
#
#
# class Product(Base):
#     __tablename__ = 'products'
#     id = Column(Integer, primary_key=True)
#     title = Column('title', String(32))
#     in_stock = Column('in_stock', Boolean)
#     quantity = Column('quantity', Integer)
#     price = Column('price', Numeric)
#
#
# class Person(Base):
#     __tablename__ = 'person'
#     id = Column(Integer, primary_key=True)
#     title = Column('title', String(32))
#     in_stock = Column('in_stock', Boolean)
#     quantity = Column('quantity', Integer)
#     price = Column('price', Numeric)

# buildDB.sql scripts automated in sql alchemy

# attackhistory table

class Attackhistory(Base):
    __tablename__ = 'attackhistory'
    attacker_id = Column('attacker_id',String(100),primary_key=True)
    source_IP = Column('source_IP', String(20), nullable=True)
    destination_IP = Column('destination_IP', String(20), nullable=True)
    attackStartTime = Column(DateTime, nullable= True)
    attackStopTime = Column(DateTime, nullable=True)
    numberOfPackets = Column('numberOfPackets', Integer, nullable=True)


class Blacklist(Base):
    __tablename__ = 'blacklist'
    ipAddress = Column('ipAddress',String(20), primary_key=True)
    macAddress = Column('macAddress', String(20), nullable=True)
    blacklistedOn = Column('blacklistedOn',DateTime, nullable= True)


class DevicesType(Base):
    __tablename__ = 'deviceType'
    type = Column('type', Integer, primary_key=True)
    name = Column('name', String(45), default="DefaultType")


class Devices(Base):
    __tablename__ = 'devices'
    deviceID = Column('deviceID', Integer, primary_key=True)
    name = Column('name', String(45), nullable=False, unique=True)
    type = Column('type', Integer, nullable=False, default=0)
    ipv4 = Column('ipv4',String(15), nullable=True)
    ipv6 = Column('ipv6', String(45), nullable=True)
    mac = Column('mac', String(45), nullable=True)


class Login(Base):
    __tablename__ = 'login'
    adminUID = Column('adminUID', String(255), primary_key=True, nullable=False)
    username = Column('username', String(255), nullable=True)
    passwd = Column('passwd', String(255), nullable=True)
    salt = Column('salt', String(255), nullable=True)

class Logs(Base):
    __tablename__ = 'logs'
    switch_id = Column('switch_id', Integer, primary_key=True, nullable=False)
    port_id = Column('port_id', Integer, primary_key=True, nullable=False)
    timestamp = Column(DateTime, primary_key=True, nullable=False)
    rx_packets = Column('rx_packets', Integer, nullable=False, default=0)
    delta_rx_packets = Column('delta_rx_packets', Integer, default=0)
    tx_packets = Column('tx_packets', Integer, nullable=False, default=0)
    delta_tx_packets = Column('delta_tx_packets', Integer, default=0)
    rx_bytes = Column('rx_bytes', Integer, nullable=False, default=0)
    delta_rx_bytes = Column('delta_rx_bytes', Integer, default=0)
    tx_bytes = Column('tx_bytes', Integer, nullable=False, default=0)
    delta_tx_bytes = Column('delta_tx_bytes', Integer, default=0)
    rx_dropped = Column('rx_dropped', Integer, nullable=False, default=0)
    tx_dropped = Column('tx_dropped', Integer, nullable=False, default=0)
    rx_errors = Column('rx_errors', Integer, nullable=False, default=0)
    tx_errors = Column('tx_errors', Integer, nullable=False, default=0)
    rx_fram_err = Column('rx_fram_err', Integer, nullable=False, default=0)
    rx_over_err = Column('rx_over_err', Integer, nullable=False, default=0)
    rx_crc_err = Column('rx_crc_err', Integer, nullable=False, default=0)
    collisions = Column('collisions', Integer, nullable=False)


class PacketLogs(Base):
    __tablename__ = 'packet_logs'
    switch_id = Column('switch_id', Integer, primary_key=True, nullable=False)
    trace_id = Column('trace_id', Integer, primary_key=True, nullable=False)
    frame_number = Column('frame_number', Integer, primary_key=True, nullable=False)
    frame_time = Column('frame_time', Integer, nullable=False)
    frame_time_relative = Column('frame_time_relative', Numeric(10), nullable=False, default=0)
    frame_protocols = Column('frame_protocols', String(20), nullable=False)
    frame_len = Column('frame_len', Integer, nullable=False)
    eth_src = Column('eth_src', String(17), nullable=True)
    eth_dst = Column('eth_dst', String(17), nullable=True)
    eth_type = Column('eth_type', String(10), nullable=True)
    ip_proto = Column('ip_proto', Integer, nullable=False, default=0)
    ip_src = Column('ip_src', String(15), nullable=True, index=True)
    ip_dst = Column('ip_dst', String(15), nullable=True, index=True)
    tcp_srcport = Column('tcp_srcport', Integer, nullable=False)
    tcp_dstport = Column('tcp_dstport', Integer, nullable=False, default=0)
    udp_srcport = Column('udp_srcport', Integer, nullable=False, default=0)
    udp_dstport = Column('udp_dstport', Integer, nullable=False, default=0)
    vlan = Column('vlan', String(45), nullable=True)
    vlanPcp = Column('vlanPcp', String(45), nullable=True)


class Policies(Base):
    __tablename__ = 'policies'
    policyID = Column('policyID', String(36), primary_key=True)
    deviceID = Column('deviceID', Integer, ForeignKey("devices.deviceID"), nullable=False)
    policy = Column('policy', String(40), nullable=False)
    loaded = Column('loaded',Integer, nullable=False, default=0)
    ipv6 = Column('ipv6', String(45), nullable=True)
    mac = Column('mac', String(45), nullable=True)


class Qvm(Base):
    __tablename__ = 'qvm'
    qvmUID = Column('qvmUID', String(100), primary_key=True)
    qvmName = Column('qvmName', String(255), nullable=True)
    qvmIP = Column('qvmIP', String(20), nullable=True)
    qvmStartTime = Column('qvmStartTime',DateTime, nullable=True)
    numberOfAttackers = Column('numberOfAttackers', Integer, nullable=True)
    currentlyActive = Column('currentlyActive', Integer, nullable=True)


class Rules(Base):
    __tablename__ = 'rules'
    rule = Column('rule', String(40), nullable=False, primary_key=True)
    loaded = Column('loaded', Integer, default=0)


class Servers(Base):
    __tablename__ = 'servers'
    serverUID = Column('serverUID', String(100), primary_key=True)
    serverName = Column('serverName', String(1255), nullable=False)
    serverIP = Column('serverIP', String(20), nullable=False)
    serverCreatedOn = Column('serverCreatedOn',DateTime, nullable=True)
    reputationValue = Column('reputationValue', Numeric, nullable=True)
    bidValue = Column('bidValue', Numeric, nullable=True)


class SimplePolicies(Base):
    __tablename__ = 'simple_policies'
    policyID = Column('policyID', String(36), primary_key=True)
    deviceSrcID = Column('deviceSrcID', Integer, nullable=True)
    deviceDstID = Column('deviceDstID', Integer, nullable=True)
    loaded = Column('loaded',Integer, nullable=True)


class SuspiciousnessScores(Base):
    __tablename__ = 'suspiciousness_scores'
    name = Column('name', String(45), nullable=False)
    traceID = Column('traceID', Integer, primary_key=True)
    score = Column('score', Numeric, primary_key=True)

class SuspiciousnessScoresByTime(Base):
    __tablename__ = 'suspiciousness_scores_by_time'
    frame_time = Column('frame_time', Integer, primary_key=True)
    score = Column('score', Numeric, default=0)


class SwitchDevices(Base):
    __tablename__ = 'switch_devices'
    switchID = Column('switchID', Integer, ForeignKey("switches.switchID"), nullable=False)
    deviceID = Column('deviceID', ForeignKey("devices.deviceID"), nullable=False)
    port = Column('port', Integer, primary_key=True)


class Switches(Base):
    __tablename__ = 'switches'
    switchID = Column('switchID', Integer, nullable=False,primary_key=True)
    name = Column('name', String(45), nullable=False, unique=True)
    totalPorts = Column('traceID', Integer, nullable=False, default=0)
    score = Column('score', Numeric, primary_key=True)


class UserMigration(Base):
    __tablename__ = 'usermigration'
    userMigrationUID = Column('userMigrationUID', String(100), nullable=False,primary_key=True)
    userIP = Column('userIP', String(45), nullable=True)
    originalServerIP = Column('originalServerIP', String(45), nullable=True)
    migratedServerIP = Column('migratedServerIP', String(45), nullable=True)
    migrationStartTime = Column('migrationStartTime', DateTime, nullable=True)
    migrationStopTime = Column('migrationStopTime', DateTime, nullable=True)


class Users(Base):
    __tablename__ = 'users'
    userUID = Column('userMigrationUID', String(100), nullable=False,primary_key=True)
    username = Column('username', String(100), nullable=True)
    ipAddress = Column('ipAddress', String(20), nullable=True)
    connectionStartTime = Column('connectionStartTime', DateTime, nullable=True)
    connectionStopTime = Column('connectionStopTime', DateTime, nullable=True)


class Whitelist(Base):
    __tablename__ = 'whitelist'
    ipv4 = Column('ipv4', String(15), nullable=False,primary_key=True)



session = Session()
Base.metadata.create_all(engine)
# ad_rule = Rules(rule="Rule 3",loaded=3)
# session.add(ad_rule)
#session.execute("Alter table products add rating Integer;")
session.commit()
