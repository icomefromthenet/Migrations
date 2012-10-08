USE sakila;

DROP TABLE IF EXISTS `migrations_data`;

CREATE TABLE `migrations_data`(`timestamp` integer(11) NOT NULL, PRIMARY KEY (`timestamp`))ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;