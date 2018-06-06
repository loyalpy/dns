#!/usr/bin/env python
# encoding: utf-8
import socket #tcp
import tornado
import tornado.httpserver
import tornado.ioloop
import tornado.web
import tornado.autoreload

import httplib,urllib
import re,sys,os,time

#导入监控基本库
from DNSsys import *
#检测请求超时时间
#创建日志对象
logger = initlog('checkdns.log','ERROR')
#IPwhois查询
class Whois(tornado.web.RequestHandler):
    def get(self):
        querystr = self.get_argument("querystr","")

        timeout = self.get_argument("timeout",5)
        timestamp = self.get_argument("timestamp",0)
        checkcode = self.get_argument("checkcode","")

        if not rz(checkcode,querystr=querystr,timeout=timeout,timestamp=timestamp):
            self.write("no authorize!")
            return 0
        else:
            if querystr == "":
                self.write("no parm")
                return 0
            qorder = "whois %s"%(querystr)
            ret = sys_exec(qorder,int(timeout))

            if len(ret)>= 2:
                self.write(ret[1])
            else:
                self.write("timeout")

#检查域名的NS    
class CheckDNS(tornado.web.RequestHandler):
    def get(self):
        ns_server = self.get_argument("ns_server","8.8.8.8")
        domain = self.get_argument("domain","")
        qtype = self.get_argument("qtype","ns")
        qclass = self.get_argument("qclass","")
        qopt = self.get_argument("qopt","")
        dopt = self.get_argument("dopt","trace")
        
        timeout = self.get_argument("timeout",15)
        timestamp = self.get_argument("timestamp",0)
        checkcode = self.get_argument("checkcode","")

        if not rz(checkcode,domain=domain,qtype=qtype,qclass=qclass,qopt=qopt,dopt=dopt,timeout=timeout,ns_server=ns_server,timestamp=timestamp):
            self.write("no authorize!")
            return 0
        else:
            if(domain == "" or qtype == "" or dopt == ""):
                self.write("no parm")
                return 0
            qorder = "dig %s +%s -t %s @%s"%(domain,dopt,qtype,ns_server)
            ret = sys_exec(qorder,int(timeout))

            if len(ret)>= 2:
                self.write(ret[1])
            else:
                self.write("timeout")                
settings = {"debug":False}
if __name__ == "__main__":
    application = tornado.web.Application([
        (r"/CheckDNS",CheckDNS),
        (r"/Whois",Whois),
    ],**settings)
    http_server = tornado.httpserver.HTTPServer(application)
    http_server.listen(int(sys.argv[1]),"127.0.0.1")
    instance = tornado.ioloop.IOLoop.instance()
    tornado.autoreload.start(instance)
    instance.start()
