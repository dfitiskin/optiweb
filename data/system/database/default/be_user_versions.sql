DROP TABLE IF EXISTS `be_user_versions`;
CREATE TABLE `be_user_versions` (
  `version_id` int(10) unsigned NOT NULL default '0',
  `uid` int(10) unsigned NOT NULL default '0',
  `perms` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`version_id`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251