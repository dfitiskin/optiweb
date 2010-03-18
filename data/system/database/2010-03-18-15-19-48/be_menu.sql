DROP TABLE IF EXISTS `be_menu`;
CREATE TABLE `be_menu` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `alias` varchar(50) NOT NULL default '',
  `sort` tinyint(1) unsigned NOT NULL default '0',
  `perms` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=cp1251;
INSERT INTO be_menu(`id`, `name`, `alias`, `sort`, `perms`) VALUES ("1", "Структура", "struct", "1", "1");
INSERT INTO be_menu(`id`, `name`, `alias`, `sort`, `perms`) VALUES ("2", "Интерактив", "interactive", "2", "1");
INSERT INTO be_menu(`id`, `name`, `alias`, `sort`, `perms`) VALUES ("3", "Изображения", "images", "3", "1");
INSERT INTO be_menu(`id`, `name`, `alias`, `sort`, `perms`) VALUES ("4", "Модули", "modules", "4", "1");
INSERT INTO be_menu(`id`, `name`, `alias`, `sort`, `perms`) VALUES ("5", "Шаблоны", "templates", "5", "1");
INSERT INTO be_menu(`id`, `name`, `alias`, `sort`, `perms`) VALUES ("6", "Сервисы", "services", "6", "1");
INSERT INTO be_menu(`id`, `name`, `alias`, `sort`, `perms`) VALUES ("7", "Файлы", "files", "7", "1");
INSERT INTO be_menu(`id`, `name`, `alias`, `sort`, `perms`) VALUES ("8", "Система", "system", "8", "1");
INSERT INTO be_menu(`id`, `name`, `alias`, `sort`, `perms`) VALUES ("9", "Изображения", "imagesdlg", "9", "2")