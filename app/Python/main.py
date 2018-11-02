

#!/usr/bin/env python

import sys
from app.Python.settings import Session, engine, Base
from app.Python.calcSSByTime import calculateSSByTime
from app.Python.calcSS import calculateSS
from app.Python.models import SuspiciousnessScores, devices

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
    calculateSS(row_count,trace_id,device_id)
    calculateSSByTime(row_count,trace_id, device_id)
    row_count = row_count+1
print('executed')
