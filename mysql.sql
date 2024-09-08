/*
 Navicat Premium Data Transfer

 Source Server         : SQLite
 Source Server Type    : SQLite
 Source Server Version : 3035005 (3.35.5)
 Source Schema         : main

 Target Server Type    : MySQL
 Target Server Version : 50699
 File Encoding         : 65001

 Date: 08/09/2024 17:29:13
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for actions_log
-- ----------------------------
DROP TABLE IF EXISTS `actions_log`;
CREATE TABLE `actions_log`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `datetime` longtext NULL,
  `email` longtext NULL,
  `phone` longtext NULL,
  `last_name` longtext NULL,
  `select_service` longtext NULL,
  `select_price` longtext NULL,
  `comments` longtext NULL,
  `ip` longtext NULL,
  `country` longtext NULL,
  `city` longtext NULL,
  `status` longtext NULL,
  `form` int NULL,
  `landingUrl` longtext NULL,
  `response` longtext NULL,
  `badData` longtext NULL,
  `fieldError` longtext NULL,
  `emailBefore` longtext NULL,
  `page` longtext NULL,
  `first_name` longtext NULL,
  PRIMARY KEY (`id`)
);

-- ----------------------------
-- Records of actions_log
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for leads
-- ----------------------------
DROP TABLE IF EXISTS `leads`;
CREATE TABLE `leads`  (
  `id` int NULL AUTO_INCREMENT,
  `datetime` longtext NULL,
  `email` longtext NULL,
  `phone` longtext NULL,
  `first_name` longtext NULL,
  `last_name` longtext NULL,
  `select_service` longtext NULL,
  `select_price` longtext NULL,
  `ip` longtext NULL,
  `country` longtext NULL,
  `city` longtext NULL,
  `form` int NULL,
  `landingUrl` longtext NULL,
  `emailBefore` longtext NULL,
  `status` longtext NULL,
  `response` longtext NULL,
  `fieldError` longtext NULL,
  `page` longtext NULL,
  `badData` longtext NULL,
  `comments` longtext NULL,
  PRIMARY KEY (`id`)
);

-- ----------------------------
-- Records of leads
-- ----------------------------
BEGIN;
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;