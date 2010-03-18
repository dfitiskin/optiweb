DROP TABLE IF EXISTS `be_profiles`;
CREATE TABLE `be_profiles` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `alias` varchar(20) NOT NULL default '',
  `name` varchar(20) NOT NULL default '',
  `main` tinyint(1) unsigned NOT NULL default '0',
  `perms` tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=cp1251;
