#!/usr/bin/env python
# coding=utf-8
#
# Copyright 2013 hublog.cn by thinkhu
import yaml
from os.path import isfile

class Loader(object):
    def __init__(self):
        self.loaded = {
            "model": {},
            "handler": {}
        }

    def use(self, name):
        _name = name.split(".")
        _type = name[0]

        if(_type == "model"):
            return self.load_model(name)

        if(_type == "handler"):
            return self.load_handler(name)

    def load_conf(self, path):
        if not isfile(path):
            print "[FATAL] can't find config file %s !" % path
            exit(1)
        f = open(path, 'r')
        x = yaml.load(f)
        f.close
        return x

    def load_model(self, name,db):
        if(name in self.loaded["model"]):
            return self.loaded["model"][name]
        instance_name = "%s%s" % (name.capitalize(), "Model")
        self.loaded["model"][name] = __import__("model.%s" % name)
        self.loaded["model"][name] = eval('self.loaded["model"][name].%s.%s' % (name, instance_name))
        self.loaded["model"][name] = self.loaded["model"][name](db)
        return self.loaded["model"][name]

    def load_handler(self, name):
        if(name in self.loaded["handler"]):
            return self.loaded["handler"][name]
        instance_name = "%s%s" % (name.capitalize(), "Handle")
        self.loaded["handler"][name] = __import__("handler.%s" % name)
        self.loaded["handler"][name] = eval('self.loaded["handler"][name].%s.%s' % (name, instance_name))
        self.loaded["handler"][name] = self.loaded["handler"][name]()
        return self.loaded["handler"][name]