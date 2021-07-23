SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

USE `invoiceManagement`;

DROP TABLE IF EXISTS `invoices`;
CREATE TABLE `invoices` (
                       `invoiceId` int NOT NULL AUTO_INCREMENT,
                       `invoiceNumber` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                       `body` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                       PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;