DROP TABLE IF EXISTS `be_planner_sessions`;
CREATE TABLE `be_planner_sessions` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `mid` smallint(5) unsigned NOT NULL default '0',
  `stime` datetime NOT NULL default '0000-00-00 00:00:00',
  `seconds` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251