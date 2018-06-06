CREATE TABLE IF NOT EXISTS `wo_domain_acl` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `pid` mediumint(8) NOT NULL DEFAULT '0' COMMENT '默认级别',
  `ident` varchar(20) NOT NULL DEFAULT '' COMMENT '名称',
  `name` varchar(60) NOT NULL COMMENT '中文注释',
  `ippath` varchar(128) NOT NULL DEFAULT '' COMMENT 'ip路径',
  `ipdata` longtext NOT NULL COMMENT 'IP列表',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否锁定',
  `sort` mediumint(8) NOT NULL COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;

CREATE TABLE IF NOT EXISTS `wo_domain_aclip` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'autoid',
  `addr` varchar(60) NOT NULL DEFAULT '' COMMENT '地址',
  `aclid` int(8) unsigned NOT NULL DEFAULT '0' COMMENT '控制器ID',
  PRIMARY KEY (`id`),
  KEY `aclid` (`aclid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;


ALTER TABLE  `wo_domain_aclip` ADD  `iptype` TINYINT( 1 ) NOT NULL DEFAULT  '0' COMMENT  'ip类型' AFTER  `aclid`;

ALTER TABLE  `1dnswebdb`.`wo_domain_aclip` DROP INDEX  `aclid` ,
ADD INDEX  `aclid` (  `aclid` ,  `iptype` );


ALTER TABLE  `wo_domain_service` ADD  `acls` MEDIUMTEXT NOT NULL COMMENT  '线路' AFTER  `bz`;
