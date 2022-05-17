CREATE DATABASE `yesql`;
CREATE USER 'yesql'@'localhost' IDENTIFIED BY 'yesql';
GRANT ALL PRIVILEGES ON yesql.* TO 'yesql'@'%' WITH GRANT OPTION;
DROP TABLE IF EXISTS `yesql`.`test_table`;
CREATE TABLE `yesql`.`test_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `something` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
