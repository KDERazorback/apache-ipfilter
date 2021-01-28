/*
 Server Type    : MySQL
 Server Version : 50640
 Schema         : {{SCHEMA}}

 Target Server Type    : MySQL
 Target Server Version : 50640
 File Encoding         : 65001
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

/* ----------------------------
-- Table structure for {{TABLE_ANALYTIC}}
-- --------------------------*/
DROP TABLE IF EXISTS `{{TABLE_ANALYTIC}}`;
CREATE TABLE `{{TABLE_ANALYTIC}}`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `filter_id` int(10) UNSIGNED NULL DEFAULT NULL,
  `remote_addr` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `date` datetime NULL DEFAULT CURRENT_TIMESTAMP,
  `sess_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `referrer` varchar(512) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `useragent` varchar(512) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `filter_id`(`filter_id`) USING BTREE,
  CONSTRAINT `{{TABLE_ANALYTIC}}_ibfk_1` FOREIGN KEY (`filter_id`) REFERENCES `{{TABLE_ENTRY}}` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

/* ----------------------------
-- Table structure for {{TABLE_ENTRY}}
-- --------------------------*/
DROP TABLE IF EXISTS `{{TABLE_ENTRY}}`;
CREATE TABLE `{{TABLE_ENTRY}}`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip_cidr` varchar(18) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `enabled` bit(1) NOT NULL DEFAULT b'1',
  `date_added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_dec_min` int(10) UNSIGNED NULL DEFAULT NULL,
  `ip_dec_max` int(10) UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

/* ----------------------------
-- Table structure for {{TABLE_ENTRYCACHE}}
-- --------------------------*/
DROP TABLE IF EXISTS `{{TABLE_ENTRYCACHE}}`;
CREATE TABLE `{{TABLE_ENTRYCACHE}}`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip_dec` int(10) UNSIGNED NOT NULL,
  `filtered` bit(1) NOT NULL DEFAULT b'1',
  `filter` int(10) UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `filter`(`filter`) USING BTREE,
  CONSTRAINT `{{TABLE_ENTRYCACHE}}_ibfk_1` FOREIGN KEY (`filter`) REFERENCES `{{TABLE_ENTRY}}` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

SET FOREIGN_KEY_CHECKS = 1;
