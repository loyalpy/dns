#!/bin/sh
#moniter 心跳程序
ps -fe|grep "/usr/bin/mongod" |grep -v grep >> /dev/null
if [ $? -ne 0 ]
then
echo "restarting mongod ..." >> monitor_result.log
systemctl start mongod >> monitor_result.log
fi


ps -fe|grep "/data/beanstalk/bin/beanstalkd" |grep -v grep >> /dev/null
if [ $? -ne 0 ]
then
echo "restarting beanstalkd ..." >> monitor_result.log
/etc/init.d/beanstalkd start >> monitor_result.log
fi



ps -fe|grep "/home/webroot/bajiednsweb/bin/appcrond start" |grep -v grep >> /dev/null
if [ $? -ne 0 ]
then
echo "restarting appcrond ..." >> monitor_result.log
/home/webroot/bajiednsweb/bin/appcrond stop >> monitor_result.log
/home/webroot/bajiednsweb/bin/appcrond start >> monitor_result.log
fi

