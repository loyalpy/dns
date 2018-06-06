#!/usr/bin/env python
# coding=utf-8
#
# Copyright 2013 hublog.cn by thinkhu
import socket #tcp
import httplib,urllib
import re,sys,os,time
import ping

#检测请求超时时间
socket.setdefaulttimeout(1)
#检测IP
def checkip(ip):
    return re.match('^(([01]?\d\d?|2[0-4]\d|25[0-5])\.){3}([01]?\d\d?|2[0-4]\d|25[0-5])$',ip)

def monitor_exe(domain,ip,port=80,path="/",mtype=1):
        #data = urllib.urlencode({})
        ret = {"status":0,"status_code":"0","reason":"out","restime":0}
        #根据监控类型来监控
        if mtype == 1:#HTTP监控
            headers = {"Content-type":"application/x-www-form-urlencoded","Accept":"text/plain","Connection":"close"}
            headers['Host']=str(domain)
            try:
                starttime = time.time()
                conn = httplib.HTTPConnection(ip,port,timeout=1)
                conn.request('GET',str(path),headers=headers)
                http_res = conn.getresponse()
                conn.close()
                distime = int((time.time()-starttime)*1000)
                ret['status'] = 1
                ret['status_code'] = http_res.status
                ret['reason'] = http_res.reason
                ret['restime'] = distime
            except:
                pass
        elif mtype == 2:#SOCKET 监控
            try:
                #ip = socket.gethostbyname("www2cto.com")
                sk = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
                sk.settimeout(1)
                starttime = time.time()
                sk.connect((ip,port))
                distime = int((time.time()-starttime)*1000)
                sk.close()
                ret['status'] = 1
                ret['status_code'] = '1'
                ret['reason'] = 'ok'
                ret['restime'] = distime
            except:
                pass
        elif mtype == 3:#PING 监控
            try:
                result = ping.quiet_ping(str(ip), timeout=1, count=4, psize=64)
                if result[0] < 100:
                    ret['status'] = 1
                    ret['status_code'] = result[0]
                    ret['reason'] = "fail:%s"%str(result[0])
                    ret['restime'] = int(result[1])
            except:
                pass           
        return ret