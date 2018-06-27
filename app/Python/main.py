#!/usr/bin/env python

import sys
from settings import Session, engine, Base
from calcSSByTime import calSSByTime
from calSS import calSS
from python.models import SuspiciousnessScores


session = Session()
print("I am here")
records = session.query(SuspiciousnessScores).all()
for rec in records
    trace_id = rec["trace_id"]
    calSSByTime(trace_id)
    calSS(trace_id)

