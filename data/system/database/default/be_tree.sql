DROP TABLE IF EXISTS `be_tree`;
CREATE TABLE `be_tree` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `prid` smallint(5) unsigned NOT NULL default '0',
  `alias` varchar(20) NOT NULL default '',
  `name` varchar(40) NOT NULL default '',
  `fullname` varchar(100) NOT NULL default '',
  `sort` smallint(5) unsigned NOT NULL default '0',
  `terminal` tinyint(1) unsigned NOT NULL default '0',
  `level` tinyint(2) unsigned NOT NULL default '0',
  `type` tinyint(1) unsigned NOT NULL default '0',
  `menu` varchar(10) NOT NULL default '',
  `content` tinyint(1) NOT NULL default '0',
  `link` smallint(5) unsigned NOT NULL default '0',
  `updatetime` datetime default NULL,
  `contenttime` datetime default NULL,
  `url` varchar(70) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=119 DEFAULT CHARSET=cp1251;
