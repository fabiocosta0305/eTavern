-- phpMyAdmin SQL Dump
-- version 3.5.6
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tempo de Geração: 21/02/2013 às 14:27:04
-- Versão do Servidor: 5.5.30
-- Versão do PHP: 5.4.11

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
-- Estrutura da tabela `adv_table`
--

CREATE TABLE IF NOT EXISTS `adv_table` (
  `advid` varchar(40) NOT NULL,
  `userid` bigint(20) NOT NULL,
  `charid` bigint(20) NOT NULL,
  `stillOn` tinyint(1) DEFAULT '1',
  UNIQUE KEY `advId_2` (`advid`,`userid`,`charid`),
  KEY `advId` (`advid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `adventure`
--

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
-- Estrutura stand-in para visualizar `allChatLog`
--
CREATE TABLE IF NOT EXISTS `allChatLog` (
`advid` varchar(40)
,`userid` bigint(20)
,`postedOn` timestamp
,`text` text
);
-- --------------------------------------------------------

--
-- Estrutura stand-in para visualizar `char_conditions`
--
CREATE TABLE IF NOT EXISTS `char_conditions` (
`username` varchar(20)
,`advid` varchar(40)
,`stillOn` tinyint(1)
,`char_name` varchar(100)
,`charid` bigint(20)
,`description` varchar(255)
,`value` varchar(255)
,`goneAway` tinyint(1)
);
-- --------------------------------------------------------

--
-- Estrutura da tabela `characters`
--

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=47 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `conditions`
--

CREATE TABLE IF NOT EXISTS `conditions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `charid` bigint(20) NOT NULL,
  `description` varchar(255) NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  `goneAway` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `charid` (`charid`,`description`,`goneAway`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `loggedOn`
--

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
-- Estrutura stand-in para visualizar `parties`
--
CREATE TABLE IF NOT EXISTS `parties` (
`charid` bigint(20)
,`char_name` varchar(100)
,`userid` bigint(20)
,`username` varchar(20)
,`realname` varchar(60)
,`advid` varchar(40)
,`stillOn` tinyint(1)
);
-- --------------------------------------------------------

--
-- Estrutura stand-in para visualizar `used_chars`
--
CREATE TABLE IF NOT EXISTS `used_chars` (
`username` varchar(20)
,`realname` varchar(60)
,`advid` varchar(40)
,`stillOn` tinyint(1)
,`charid` bigint(20)
,`id` bigint(20)
,`userid` bigint(20)
,`char_name` varchar(100)
,`system` varchar(100)
,`base_desc` varchar(100)
,`sheet` text
,`history` text
);
-- --------------------------------------------------------

--
-- Estrutura da tabela `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) COLLATE utf8_bin NOT NULL,
  `realname` varchar(60) COLLATE utf8_bin NOT NULL DEFAULT 'Anonymous Coward',
  `email` varchar(30) COLLATE utf8_bin NOT NULL,
  `password` varchar(40) CHARACTER SET latin1 NOT NULL,
  `master` tinyint(1) DEFAULT '0',
  `aboutYou` text COLLATE utf8_bin,
  `site` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `facebook` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `twitter` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `googleplus` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`,`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=19 ;

-- --------------------------------------------------------

--
-- Estrutura para visualizar `allChatLog`
--
DROP TABLE IF EXISTS `allChatLog`;

CREATE ALGORITHM=UNDEFINED DEFINER=`etavern`@`%` SQL SECURITY DEFINER VIEW `allChatLog` AS select `onChatLog`.`advId` AS `advid`,`onChatLog`.`userid` AS `userid`,`onChatLog`.`postedOn` AS `postedOn`,`onChatLog`.`text` AS `text` from `onChatLog` union select `offChatLog`.`advid` AS `advid`,`offChatLog`.`userid` AS `userid`,`offChatLog`.`postedOn` AS `postedOn`,`offChatLog`.`text` AS `text` from `offChatLog`;

-- --------------------------------------------------------

--
-- Estrutura para visualizar `char_conditions`
--
DROP TABLE IF EXISTS `char_conditions`;

CREATE ALGORITHM=UNDEFINED DEFINER=`etavern`@`%` SQL SECURITY DEFINER VIEW `char_conditions` AS select `user`.`username` AS `username`,`adv_table`.`advid` AS `advid`,`adv_table`.`stillOn` AS `stillOn`,`characters`.`char_name` AS `char_name`,`conditions`.`charid` AS `charid`,`conditions`.`description` AS `description`,`conditions`.`value` AS `value`,`conditions`.`goneAway` AS `goneAway` from (((`user` join `characters`) join `adv_table`) join `conditions`) where ((not(`conditions`.`goneAway`)) and (`adv_table`.`userid` = `user`.`id`) and (`characters`.`id` = `adv_table`.`charid`) and (`conditions`.`charid` = `adv_table`.`charid`));

-- --------------------------------------------------------

--
-- Estrutura para visualizar `parties`
--
DROP TABLE IF EXISTS `parties`;

CREATE ALGORITHM=UNDEFINED DEFINER=`etavern`@`%` SQL SECURITY DEFINER VIEW `parties` AS select `characters`.`id` AS `charid`,`characters`.`char_name` AS `char_name`,`user`.`id` AS `userid`,`user`.`username` AS `username`,`user`.`realname` AS `realname`,`adventure`.`advid` AS `advid`,`adv_table`.`stillOn` AS `stillOn` from (((`adventure` join `user`) join `characters`) join `adv_table`) where ((`adventure`.`advid` = `adv_table`.`advid`) and `adv_table`.`stillOn` and (`adv_table`.`userid` = `user`.`id`) and (`characters`.`id` = `adv_table`.`charid`)) order by (`characters`.`id` = 0) desc;

-- --------------------------------------------------------

--
-- Estrutura para visualizar `used_chars`
--
DROP TABLE IF EXISTS `used_chars`;

CREATE ALGORITHM=UNDEFINED DEFINER=`etavern`@`%` SQL SECURITY DEFINER VIEW `used_chars` AS select `user`.`username` AS `username`,`user`.`realname` AS `realname`,`adventure`.`advid` AS `advid`,`adv_table`.`stillOn` AS `stillOn`,`characters`.`id` AS `charid`,`characters`.`id` AS `id`,`characters`.`userid` AS `userid`,`characters`.`char_name` AS `char_name`,`characters`.`system` AS `system`,`characters`.`base_desc` AS `base_desc`,`characters`.`sheet` AS `sheet`,`characters`.`history` AS `history` from (((`user` join `characters`) join `adventure`) join `adv_table`) where ((`user`.`id` = `adv_table`.`userid`) and (`adventure`.`advid` = `adv_table`.`advid`) and (`characters`.`id` = `adv_table`.`charid`)) order by `adventure`.`advid`;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
