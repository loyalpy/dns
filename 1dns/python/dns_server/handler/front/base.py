#!/usr/bin/env python
# coding=utf-8
#
# Copyright 2013 hublog.cn by thinkhu

import tornado.web
import time
from library import jsonp
#import helper

class BaseHandle(tornado.web.RequestHandler):
    def __init__(self, *argc, **argkw):
        super(BaseHandle, self).__init__(*argc, **argkw)
        #self.jinja2  = self.settings.get("jinja2")
        self.loader  = self.application.loader

    @property
    def mg_db(self):
        return self.application.mg_db

    @property
    def db(self):
        return 0
        #return self.application.db

    @property
    def user_model(self):
        return 1
        #return self.application.user_model

    @property
    def dns_conf(self):
        return self.loader.load_conf("%sconf/dns.conf"%(self.settings.get('root')))
    

    def json(self,data = {}):
        self.write(jsonp.dump(data))
        return 0

    def json_success(self,msg="success",data={}):
        return self.json({"status":1,"msg":msg,"data":data})

    def json_error(self,msg="error",data={}):
        return self.json({"status":0,"msg":msg,"data":data})
    
    ## 认证    
    def rz(self,arg,checkcode,timestamp):
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
        
    def render(self, tpl_name, **tpl_vars):
        html = self.render_string(tpl_name, **tpl_vars)
        self.write(html)

    def render_string(self, tpl_name, **tpl_vars):
        tpl_vars["xsrf_form_html"] = self.xsrf_form_html
        #tpl_vars["current_user"] = self.current_user
        #tpl_vars["request"] = self.request
        #tpl_vars["request_handler"] = self
        tpl = self.jinja2.get_template(tpl_name)
        return tpl.render(**tpl_vars)

    def render_from_string(self, tpl_string, **tpl_vars):
        tpl = self.jinja2.from_string(tpl_string)
        return tpl.render(**tpl_vars)
    
    def get_wallpaper(self):
        wallpaper = "../../static/images/wallpaper.png"
        return wallpaper

