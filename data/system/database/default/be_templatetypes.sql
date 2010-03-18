DROP TABLE IF EXISTS `be_templatetypes`;
CREATE TABLE `be_templatetypes` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `alias` varchar(10) NOT NULL default '',
  `name` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=cp1251;
INSERT INTO be_templatetypes(`id`, `alias`, `name`) VALUES ("1", "block", "Блок")