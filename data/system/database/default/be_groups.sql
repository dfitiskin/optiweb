DROP TABLE IF EXISTS `be_groups`;
CREATE TABLE `be_groups` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `groupname` varchar(20) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251