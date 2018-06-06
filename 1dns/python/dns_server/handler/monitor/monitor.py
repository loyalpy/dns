#!/usr/bin/env python
# coding=utf-8
#
# Copyright 2013 by thinkhu
import sys
import time,datetime
import httplib,urllib

from base import BaseHandle
from library.monitor import *

class IndexHandle(BaseHandle):
    def get(self, tpl_vars = {}):
        self.write("no")
class CallbackHandle(BaseHandle):
    def get(self, tpl_vars = {}):
        domain         = self.get_argument("domain","")
        port           = self.get_argument("port",0)
        ip             = self.get_argument("ip","")
        path           = self.get_argument("path","/")
        mtype          = self.get_argument("mtype",1)
        record_id      = self.get_argument("record_id",0)
        node_id        = self.get_argument("node_id",0)

        interface_host = self.get_argument("ihost","")
        interface_port = self.get_argument("iport",0)
        interface_path = self.get_argument("ipath","")
        
        if(not checkip(ip) or int(port)<0 or int(interface_port)<0 ):
            self.write("parm fail!")
            return False
        
        res = monitor_exe(domain,ip,port=int(port),path=path,mtype=int(mtype))
        interface_path = interface_path + "?id=%s&node_id=%s&status=%s&status_code=%s&reason=%s&restime=%s"
        interface_path = interface_path%(str(record_id),str(node_id),str(res['status']),str(res['status_code']),urllib.quote(str(res['reason'])),str(res['restime']))
        headers = {"Content-type": "application/x-www-form-urlencoded","Accept": "text/plain","Connection":"close"}
        try:
            conn = httplib.HTTPConnection(interface_host,int(interface_port),timeout=1)
            conn.request('GET', interface_path, {}, headers)
            response = conn.getresponse()
            conn.close()
            #logger.info(interface_path)
        except:
            pass
        self.write(str(res['status'])+":"+str(res['status_code'])+":"+str(res['reason'])+":"+str(res['restime'])+"   "+interface_host+str(interface_port)+interface_path)
        return False;