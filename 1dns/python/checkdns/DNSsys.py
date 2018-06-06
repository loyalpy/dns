#!/usr/bin/env python
# encoding: utf-8
import os,sys,time,re
import fcntl
import select
import signal
import commands
import subprocess
#定义根路径
rootpath = os.getcwd()
## 执行系统命令 
class sys_command:
    def __init__(self):
        pass
    def __AsyncRecv(self,fdsock,nMaxRead = 1024*8):
        if not fdsock or fdsock.closed:
            return (False,'')
        #set fd non-block
        nFlags = fcntl.fcntl(fdsock,fcntl.F_GETFL)
        fcntl.fcntl(fdsock,fcntl.F_SETFL,nFlags|os.O_NONBLOCK)

        bRet = False
        strRead = ''
        try:
            #check can be read
            if not select.select([fdsock],[],[],0)[0]:
                return (True,'')
            strRead = fdsock.read(nMaxRead);
            #if read empty,then close it
            if len(strRead) <= 0:
                fdsock.close();
            bRet = True
        except:
            bRet = False

        #reset fd
        if not fdsock.closed:
            fcntl.fcntl(fdsock,fcntl.F_SETFL,nFlags)

        return (bRet,strRead)

    def run(self,lsCmd,nTimeOut = 10,nIntervalTime = 1):
        #logger.info("run "+lsCmd)
        oProc = subprocess.Popen(lsCmd,shell=True,stdout=subprocess.PIPE,stderr=subprocess.PIPE)
        strOut = ''
        strErr = ''

        nStartTime = time.time()
        while True:
            if None != oProc.poll():
                break

            #sleep nIntervalTime
            time.sleep(nIntervalTime)

            bRet,strBuf1 = self.__AsyncRecv(oProc.stdout)
            bRet,strBuf2 = self.__AsyncRecv(oProc.stderr)

            if len(strBuf1) >0:
                strOut += strBuf1
            if len(strBuf2) >0:
                strOut += strBuf2

            if (nTimeOut > 0) and (time.time() - nStartTime) > nTimeOut:
                #logger.error("time out...");
                break;
        #get last buff   
        bRet, strBuf1 = self.__AsyncRecv(oProc.stdout) 
        bRet, strBuf2 = self.__AsyncRecv(oProc.stderr)
        
        if len(strBuf1) > 0:
            strOut += strBuf1
        if len(strBuf2) > 0:
            strErr += strBuf2
        #if not finish, so timeout   
        if None == oProc.poll():
            self.KillAll(oProc.pid)
        #if strErr is not None or strErr != '':
            #logger.error(str(oProc.returncode)+"\t"+strOut+"\t"+strErr);
        return (oProc.returncode, strOut, strErr)

    def KillAll(self, nKillPid, nKillSignal = signal.SIGKILL):
        #logger.info("kill pid:%s" %nKillPid)
        nRet, strOutput = commands.getstatusoutput('ps -A -o pid,ppid')
        if 0 != nRet:
            return (False, strOutput);
        mapPid = {};   
        #make all ppid & pid map
        for strLine in strOutput.split('\n'):
            lsPid = strLine.strip().split();
            if 2 != len(lsPid):
                continue;
            strPid = lsPid[0];
            strPPid = lsPid[1];
            if strPPid in mapPid.keys():
                mapPid[strPPid].append(strPid);
            else:
                mapPid[strPPid] = [strPid];   
  
        #get all kill pid list   
        lsAllKillPid = [str(nKillPid)];   
        lsToKillPid = [str(nKillPid)];   
        while True:   
            if len(lsToKillPid) <= 0:   
                break;   
            lsChild = []   
            for strPid in lsToKillPid:   
                if strPid in mapPid.keys():   
                    lsAllKillPid.extend(mapPid[strPid]);   
                    lsChild.extend(mapPid[strPid]);
                    #print("[%s]append:%s" %(strPid, mapPid[strPid]));
            lsToKillPid = lsChild;
        #logger.info("kill pid list\n%s" %lsAllKillPid)   
        #kill all process   
        for strPid in reversed(lsAllKillPid):   
            try:   
                #print("kill %s" %(strPid))   
                os.kill(int(strPid), nKillSignal)   
            except:   
                pass
        return (True, '')
    
## 日志对象
def initlog(log_path='log.log',log_level='INFO'):
    import logging
    logger = logging.getLogger()
    hdlr = logging.FileHandler(rootpath+ "/"+log_path)
    formatter = logging.Formatter('%(asctime)s %(levelname)s %(message)s')
    hdlr.setFormatter(formatter)
    logger.addHandler(hdlr)
    level_o = {'CRITICAL':logging.CRITICAL,'ERROR':logging.ERROR,'WARNING':logging.WARNING,'INFO':logging.INFO,'DEBUG':logging.DEBUG,'NOTSET':logging.NOTSET}
    logger.setLevel(level_o[log_level])#CRITICAL  ERROR  WARNING INFO  DEBUG  NOTSET
    return logger

## mysql对象
def initdb(sqlconf=("","","","","")):
    import MySQLdb
    host,user,passwd,database,charset = sqlconf
    try:
        conn=MySQLdb.connect(host=host,user=user,passwd=passwd,db=database,charset=charset)
        db = conn.cursor()
        return (conn,db)
    except:
        return (None,None)

## 检查IP
def checkip(ip):
    return re.match('^(([01]?\d\d?|2[0-4]\d|25[0-5])\.){3}([01]?\d\d?|2[0-4]\d|25[0-5])$',ip)

## 认证    
def rz(checkcode,timestamp,**arg):
    import hashlib
    key = "d3352d339e245e44c684da4bb94cdc5b"
    hashmd5 = hashlib.md5()
    parmstr = ""
    arg = sorted(arg.items(), key=lambda t: t[0])
    for k in arg:
        parmstr += "&%s=%s" %(k[0],k[1])
    parmstr = parmstr[1:] + "&timestamp=%s"%timestamp
    md5str = key + parmstr + key
    hashmd5.update(md5str)
    md5value = hashmd5.hexdigest()
    if md5value == checkcode and timestamp > time.time():
        return True
    return False

## 简化系统执行函数
def sys_exec(lsCmd,nTimeOut = 10,nIntervalTime = 0.005):
    obj = sys_command()
    ret = obj.run(lsCmd,nTimeOut,nIntervalTime)
    del obj
    return ret
