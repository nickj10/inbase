SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

USE `test`;

DROP TABLE IF EXISTS `invoices`;
CREATE TABLE `invoices` (
                       `invoiceId` int NOT NULL AUTO_INCREMENT,
                       `invoiceNumber` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                       `clientName` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                       `baseAmount` decimal(19,2) NOT NULL,
                       `iva` decimal NOT NULL,
                       `totalAmount` decimal(19,2) NOT NULL,
                       `invoiceDate` DATETIME,
                       `dueDate` DATETIME,
                       `paymentDate` DATETIME,
                       `createdAt` DATETIME,
                       `paid` BOOLEAN,
                       `body` text CHARACTER SET utf8 COLLATE utf8_general_ci,
                       PRIMARY KEY (`invoiceId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;