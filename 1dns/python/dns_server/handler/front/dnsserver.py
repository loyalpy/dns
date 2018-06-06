#!/usr/bin/env python
# coding=utf-8
#
# Copyright 2013 by thinkhu


import sys
import time,datetime
import re
import os
from os.path import isfile

from base import BaseHandle
from library.tools import sys_exec

#软件重启
class CmdHandle(BaseHandle):
    def post(self):
        parm = {}
        parm['cmd'] = self.get_argument("cmd","")
        req_timestamp = self.get_argument("timestamp",0)
        req_checkcode = self.get_argument("checkcode","")
        if not self.rz(parm,req_checkcode,req_timestamp):
            self.json_error("not author")
        else:
            if parm['cmd'] not in self.dns_conf['cmd']:
                self.json_error("not in cmds")
            else:
                exestr = "%s%s %s"%(self.dns_conf['path'],self.dns_conf['exe'],parm['cmd'])
                res = sys_exec(exestr)
                self.json_success(res)        
#软件管理控制
class MCmdHandle(BaseHandle):
    def post(self):
        parm = {}
        parm['mcmd']  = self.get_argument("mcmd","")
        parm['parm']  = self.get_argument("parm","")
        req_timestamp = self.get_argument("timestamp",0)
        req_checkcode = self.get_argument("checkcode","")
        if not self.rz(parm,req_checkcode,req_timestamp):
            self.json_error("not author")
        else:
            if parm['mcmd'] not in self.dns_conf['mcmd']:
                self.json_error("not in mcmds")
            else:
                exestr = "%s%s %s %s"%(self.dns_conf['path'],self.dns_conf['mexe'],parm['mcmd'],parm['parm'])
                res = sys_exec(exestr)
                self.json_success(res)
#线路重新生成
class AclHandle(BaseHandle):
    def post(self):
        parm = {}
        parm['acl']    = self.get_argument("acl","")
        parm['iptype'] = self.get_argument("iptype",0)

        req_timestamp  = self.get_argument("timestamp",0)
        req_checkcode  = self.get_argument("checkcode","")

        if not self.rz(parm,req_checkcode,req_timestamp):
            self.json_error("not author")
        else:
            acl     = parm['acl']            
            iptype  = int(parm['iptype'])
            acl_row = None
            try:
                acl_row = self.mg_db.acls.find_one({"acl":acl,"iptype":iptype})
            except:
                self.json_error("find mongo fail")

            if acl_row != None and acl_row.has_key('acl'):
                acl = acl_row['acl']
                if iptype > 0:
                    aclfile = "%s%s%s/%s"%(self.dns_conf['path'],self.dns_conf['etc'],self.dns_conf['acl_ipv6_etc'],acl)
                else:
                    aclfile = "%s%s%s/%s"%(self.dns_conf['path'],self.dns_conf['etc'],self.dns_conf['acl_ipv4_etc'],acl)

                if int(acl_row['status']) == 1:
                    try:
                        fp = file(aclfile,"w")
                        fp.write(acl_row['ipdata'])
                        fp.close();
                        try:
                            #重新加载配置
                            exestr = "%s%s %s %s"%(self.dns_conf['path'],self.dns_conf['mexe'],"reloadisp",acl)
                            sys_exec(exestr)
                        except:
                            pass
                        self.json_success("write success")
                    except:
                        self.json_error("write fail")
                else:
                    if os.path.isfile(aclfile):
                        os.remove(aclfile)
                        try:
                            #重新加载配置
                            exestr = "%s%s %s %s"%(self.dns_conf['path'],self.dns_conf['mexe'],"reloadisp",acl)
                            sys_exec(exestr)
                        except:
                            pass

                        self.json_error("rewrite delete success")
            else:
                self.json_error("data no find")


#自定义线路重新生成
class CustAclHandle(BaseHandle):
    def post(self):
        parm = {}
        parm['acl']      = self.get_argument("acl","")
        parm['ns_group'] = self.get_argument("ns_group","free")

        req_timestamp  = self.get_argument("timestamp",0)
        req_checkcode  = self.get_argument("checkcode","")

        if not self.rz(parm,req_checkcode,req_timestamp):
            self.json_error("not author")
        else:
            acl      = parm['acl']
            ns_group = parm['ns_group']
            acl_row  = None
            try:
                acl_row = self.mg_db.cust_acls.find_one({"acl":acl,"ns_group":ns_group})
            except:
                self.json_error("find mongo fail")

            aclfile = "%s%s%s/%s"%(self.dns_conf['path'],self.dns_conf['etc'],self.dns_conf['cust_acl_etc'],acl)
            if acl_row != None and acl_row.has_key('acl'):
                try:
                    fp = file(aclfile,"w")
                    fp.write(acl_row['ipdata'])
                    fp.close();
                    try:
                       #重新加载配置
                        exestr = "%s%s %s %s"%(self.dns_conf['path'],self.dns_conf['mexe'],"reloadcustom",acl)
                        sys_exec(exestr)
                    except:
                        pass
                    self.json_success("write success")
                except:
                    self.json_error("write fail")        
            else:
                if os.path.isfile(aclfile):
                    os.remove(aclfile)
                try:
                    #重新加载配置
                    exestr = "%s%s %s %s"%(self.dns_conf['path'],self.dns_conf['mexe'],"reloadcustom",acl)
                    sys_exec(exestr)
                except:
                    pass

                self.json_error("rewrite delete success")