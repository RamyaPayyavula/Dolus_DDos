import sys
from sqlalchemy import func
from settings import Session
from models import PacketLogs
import datetime
import time

timestamp = time.time()
current_date_timestamp = datetime.datetime.fromtimestamp(timestamp).strftime('%Y-%m-%d %H:%M:%S')


def calculateSSByTime(trace_id, device_id):
    session = Session()
    # findMin = session.query(func.min(Product.price), func.max(Product.price)).filter(Product.title=="Jones").one()
    findMin = session.query(func.min(PacketLogs.frame_time), func.max(PacketLogs.frame_time)).filter(
        PacketLogs.trace_id == trace_id).one()
    minFrame = findMin[0]
    maxFrame = findMin[1]
    try:
        while minFrame < maxFrame:
            query = f"""INSERT INTO suspiciousness_scores_by_time(device_id,traceID,suspiciousness_caluculated_time,frame_time,score) VALUES({str(device_id)},{str(trace_id)},{str(current_date_timestamp)},{str(minFrame)},
                    (SELECT SUM(POWER(((POWER(((connections_total - connections_min) / (connections_max - connections_min)),2) + 
            		POWER(((flows_total - flows_min) / (flows_max - flows_min)),2) + 
            		POWER(((bytes_total - bytes_min) / (bytes_max - bytes_min)),2) )/3),(1.0/2.0))) 'score' 
            		FROM (SELECT d.name, COUNT(DISTINCT ip_dst) AS 'connections_total', 
            		CASE WHEN d.name LIKE 'server%' THEN 10 ELSE 1 END AS 'connections_min', 
            		CASE WHEN d.name LIKE 'server%' THEN 1000 ELSE 10 END AS 'connections_max' 
            		FROM packet_logs l, devices d 
            		WHERE d.ipv4 = l.ip_src AND ip_src <> ' ' AND ip_dst <> ' ' 
            		AND trace_id = {str(trace_id)} AND frame_time >= {str(minFrame)}
            		AND frame_time < {str(maxFrame)} + 10 GROUP BY d.name) a, 
            		(SELECT d.name, count(*) AS 'flows_total', CASE WHEN d.name LIKE 'server%' THEN 1000 ELSE 100 END AS 'flows_min', 
            		CASE WHEN d.name LIKE 'server%' THEN 10000 ELSE 1000 END AS 'flows_max' 
            		FROM packet_logs l, devices d WHERE d.ipv4 = l.ip_src AND ip_src <> ' ' AND ip_dst <> ' ' 
            		AND trace_id = {str(trace_id)} AND frame_time >= {str(minFrame)} AND frame_time <{str(maxFrame)}+ 10 GROUP BY d.name) b, 
            		(SELECT d.name , SUM(frame_len) AS 'bytes_total', CASE WHEN d.name LIKE 'server%' THEN 100000 ELSE 100 END AS 'bytes_min', 
            		CASE WHEN d.name LIKE 'server%' THEN 100000000 ELSE 100000 END AS 'bytes_max' 
            		FROM packet_logs l, devices d WHERE d.ipv4 = l.ip_src AND ip_src <> ' ' AND ip_dst <> ' ' 
            		AND trace_id = {str(trace_id)} AND frame_time >= {str(minFrame)} AND frame_time < {str(maxFrame)} + 10 
            		GROUP BY d.name) c WHERE a.name = b.name AND a.name = c.name));"""
            # print(query)
            session.execute(query)
            minFrame += 10
            session.commit()
    except TypeError:
        print('there is no value in Minframe and Max Frame')
