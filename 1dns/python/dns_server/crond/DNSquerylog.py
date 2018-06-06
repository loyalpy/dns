#!/usr/bin/env python     
# -*- coding: utf-8 -*-     
import time,datetime
import re,os,sys
from string import join
import uuid

from pymongo import MongoClient

def get_mac_address(): 
    mac=uuid.UUID(int = uuid.getnode()).hex[-12:] 
    return ":".join([mac[e:e+2] for e in range(0,11,2)])

dbconfig = {'dsn': "mongodb://mglogdbuser:131121W787903@115.231.26.213:27017/dns_log", 'db_name': "dns_log"}
querylog_file = "/data/8jdns/var/log/8jdns/stat/query.log"

def start_analysis():
    timestamp = int(time.time())
    mac       = get_mac_address()
    lines     = []
    try:                
        fp = open(querylog_file,"r")
        lines = fp.readlines()
    except:
        return 0

    try:
        mg_client = MongoClient(dbconfig['dsn'])
        mg_db     = mg_client[dbconfig['db_name']]
    except:
        return 0

    last_dateline = 0
    try:
        last_row = mg_db.query_log.find({"mac":mac}).sort('dateline',-1).limit(1).skip(1)
        if last_row.count() > 0:
            last_dateline = last_row[0]['dateline']
        last_row.close()
    except:
        last_dateline = 0
    
    result = []    
    for line in lines:
        row = line.strip('\n').split("\t")
        if len(row) == 4:
            dateline = long(time.mktime(time.strptime(row[0],'%Y-%m-%d %H:%M:%S')))
            host     = row[1].lower()
            domain   = row[2].lower()
            querytimes    = int(row[3])
       
            if dateline>last_dateline and len(domain)>3 and querytimes > 0 and domain and host:
                result.append({"host":host,"domain":domain,"querytimes":querytimes,"dateline":dateline,"mac":mac})   
    #打印出来
    if len(result) > 0:
        try:
            mg_db.query_log.insert_many(result)
        except:
            pass
    fp.close()
if __name__ == "__main__":
    start_analysis()

