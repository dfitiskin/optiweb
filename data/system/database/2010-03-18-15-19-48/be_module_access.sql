DROP TABLE IF EXISTS `be_module_access`;
CREATE TABLE `be_module_access` (
  `mid` int(10) unsigned NOT NULL default '0',
  `perms` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`mid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251