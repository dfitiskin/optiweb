DROP TABLE IF EXISTS `be_user_menu`;
CREATE TABLE `be_user_menu` (
  `mid` int(10) unsigned NOT NULL default '0',
  `uid` int(10) unsigned NOT NULL default '0',
  KEY `mid` (`mid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;
INSERT INTO be_user_menu(`mid`, `uid`) VALUES ("3", "3");
INSERT INTO be_user_menu(`mid`, `uid`) VALUES ("2", "3");
INSERT INTO be_user_menu(`mid`, `uid`) VALUES ("3", "6");
INSERT INTO be_user_menu(`mid`, `uid`) VALUES ("1", "3");
INSERT INTO be_user_menu(`mid`, `uid`) VALUES ("7", "3");
INSERT INTO be_user_menu(`mid`, `uid`) VALUES ("3", "4");
INSERT INTO be_user_menu(`mid`, `uid`) VALUES ("2", "4");
INSERT INTO be_user_menu(`mid`, `uid`) VALUES ("4", "4");
INSERT INTO be_user_menu(`mid`, `uid`) VALUES ("6", "4");
INSERT INTO be_user_menu(`mid`, `uid`) VALUES ("8", "4");
INSERT INTO be_user_menu(`mid`, `uid`) VALUES ("1", "4");
INSERT INTO be_user_menu(`mid`, `uid`) VALUES ("7", "4");
INSERT INTO be_user_menu(`mid`, `uid`) VALUES ("5", "4");
INSERT INTO be_user_menu(`mid`, `uid`) VALUES ("7", "6");
INSERT INTO be_user_menu(`mid`, `uid`) VALUES ("1", "6");
INSERT INTO be_user_menu(`mid`, `uid`) VALUES ("2", "6")