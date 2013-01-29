-- phpMyAdmin SQL Dump
-- version 3.5.4
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tempo de Geração: 29/01/2013 às 13:48:37
-- Versão do Servidor: 5.5.28
-- Versão do PHP: 5.4.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Banco de Dados: `etavern`
--
CREATE DATABASE `etavern` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `etavern`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `adventure`
--

DROP TABLE IF EXISTS `adventure`;
CREATE TABLE IF NOT EXISTS `adventure` (
  `advid` varchar(40) NOT NULL,
  `masterid` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `system` varchar(255) NOT NULL,
  `description` text,
  `defaultDice` varchar(10) DEFAULT NULL,
  `opened` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ended` tinyint(1) DEFAULT '0',
  UNIQUE KEY `advid` (`advid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `characters`
--

DROP TABLE IF EXISTS `characters`;
CREATE TABLE IF NOT EXISTS `characters` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `userid` bigint(20) NOT NULL,
  `char_name` varchar(100) NOT NULL,
  `system` varchar(100) NOT NULL,
  `base_desc` varchar(100) NOT NULL,
  `sheet` text,
  `history` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `userid` (`userid`,`char_name`,`system`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=42 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `conditions`
--

DROP TABLE IF EXISTS `conditions`;
CREATE TABLE IF NOT EXISTS `conditions` (
  `charid` bigint(20) NOT NULL,
  `description` varchar(255) NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  `goneAway` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `loggedOn`
--

DROP TABLE IF EXISTS `loggedOn`;
CREATE TABLE IF NOT EXISTS `loggedOn` (
  `id` int(11) NOT NULL,
  `lasttime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `id_2` (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `offChatLog`
--

DROP TABLE IF EXISTS `offChatLog`;
CREATE TABLE IF NOT EXISTS `offChatLog` (
  `advid` varchar(40) DEFAULT NULL,
  `userid` bigint(20) NOT NULL,
  `postedOn` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `text` text NOT NULL,
  KEY `postedOn` (`postedOn`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `onChatLog`
--

DROP TABLE IF EXISTS `onChatLog`;
CREATE TABLE IF NOT EXISTS `onChatLog` (
  `advId` varchar(40) DEFAULT NULL,
  `userid` bigint(20) NOT NULL,
  `postedOn` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `command` varchar(255) DEFAULT NULL,
  `parm` varchar(255) DEFAULT NULL,
  `text` text NOT NULL,
  KEY `postedOn` (`postedOn`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `table`
--

DROP TABLE IF EXISTS `table`;
CREATE TABLE IF NOT EXISTS `table` (
  `advId` varchar(40) NOT NULL,
  `userid` bigint(20) NOT NULL,
  `charid` bigint(20) NOT NULL,
  KEY `advId` (`advId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) COLLATE utf8_bin NOT NULL,
  `realname` varchar(60) COLLATE utf8_bin NOT NULL DEFAULT 'Anonymous Coward',
  `email` varchar(30) COLLATE utf8_bin NOT NULL,
  `password` varchar(40) CHARACTER SET latin1 NOT NULL,
  `master` tinyint(1) DEFAULT '0',
  `aboutYou` text COLLATE utf8_bin NOT NULL,
  `site` varchar(255) COLLATE utf8_bin NOT NULL,
  `facebook` varchar(255) COLLATE utf8_bin NOT NULL,
  `twitter` varchar(100) COLLATE utf8_bin NOT NULL,
  `googleplus` varchar(255) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`,`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=13 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
