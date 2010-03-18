DROP TABLE IF EXISTS `be_modules`;
CREATE TABLE `be_modules` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `alias` varchar(20) NOT NULL default '',
  `name` varchar(40) NOT NULL default '',
  `interactive` tinyint(1) unsigned NOT NULL default '0',
  `config` tinyint(1) NOT NULL default '0',
  `service` tinyint(1) unsigned NOT NULL default '0',
  `blocklink` tinyint(1) unsigned NOT NULL default '0',
  `nodelink` tinyint(1) unsigned NOT NULL default '0',
  `templates` tinyint(1) unsigned NOT NULL default '0',
  `multiversion` tinyint(1) unsigned NOT NULL default '0',
  `planner` tinyint(1) unsigned NOT NULL default '0',
  `export` tinyint(1) unsigned NOT NULL default '0',
  `replication` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=cp1251;
INSERT INTO be_modules(`id`, `alias`, `name`, `interactive`, `config`, `service`, `blocklink`, `nodelink`, `templates`, `multiversion`, `planner`, `export`, `replication`) VALUES ("1", "navigation", "Навигация", "0", "1", "1", "1", "0", "1", "1", "0", "1", "1");
INSERT INTO be_modules(`id`, `alias`, `name`, `interactive`, `config`, `service`, `blocklink`, `nodelink`, `templates`, `multiversion`, `planner`, `export`, `replication`) VALUES ("2", "feedback", "Форма обратной связи", "1", "1", "1", "1", "1", "1", "1", "1", "1", "1")