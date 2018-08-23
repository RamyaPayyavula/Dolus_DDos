import sys
from app.Python.settings import Session, engine, Base
import datetime
import time
timestamp = time.time()
current_date_timestamp = datetime.datetime.fromtimestamp(timestamp).strftime('%Y-%m-%d %H:%M:%S')

def calculateSS(trace_id):
    session = Session()
    query = f""" INSERT INTO suspiciousness_scores(traceID,ssscore_caluculated_time,device_id,score) (SELECT {str(trace_id)}, '{str(current_date_timestamp)}',g.deviceID,g.score
                 FROM (SELECT a.deviceID, c.bytes_total, c.bytes_min, c.bytes_max, b.flows_total,
                 b.flows_min, b.flows_max, a.connections_total, a.connections_min, a.connections_max,
                 (connections_total - connections_min) / (connections_max - connections_min)
                 as 'connections_normalized',(flows_total - flows_min) / (flows_max - flows_min)
                 as 'flows_normalized',(bytes_total - bytes_min) / (bytes_max - bytes_min) as 'bytes_normalized',
                 POWER(((POWER(((connections_total - connections_min) / (connections_max - connections_min)),2)
                 + POWER(((flows_total - flows_min) / (flows_max - flows_min)),2)
                 +POWER(((bytes_total - bytes_min) / (bytes_max - bytes_min)),2))/3),(1.0/2.0)) 'score'
                 from (select d.name,d.deviceID, COUNT(DISTINCT ip_dst) AS 'connections_total',
                 case when d.name like 'server%' then 10 else 1 end as 'connections_min',
                 case when d.name like 'server%' then 1000 else 10 end as 'connections_max'
                 FROM packet_logs l, devices d WHERE d.ipv4 = l.ip_src and ip_src <> ' '
                 and ip_dst <> ' ' and trace_id = {str(trace_id)} group by d.name) a,
                 (SELECT d.name, count(*) as 'flows_total', case when d.name like 'server%' then 1000 else 100
                 end as 'flows_min', case when d.name like 'server%' then 10000 else 1000 end as 'flows_max'
                 FROM packet_logs l, devices d WHERE d.ipv4 = l.ip_src and ip_src <> ' '
                 and ip_dst <> ' ' and trace_id = {str(trace_id)} group by d.name) b,
                 (SELECT d.name , SUM(frame_len) as 'bytes_total',
                 case when d.name like 'server%' then 100000 else 100 end as 'bytes_min',
                 case when d.name like 'server%' then 100000000 else 100000 end as 'bytes_max'
                 FROM packet_logs l, devices d WHERE d.ipv4 = l.ip_src and ip_src <> ' '
                 and ip_dst <> ' ' and (trace_id = {str(trace_id)})  group by d.name) c
                 WHERE a.name = b.name and a.name = c.name)g);"""



    # print('query', query)
    session.execute(query)
    session.commit()

