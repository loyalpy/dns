#!/usr/bin/env python
# encoding: utf-8
import socket #tcp
import re
import sys
import httplib,urllib
import socket
import time
import os
import random
from threading import Thread

#检测请求超时时间
socket.setdefaulttimeout(1)
#监控频率
sleep_rate = 0
#创建日志对象

#监控节点
monitor_nodes = {1:['61.153.110.147:9010'],2:['101.71.20.114:9010'],3:['122.226.161.9:9010']}
#监控结果后回调
interface_host = {'host':"api.bajiedns.com",'port':'443','path':'/Common/MonitorCallBack'}
#监控队列数据目录
datadir = "/home/webroot/bajiednsweb/cache/static"

def http_monitor(domain,ip,port=80,path="/",data={},timeout=0):
    data = urllib.urlencode(data)
    headers = {"Content-type":"application/x-www-form-urlencoded","Accept":"text/plain","Connection":"close"}
    headers['Host']=domain
    try:
        conn = httplib.HTTPConnection(ip,port,timeout=timeout)
        conn.request('GET',str(path),data,headers=headers)
        response = conn.getresponse()
        conn.close()
    except:
        pass

class JQ_webserver(Thread):
    def __init__(self,threadname,rate=3):
        Thread.__init__(self,name = threadname)
        #获取监控列表
        self.isruning = True
        self.rate = rate
        self.cnodes = {}
    def run(self):
        while self.isruning:
            #0:monitor_id,
            #1:record_id
            #2:ip
            #3:RRname
            #4:domain
            #5:monitor_type
            #6:monitor_http
            #7:monitor_port
            #8:monitor_path
            cache_file = "%s/monitor_queue_%d.data"%(datadir,self.rate)
            lines = []
            try:
                fp = open(cache_file,"r")
                lines = fp.readlines()
            except:
                self.stop()
                break

            for line in lines:
                row = line.strip('\n').split("|")
                timestamp = int(time.time())
                data = {}
                path = "/Monitor/Callback?domain=" + str(row[3]) + "." + str(row[4])
                path += "&ip=" + str(row[2])
                path += "&path=" + str(row[8])
                path += "&port=" + str(row[7])
                path += "&record_id=" + str(row[1])
                path += "&mtype=" + str(row[5])
                path += "&ihost=" + interface_host['host']
                path += "&iport=" + interface_host['port']
                path += "&ipath=" + interface_host['path']
                for (k1,v1) in  monitor_nodes.items():
                    node_id =  k1
                    node_s = v1[0].split(":")
                    http_monitor(str(node_s[0]),str(node_s[0]),int(node_s[1]),(path+"&node_id=" + str(node_id)),data=data,timeout=0.3)
                    time.sleep(0.01)
                #print path
            #执行完毕结束线程
            self.stop()
            fp.close()
    def stop(self):
        self.isruning = False
        del self
if __name__ == "__main__":
    #获取监控频率
    sleep_rate = int(sys.argv[1])
    #独立进程运行
    JQ_webserver("monitor_"+str(int(time.time())),sleep_rate).start()
