/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : yafelement

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2021-01-26 19:39:51
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `yaf_admin`
-- ----------------------------
DROP TABLE IF EXISTS `yaf_admin`;
CREATE TABLE `yaf_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL DEFAULT '' COMMENT '用户名',
  `addr` varchar(30) NOT NULL DEFAULT '0' COMMENT 'ip 地址',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `pwd` varchar(255) NOT NULL DEFAULT '' COMMENT '密码',
  `login_time` datetime DEFAULT '0000-00-00 00:00:00',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '0超级管理员，否则为下级管理员',
  `role_id` int(11) NOT NULL DEFAULT '0' COMMENT '所属角色，0为超级管理员',
  `create_time` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yaf_admin
-- ----------------------------
INSERT INTO `yaf_admin` VALUES ('1', 'guest', '127.0.0.1', '1', '$2y$10$jowDJjm6fVmQmKwYO0ZaNeV.89wc1STHQLlUmCY1a2VSra6wPIANK', '2021-01-26 19:36:21', '0', '0', '0000-00-00 00:00:00');
INSERT INTO `yaf_admin` VALUES ('5', '章节', '127.0.0.1', '1', '$2y$10$jowDJjm6fVmQmKwYO0ZaNeV.89wc1STHQLlUmCY1a2VSra6wPIANK', '2021-01-26 19:27:24', '1', '2', '2021-01-26 18:04:01');

-- ----------------------------
-- Table structure for `yaf_config`
-- ----------------------------
DROP TABLE IF EXISTS `yaf_config`;
CREATE TABLE `yaf_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL DEFAULT '',
  `value` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yaf_config
-- ----------------------------
INSERT INTO `yaf_config` VALUES ('1', 'title', '发撒发撒法');
INSERT INTO `yaf_config` VALUES ('2', 'log', '/upload/20191227/e154ca97bbaeba5e6ead3bd32176a69b.png');
INSERT INTO `yaf_config` VALUES ('3', 'keyword', '发生发撒事实上');
INSERT INTO `yaf_config` VALUES ('4', 'desc', '发生发撒算算是否');
INSERT INTO `yaf_config` VALUES ('5', 'sys_title', '炫纹科技后台管理中心');

-- ----------------------------
-- Table structure for `yaf_role`
-- ----------------------------
DROP TABLE IF EXISTS `yaf_role`;
CREATE TABLE `yaf_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '角色名称',
  `routes` varchar(255) NOT NULL DEFAULT '' COMMENT '权限id',
  `desc` varchar(255) NOT NULL DEFAULT '' COMMENT '角色描述',
  `selected` varchar(255) NOT NULL DEFAULT '',
  `create_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yaf_role
-- ----------------------------
INSERT INTO `yaf_role` VALUES ('2', '业务员', '[[\"1\",\"2\"],[\"1\",\"6\",\"8\"]]', '拥有部分管理权限', '1,2,6,8', '2021-01-26 17:25:53');
INSERT INTO `yaf_role` VALUES ('3', '管理员', '[[\"1\"],[\"1\",\"2\"]]', '拥有大多数权限', '1,2', '2021-01-26 17:49:27');

-- ----------------------------
-- Table structure for `yaf_route`
-- ----------------------------
DROP TABLE IF EXISTS `yaf_route`;
CREATE TABLE `yaf_route` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '分类名称',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '上级ID编号',
  `icon` varchar(255) NOT NULL DEFAULT '',
  `sorts` int(11) NOT NULL DEFAULT '0' COMMENT '排序值',
  `route` varchar(255) NOT NULL DEFAULT '' COMMENT '路由',
  `create_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yaf_route
-- ----------------------------
INSERT INTO `yaf_route` VALUES ('1', '系统设置', '0', 'el-icon-setting', '0', '', '2019-12-25 16:11:40');
INSERT INTO `yaf_route` VALUES ('2', '站点配置', '1', '', '0', '/config/index', '2019-12-25 17:41:11');
INSERT INTO `yaf_route` VALUES ('4', '菜单设置', '6', '', '0', '/route/index', '2019-12-26 16:17:28');
INSERT INTO `yaf_route` VALUES ('8', '管理员管理', '6', '', '0', '/Admin/index', '2020-01-02 18:12:32');
INSERT INTO `yaf_route` VALUES ('6', '权限管理', '1', '', '0', '', '2019-12-26 16:19:08');
INSERT INTO `yaf_route` VALUES ('9', '角色管理', '6', '', '0', '/role/index', '2021-01-26 18:48:28');
