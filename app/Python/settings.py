
from sqlalchemy import create_engine

from sqlalchemy.orm import sessionmaker
from sqlalchemy.ext.declarative import declarative_base
# create an engine
engine = create_engine('mysql+pymysql://root:root@localhost:3306/test')
# engine = create_engine('mysql+pymysql://root:root@localhost:3306/mtd')

# create a configured "Session" class
Session = sessionmaker(bind=engine)

# create a Session
session = Session()
Base = declarative_base()
