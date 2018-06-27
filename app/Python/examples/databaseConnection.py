import requests, json
from python.settings import Session
from python.models import Rules

import frenetic
from frenetic.syntax import *
import urllib

# db = MySQLdb.connect(user='root', passwd='1234', host='127.0.0.1', db='mtd',cursorclass=MySQLdb.cursors.DictCursor)
#
# cursor = db.cursor()
#
# # Use all the SQL you like

session=Session()
# cursor.execute("SELECT * FROM rules")
#
# data = cursor.fetchone()
#print ("Data : %s " % data['rule'])

data=session.query(Rules).all()
print(data[1].rule)
#
# Pol = eval(data['rule'])
# print(Pol)
# policy = json.dumps(Pol.to_json(),indent=4, sort_keys=False, separators=(',', ': '),ensure_ascii=False)
# print(policy)
#
# url = 'http://localhost:9000/adminPol/update_json'
# req = urllib2.Request(url, str(policy), {'Content-Type': 'application/json'})
# f = urllib2.urlopen(req)
# #print f
# #for x in f:
# #    print(x)
# f.close()
# session.commit()