DROP TABLE IF EXISTS `be_groups`;
CREATE TABLE `be_groups` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `groupname` varchar(20) default NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

DROP TABLE IF EXISTS `be_module_links`;
CREATE TABLE `be_module_links` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `mid` smallint(5) unsigned NOT NULL default '0',
  `tid` int(10) unsigned NOT NULL default '0',
  `prid` smallint(5) unsigned NOT NULL default '0',
  `version` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

DROP TABLE IF EXISTS `be_module_versions`;
CREATE TABLE `be_module_versions` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `mid` smallint(5) unsigned NOT NULL default '0',
  `prid` int(10) unsigned NOT NULL default '0',
  `alias` varchar(20) NOT NULL default '',
  `name` varchar(40) NOT NULL default '',
  `type` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

DROP TABLE IF EXISTS `be_modules`;
CREATE TABLE `be_modules` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `alias` varchar(20) NOT NULL default '',
  `name` varchar(40) NOT NULL default '',
  `interactive` tinyint(1) unsigned NOT NULL default '0',
  `config` tinyint(1) NOT NULL default '0',
  `service` tinyint(1) unsigned NOT NULL default '0',
  `blocklink` tinyint(1) unsigned NOT NULL default '0',
  `nodelink` tinyint(1) unsigned NOT NULL default '0',
  `templates` tinyint(1) unsigned NOT NULL default '0',
  `multiversion` tinyint(1) unsigned NOT NULL default '0',
  `planner` tinyint(1) unsigned NOT NULL default '0',
  `export` tinyint(1) unsigned NOT NULL default '0',
  `replication` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

DROP TABLE IF EXISTS `be_navigation`;
CREATE TABLE `be_navigation` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `prid` smallint(5) unsigned NOT NULL default '0',
  `alias` varchar(20) NOT NULL default '',
  `name` varchar(40) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

DROP TABLE IF EXISTS `be_planner_modules`;
CREATE TABLE `be_planner_modules` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `mid` smallint(5) unsigned NOT NULL default '0',
  `monthtime` smallint(5) unsigned NOT NULL default '0',
  `priority` smallint(5) unsigned NOT NULL default '0',
  `currenttime` smallint(5) unsigned NOT NULL default '0',
  `blocked` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

DROP TABLE IF EXISTS `be_planner_sessions`;
CREATE TABLE `be_planner_sessions` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `mid` smallint(5) unsigned NOT NULL default '0',
  `stime` datetime NOT NULL default '0000-00-00 00:00:00',
  `seconds` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

DROP TABLE IF EXISTS `be_profiles`;
CREATE TABLE `be_profiles` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `alias` varchar(20) NOT NULL default '',
  `name` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

DROP TABLE IF EXISTS `be_profiles_access`;
CREATE TABLE `be_profiles_access` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` smallint(5) unsigned NOT NULL default '0',
  `hostname` varchar(30) NOT NULL default '',
  `rootdir` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

DROP TABLE IF EXISTS `be_templatetypes`;
CREATE TABLE `be_templatetypes` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `alias` varchar(10) NOT NULL default '',
  `name` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

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
) TYPE=MyISAM;

DROP TABLE IF EXISTS `be_user_groups`;
CREATE TABLE `be_user_groups` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `uid` smallint(5) unsigned NOT NULL default '0',
  `gid` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

DROP TABLE IF EXISTS `be_users`;
CREATE TABLE `be_users` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `login` varchar(20) NOT NULL default '',
  `password` varchar(20) NOT NULL default '',
  `name` varchar(20) NOT NULL default '',
  `surname` varchar(20) NOT NULL default '',
  `phone` varchar(20) NOT NULL default '',
  `email` varchar(20) NOT NULL default '',
  `settings` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

