#!/usr/bin/env python
# coding=utf-8
#
# Copyright 2013 by thinkhu


import sys
import time,datetime
from base import BaseHandle

class IndexHandle(BaseHandle):
    def get(self, tpl_vars = {}):
        parm = {}
        parm['wo'] = self.get_argument("wo","test")
        parm['do'] = self.get_argument("do","test")
        req_timestamp = self.get_argument("timestamp",0)
        req_checkcode = self.get_argument("checkcode","")
        if not self.rz(parm,req_checkcode,req_timestamp):
        	self.write("no author\t")
        else:
        	self.write("ok")

    def post(self):
        self.write("ok")