DROP TABLE IF EXISTS `be_profiles_access`;
CREATE TABLE `be_profiles_access` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` smallint(5) unsigned NOT NULL default '0',
  `hostname` varchar(255) NOT NULL default '',
  `rootdir` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=cp1251