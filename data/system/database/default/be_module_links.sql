DROP TABLE IF EXISTS `be_module_links`;
CREATE TABLE `be_module_links` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `mid` smallint(5) unsigned NOT NULL default '0',
  `tid` int(10) unsigned NOT NULL default '0',
  `prid` smallint(5) unsigned NOT NULL default '0',
  `version` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=cp1251