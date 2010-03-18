DROP TABLE IF EXISTS `be_profiles`;
CREATE TABLE `be_profiles` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `alias` varchar(20) NOT NULL default '',
  `name` varchar(20) NOT NULL default '',
  `main` tinyint(1) unsigned NOT NULL default '0',
  `perms` tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=cp1251;
INSERT INTO be_profiles(`id`, `alias`, `name`, `main`, `perms`) VALUES ("9", "test", "test", "0", "1")