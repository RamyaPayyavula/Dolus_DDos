#!/usr/bin/env python

import sys
from settings import Session, engine, Base
from calcSSByTime import calculateSSByTime
from calcSS import calculateSS
from models import SuspiciousnessScores


session = Session()
lastrecord = session.query(SuspiciousnessScores).order_by(SuspiciousnessScores.traceID.desc()).first()

if lastrecord is None:
    trace_id = 1
else:
    trace_id = lastrecord.traceID +1
calculateSS(trace_id)
records = session.query(SuspiciousnessScores).all()
for rec in records:
    trace_id1 = rec.traceID
    device_id = rec.deviceID
    calculateSSByTime(trace_id1, device_id)


