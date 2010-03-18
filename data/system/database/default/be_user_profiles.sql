DROP TABLE IF EXISTS `be_user_profiles`;
CREATE TABLE `be_user_profiles` (
  `uid` int(10) unsigned NOT NULL default '0',
  `prid` int(10) unsigned NOT NULL default '0',
  `access` smallint(6) NOT NULL default '0',
  PRIMARY KEY  (`uid`,`prid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;
