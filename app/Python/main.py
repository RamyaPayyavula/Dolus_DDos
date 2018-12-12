

#!/usr/bin/env python

import sys
from settings import Session, engine, Base
from calcSSByTime import calculateSSByTime
from calcSS import calculateSS
from models import SuspiciousnessScores, Devices

session = Session()
lastrecord = session.query(SuspiciousnessScores).order_by(SuspiciousnessScores.traceID.desc()).first()

if lastrecord is None:
    trace_id = 1
else:
    trace_id = lastrecord.traceID +1

records = session.query(Devices).all()
lastssID = session.query(SuspiciousnessScores).order_by(SuspiciousnessScores.SSID.desc()).first()
if lastssID is None:
    row_count=0
else:
    row_count = lastssID.SSID
for rec in records:
    device_id = rec.deviceID
    row_count = row_count+1
    calculateSS(row_count,trace_id,device_id)
    #calculateSSByTime(trace_id, device_id)
    
print('executed')
