DROP TABLE IF EXISTS `be_version_access`;
CREATE TABLE `be_version_access` (
  `version_id` int(10) unsigned NOT NULL default '0',
  `perms` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`version_id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251