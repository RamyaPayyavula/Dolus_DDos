

#!/usr/bin/env python

import sys
from settings import Session, engine, Base
from calcTargetedSSByTime import calculateTargetedSSByTime
from calcTargetedSS import calculateTargetedSS
from models import SuspiciousnessScores, Devices

session = Session()
lastrecord = session.query(SuspiciousnessScores).order_by(SuspiciousnessScores.traceID.desc()).first()

if lastrecord is None:
    trace_id = 1
else:
    trace_id = lastrecord.traceID +1

records = session.query(Devices).all()
row_count = session.query(SuspiciousnessScores).count()
for rec in records:
    device_id = rec.deviceID
    row_count = row_count+1
    calculateTargetedSS(row_count,trace_id,device_id)
    #calculateTargetedSSByTime(trace_id, device_id)
    
print('executed')
