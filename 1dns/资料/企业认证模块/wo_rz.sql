/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50153
Source Host           : localhost:3306
Source Database       : 10000rcwebdb

Target Server Type    : MYSQL
Target Server Version : 50153
File Encoding         : 65001

Date: 2016-07-04 09:27:22
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `wo_rz`
-- ----------------------------
DROP TABLE IF EXISTS `wo_rz`;
CREATE TABLE `wo_rz` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(18) NOT NULL DEFAULT '',
  `name_no` varchar(60) NOT NULL DEFAULT '' COMMENT '证件编号',
  `path` varchar(80) NOT NULL DEFAULT '',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0 初次上传 1变更 2审核失败  3 审核成功',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wo_rz
-- ----------------------------
INSERT INTO `wo_rz` VALUES ('1', '26', 'idcard', '', '/attach/rz/2015/05/16/20150516115454840.jpg', '1');
INSERT INTO `wo_rz` VALUES ('2', '26', 'zhizhao', '', '/attach/rz/2015/05/16/20150516115209835.jpg', '0');
INSERT INTO `wo_rz` VALUES ('3', '26', 'chengruoshu', '', '/attach/rz/2015/05/16/20150516115226544.jpg', '0');
INSERT INTO `wo_rz` VALUES ('4', '26', 'xukezheng', '', '/attach/rz/2015/05/16/20150516115239545.jpg', '0');
INSERT INTO `wo_rz` VALUES ('5', '32', 'idcard', '', '/attach/rz/2015/06/10/20150610084610364.jpg', '0');
INSERT INTO `wo_rz` VALUES ('6', '32', 'zhizhao', '', '/attach/rz/2015/06/10/20150610084614329.jpg', '0');
INSERT INTO `wo_rz` VALUES ('7', '33', 'idcard', '', '/attach/rz/2015/08/13/20150813030632603.jpg', '0');
INSERT INTO `wo_rz` VALUES ('8', '33', 'zhizhao', '', '/attach/rz/2015/06/15/20150615122547387.jpg', '0');
INSERT INTO `wo_rz` VALUES ('9', '33', 'chengruoshu', '', '/attach/rz/2015/06/15/20150615122550554.jpg', '0');
INSERT INTO `wo_rz` VALUES ('10', '33', 'xukezheng', '', '/attach/rz/2015/06/15/20150615122618983.jpg', '0');
INSERT INTO `wo_rz` VALUES ('11', '216', 'zhizhao', '', '/attach/rz/2015/07/04/20150704061413668.jpg', '0');
