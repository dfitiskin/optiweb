DROP TABLE IF EXISTS `be_navigation`;
CREATE TABLE `be_navigation` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `prid` smallint(5) unsigned NOT NULL default '0',
  `alias` varchar(20) NOT NULL default '',
  `name` varchar(40) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=cp1251