-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 17. Dezember 2010 um 16:18
-- Server Version: 5.1.41
-- PHP-Version: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `tinycms`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `contents`
--

CREATE TABLE IF NOT EXISTS `contents` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `path` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `img_thumb_size` varchar(16) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `img_def_size` varchar(16) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Daten für Tabelle `contents`
--

INSERT INTO `contents` (`id`, `name`, `path`, `img_thumb_size`, `img_def_size`) VALUES
(1, 'system', 'system', 's120x90', 's280x210'),
(2, 'home', 'app', 's120x90', 's280x210');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `content_labels`
--

CREATE TABLE IF NOT EXISTS `content_labels` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content_id` int(10) unsigned NOT NULL,
  `parent_id` int(10) unsigned DEFAULT NULL,
  `parent_type` char(6) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'DEF',
  `listid` tinyint(5) DEFAULT NULL,
  `name` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` char(4) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `text_format` varchar(8) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `macro` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `template_id` (`content_id`),
  KEY `parent_id` (`parent_id`),
  KEY `label` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Daten für Tabelle `content_labels`
--

INSERT INTO `content_labels` (`id`, `content_id`, `parent_id`, `parent_type`, `listid`, `name`, `type`, `text_format`, `macro`) VALUES
(1, 1, NULL, 'DEF', NULL, 'OK', 'SIMP', 'TEXT', NULL),
(2, 1, NULL, 'DEF', NULL, 'CANCEL', 'SIMP', 'TEXT', NULL),
(4, 2, NULL, 'DEF', NULL, 'PARA01', 'PARA', 'TEXT', NULL),
(5, 2, NULL, 'DEF', NULL, 'PARA02', 'PARA', 'TEXT', NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `content_label_caches`
--

CREATE TABLE IF NOT EXISTS `content_label_caches` (
  `id` int(10) unsigned NOT NULL,
  `content_id` int(10) unsigned NOT NULL,
  `label_id` int(10) unsigned NOT NULL,
  `lang_id` char(2) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `cache` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `content_id` (`content_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `content_label_caches`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `content_label_images`
--

CREATE TABLE IF NOT EXISTS `content_label_images` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content_id` int(10) unsigned NOT NULL,
  `label_id` int(10) unsigned NOT NULL,
  `listid` int(11) DEFAULT NULL,
  `file_id` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `file_format` varchar(4) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT 'jpg',
  `href` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `size` varchar(16) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `align` varchar(16) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  KEY `template_id` (`content_id`),
  KEY `label_id` (`label_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Daten für Tabelle `content_label_images`
--

INSERT INTO `content_label_images` (`id`, `content_id`, `label_id`, `listid`, `file_id`, `file_format`, `href`, `size`, `align`) VALUES
(1, 2, 4, 1, 'pic001', 'jpg', NULL, '720x720', 'ri'),
(2, 2, 4, 2, 'pic002', 'jpg', NULL, '720x720', 'le');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `content_label_links`
--

CREATE TABLE IF NOT EXISTS `content_label_links` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content_id` int(10) unsigned NOT NULL,
  `label_id` int(10) unsigned NOT NULL,
  `listid` int(11) DEFAULT NULL,
  `href` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `template_id` (`content_id`,`label_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Daten für Tabelle `content_label_links`
--

INSERT INTO `content_label_links` (`id`, `content_id`, `label_id`, `listid`, `href`) VALUES
(1, 2, 4, 1, 'http://www.google.at'),
(2, 2, 4, 2, 'http://www.gamestar.de');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `content_label_texts`
--

CREATE TABLE IF NOT EXISTS `content_label_texts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content_id` int(10) unsigned NOT NULL,
  `label_id` int(10) unsigned NOT NULL,
  `parent_id` int(10) unsigned DEFAULT NULL,
  `parent_type` char(6) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'DEF',
  `lang_id` char(2) COLLATE utf8_unicode_ci NOT NULL,
  `listid` int(11) DEFAULT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `label_id` (`label_id`),
  KEY `template_id` (`content_id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=11 ;

--
-- Daten für Tabelle `content_label_texts`
--

INSERT INTO `content_label_texts` (`id`, `content_id`, `label_id`, `parent_id`, `parent_type`, `lang_id`, `listid`, `text`) VALUES
(1, 1, 1, NULL, 'DEF', 'en', NULL, 'OK'),
(2, 1, 2, NULL, 'DEF', 'en', NULL, 'Cancel'),
(3, 1, 1, NULL, 'DEF', 'de', NULL, 'OK'),
(4, 1, 2, NULL, 'DEF', 'de', NULL, 'Abbruch'),
(5, 2, 4, NULL, 'DEF', 'de', NULL, '[S:1]Wilfried Reiter[/S] auf der [LK:1]Suche[/LK] nach den [S:1]Jungs![/S][IMG:2]'),
(6, 2, 5, NULL, 'DEF', 'de', NULL, 'Carmen Brabsche'),
(7, 2, 4, 1, 'LNK', 'de', NULL, 'www.google.at'),
(8, 2, 4, 2, 'LNK', 'de', NULL, 'Gamestar'),
(9, 2, 4, 1, 'IMG', 'de', NULL, 'Carmen mit lieben Freunden'),
(10, 2, 4, 2, 'IMG', 'de', NULL, 'Unsere Jungs');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
