from datetime import datetime
from pytz import timezone
from python.settings import session
from python.models import Policies

def getExistingPolicy():
    existingPolicies = ''

    # cursor.execute("SELECT policy FROM policies where loaded = 1")
    # policies = cursor.fetchall()
    policies = session.query(Policies).filter(Policies.loaded == 1).all()

    for policy  in policies:
        existingPolicies = existingPolicies + policy['policy'] + '|'

    return existingPolicies[:-1]

def getCurrentPolicy():
    # cursor.execute("SELECT * FROM policies where loaded = 0")
    # policy = cursor.fetchall()
    policy=session.query(Policies).filter(Policies.loaded == 0).all()
    ids = list()
    current_policy = ''

    for array in policy:
        ids.append(array['policyID'])
        current_policy = current_policy + array['policy'] + ' |'

    return current_policy[:-1], ids


def updatePolicyLog(filename, current_policy, dateFormat="%m/%d/%Y", timeFormat="%H:%M:%S"):
    try:
        log_file = open(filename, 'a')
        print(log_file)
        now_utc = datetime.now(timezone('UTC'))
        central_time = now_utc.astimezone(timezone('US/Central'))
        log_file.write(central_time.strftime(timeFormat)
                        + '\t'+central_time.strftime(dateFormat)
                        + '\t'
                        + current_policy
                        + '\n'
                        )
        #print now_utc
        #print central_time
        log_file.close()
    except:
        print("Error with: " + filename)