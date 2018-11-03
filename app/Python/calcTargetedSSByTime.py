import datetime
import MySQLdb
import MySQLdb.cursors
import time

timestamp = time.time()
current_date_timestamp = datetime.datetime.fromtimestamp(timestamp).strftime('%Y-%m-%d %H:%M:%S')


def calculateTargetedSSByTime(SSSID,trace_id, device_id):
    db = MySQLdb.connect(user='root', passwd='root', host='127.0.0.1', db='test',
                         cursorclass=MySQLdb.cursors.DictCursor)
    cursor = db.cursor()
    findMin = "SELECT MIN(frame_time) as minFrameTime, MAX(frame_time) as maxFrameTime FROM packet_logs where trace_id = " + str(trace_id) + ";"
    cursor.execute(findMin)
    results = cursor.fetchone()

    minFrame = results["minFrameTime"]
    maxFrame = results["maxFrameTime"]
    try:
        while minFrame < maxFrame:
            db = MySQLdb.connect(user='root', passwd='root', host='127.0.0.1', db='test',
                                 cursorclass=MySQLdb.cursors.DictCursor)
            cursor = db.cursor()
            query = "INSERT INTO suspiciousness_scores SELECT "+ str(SSSID)+","+ str(device_id) + "," + str(trace_id) + ",'"+str(current_date_timestamp)+"',g.name, g.score"
            query += " FROM (SELECT a.name, a.bytes_total, a.bytes_min, a.bytes_max, a.flows_total,"
            query += "a.flows_min, a.flows_max, a.connections_total, a.connections_min, a.connections_max,"
            query += "(connections_total - connections_min) / (connections_max - connections_min)"
            query += "as 'connections_normalized',(flows_total - flows_min) / (flows_max - flows_min)"
            query += "as 'flows_normalized',(bytes_total - bytes_min) / (bytes_max - bytes_min) as 'bytes_normalized',"
            query += "POWER(((POWER(((connections_total - connections_min) / (connections_max - connections_min)),2)"
            query += "+ POWER(0.7*((flows_total - flows_min) / (flows_max - flows_min)),2)"
            query += "+POWER(0.7*((bytes_total - bytes_min) / (bytes_max - bytes_min)),2))/3),(1.0/2.0)) 'score'"
            query += " from("
            query += "SELECT d.name, SUM(l.frame_len) as 'bytes_total', case when d.name like 'server%' then 100000 else 100 end as 'bytes_min',"
            query += "case when d.name like 'server%' then 100000000 else 100000 end as 'bytes_max',"
            query += "count(*) as 'flows_total',case when d.name like 'server%' then 1000 else 100 end as 'flows_min',"
            query += "case when d.name like 'server%' then 10000 else 1000 end as 'flows_max',"
            query += "COUNT(DISTINCT l.ip_dst) AS 'connections_total',case when d.name like 'server%' then 10 else 1 end as 'connections_min',"
            query += "case when d.name like 'server%' then 1000 else 10 end as 'connections_max'"
            query += "FROM packet_logs l, devices d WHERE d.ipv4 = l.ip_src and l.ip_src <> ' '"
            query += "and l.ip_dst <> ' ' and trace_id = " + str(trace_id) + " and frame_time >= " + str(minFrame) + " and frame_time < " + str(minFrame) + " + 10 group by d.name) a	) g;"

            # print(query)
            cursor.execute(query)
            minFrame += 10
            db.commit()
    except TypeError:
        print('there is no value in Minframe and Max Frame')
