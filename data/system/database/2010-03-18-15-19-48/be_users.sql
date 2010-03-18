DROP TABLE IF EXISTS `be_users`;
CREATE TABLE `be_users` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `login` varchar(20) NOT NULL default '',
  `password` varchar(20) NOT NULL default '',
  `name` varchar(20) NOT NULL default '',
  `surname` varchar(20) NOT NULL default '',
  `phone` varchar(20) NOT NULL default '',
  `email` varchar(20) NOT NULL default '',
  `settings` text NOT NULL,
  `master` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=cp1251;
INSERT INTO be_users(`id`, `login`, `password`, `name`, `surname`, `phone`, `email`, `settings`, `master`) VALUES ("1", "root", "root", "Администратор", "", "", "", "", "1")