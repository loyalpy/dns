#!/usr/bin/env python
# coding=utf-8
#
# Copyright 2013 hublog.cn by thinkhu

def to_float(number):
	try:
		number = str(number).strip()
		if len(number)>0 and  number[0].isdigit() == False:
			number = 0
		return float(number)
	except ValueError:
		return 0