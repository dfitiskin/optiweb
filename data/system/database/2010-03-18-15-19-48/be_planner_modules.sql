DROP TABLE IF EXISTS `be_planner_modules`;
CREATE TABLE `be_planner_modules` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `mid` smallint(5) unsigned NOT NULL default '0',
  `monthtime` smallint(5) unsigned NOT NULL default '0',
  `priority` smallint(5) unsigned NOT NULL default '0',
  `currenttime` smallint(5) unsigned NOT NULL default '0',
  `blocked` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251