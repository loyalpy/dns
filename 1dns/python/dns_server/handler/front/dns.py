#!/usr/bin/env python
# coding=utf-8
#
# Copyright 2013 by thinkhu


import sys
import time,datetime
import re
import os
from os.path import isfile
from string import Template
from base import BaseHandle
from library.tools import sys_exec
#记录
zone_record = "%-20s %-6s IN %-6s %-6s %-6s %-6s %s\n"
#记录模版
zone_tmpl = '''$TTL	; 10 minutes
@		IN SOA	$MASTER_DNS. root.8jdns.com. $SERIL 10800 300 1209600 600
$RECORDS
'''

#Zone添加
class ZoneHandle(BaseHandle):
    def post(self):
        parm = {}
        parm['domain']   = self.get_argument("domain","")
        parm['ns_group'] = self.get_argument("ns_group","free")
        req_timestamp    = self.get_argument("timestamp",0)
        req_checkcode    = self.get_argument("checkcode","")
        if not self.rz(parm,req_checkcode,req_timestamp):
            return self.json_error("not author")
        domain   = parm['domain']
        ns_group = parm['ns_group']
        #zonefile
        zonefile = "%s%s%s/%s"%(self.dns_conf['path'],self.dns_conf['etc'],self.dns_conf['zone_etc'],domain)
        domain_exists = 1
        try:
            domain_row = self.mg_db.domains.find_one({"domain":domain,"ns_group":ns_group})
            if domain_row == None or not domain_row.has_key('domain'):
                domain_exists = 0
        except:
            domain_exists = -1

        #域名不存在将删除
        ret = 0
        if domain_exists == 0:            
            try:
                os.remove(zonefile)
            except:
                pass
            ret = 1
        elif domain_exists == 1:
            records_str = "";
            nss = domain_row['ns'].split(";")
            #处理NS
            for ns in nss:
                records_str = "%s			NS    %s.\n"%(records_str,ns)
            #处理无记录的域名
            if not isinstance(domain_row['records'],dict):
                domain_row['records'] = {}

            for RRname,v0 in domain_row['records'].items():
                for RRtype,v1 in v0.items():
                    for Acl,v2 in v1.items():
                        for v3 in v2:
                            RR = {}
                            RR['value']    = str(v3[0])
                            RR['ttl']      = str(v3[1])
                            RR['mx']       = str(v3[2])
                            RR['acltype']  = str(v3[3])
                            RR['acl']      = Acl
                            RR['name']     = RRname.replace("#",".")
                            RR['type']     = RRtype

                            if Acl == "any" and RR['acltype'] == 'DI':
                                RR['acltype'] = ''
                                RR['acl']     = ''

                            if RRtype in ['AAAA','A']:
                                RR['value']    = "%s"%(RR['value'])
                                RR['mx'] = ""  
                            elif RRtype == 'CNAME':
                                RR['value'] = "%s."%(RR['value']) 
                                RR['mx'] = ""
                            elif RRtype in ['URL','URLY','URLN']:
                                RR['type'] = "CNAME"
                                RR['value'] = "%s."%(self.dns_conf['URLYN']) 
                                RR['mx'] = ""                                           
                            elif RRtype == 'TXT':
                                RR['value'] = "\"%s\""%(RR['value'])
                                RR['mx'] = "" 
                            elif RRtype == 'MX':
                                RR['value'] = "%s."%(RR['value'])
                                RR['mx'] = "%s"%(RR['mx']) 
                            else:
                                RR['value'] = "%s."%(RR['value'])
                                RR['mx'] = "" 
                            records_str = "%s%s"%(records_str,zone_record%(RR['name'],RR['ttl'],RR['acltype'],RR['acl'],RR['type'],RR['mx'],RR['value']))
            #写区域记录文件
            TTL   = str(domain_row['ttl'])
            SERIL = str(int(time.time()))
            zonetmpl = Template(zone_tmpl)
            zoneconf = zonetmpl.substitute(TTL="$TTL %s"%TTL,MASTER_DNS=nss[0],SERIL=SERIL,RECORDS=records_str)
            try:
                fp = file(zonefile,"w")
                fp.write(zoneconf)
                fp.close();
                ret = 1
            except:
                pass
        else:
            pass
        try:
            #重新加载配置
            exestr = "%s%s %s %s"%(self.dns_conf['path'],self.dns_conf['mexe'],"reloadzone",domain)
            sys_exec(exestr)
        except:
            pass
            
        if ret == 1:
            self.json_success("success")
        else:
            self.json_error("fail") 

