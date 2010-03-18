DROP TABLE IF EXISTS `be_navigation`;
CREATE TABLE `be_navigation` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `prid` smallint(5) unsigned NOT NULL default '0',
  `alias` varchar(20) NOT NULL default '',
  `name` varchar(40) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=cp1251;
INSERT INTO be_navigation(`id`, `prid`, `alias`, `name`) VALUES ("10", "9", "test", "Тестовая навигация")