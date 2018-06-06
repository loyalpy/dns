#!/usr/bin/env python
# coding=utf-8
#
# Copyright 2013 hublog.cn by thinkhu

import re

def find_mentions(content):
    regex = re.compile(ur"@(?P<username>(?!_)(?!.*?_$)(?!\d+)([a-zA-Z0-9_\u4e00-\u9fa5]+))(\s|$)", re.I)
    return [m.group("username") for m in regex.finditer(content)]

