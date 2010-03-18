DROP TABLE IF EXISTS `be_module_versions`;
CREATE TABLE `be_module_versions` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `mid` smallint(5) unsigned NOT NULL default '0',
  `prid` int(10) unsigned NOT NULL default '0',
  `alias` varchar(20) NOT NULL default '',
  `name` varchar(40) NOT NULL default '',
  `type` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=cp1251