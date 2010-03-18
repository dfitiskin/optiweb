DROP TABLE IF EXISTS `be_profiles_menu`;
CREATE TABLE `be_profiles_menu` (
  `prid` int(10) unsigned NOT NULL default '0',
  `mid` int(10) unsigned NOT NULL default '0',
  KEY `prid` (`prid`),
  KEY `mid` (`mid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251