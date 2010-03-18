DROP TABLE IF EXISTS `be_user_menu`;
CREATE TABLE `be_user_menu` (
  `mid` int(10) unsigned NOT NULL default '0',
  `uid` int(10) unsigned NOT NULL default '0',
  KEY `mid` (`mid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;
