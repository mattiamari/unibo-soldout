-- Adminer 4.7.6 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `api_key`;
CREATE TABLE `api_key` (
  `api_key` char(32) NOT NULL,
  `user_id` varchar(11) NOT NULL,
  `expiry` datetime NOT NULL,
  PRIMARY KEY (`api_key`),
  KEY `fk_api_key_user` (`user_id`),
  CONSTRAINT `api_key_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `artist`;
CREATE TABLE `artist` (
  `id` varchar(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `description` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `cart`;
CREATE TABLE `cart` (
  `id` varchar(11) NOT NULL,
  `customer_id` varchar(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cart_customer` (`customer_id`),
  CONSTRAINT `fk_cart_customer` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `cart_item`;
CREATE TABLE `cart_item` (
  `cart_id` varchar(11) NOT NULL,
  `ticket_type_id` varchar(11) NOT NULL,
  `quantity` int(10) unsigned DEFAULT 1,
  PRIMARY KEY (`cart_id`,`ticket_type_id`),
  KEY `fk_cart_item_ticker_type` (`ticket_type_id`),
  CONSTRAINT `fk_cart_item_cart` FOREIGN KEY (`cart_id`) REFERENCES `cart` (`id`),
  CONSTRAINT `fk_cart_item_ticker_type` FOREIGN KEY (`ticket_type_id`) REFERENCES `ticket_type` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `city`;
CREATE TABLE `city` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `state_id` mediumint(8) unsigned NOT NULL,
  `state_code` varchar(255) NOT NULL,
  `country_id` mediumint(8) unsigned NOT NULL,
  `country_code` char(2) NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cities_test_ibfk_1` (`state_id`),
  KEY `cities_test_ibfk_2` (`country_id`),
  CONSTRAINT `city_ibfk_1` FOREIGN KEY (`state_id`) REFERENCES `geo_state` (`id`),
  CONSTRAINT `city_ibfk_2` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=141852 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;


DROP TABLE IF EXISTS `country`;
CREATE TABLE `country` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `iso3` char(3) DEFAULT NULL,
  `iso2` char(2) DEFAULT NULL,
  `phonecode` varchar(255) DEFAULT NULL,
  `capital` varchar(255) DEFAULT NULL,
  `currency` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=248 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `customer`;
CREATE TABLE `customer` (
  `user_id` varchar(11) NOT NULL,
  `firstname` varchar(32) DEFAULT NULL,
  `lastname` varchar(32) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  CONSTRAINT `fk_customer_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `geo_state`;
CREATE TABLE `geo_state` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `country_id` mediumint(8) unsigned NOT NULL,
  `country_code` char(2) NOT NULL,
  `fips_code` varchar(255) DEFAULT NULL,
  `iso2` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `country_region` (`country_id`),
  CONSTRAINT `country_region_final` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4852 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;


DROP TABLE IF EXISTS `image`;
CREATE TABLE `image` (
  `subject_id` varchar(11) NOT NULL,
  `subject` enum('show','venue','artist') NOT NULL,
  `name` varchar(128) NOT NULL,
  `type` varchar(64) NOT NULL,
  `altText` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`subject_id`,`subject`,`type`),
  KEY `subject_id` (`subject_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `manager`;
CREATE TABLE `manager` (
  `user_id` varchar(11) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT 0,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`user_id`),
  CONSTRAINT `fk_manager_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `notification`;
CREATE TABLE `notification` (
  `id` varchar(11) NOT NULL,
  `content` text NOT NULL,
  `action` varchar(256) DEFAULT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `order`;
CREATE TABLE `order` (
  `cart_id` varchar(11) NOT NULL,
  `reference` varchar(5) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `status` enum('waiting_payment','paid') DEFAULT NULL,
  PRIMARY KEY (`cart_id`),
  UNIQUE KEY `reference` (`reference`),
  CONSTRAINT `fk_order_cart` FOREIGN KEY (`cart_id`) REFERENCES `cart` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `show`;
CREATE TABLE `show` (
  `id` varchar(11) NOT NULL,
  `title` varchar(128) NOT NULL,
  `date` datetime NOT NULL,
  `show_category_id` varchar(11) NOT NULL,
  `venue_id` varchar(11) DEFAULT NULL,
  `artist_id` varchar(11) DEFAULT NULL,
  `manager_id` varchar(11) NOT NULL,
  `max_tickets_per_order` int(10) unsigned NOT NULL DEFAULT 1,
  `description` text DEFAULT NULL,
  `enabled` tinyint(1) DEFAULT 0,
  `creation_date` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `date` (`date`),
  KEY `fk_show_show_category` (`show_category_id`),
  KEY `fk_show_artist` (`artist_id`),
  KEY `fx_show_venue` (`venue_id`),
  KEY `fk_show_manager` (`manager_id`),
  CONSTRAINT `fk_show_artist` FOREIGN KEY (`artist_id`) REFERENCES `artist` (`id`),
  CONSTRAINT `fk_show_manager` FOREIGN KEY (`manager_id`) REFERENCES `manager` (`user_id`),
  CONSTRAINT `fx_show_venue` FOREIGN KEY (`venue_id`) REFERENCES `venue` (`id`),
  CONSTRAINT `show_ibfk_1` FOREIGN KEY (`show_category_id`) REFERENCES `show_category` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `show_category`;
CREATE TABLE `show_category` (
  `id` varchar(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ticket_type`;
CREATE TABLE `ticket_type` (
  `id` varchar(11) NOT NULL,
  `show_id` varchar(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `description` varchar(256) DEFAULT NULL,
  `price` float NOT NULL,
  `max_tickets` int(10) unsigned DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `fx_ticket_type_show` (`show_id`),
  CONSTRAINT `fx_ticket_type_show` FOREIGN KEY (`show_id`) REFERENCES `show` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` varchar(11) NOT NULL,
  `email` varchar(128) NOT NULL,
  `password` char(64) NOT NULL,
  `salt` char(32) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `user_notification`;
CREATE TABLE `user_notification` (
  `notification_id` varchar(11) NOT NULL,
  `user_id` varchar(11) NOT NULL,
  KEY `fk_user_notification_notification` (`notification_id`),
  KEY `fk_user_notification_user` (`user_id`),
  CONSTRAINT `fk_user_notification_notification` FOREIGN KEY (`notification_id`) REFERENCES `notification` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_user_notification_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `venue`;
CREATE TABLE `venue` (
  `id` varchar(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `description` text DEFAULT NULL,
  `address` varchar(128) DEFAULT NULL,
  `city_id` mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `fx_venue_city` (`city_id`),
  CONSTRAINT `fx_venue_city` FOREIGN KEY (`city_id`) REFERENCES `city` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- 2020-02-19 18:21:35
