CREATE DATABASE `lesley`;
USE `lesley`

CREATE TABLE `biography` (
  `bio` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `fontfamilies` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `fonts` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  `family` tinyint(3) unsigned NOT NULL,
  `backup` tinyint(3) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `info` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `width` decimal(4,2) unsigned DEFAULT NULL,
  `height` decimal(4,2) unsigned DEFAULT NULL,
  `year` year(4) DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `sold` bit(2) DEFAULT NULL,
  `price` smallint(5) unsigned DEFAULT NULL,
  `fineartamerica` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `etsy` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sequence` tinyint(3) unsigned DEFAULT NULL,
  `filename` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `md5` char(32) COLLATE utf8_unicode_ci NOT NULL,
  `rotation` smallint(5) unsigned NOT NULL DEFAULT 0,
  `leftcrop` smallint(5) unsigned NOT NULL DEFAULT 0,
  `rightcrop` smallint(5) unsigned NOT NULL DEFAULT 0,
  `topcrop` smallint(5) unsigned NOT NULL DEFAULT 0,
  `bottomcrop` smallint(5) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `filename` (`filename`),
  UNIQUE KEY `md5` (`md5`),
  UNIQUE KEY `sequence` (`sequence`),
  UNIQUE KEY `fineartamerica` (`fineartamerica`),
  UNIQUE KEY `etsy` (`etsy`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `style` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `hue` smallint(5) unsigned NOT NULL,
  `saturation` tinyint(3) unsigned NOT NULL,
  `primarylightness` tinyint(3) unsigned NOT NULL,
  `secondarylightness` tinyint(3) unsigned DEFAULT NULL,
  `backgroundlightness` tinyint(3) unsigned NOT NULL,
  `primaryfont` tinyint(3) unsigned NOT NULL,
  `secondaryfont` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `shuffle` (
  `shuffle` tinyint(1) unsigned NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
