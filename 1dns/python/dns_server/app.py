#!/usr/bin/env python
# coding=utf-8
#
# Copyright 2013 hublog.cn by thinkhu


import sys
reload(sys)
sys.setdefaultencoding("utf8")

import os.path
import re
import tornado.httpserver
import tornado.ioloop
import tornado.options
import tornado.web

from tornado.options import define, options
from jinja2 import Environment, FileSystemLoader,runtime

#memcache
import memcache

#mysql db
#import torndb

#mongo db
from pymongo import MongoClient

from library.loader import Loader

define("host", default = "0.0.0.0", help = "run on the given host", type = str)
define("port", default = 9000, help = "run on the given port", type = int)
define("name", default = "front", help = "run on the given name", type = str)

class NonIterableUndefined(runtime.Undefined):
    def _fail_with_undefined_error(self):
        return ''
class HttpError(tornado.web.RequestHandler):
    def get(self):
        self.write_error(404)
    def write_error(self, status_code, **kwargs):
        if status_code == 404:
            self.write('HTTP:PAGE NO FOUND')
            #raise tornado.web.HTTPError(status_code)
        elif status_code == 500:
            self.write('HTTP:PAGE NO FOUND')
            #raise tornado.web.HTTPError(status_code)
        else:
            self.write(status_code)
            #raise tornado.web.HTTPError(status_code)
class Application(tornado.web.Application):
    def __init__(self,name):
        #自动加载app下的所有类
        exec "from handler.%s import *"%name
        self.dir = os.path.realpath(__file__)
        self.dir = "%s/"%(os.path.dirname(os.path.abspath(self.dir)))

        self.name   = name
        self.loader = Loader()

        settings = self.loader.load_conf("%sconf/%s.conf"%(self.dir,self.name))
        settings['root'] = self.dir
        settings['static_path'] = os.path.join(settings['root'], settings['static_path'])
        settings['jinja2'] = Environment(loader = FileSystemLoader(os.path.join(settings['root'], "views/%s"%settings['theme'])), trim_blocks = True,undefined=NonIterableUndefined)
        

        if self.name == 'front':
            #dns server
            handlers = [
                (r"/", site.IndexHandle),
                (r"/DnsServer/Cmd", dnsserver.CmdHandle),
                (r"/DnsServer/MCmd", dnsserver.MCmdHandle),
                (r"/DnsServer/Acl", dnsserver.AclHandle),
                (r"/DnsServer/CustAcl", dnsserver.CustAclHandle),
                (r"/Dns/Zone", dns.ZoneHandle),
                (r"/(favicon\.ico)", tornado.web.StaticFileHandler, dict(path = settings["static_path"])),
                (r".*", HttpError),
            ]
            #database
            dbconfig = self.loader.load_conf(os.path.join(settings['root'], "conf/db.conf"))

            #self.db = torndb.Connection(
            #    host = dbconfig['mysql']['db_host'], database = dbconfig['mysql']['db_name'],
            #    user = dbconfig['mysql']['db_user'], password = dbconfig['mysql']['db_pass']
            #)
            self.mg_client = MongoClient(dbconfig['mongo']['dsn'])
            self.mg_db = self.mg_client[dbconfig['mongo']['db_name']]
      
        elif self.name == 'monitor':
            #monitor
            handlers = [
                (r"/", monitor.IndexHandle),
                (r"/Monitor/Callback", monitor.CallbackHandle),
                (r"/(favicon\.ico)", tornado.web.StaticFileHandler, dict(path = settings["static_path"])),
                (r".*", HttpError),
            ]

        
        # Have one global memcache controller
        #self.mc = memcache.Client(["127.0.0.1:11211"])        
        tornado.web.Application.__init__(self, handlers, **settings)

def main():
    tornado.options.parse_command_line()   
    http_server = tornado.httpserver.HTTPServer(Application(options.name))
    http_server.listen(options.port,options.host)
    #http_server.start(0)
    tornado.ioloop.IOLoop.instance().start()

if __name__ == "__main__":
    main()
