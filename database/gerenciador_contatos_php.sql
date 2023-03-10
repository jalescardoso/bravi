-- Adminer 4.8.1 MySQL 8.0.32-0ubuntu0.22.04.2 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP DATABASE IF EXISTS `gerenciador-contatos-php`;
CREATE DATABASE `gerenciador-contatos-php` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `gerenciador-contatos-php`;

DROP TABLE IF EXISTS `contato`;
CREATE TABLE `contato` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `id_pessoa` int unsigned NOT NULL,
  `tipo` enum('Telefone','Whatsapp','Email') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `descricao` varchar(500) NOT NULL,
  `valor` varchar(500) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_pessoa` (`id_pessoa`),
  CONSTRAINT `contato_ibfk_1` FOREIGN KEY (`id_pessoa`) REFERENCES `pessoa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


DROP TABLE IF EXISTS `pessoa`;
CREATE TABLE `pessoa` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


-- 2023-03-11 13:54:19
