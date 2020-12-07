/*
 Navicat Premium Data Transfer

 Source Server         : pixeledweb_ipfilter
 Source Server Type    : MySQL
 Source Server Version : 50640
 Source Host           : pixeledweb.com:3306
 Source Schema         : pixeledw_ipfilter

 Target Server Type    : MySQL
 Target Server Version : 50640
 File Encoding         : 65001

 Date: 08/04/2020 06:35:50

 NOTE: REPLACE pixw_ WITH THE APPROPRIATE TABLE PREFIX
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for analytic
-- ----------------------------
DROP TABLE IF EXISTS `pixw_analytic`;
CREATE TABLE `pixw_analytic`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `filter_id` int(10) UNSIGNED NULL DEFAULT NULL,
  `remote_addr` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `date` datetime NULL DEFAULT CURRENT_TIMESTAMP,
  `sess_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `referrer` varchar(512) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `useragent` varchar(512) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `pixw_filter_id`(`filter_id`) USING BTREE,
  CONSTRAINT `pixw_analytic_ibfk_1` FOREIGN KEY (`filter_id`) REFERENCES `pixw_ipentry` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for ipentry
-- ----------------------------
DROP TABLE IF EXISTS `pixw_ipentry`;
CREATE TABLE `pixw_ipentry`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip_cidr` varchar(18) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `enabled` bit(1) NOT NULL DEFAULT b'1',
  `date_added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_dec_min` int(10) UNSIGNED NULL DEFAULT NULL,
  `ip_dec_max` int(10) UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for ipentrycache
-- ----------------------------
DROP TABLE IF EXISTS `pixw_ipentrycache`;
CREATE TABLE `pixw_ipentrycache`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip_dec` int(10) UNSIGNED NOT NULL,
  `filtered` bit(1) NOT NULL DEFAULT b'1',
  `filter` int(10) UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `filter`(`filter`) USING BTREE,
  CONSTRAINT `pixw_ipentrycache_ibfk_1` FOREIGN KEY (`filter`) REFERENCES `pixw_ipentry` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

SET FOREIGN_KEY_CHECKS = 1;
