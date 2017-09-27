/*
Navicat MySQL Data Transfer

Source Server         : Mickey
Source Server Version : 50714
Source Host           : localhost:3306
Source Database       : wserver

Target Server Type    : MYSQL
Target Server Version : 50714
File Encoding         : 65001

Date: 2017-09-27 17:47:20
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for jobs
-- ----------------------------
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8_unicode_ci NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_reserved_reserved_at_index` (`queue`,`reserved`,`reserved_at`)
) ENGINE=MyISAM AUTO_INCREMENT=115 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of jobs
-- ----------------------------

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of migrations
-- ----------------------------

-- ----------------------------
-- Table structure for permissions
-- ----------------------------
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_unique` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=83 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of permissions
-- ----------------------------
INSERT INTO `permissions` VALUES ('1', 'manage.home.index', '', '首页', '2017-05-22 17:03:12', '2017-05-22 17:03:15');
INSERT INTO `permissions` VALUES ('2', 'manage.statistics.index', '', '统计管理', '2017-05-22 16:23:29', '2017-05-22 16:23:31');
INSERT INTO `permissions` VALUES ('3', 'manage.rbac.index', null, '角色管理', '2017-07-04 09:00:43', '2017-07-04 09:00:46');
INSERT INTO `permissions` VALUES ('4', 'manage.user.index', null, '会员管理', '2017-07-04 09:01:35', '2017-07-04 09:01:37');
INSERT INTO `permissions` VALUES ('5', 'manage.adCategory.index', null, '广告管理', '2017-07-04 09:02:11', '2017-07-04 09:02:14');
INSERT INTO `permissions` VALUES ('6', 'manage.goods.index', null, '商品列表', '2017-07-04 09:03:47', '2017-07-04 09:03:50');
INSERT INTO `permissions` VALUES ('7', 'manage.goods.recycle', null, '商品回收站', '2017-07-04 09:04:34', '2017-07-04 09:04:38');
INSERT INTO `permissions` VALUES ('8', 'manage.order.index', null, '订单列表', '2017-07-04 09:05:18', '2017-07-04 09:05:21');
INSERT INTO `permissions` VALUES ('9', 'manage.order.home', null, '发货单列表', '2017-07-04 09:05:57', '2017-07-04 09:06:01');
INSERT INTO `permissions` VALUES ('10', 'manage.makter.index', null, '猜大盘', '2017-07-04 09:06:37', '2017-07-04 09:06:39');
INSERT INTO `permissions` VALUES ('11', 'manage.transfer.index', null, '提现管理', '2017-07-04 09:07:16', '2017-07-04 09:07:18');
INSERT INTO `permissions` VALUES ('12', 'manage.finance.index', null, '销售排行', '2017-07-04 09:10:00', '2017-07-04 09:10:02');
INSERT INTO `permissions` VALUES ('13', 'manage.finance.index_deta', null, '销售查询', '2017-07-04 09:10:21', '2017-07-04 09:10:23');
INSERT INTO `permissions` VALUES ('14', 'manage.store.index', null, '店铺管理首页', '2017-07-04 09:11:01', '2017-07-04 09:11:03');
INSERT INTO `permissions` VALUES ('15', 'manage.group.index', null, '团购首页', '2017-07-04 09:11:31', '2017-07-04 09:11:33');
INSERT INTO `permissions` VALUES ('16', 'manage.version.ios', null, 'iOS版本管理', '2017-07-04 09:12:20', '2017-07-04 09:12:21');
INSERT INTO `permissions` VALUES ('17', 'manage.version.android', null, 'Android版本管理', '2017-07-04 09:12:42', '2017-07-04 09:12:43');
INSERT INTO `permissions` VALUES ('18', 'manage.group.goods', null, '团长信息', '2017-07-04 11:48:26', '2017-07-04 11:48:28');
INSERT INTO `permissions` VALUES ('19', 'manage.group.groupinfo', null, '团员信息', '2017-07-04 11:49:32', '2017-07-04 11:49:35');
INSERT INTO `permissions` VALUES ('20', 'manage.store.show', null, '店铺管理', '2017-07-04 11:50:27', '2017-07-04 11:50:29');
INSERT INTO `permissions` VALUES ('21', 'manage.store.city', null, '店铺管理', '2017-07-04 11:52:28', '2017-07-04 11:52:30');
INSERT INTO `permissions` VALUES ('22', 'manage.store.add', null, '店铺添加', '2017-07-04 14:13:33', '2017-07-04 14:13:35');
INSERT INTO `permissions` VALUES ('23', 'manage.store.edit', null, '店铺管理', '2017-07-04 14:14:12', '2017-07-04 14:14:14');
INSERT INTO `permissions` VALUES ('24', 'manage.store.store', null, '店铺管理', '2017-07-04 14:14:43', '2017-07-04 14:14:45');
INSERT INTO `permissions` VALUES ('25', 'manage.group.examine', null, '团购审核', '2017-07-04 14:36:39', '2017-07-04 14:36:41');
INSERT INTO `permissions` VALUES ('26', 'manage.group.uploade', null, '团购商品上传页', '2017-07-04 14:38:36', '2017-07-04 14:38:38');
INSERT INTO `permissions` VALUES ('27', 'manage.group.lookinfo', null, '团购审核商品详情', '2017-07-04 14:40:19', '2017-07-04 14:40:21');
INSERT INTO `permissions` VALUES ('28', 'manage.finance.deta_excel', null, '销售查询导出', '2017-07-04 14:45:53', '2017-07-04 14:45:54');
INSERT INTO `permissions` VALUES ('29', 'manage.finance.rank_excel', null, '销售排行导出', '2017-07-04 14:46:34', '2017-07-04 14:46:36');
INSERT INTO `permissions` VALUES ('30', 'manage.transfer.show', null, '查看提现详情', '2017-07-04 14:49:08', '2017-07-04 14:49:09');
INSERT INTO `permissions` VALUES ('31', 'manage.upload.store', null, '公共文件上传', '2017-07-04 14:51:31', '2017-07-04 14:51:32');
INSERT INTO `permissions` VALUES ('32', 'manage.home.show', null, '登录入口', '2017-07-04 14:52:01', '2017-07-04 14:52:02');
INSERT INTO `permissions` VALUES ('33', 'manage.user.store', null, '用户相关', '2017-07-04 14:52:45', '2017-07-04 14:52:46');
INSERT INTO `permissions` VALUES ('34', 'manage.user.create', null, '用户相关', '2017-07-04 14:53:34', '2017-07-04 14:53:35');
INSERT INTO `permissions` VALUES ('35', 'manage.user.edit', null, '用户相关', '2017-07-04 14:53:45', '2017-07-04 14:53:46');
INSERT INTO `permissions` VALUES ('36', 'manage.user.show', null, '用户相关', '2017-07-04 14:53:56', '2017-07-04 14:53:57');
INSERT INTO `permissions` VALUES ('37', 'manage.user.destroy', null, '用户相关', '2017-07-04 14:54:10', '2017-07-04 14:54:11');
INSERT INTO `permissions` VALUES ('38', 'manage.user.subor', null, '下级用户查看', '2017-07-04 14:55:00', '2017-07-04 14:55:02');
INSERT INTO `permissions` VALUES ('39', 'manage.user.thewine', null, '酒币明细', '2017-07-04 14:55:14', '2017-07-04 14:55:15');
INSERT INTO `permissions` VALUES ('40', 'manage.ads.index', null, '广告首页', '2017-07-04 14:56:02', '2017-07-04 14:56:03');
INSERT INTO `permissions` VALUES ('41', 'manage.ads.store', null, '广告相关', '2017-07-04 14:56:24', '2017-07-04 14:56:25');
INSERT INTO `permissions` VALUES ('42', 'manage.ads.create', null, '广告相关', '2017-07-04 14:56:39', '2017-07-04 14:56:40');
INSERT INTO `permissions` VALUES ('43', 'manage.ads.edit', null, '广告相关', '2017-07-04 14:56:57', '2017-07-04 14:56:58');
INSERT INTO `permissions` VALUES ('44', 'manage.adCategory.store', null, '广告分类相关', '2017-07-04 14:57:23', '2017-07-04 14:57:23');
INSERT INTO `permissions` VALUES ('45', 'manage.rbac.show', null, '角色管理', '2017-07-04 14:57:59', '2017-07-04 14:57:59');
INSERT INTO `permissions` VALUES ('46', 'manage.rbac.create', null, '角色管理', '2017-07-04 14:58:16', '2017-07-04 14:58:16');
INSERT INTO `permissions` VALUES ('47', 'manage.rbac.store', null, '角色管理', '2017-07-04 14:58:28', '2017-07-04 14:58:29');
INSERT INTO `permissions` VALUES ('48', 'manage.goods.create', null, '商品相关', '2017-07-04 14:58:53', '2017-07-04 14:58:54');
INSERT INTO `permissions` VALUES ('49', 'manage.goods.updatenumber', null, '商品修改', '2017-07-04 14:59:37', '2017-07-04 14:59:38');
INSERT INTO `permissions` VALUES ('50', 'manage.goods.updatestatus', null, '商品修改', '2017-07-04 14:59:49', '2017-07-04 14:59:50');
INSERT INTO `permissions` VALUES ('51', 'manage.goods.save', null, '商品修改', '2017-07-04 15:00:32', '2017-07-04 15:00:33');
INSERT INTO `permissions` VALUES ('52', 'manage.goods.store', null, '商品相关', '2017-07-04 15:01:05', '2017-07-04 15:01:06');
INSERT INTO `permissions` VALUES ('53', 'manage.goods.edit', null, '商品相关', '2017-07-04 15:01:37', '2017-07-04 15:01:38');
INSERT INTO `permissions` VALUES ('54', 'manage.order.show', null, '订单相关', '2017-07-04 15:02:10', '2017-07-04 15:02:11');
INSERT INTO `permissions` VALUES ('55', 'manage.order.store', null, '订单相关', '2017-07-04 15:03:09', '2017-07-04 15:03:10');
INSERT INTO `permissions` VALUES ('56', 'manage.goods.shop_category', null, '第三方店铺分类', '2017-07-04 15:04:04', '2017-07-04 15:04:05');
INSERT INTO `permissions` VALUES ('57', 'manage.goods.supplier_category_add', null, '第三方店铺', '2017-07-04 15:04:19', '2017-07-04 15:04:20');
INSERT INTO `permissions` VALUES ('58', 'manage.goods.update_supplier_cat', null, '第三方店铺', '2017-07-04 15:04:35', '2017-07-04 15:04:36');
INSERT INTO `permissions` VALUES ('59', 'manage.goods.update_supplier_price', '', '第三方店铺', '2017-07-04 15:04:35', '2017-07-04 15:04:36');
INSERT INTO `permissions` VALUES ('60', 'manage.goods.recycle_bin', null, '第三方店铺店内分类', '2017-07-04 15:05:45', '2017-07-04 15:05:46');
INSERT INTO `permissions` VALUES ('61', 'manage.goods.supplier_category_transfer', null, '第三方店铺店内分类转移', '2017-07-04 15:06:03', '2017-07-04 15:06:04');
INSERT INTO `permissions` VALUES ('62', 'manage.goods.supplier_status', null, '第三方店铺物品审核', '2017-07-04 15:06:22', '2017-07-04 15:06:23');
INSERT INTO `permissions` VALUES ('63', 'manage.goods.third_party', null, '第三方店铺管理员审核', '2017-07-04 15:06:47', '2017-07-04 15:06:48');
INSERT INTO `permissions` VALUES ('64', 'manage.goods.name', null, '第三方店铺', '2017-07-04 15:07:17', '2017-07-04 15:07:18');
INSERT INTO `permissions` VALUES ('65', 'manage.goods.best', null, '第三方店铺', '2017-07-04 15:07:32', '2017-07-04 15:07:33');
INSERT INTO `permissions` VALUES ('66', 'manage.goods.addgoods', null, '第三方店铺', '2017-07-04 15:07:45', '2017-07-04 15:07:46');
INSERT INTO `permissions` VALUES ('67', 'manage.goods.goods_update', null, '第三方店铺', '2017-07-04 15:08:04', '2017-07-04 15:08:05');
INSERT INTO `permissions` VALUES ('68', 'manage.goods.cat_onlyadd', null, '第三方店铺', '2017-07-04 15:08:19', '2017-07-04 15:08:19');
INSERT INTO `permissions` VALUES ('69', 'manage.rbac.users', null, '角色管理', '2017-07-04 15:47:49', '2017-07-04 15:47:53');
INSERT INTO `permissions` VALUES ('70', 'manage.order.export', null, '导出未发货订单', '2017-07-04 16:11:31', '2017-07-04 16:11:32');
INSERT INTO `permissions` VALUES ('71', 'manage.order.exportorder', null, '导出已发货订单', '2017-07-04 16:11:51', '2017-07-04 16:11:51');
INSERT INTO `permissions` VALUES ('72', 'manage.group.switch', null, '团购开关', '2017-07-04 16:21:46', '2017-07-04 16:21:46');
INSERT INTO `permissions` VALUES ('73', 'manage.group.load', null, '修改上传团购商品', '2017-07-04 16:52:44', '2017-07-04 16:52:45');
INSERT INTO `permissions` VALUES ('74', 'manage.goods.rect', null, '驳回第三方商品', '2017-08-21 11:56:15', '2017-08-21 11:56:16');
INSERT INTO `permissions` VALUES ('75', 'manage.goods.istrator', null, '第三方商品管理', '2017-08-21 12:00:45', '2017-08-21 12:00:46');
INSERT INTO `permissions` VALUES ('76', 'manage.group.js', null, '团购商品搜索', '2017-08-30 16:13:34', '2017-08-30 16:13:37');
INSERT INTO `permissions` VALUES ('77', 'manage.shipp.index', null, '运费模板首页', '2017-09-27 11:22:01', '2017-09-27 11:22:03');
INSERT INTO `permissions` VALUES ('78', 'manage.shipp.city', null, '运费模板添加页面', '2017-09-27 11:22:23', '2017-09-27 11:22:25');
INSERT INTO `permissions` VALUES ('79', 'manage.shipp.shipp', null, '运费模板保存', '2017-09-27 11:22:43', '2017-09-27 11:22:44');
INSERT INTO `permissions` VALUES ('80', 'manage.shipp.edit', null, '默认模板编辑', '2017-09-27 11:23:01', '2017-09-27 11:23:02');
INSERT INTO `permissions` VALUES ('81', 'manage.rbac.addadmin', null, '角色管理', '2017-09-27 11:44:10', '2017-09-27 11:44:11');
INSERT INTO `permissions` VALUES ('82', 'manage.rbac.admindel', null, '角色删除', '2017-09-27 11:44:23', '2017-09-27 11:44:25');

-- ----------------------------
-- Table structure for permission_role
-- ----------------------------
DROP TABLE IF EXISTS `permission_role`;
CREATE TABLE `permission_role` (
  `permission_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `permission_role_role_id_foreign` (`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of permission_role
-- ----------------------------
INSERT INTO `permission_role` VALUES ('1', '1');
INSERT INTO `permission_role` VALUES ('1', '2');
INSERT INTO `permission_role` VALUES ('2', '1');
INSERT INTO `permission_role` VALUES ('3', '1');
INSERT INTO `permission_role` VALUES ('4', '1');
INSERT INTO `permission_role` VALUES ('5', '1');
INSERT INTO `permission_role` VALUES ('6', '1');
INSERT INTO `permission_role` VALUES ('7', '1');
INSERT INTO `permission_role` VALUES ('8', '1');
INSERT INTO `permission_role` VALUES ('9', '1');
INSERT INTO `permission_role` VALUES ('10', '1');
INSERT INTO `permission_role` VALUES ('11', '1');
INSERT INTO `permission_role` VALUES ('12', '1');
INSERT INTO `permission_role` VALUES ('13', '1');
INSERT INTO `permission_role` VALUES ('14', '1');
INSERT INTO `permission_role` VALUES ('15', '1');
INSERT INTO `permission_role` VALUES ('16', '1');
INSERT INTO `permission_role` VALUES ('17', '1');
INSERT INTO `permission_role` VALUES ('18', '1');
INSERT INTO `permission_role` VALUES ('19', '1');
INSERT INTO `permission_role` VALUES ('20', '1');
INSERT INTO `permission_role` VALUES ('21', '1');
INSERT INTO `permission_role` VALUES ('22', '1');
INSERT INTO `permission_role` VALUES ('23', '1');
INSERT INTO `permission_role` VALUES ('24', '1');
INSERT INTO `permission_role` VALUES ('25', '1');
INSERT INTO `permission_role` VALUES ('26', '1');
INSERT INTO `permission_role` VALUES ('27', '1');
INSERT INTO `permission_role` VALUES ('28', '1');
INSERT INTO `permission_role` VALUES ('29', '1');
INSERT INTO `permission_role` VALUES ('30', '1');
INSERT INTO `permission_role` VALUES ('31', '1');
INSERT INTO `permission_role` VALUES ('32', '1');
INSERT INTO `permission_role` VALUES ('33', '1');
INSERT INTO `permission_role` VALUES ('34', '1');
INSERT INTO `permission_role` VALUES ('35', '1');
INSERT INTO `permission_role` VALUES ('36', '1');
INSERT INTO `permission_role` VALUES ('37', '1');
INSERT INTO `permission_role` VALUES ('38', '1');
INSERT INTO `permission_role` VALUES ('39', '1');
INSERT INTO `permission_role` VALUES ('40', '1');
INSERT INTO `permission_role` VALUES ('41', '1');
INSERT INTO `permission_role` VALUES ('42', '1');
INSERT INTO `permission_role` VALUES ('43', '1');
INSERT INTO `permission_role` VALUES ('44', '1');
INSERT INTO `permission_role` VALUES ('45', '1');
INSERT INTO `permission_role` VALUES ('46', '1');
INSERT INTO `permission_role` VALUES ('47', '1');
INSERT INTO `permission_role` VALUES ('48', '1');
INSERT INTO `permission_role` VALUES ('49', '1');
INSERT INTO `permission_role` VALUES ('50', '1');
INSERT INTO `permission_role` VALUES ('51', '1');
INSERT INTO `permission_role` VALUES ('52', '1');
INSERT INTO `permission_role` VALUES ('53', '1');
INSERT INTO `permission_role` VALUES ('54', '1');
INSERT INTO `permission_role` VALUES ('55', '1');
INSERT INTO `permission_role` VALUES ('56', '1');
INSERT INTO `permission_role` VALUES ('57', '1');
INSERT INTO `permission_role` VALUES ('58', '1');
INSERT INTO `permission_role` VALUES ('59', '1');
INSERT INTO `permission_role` VALUES ('60', '1');
INSERT INTO `permission_role` VALUES ('61', '1');
INSERT INTO `permission_role` VALUES ('62', '1');
INSERT INTO `permission_role` VALUES ('63', '1');
INSERT INTO `permission_role` VALUES ('64', '1');
INSERT INTO `permission_role` VALUES ('65', '1');
INSERT INTO `permission_role` VALUES ('66', '1');
INSERT INTO `permission_role` VALUES ('67', '1');
INSERT INTO `permission_role` VALUES ('68', '1');
INSERT INTO `permission_role` VALUES ('69', '1');
INSERT INTO `permission_role` VALUES ('70', '1');
INSERT INTO `permission_role` VALUES ('71', '1');
INSERT INTO `permission_role` VALUES ('72', '1');
INSERT INTO `permission_role` VALUES ('73', '1');
INSERT INTO `permission_role` VALUES ('74', '1');
INSERT INTO `permission_role` VALUES ('75', '1');
INSERT INTO `permission_role` VALUES ('76', '1');
INSERT INTO `permission_role` VALUES ('77', '1');
INSERT INTO `permission_role` VALUES ('78', '1');
INSERT INTO `permission_role` VALUES ('79', '1');
INSERT INTO `permission_role` VALUES ('80', '1');
INSERT INTO `permission_role` VALUES ('81', '1');
INSERT INTO `permission_role` VALUES ('82', '1');

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `config_menu` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of roles
-- ----------------------------
INSERT INTO `roles` VALUES ('1', 'admin', '管理员', 'admin role', '2017-05-21 12:46:56', '2017-05-21 12:46:58', 'admin_menu');
INSERT INTO `roles` VALUES ('2', 'fina', '财务', 'fina role', '2017-05-22 16:22:58', '2017-05-22 16:23:00', 'fina_menu');
INSERT INTO `roles` VALUES ('3', 'duct', '产品', 'duct role', '2017-07-03 17:26:47', '2017-07-03 17:26:50', 'duct_menu');
INSERT INTO `roles` VALUES ('4', 'cust', '客服', 'cust role', '2017-07-03 17:27:09', '2017-07-03 17:27:11', 'cust_menu');
INSERT INTO `roles` VALUES ('5', 'third', '第三方', 'third role', '2017-07-03 17:27:30', '2017-07-03 17:27:33', 'store_menu');
INSERT INTO `roles` VALUES ('6', 'shipp', '物流', 'shipp role', '2017-07-03 17:28:27', '2017-07-03 17:28:29', 'shipp_menu');

-- ----------------------------
-- Table structure for role_user
-- ----------------------------
DROP TABLE IF EXISTS `role_user`;
CREATE TABLE `role_user` (
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `role_user_role_id_foreign` (`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of role_user
-- ----------------------------
INSERT INTO `role_user` VALUES ('1', '1');

-- ----------------------------
-- Table structure for version
-- ----------------------------
DROP TABLE IF EXISTS `version`;
CREATE TABLE `version` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `version_number` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `updates` int(11) NOT NULL DEFAULT '0',
  `version_name` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `appurl` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `system` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of version
-- ----------------------------
INSERT INTO `version` VALUES ('1', '0.0.1', '0', '0.0.1', '', '1', '2017-08-03 11:07:34', '2017-08-03 11:07:35');
INSERT INTO `version` VALUES ('2', '0.0.1', '0', '0.0.1', 'http://www.baidu.com', '2', '2017-08-03 11:07:34', '2017-08-03 11:07:35');

-- ----------------------------
-- Table structure for woke_account_log
-- ----------------------------
DROP TABLE IF EXISTS `woke_account_log`;
CREATE TABLE `woke_account_log` (
  `log_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `user_money` decimal(10,2) NOT NULL,
  `frozen_money` decimal(10,2) NOT NULL,
  `rank_points` mediumint(9) NOT NULL,
  `pay_points` mediumint(9) NOT NULL,
  `change_time` int(10) unsigned NOT NULL,
  `change_desc` varchar(255) NOT NULL,
  `change_type` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`log_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=26497 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of woke_account_log
-- ----------------------------

-- ----------------------------
-- Table structure for woke_admin_user
-- ----------------------------
DROP TABLE IF EXISTS `woke_admin_user`;
CREATE TABLE `woke_admin_user` (
  `user_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(60) NOT NULL DEFAULT '',
  `email` varchar(60) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `ec_salt` varchar(10) DEFAULT NULL,
  `add_time` int(11) NOT NULL DEFAULT '0',
  `last_login` int(11) NOT NULL DEFAULT '0',
  `last_ip` varchar(15) NOT NULL DEFAULT '',
  `action_list` text NOT NULL,
  `nav_list` text NOT NULL,
  `lang_type` varchar(50) NOT NULL DEFAULT '',
  `agency_id` smallint(5) unsigned NOT NULL,
  `supplier_id` smallint(5) unsigned DEFAULT '0',
  `todolist` longtext,
  `role_id` smallint(5) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  KEY `user_name` (`user_name`),
  KEY `agency_id` (`agency_id`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of woke_admin_user
-- ----------------------------
INSERT INTO `woke_admin_user` VALUES ('1', 'admin', 'admin@woke.com', '82ce5a31c232e61a49538ebaca31551f', '4569', '0', '0', '', '', '', '', '0', '0', '', '1');

-- ----------------------------
-- Table structure for woke_advertisement
-- ----------------------------
DROP TABLE IF EXISTS `woke_advertisement`;
CREATE TABLE `woke_advertisement` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `advertisement_category_id` int(10) unsigned NOT NULL,
  `ad_name` varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ad_link` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ad_file` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `start_time` int(10) unsigned NOT NULL DEFAULT '0',
  `end_time` int(10) unsigned NOT NULL DEFAULT '0',
  `enabled` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `advertisement_category_id_index` (`advertisement_category_id`),
  KEY `advertisement_start_time_index` (`start_time`),
  KEY `advertisement_end_time_index` (`end_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of woke_advertisement
-- ----------------------------

-- ----------------------------
-- Table structure for woke_advertisement_category
-- ----------------------------
DROP TABLE IF EXISTS `woke_advertisement_category`;
CREATE TABLE `woke_advertisement_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ad_height` int(10) unsigned NOT NULL,
  `ad_width` int(10) unsigned NOT NULL,
  `category_name` varchar(12) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `category_desc` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of woke_advertisement_category
-- ----------------------------
INSERT INTO `woke_advertisement_category` VALUES ('1', '160', '375', '首页banner', 'app首页头部广告', null, null);

-- ----------------------------
-- Table structure for woke_apps
-- ----------------------------
DROP TABLE IF EXISTS `woke_apps`;
CREATE TABLE `woke_apps` (
  `id` int(10) unsigned NOT NULL,
  `app_id` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `app_name` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `app_secret` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `app_desc` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of woke_apps
-- ----------------------------
INSERT INTO `woke_apps` VALUES ('1', '631911b6a702ba97700dbac572097a5', '蜗客商城', '8e8f1bd41d490f2e0c9408499c74ec9f', '蜗客商城APP', '1', '2017-07-19 15:23:50', '2017-07-19 15:23:52');

-- ----------------------------
-- Table structure for woke_area
-- ----------------------------
DROP TABLE IF EXISTS `woke_area`;
CREATE TABLE `woke_area` (
  `area_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `area_name` varchar(40) COLLATE utf8_unicode_ci NOT NULL COMMENT '地区划分表',
  `sort` int(11) NOT NULL COMMENT '分类',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`area_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of woke_area
-- ----------------------------
INSERT INTO `woke_area` VALUES ('1', '华东', '0', '2017-07-19 14:10:51', '2017-07-19 14:10:55');
INSERT INTO `woke_area` VALUES ('2', '华北', '0', '2017-07-19 14:10:57', '2017-07-19 14:10:59');
INSERT INTO `woke_area` VALUES ('3', '华南', '0', '2017-07-19 14:11:01', '2017-07-19 14:11:03');
INSERT INTO `woke_area` VALUES ('4', '华中', '0', '2017-07-19 14:11:06', '2017-07-19 14:11:08');
INSERT INTO `woke_area` VALUES ('5', '东北', '0', '2017-07-19 14:11:11', '2017-07-19 14:11:15');
INSERT INTO `woke_area` VALUES ('6', '西北', '0', '2017-07-19 14:11:17', '2017-07-19 14:11:20');
INSERT INTO `woke_area` VALUES ('7', '西南', '0', '2017-07-19 14:11:23', '2017-07-19 14:11:26');
INSERT INTO `woke_area` VALUES ('8', '港澳台', '0', '2017-07-19 14:11:28', '2017-07-19 14:11:30');

-- ----------------------------
-- Table structure for woke_area_extends
-- ----------------------------
DROP TABLE IF EXISTS `woke_area_extends`;
CREATE TABLE `woke_area_extends` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `area_id` int(11) DEFAULT NULL,
  `name` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `parent_id` int(11) NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of woke_area_extends
-- ----------------------------
INSERT INTO `woke_area_extends` VALUES ('1', '2', '北京市', '0', '1');
INSERT INTO `woke_area_extends` VALUES ('2', '2', '天津市', '0', '2');
INSERT INTO `woke_area_extends` VALUES ('3', '1', '上海市', '0', '3');
INSERT INTO `woke_area_extends` VALUES ('4', '7', '重庆市', '0', '4');
INSERT INTO `woke_area_extends` VALUES ('5', '2', '河北省', '0', '5');
INSERT INTO `woke_area_extends` VALUES ('6', '2', '山西省', '0', '6');
INSERT INTO `woke_area_extends` VALUES ('7', '2', '内蒙古', '0', '7');
INSERT INTO `woke_area_extends` VALUES ('8', '5', '辽宁省', '0', '8');
INSERT INTO `woke_area_extends` VALUES ('9', '5', '吉林省', '0', '9');
INSERT INTO `woke_area_extends` VALUES ('10', '5', '黑龙江省', '0', '10');
INSERT INTO `woke_area_extends` VALUES ('11', '1', '江苏省', '0', '11');
INSERT INTO `woke_area_extends` VALUES ('12', '1', '浙江省', '0', '12');
INSERT INTO `woke_area_extends` VALUES ('13', '1', '安徽省', '0', '13');
INSERT INTO `woke_area_extends` VALUES ('14', '3', '福建省', '0', '14');
INSERT INTO `woke_area_extends` VALUES ('15', '1', '江西省', '0', '15');
INSERT INTO `woke_area_extends` VALUES ('16', '2', '山东省', '0', '16');
INSERT INTO `woke_area_extends` VALUES ('17', '4', '河南省', '0', '17');
INSERT INTO `woke_area_extends` VALUES ('18', '4', '湖北省', '0', '18');
INSERT INTO `woke_area_extends` VALUES ('19', '4', '湖南省', '0', '19');
INSERT INTO `woke_area_extends` VALUES ('20', '3', '广东省', '0', '20');
INSERT INTO `woke_area_extends` VALUES ('21', '3', '广西省', '0', '21');
INSERT INTO `woke_area_extends` VALUES ('22', '3', '海南省', '0', '22');
INSERT INTO `woke_area_extends` VALUES ('23', '7', '四川省', '0', '23');
INSERT INTO `woke_area_extends` VALUES ('24', '7', '贵州省', '0', '24');
INSERT INTO `woke_area_extends` VALUES ('25', '7', '云南省', '0', '25');
INSERT INTO `woke_area_extends` VALUES ('26', '7', '西藏', '0', '26');
INSERT INTO `woke_area_extends` VALUES ('27', '6', '陕西省', '0', '27');
INSERT INTO `woke_area_extends` VALUES ('28', '6', '甘肃省', '0', '28');
INSERT INTO `woke_area_extends` VALUES ('29', '6', '青海省', '0', '29');
INSERT INTO `woke_area_extends` VALUES ('30', '6', '宁夏', '0', '30');
INSERT INTO `woke_area_extends` VALUES ('31', '6', '新疆', '0', '31');
INSERT INTO `woke_area_extends` VALUES ('32', '8', '台湾省', '0', '32');
INSERT INTO `woke_area_extends` VALUES ('33', '8', '香港', '0', '33');
INSERT INTO `woke_area_extends` VALUES ('34', '8', '澳门', '0', '34');

-- ----------------------------
-- Table structure for woke_cart
-- ----------------------------
DROP TABLE IF EXISTS `woke_cart`;
CREATE TABLE `woke_cart` (
  `rec_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `session_id` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0',
  `goods_sn` varchar(60) NOT NULL DEFAULT '',
  `product_id` int(11) unsigned NOT NULL DEFAULT '0',
  `goods_name` varchar(120) NOT NULL DEFAULT '',
  `market_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `goods_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `goods_number` smallint(5) unsigned NOT NULL DEFAULT '0',
  `goods_attr` text NOT NULL,
  `is_real` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `extension_code` varchar(30) NOT NULL DEFAULT '',
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0',
  `rec_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_gift` smallint(5) unsigned NOT NULL DEFAULT '0',
  `is_shipping` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `can_handsel` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `goods_attr_id` varchar(255) NOT NULL DEFAULT '',
  `add_time` int(10) unsigned NOT NULL,
  `package_attr_id` varchar(100) NOT NULL,
  `cost_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `promote_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `is_select` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`rec_id`),
  KEY `session_id` (`session_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3595 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of woke_cart
-- ----------------------------

-- ----------------------------
-- Table structure for woke_category
-- ----------------------------
DROP TABLE IF EXISTS `woke_category`;
CREATE TABLE `woke_category` (
  `cat_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(90) NOT NULL DEFAULT '',
  `keywords` varchar(255) NOT NULL DEFAULT '',
  `cat_desc` varchar(255) NOT NULL DEFAULT '',
  `parent_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `sort_order` tinyint(1) unsigned NOT NULL DEFAULT '50',
  `template_file` varchar(50) NOT NULL DEFAULT '',
  `measure_unit` varchar(15) NOT NULL DEFAULT '',
  `show_in_nav` tinyint(1) NOT NULL DEFAULT '0',
  `style` varchar(150) NOT NULL,
  `is_show` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `grade` tinyint(4) NOT NULL DEFAULT '0',
  `filter_attr` varchar(255) NOT NULL DEFAULT '0',
  `category_index` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `category_index_dwt` tinyint(1) NOT NULL DEFAULT '0',
  `index_dwt_file` varchar(150) DEFAULT NULL,
  `show_in_index` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `cat_index_rightad` varchar(255) NOT NULL,
  `cat_adimg_1` varchar(255) NOT NULL,
  `cat_adurl_1` varchar(255) NOT NULL,
  `cat_adimg_2` varchar(255) NOT NULL,
  `cat_adurl_2` varchar(255) NOT NULL,
  `cat_nameimg` varchar(255) NOT NULL,
  `brand_qq` varchar(255) NOT NULL DEFAULT '',
  `attr_wwwecshop68com` varchar(255) NOT NULL DEFAULT '',
  `path_name` varchar(100) NOT NULL DEFAULT '',
  `type_img` varchar(100) NOT NULL COMMENT '微信商城分类图标',
  PRIMARY KEY (`cat_id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of woke_category
-- ----------------------------

-- ----------------------------
-- Table structure for woke_collect_goods
-- ----------------------------
DROP TABLE IF EXISTS `woke_collect_goods`;
CREATE TABLE `woke_collect_goods` (
  `rec_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0',
  `is_attention` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`rec_id`),
  KEY `user_id` (`user_id`),
  KEY `goods_id` (`goods_id`),
  KEY `is_attention` (`is_attention`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of woke_collect_goods
-- ----------------------------

-- ----------------------------
-- Table structure for woke_collect_supplier
-- ----------------------------
DROP TABLE IF EXISTS `woke_collect_supplier`;
CREATE TABLE `woke_collect_supplier` (
  `rec_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `add_time` int(11) NOT NULL,
  `is_attention` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`rec_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of woke_collect_supplier
-- ----------------------------

-- ----------------------------
-- Table structure for woke_goods
-- ----------------------------
DROP TABLE IF EXISTS `woke_goods`;
CREATE TABLE `woke_goods` (
  `goods_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cat_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `goods_sn` varchar(60) NOT NULL DEFAULT '',
  `goods_name` varchar(120) NOT NULL DEFAULT '',
  `goods_name_style` varchar(60) NOT NULL DEFAULT '+',
  `click_count` int(10) unsigned NOT NULL DEFAULT '0',
  `brand_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `provider_name` varchar(100) NOT NULL DEFAULT '',
  `goods_number` smallint(5) unsigned NOT NULL DEFAULT '0',
  `goods_weight` decimal(10,3) unsigned NOT NULL DEFAULT '0.000',
  `goods_texture` varchar(100) DEFAULT NULL,
  `goods_norms` varchar(100) DEFAULT NULL,
  `goods_brand` varchar(100) DEFAULT NULL,
  `goods_author` varchar(100) DEFAULT NULL,
  `goods_area` varchar(100) DEFAULT NULL,
  `goods_style` varchar(100) DEFAULT NULL,
  `market_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `shop_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `promote_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `promote_start_date` int(11) unsigned NOT NULL DEFAULT '0',
  `promote_end_date` int(11) unsigned NOT NULL DEFAULT '0',
  `is_buy` int(11) NOT NULL,
  `buymax` int(11) NOT NULL,
  `buymax_start_date` int(11) NOT NULL,
  `buymax_end_date` int(11) NOT NULL,
  `warn_number` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `keywords` varchar(255) NOT NULL DEFAULT '',
  `goods_brief` varchar(255) NOT NULL DEFAULT '',
  `goods_desc` text NOT NULL,
  `goods_thumb` varchar(255) NOT NULL DEFAULT '',
  `goods_img` varchar(255) NOT NULL DEFAULT '',
  `original_img` varchar(255) NOT NULL DEFAULT '',
  `is_real` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `extension_code` varchar(30) NOT NULL DEFAULT '',
  `is_on_sale` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `is_alone_sale` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `is_shipping` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `integral` int(10) unsigned NOT NULL DEFAULT '0',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0',
  `sort_order` smallint(4) unsigned NOT NULL DEFAULT '100',
  `is_delete` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_best` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_new` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_hot` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_promote` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `zhekou` decimal(3,1) NOT NULL DEFAULT '10.0',
  `bonus_type_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `last_update` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_type` smallint(5) unsigned NOT NULL DEFAULT '0',
  `seller_note` varchar(255) NOT NULL DEFAULT '',
  `give_integral` int(11) NOT NULL DEFAULT '-1',
  `rank_integral` int(11) NOT NULL DEFAULT '-1',
  `rec_content` varchar(40) DEFAULT NULL,
  `supplier_id` int(11) unsigned NOT NULL DEFAULT '0',
  `supplier_status` tinyint(1) NOT NULL DEFAULT '0',
  `supplier_status_txt` text NOT NULL,
  `is_check` tinyint(1) unsigned DEFAULT NULL,
  `is_catindex` tinyint(1) unsigned NOT NULL,
  `cost_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `is_show_user` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '是否显示在晒晒',
  PRIMARY KEY (`goods_id`),
  KEY `goods_sn` (`goods_sn`),
  KEY `cat_id` (`cat_id`),
  KEY `last_update` (`last_update`),
  KEY `brand_id` (`brand_id`),
  KEY `goods_weight` (`goods_weight`),
  KEY `promote_end_date` (`promote_end_date`),
  KEY `promote_start_date` (`promote_start_date`),
  KEY `goods_number` (`goods_number`),
  KEY `sort_order` (`sort_order`),
  KEY `is_catindex` (`is_catindex`)
) ENGINE=InnoDB AUTO_INCREMENT=440 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of woke_goods
-- ----------------------------

-- ----------------------------
-- Table structure for woke_goods_attr
-- ----------------------------
DROP TABLE IF EXISTS `woke_goods_attr`;
CREATE TABLE `woke_goods_attr` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` int(10) unsigned NOT NULL,
  `attr_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '属性名',
  `attr_value` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '属性值',
  `market_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '原价价格',
  `shop_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '本店价格',
  `goods_number` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `group_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `woke_goods_attr_goods_id_index` (`goods_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of woke_goods_attr
-- ----------------------------

-- ----------------------------
-- Table structure for woke_goods_gallery
-- ----------------------------
DROP TABLE IF EXISTS `woke_goods_gallery`;
CREATE TABLE `woke_goods_gallery` (
  `img_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0',
  `img_url` varchar(255) NOT NULL DEFAULT '',
  `img_desc` varchar(255) NOT NULL DEFAULT '',
  `thumb_url` varchar(255) NOT NULL DEFAULT '',
  `img_original` varchar(255) NOT NULL DEFAULT '',
  `goods_attr_id` int(10) unsigned NOT NULL DEFAULT '0',
  `is_attr_image` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `img_sort` tinyint(3) NOT NULL DEFAULT '0' COMMENT '商品图片显示顺序',
  PRIMARY KEY (`img_id`),
  KEY `goods_id` (`goods_id`,`goods_attr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of woke_goods_gallery
-- ----------------------------

-- ----------------------------
-- Table structure for woke_goods_item
-- ----------------------------
DROP TABLE IF EXISTS `woke_goods_item`;
CREATE TABLE `woke_goods_item` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` int(10) unsigned NOT NULL,
  `stor` int(10) unsigned NOT NULL,
  `key` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '属性名',
  `value` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '属性值',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `woke_goods_item_goods_id_index` (`goods_id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of woke_goods_item
-- ----------------------------

-- ----------------------------
-- Table structure for woke_group_classify
-- ----------------------------
DROP TABLE IF EXISTS `woke_group_classify`;
CREATE TABLE `woke_group_classify` (
  `ify_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ify_name` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '分类名',
  `parent_id` int(11) DEFAULT NULL COMMENT '上级分类',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ify_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of woke_group_classify
-- ----------------------------

-- ----------------------------
-- Table structure for woke_group_goods_extends
-- ----------------------------
DROP TABLE IF EXISTS `woke_group_goods_extends`;
CREATE TABLE `woke_group_goods_extends` (
  `goods_id` int(11) NOT NULL,
  `ex_number` int(11) NOT NULL,
  `ex_have` int(11) DEFAULT '0',
  `group_price` decimal(10,2) NOT NULL,
  `start_time` int(11) DEFAULT NULL,
  `end_time` int(11) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `examine_status` int(11) DEFAULT '1',
  `describe` text COLLATE utf8_unicode_ci,
  `recommend` int(11) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ify_id` int(11) DEFAULT NULL COMMENT '分类ID'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of woke_group_goods_extends
-- ----------------------------

-- ----------------------------
-- Table structure for woke_group_info
-- ----------------------------
DROP TABLE IF EXISTS `woke_group_info`;
CREATE TABLE `woke_group_info` (
  `info_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `goods_id` int(11) NOT NULL,
  `order_sn` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `pay_status` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `consignee` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tel` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `buy_number` int(11) NOT NULL,
  `pay_name` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `pay_time` int(11) NOT NULL,
  `order_amount` decimal(10,2) NOT NULL,
  `integral_amount` decimal(10,2) NOT NULL,
  `remarks` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `vat_inv_company_name` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `vat_inv_taxpayer_id` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`info_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of woke_group_info
-- ----------------------------

-- ----------------------------
-- Table structure for woke_group_open
-- ----------------------------
DROP TABLE IF EXISTS `woke_group_open`;
CREATE TABLE `woke_group_open` (
  `group_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `goods_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `have` int(11) NOT NULL,
  `start_time` int(11) NOT NULL,
  `group_status` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of woke_group_open
-- ----------------------------

-- ----------------------------
-- Table structure for woke_group_refund
-- ----------------------------
DROP TABLE IF EXISTS `woke_group_refund`;
CREATE TABLE `woke_group_refund` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `refund_price` decimal(10,2) NOT NULL,
  `refund_time` int(11) NOT NULL,
  `reason` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of woke_group_refund
-- ----------------------------

-- ----------------------------
-- Table structure for woke_interface_logs
-- ----------------------------
DROP TABLE IF EXISTS `woke_interface_logs`;
CREATE TABLE `woke_interface_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `api_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `reauest_body` text COLLATE utf8_unicode_ci NOT NULL COMMENT '请求参数',
  `reauest_header` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '请求头',
  `response` text COLLATE utf8_unicode_ci NOT NULL COMMENT '返回数据',
  `input_time` varchar(15) COLLATE utf8_unicode_ci NOT NULL COMMENT '开始时间',
  `run_time` varchar(16) COLLATE utf8_unicode_ci NOT NULL COMMENT '执行时间',
  `output_time` varchar(15) COLLATE utf8_unicode_ci NOT NULL COMMENT '输出时间',
  `ip` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0.0.0.0' COMMENT '操作ip',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2474 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of woke_interface_logs
-- ----------------------------
INSERT INTO `woke_interface_logs` VALUES ('2425', 'v2.Cart.index', '{\"app_id\":\"631911b6a702ba97700dbac572097a5\",\"nonce\":\"23423\",\"sign\":\"35521014937475FB26F1D5D669924BFA\",\"method\":\"v2.Cart.index\",\"user_id\":\"13108\"}', 'OTHER', '[1]', '1506499174.2143', '0.04234194755554', '1506499174.2567', '127.0.0.1', '2017-09-27 15:59:34', '2017-09-27 15:59:34');
INSERT INTO `woke_interface_logs` VALUES ('2426', 'v2.Cart.index', '{\"app_id\":\"631911b6a702ba97700dbac572097a5\",\"nonce\":\"23423\",\"sign\":\"35521014937475FB26F1D5D669924BFA\",\"method\":\"v2.Cart.index\",\"user_id\":\"13108\"}', 'OTHER', '[1]', '1506499180.1399', '0.02627992630004', '1506499180.1662', '127.0.0.1', '2017-09-27 15:59:40', '2017-09-27 15:59:40');
INSERT INTO `woke_interface_logs` VALUES ('2427', 'v2.Cart.index', '{\"app_id\":\"631911b6a702ba97700dbac572097a5\",\"nonce\":\"23423\",\"sign\":\"35521014937475FB26F1D5D669924BFA\",\"method\":\"v2.Cart.index\",\"user_id\":\"13108\"}', 'OTHER', '[1]', '1506499181.6866', '0.01806783676147', '1506499181.7047', '127.0.0.1', '2017-09-27 15:59:41', '2017-09-27 15:59:41');
INSERT INTO `woke_interface_logs` VALUES ('2428', 'v2.Cart.index', '{\"app_id\":\"631911b6a702ba97700dbac572097a5\",\"nonce\":\"23423\",\"sign\":\"35521014937475FB26F1D5D669924BFA\",\"method\":\"v2.Cart.index\",\"user_id\":\"13108\"}', 'OTHER', '[1]', '1506499191.9182', '0.01912808418273', '1506499191.9373', '127.0.0.1', '2017-09-27 15:59:51', '2017-09-27 15:59:51');
INSERT INTO `woke_interface_logs` VALUES ('2429', 'v2.Cart.index', '{\"app_id\":\"631911b6a702ba97700dbac572097a5\",\"nonce\":\"23423\",\"sign\":\"35521014937475FB26F1D5D669924BFA\",\"method\":\"v2.Cart.index\",\"user_id\":\"13108\"}', 'OTHER', '[1]', '1506499193.6984', '0.01850485801696', '1506499193.7169', '127.0.0.1', '2017-09-27 15:59:53', '2017-09-27 15:59:53');
INSERT INTO `woke_interface_logs` VALUES ('2430', 'v2.Cart.index', '{\"app_id\":\"631911b6a702ba97700dbac572097a5\",\"nonce\":\"23423\",\"sign\":\"35521014937475FB26F1D5D669924BFA\",\"method\":\"v2.Cart.index\",\"user_id\":\"13108\"}', 'OTHER', '[1]', '1506499200.9052', '0.02510595321655', '1506499200.9303', '127.0.0.1', '2017-09-27 16:00:00', '2017-09-27 16:00:00');
INSERT INTO `woke_interface_logs` VALUES ('2431', 'v2.Cart.index', '{\"app_id\":\"631911b6a702ba97700dbac572097a5\",\"nonce\":\"23423\",\"sign\":\"35521014937475FB26F1D5D669924BFA\",\"method\":\"v2.Cart.index\",\"user_id\":\"13108\"}', 'OTHER', '[1]', '1506499205.9494', '0.02242088317871', '1506499205.9718', '127.0.0.1', '2017-09-27 16:00:05', '2017-09-27 16:00:05');
INSERT INTO `woke_interface_logs` VALUES ('2432', 'v2.Cart.index', '{\"app_id\":\"631911b6a702ba97700dbac572097a5\",\"nonce\":\"23423\",\"sign\":\"35521014937475FB26F1D5D669924BFA\",\"method\":\"v2.Cart.index\",\"user_id\":\"13108\"}', 'OTHER', '[1]', '1506499206.7353', '0.02696990966796', '1506499206.7623', '127.0.0.1', '2017-09-27 16:00:06', '2017-09-27 16:00:06');
INSERT INTO `woke_interface_logs` VALUES ('2433', 'v2.Cart.index', '{\"app_id\":\"631911b6a702ba97700dbac572097a5\",\"nonce\":\"23423\",\"sign\":\"35521014937475FB26F1D5D669924BFA\",\"method\":\"v2.Cart.index\",\"user_id\":\"13108\"}', 'OTHER', '[1]', '1506499228.0244', '0.01835322380065', '1506499228.0427', '127.0.0.1', '2017-09-27 16:00:28', '2017-09-27 16:00:28');
INSERT INTO `woke_interface_logs` VALUES ('2434', 'v2.Cart.index', '{\"app_id\":\"631911b6a702ba97700dbac572097a5\",\"nonce\":\"23423\",\"sign\":\"35521014937475FB26F1D5D669924BFA\",\"method\":\"v2.Cart.index\",\"user_id\":\"13108\"}', 'OTHER', '[1]', '1506499228.5454', '0.01767587661743', '1506499228.5631', '127.0.0.1', '2017-09-27 16:00:28', '2017-09-27 16:00:28');
INSERT INTO `woke_interface_logs` VALUES ('2435', 'v2.Cart.index', '{\"app_id\":\"631911b6a702ba97700dbac572097a5\",\"nonce\":\"23423\",\"sign\":\"35521014937475FB26F1D5D669924BFA\",\"method\":\"v2.Cart.index\",\"user_id\":\"13108\"}', 'OTHER', '[1]', '1506499228.8469', '0.02204918861389', '1506499228.869', '127.0.0.1', '2017-09-27 16:00:28', '2017-09-27 16:00:28');
INSERT INTO `woke_interface_logs` VALUES ('2436', 'v2.Cart.index', '{\"app_id\":\"631911b6a702ba97700dbac572097a5\",\"nonce\":\"23423\",\"sign\":\"35521014937475FB26F1D5D669924BFA\",\"method\":\"v2.Cart.index\",\"user_id\":\"13108\"}', 'OTHER', '[1]', '1506499229.1891', '0.02458500862121', '1506499229.2137', '127.0.0.1', '2017-09-27 16:00:29', '2017-09-27 16:00:29');
INSERT INTO `woke_interface_logs` VALUES ('2437', 'v2.Cart.index', '{\"app_id\":\"631911b6a702ba97700dbac572097a5\",\"nonce\":\"23423\",\"sign\":\"35521014937475FB26F1D5D669924BFA\",\"method\":\"v2.Cart.index\",\"user_id\":\"13108\"}', 'OTHER', '[1]', '1506499229.4647', '0.02378010749816', '1506499229.4885', '127.0.0.1', '2017-09-27 16:00:29', '2017-09-27 16:00:29');
INSERT INTO `woke_interface_logs` VALUES ('2438', 'v2.Cart.index', '{\"app_id\":\"631911b6a702ba97700dbac572097a5\",\"nonce\":\"23423\",\"sign\":\"35521014937475FB26F1D5D669924BFA\",\"method\":\"v2.Cart.index\",\"user_id\":\"13108\"}', 'OTHER', '[1]', '1506499229.807', '0.01767706871032', '1506499229.8246', '127.0.0.1', '2017-09-27 16:00:29', '2017-09-27 16:00:29');
INSERT INTO `woke_interface_logs` VALUES ('2439', 'v2.Cart.index', '{\"app_id\":\"631911b6a702ba97700dbac572097a5\",\"nonce\":\"23423\",\"sign\":\"35521014937475FB26F1D5D669924BFA\",\"method\":\"v2.Cart.index\",\"user_id\":\"13108\"}', 'OTHER', '[1]', '1506499230.1741', '0.01787400245666', '1506499230.1919', '127.0.0.1', '2017-09-27 16:00:30', '2017-09-27 16:00:30');
INSERT INTO `woke_interface_logs` VALUES ('2440', 'v2.Cart.index', '{\"app_id\":\"631911b6a702ba97700dbac572097a5\",\"nonce\":\"23423\",\"sign\":\"35521014937475FB26F1D5D669924BFA\",\"method\":\"v2.Cart.index\",\"user_id\":\"13108\"}', 'OTHER', '[1]', '1506499230.4752', '0.02186512947082', '1506499230.4971', '127.0.0.1', '2017-09-27 16:00:30', '2017-09-27 16:00:30');
INSERT INTO `woke_interface_logs` VALUES ('2441', 'v2.Cart.index', '{\"app_id\":\"631911b6a702ba97700dbac572097a5\",\"nonce\":\"23423\",\"sign\":\"35521014937475FB26F1D5D669924BFA\",\"method\":\"v2.Cart.index\",\"user_id\":\"13108\"}', 'OTHER', '[1]', '1506499230.7889', '0.01761412620544', '1506499230.8065', '127.0.0.1', '2017-09-27 16:00:30', '2017-09-27 16:00:30');
INSERT INTO `woke_interface_logs` VALUES ('2442', 'v2.Cart.index', '{\"app_id\":\"631911b6a702ba97700dbac572097a5\",\"nonce\":\"23423\",\"sign\":\"35521014937475FB26F1D5D669924BFA\",\"method\":\"v2.Cart.index\",\"user_id\":\"13108\"}', 'OTHER', '[2]', '1506499237.2228', '0.01789999008178', '1506499237.2407', '127.0.0.1', '2017-09-27 16:00:37', '2017-09-27 16:00:37');
INSERT INTO `woke_interface_logs` VALUES ('2443', 'v2.Cart.index', '{\"app_id\":\"631911b6a702ba97700dbac572097a5\",\"nonce\":\"23423\",\"sign\":\"35521014937475FB26F1D5D669924BFA\",\"method\":\"v2.Cart.index\",\"user_id\":\"13108\"}', 'OTHER', '[2]', '1506499252.7255', '0.02117180824279', '1506499252.7467', '127.0.0.1', '2017-09-27 16:00:52', '2017-09-27 16:00:52');
INSERT INTO `woke_interface_logs` VALUES ('2444', 'v2.Cart.index', '{\"app_id\":\"631911b6a702ba97700dbac572097a5\",\"nonce\":\"23423\",\"sign\":\"35521014937475FB26F1D5D669924BFA\",\"method\":\"v2.Cart.index\",\"user_id\":\"13108\"}', 'OTHER', '[2]', '1506499253.5406', '0.01922202110290', '1506499253.5599', '127.0.0.1', '2017-09-27 16:00:53', '2017-09-27 16:00:53');
INSERT INTO `woke_interface_logs` VALUES ('2445', 'v2.Cart.index', '{\"app_id\":\"631911b6a702ba97700dbac572097a5\",\"nonce\":\"23423\",\"sign\":\"35521014937475FB26F1D5D669924BFA\",\"method\":\"v2.Cart.index\",\"user_id\":\"13108\"}', 'OTHER', '[2]', '1506499272.4639', '0.01975893974304', '1506499272.4836', '127.0.0.1', '2017-09-27 16:01:12', '2017-09-27 16:01:12');
INSERT INTO `woke_interface_logs` VALUES ('2446', 'v2.Cart.index', '{\"app_id\":\"631911b6a702ba97700dbac572097a5\",\"nonce\":\"23423\",\"sign\":\"35521014937475FB26F1D5D669924BFA\",\"method\":\"v2.Cart.index\",\"user_id\":\"13108\"}', 'OTHER', '[2]', '1506499273.1874', '0.01790094375610', '1506499273.2053', '127.0.0.1', '2017-09-27 16:01:13', '2017-09-27 16:01:13');
INSERT INTO `woke_interface_logs` VALUES ('2447', 'v2.Cart.index', '{\"app_id\":\"631911b6a702ba97700dbac572097a5\",\"nonce\":\"23423\",\"sign\":\"35521014937475FB26F1D5D669924BFA\",\"method\":\"v2.Cart.index\",\"user_id\":\"13108\"}', 'OTHER', '[2]', '1506499273.5576', '0.01777291297912', '1506499273.5753', '127.0.0.1', '2017-09-27 16:01:13', '2017-09-27 16:01:13');
INSERT INTO `woke_interface_logs` VALUES ('2448', 'v2.Cart.index', '{\"app_id\":\"631911b6a702ba97700dbac572097a5\",\"nonce\":\"23423\",\"sign\":\"35521014937475FB26F1D5D669924BFA\",\"method\":\"v2.Cart.index\",\"user_id\":\"13108\"}', 'OTHER', '{\"status\":false,\"code\":\"200\",\"msg\":\"\\u8bf7\\u6dfb\\u52a0\\u5546\\u54c1\",\"data\":0}', '1506499456.8561', '0.03016710281372', '1506499456.8863', '127.0.0.1', '2017-09-27 16:04:16', '2017-09-27 16:04:16');
INSERT INTO `woke_interface_logs` VALUES ('2449', 'v2.Cart.index', '{\"app_id\":\"631911b6a702ba97700dbac572097a5\",\"nonce\":\"23423\",\"sign\":\"35521014937475FB26F1D5D669924BFA\",\"method\":\"v2.Cart.index\",\"user_id\":\"13108\"}', 'OTHER', '{\"status\":false,\"code\":\"200\",\"msg\":\"\\u8bf7\\u6dfb\\u52a0\\u5546\\u54c1\",\"data\":0}', '1506499464.157', '0.03233695030212', '1506499464.1893', '127.0.0.1', '2017-09-27 16:04:24', '2017-09-27 16:04:24');
INSERT INTO `woke_interface_logs` VALUES ('2450', 'v2.Cart.index', '{\"nonce\":\"23423\",\"sign\":\"35521014937475FB26F1D5D669924BFA\",\"method\":\"v2.Cart.index\",\"user_id\":\"13108\"}', 'OTHER', '{\"status\":false,\"code\":\"1001\",\"msg\":\"[app_id]\\u7f3a\\u5931\"}', '1506499468.6047', '0.01662492752075', '1506499468.6213', '127.0.0.1', '2017-09-27 16:04:28', '2017-09-27 16:04:28');
INSERT INTO `woke_interface_logs` VALUES ('2451', 'v2.Cart.index', '{\"app_id\":\"631911b6a702ba97700dbac572097a5\",\"nonce\":\"23423\",\"sign\":\"35521014937475FB26F1D5D669924BFA\",\"method\":\"v2.Cart.index\",\"user_id\":\"13108\"}', 'OTHER', '{\"status\":false,\"code\":\"200\",\"msg\":\"\\u8bf7\\u6dfb\\u52a0\\u5546\\u54c1\",\"data\":0}', '1506499471.0355', '0.03999304771423', '1506499471.0755', '127.0.0.1', '2017-09-27 16:04:31', '2017-09-27 16:04:31');
INSERT INTO `woke_interface_logs` VALUES ('2452', 'v2.Cart.index', '{\"app_id\":\"631911b6a702ba97700dbac572097a5\",\"nonce\":\"23423\",\"sign\":\"35521014937475FB26F1D5D669924BFA\",\"method\":\"v2.Cart.index\",\"user_id\":\"13108\"}', 'OTHER', '[1]', '1506499486.6772', '0.01744103431701', '1506499486.6946', '127.0.0.1', '2017-09-27 16:04:46', '2017-09-27 16:04:46');
INSERT INTO `woke_interface_logs` VALUES ('2453', 'v2.Cart.index', '{\"app_id\":\"631911b6a702ba97700dbac572097a5\",\"nonce\":\"23423\",\"sign\":\"35521014937475FB26F1D5D669924BFA\",\"method\":\"v2.Cart.index\",\"user_id\":\"13108\"}', 'OTHER', '[1]', '1506499487.5579', '0.01842212677002', '1506499487.5763', '127.0.0.1', '2017-09-27 16:04:47', '2017-09-27 16:04:47');
INSERT INTO `woke_interface_logs` VALUES ('2454', 'v2.Cart.index', '{\"method\":\"v2.Cart.index\",\"user_id\":\"13108\"}', 'OTHER', '{\"status\":false,\"code\":\"1001\",\"msg\":\"[app_id]\\u7f3a\\u5931\"}', '1506499494.9844', '0.01321196556091', '1506499494.9976', '127.0.0.1', '2017-09-27 16:04:55', '2017-09-27 16:04:55');
INSERT INTO `woke_interface_logs` VALUES ('2455', 'v2.Cart.index', '{\"app_id\":\"631911b6a702ba97700dbac572097a51\",\"nonce\":\"23423\",\"sign\":\"35521014937475FB26F1D5D669924BFA\",\"method\":\"v2.Cart.index\",\"user_id\":\"13108\"}', 'OTHER', '{\"status\":false,\"code\":\"1002\",\"msg\":\"[app_id]\\u4e0d\\u5b58\\u5728\\u6216\\u65e0\\u6743\\u9650\"}', '1506499498.5741', '0.02709579467773', '1506499498.6012', '127.0.0.1', '2017-09-27 16:04:58', '2017-09-27 16:04:58');
INSERT INTO `woke_interface_logs` VALUES ('2456', 'v2.Cart.index', '{\"app_id\":\"631911b6a702ba97700dbac572097a51\",\"nonce\":\"23423\",\"sign\":\"35521014937475FB26F1D5D669924BFA\",\"method\":\"v2.Cart.index\",\"user_id\":\"13108\"}', 'OTHER', '{\"status\":false,\"code\":\"1002\",\"msg\":\"[app_id]\\u4e0d\\u5b58\\u5728\\u6216\\u65e0\\u6743\\u9650\"}', '1506499502.2603', '0.03572106361389', '1506499502.296', '127.0.0.1', '2017-09-27 16:05:02', '2017-09-27 16:05:02');
INSERT INTO `woke_interface_logs` VALUES ('2457', 'v2.Cart.index', '{\"app_id\":\"631911b6a702ba97700dbac572097a5\",\"nonce\":\"23423\",\"sign\":\"35521014937475FB26F1D5D669924BFA\",\"method\":\"v2.Cart.index\",\"user_id\":\"13108\"}', 'OTHER', '{\"status\":false,\"code\":\"200\",\"msg\":\"\\u8bf7\\u6dfb\\u52a0\\u5546\\u54c1\",\"data\":0}', '1506499504.6801', '0.03547000885009', '1506499504.7156', '127.0.0.1', '2017-09-27 16:05:04', '2017-09-27 16:05:04');
INSERT INTO `woke_interface_logs` VALUES ('2458', 'v2.Cart.index', '{\"app_id\":\"631911b6a702ba97700dbac572097a51\",\"nonce\":\"23423\",\"sign\":\"35521014937475FB26F1D5D669924BFA\",\"method\":\"v2.Cart.index\",\"user_id\":\"13108\"}', 'OTHER', '{\"status\":false,\"code\":\"200\",\"msg\":\"\\u8bf7\\u6dfb\\u52a0\\u5546\\u54c1\",\"data\":0}', '1506499516.3656', '0.03455805778503', '1506499516.4002', '127.0.0.1', '2017-09-27 16:05:16', '2017-09-27 16:05:16');
INSERT INTO `woke_interface_logs` VALUES ('2459', 'v2.Cart.index', '{\"nonce\":\"23423\",\"sign\":\"35521014937475FB26F1D5D669924BFA\",\"method\":\"v2.Cart.index\",\"user_id\":\"13108\"}', 'OTHER', '{\"status\":false,\"code\":\"1001\",\"msg\":\"[app_id]\\u7f3a\\u5931\"}', '1506499520.3938', '0.01321601867675', '1506499520.407', '127.0.0.1', '2017-09-27 16:05:20', '2017-09-27 16:05:20');
INSERT INTO `woke_interface_logs` VALUES ('2460', 'v2.Cart.index', '{\"app_id\":\"631911b6a702ba97700dbac572097a51\",\"nonce\":\"23423\",\"sign\":\"35521014937475FB26F1D5D669924BFA\",\"method\":\"v2.Cart.index\",\"user_id\":\"13108\"}', 'OTHER', '{\"status\":false,\"code\":\"200\",\"msg\":\"\\u8bf7\\u6dfb\\u52a0\\u5546\\u54c1\",\"data\":0}', '1506499522.522', '0.03211784362793', '1506499522.5541', '127.0.0.1', '2017-09-27 16:05:22', '2017-09-27 16:05:22');
INSERT INTO `woke_interface_logs` VALUES ('2461', 'v2.Cart.index', '{\"app_id\":\"631911b6a702ba97700dbac572097a51\",\"nonce\":\"23423\",\"sign\":\"35521014937475FB26F1D5D669924BFA\",\"method\":\"v2.Cart.index\",\"user_id\":\"13108\"}', 'OTHER', '{\"status\":false,\"code\":\"200\",\"msg\":\"\\u8bf7\\u6dfb\\u52a0\\u5546\\u54c1\",\"data\":0}', '1506499531.7142', '0.02972388267517', '1506499531.744', '127.0.0.1', '2017-09-27 16:05:31', '2017-09-27 16:05:31');
INSERT INTO `woke_interface_logs` VALUES ('2462', 'v2.Cart.index', '{\"app_id\":\"631911b6a702ba97700dbac572097a51\",\"nonce\":\"23423\",\"sign\":\"35521014937475FB26F1D5D669924BFA\",\"method\":\"v2.Cart.index\",\"user_id\":\"13108\"}', 'OTHER', '{\"status\":false,\"code\":\"200\",\"msg\":\"\\u8bf7\\u6dfb\\u52a0\\u5546\\u54c1\",\"data\":0}', '1506499541.1286', '0.03070998191833', '1506499541.1593', '127.0.0.1', '2017-09-27 16:05:41', '2017-09-27 16:05:41');
INSERT INTO `woke_interface_logs` VALUES ('2463', 'v2.Cart.index', '{\"app_id\":\"631911b6a702ba97700dbac572097a51\",\"nonce\":\"23423\",\"sign\":\"35521014937475FB26F1D5D669924BFA\",\"method\":\"v2.Cart.index\",\"user_id\":\"13108\"}', 'OTHER', '{\"status\":false,\"code\":\"200\",\"msg\":\"\\u8bf7\\u6dfb\\u52a0\\u5546\\u54c1\",\"data\":0}', '1506499546.3377', '0.03820705413818', '1506499546.3759', '127.0.0.1', '2017-09-27 16:05:46', '2017-09-27 16:05:46');
INSERT INTO `woke_interface_logs` VALUES ('2464', 'v2.Cart.index', '{\"app_id\":\"631911b6a702ba97700dbac572097a5\",\"nonce\":\"23423\",\"sign\":\"35521014937475FB26F1D5D669924BFA\",\"method\":\"v2.Cart.index\",\"user_id\":\"13108\"}', 'OTHER', '{\"status\":false,\"code\":\"200\",\"msg\":\"\\u8bf7\\u6dfb\\u52a0\\u5546\\u54c1\",\"data\":0}', '1506499549.213', '0.03205084800720', '1506499549.2451', '127.0.0.1', '2017-09-27 16:05:49', '2017-09-27 16:05:49');
INSERT INTO `woke_interface_logs` VALUES ('2465', 'v2.Cart.index', '{\"nonce\":\"23423\",\"sign\":\"35521014937475FB26F1D5D669924BFA\",\"method\":\"v2.Cart.index\",\"user_id\":\"13108\",\"app_id\":\"631911b6a702ba97700dbac572097a5\"}', 'OTHER', '{\"status\":false,\"code\":\"200\",\"msg\":\"\\u8bf7\\u6dfb\\u52a0\\u5546\\u54c1\",\"data\":0}', '1506499569.5657', '0.03033494949340', '1506499569.5961', '127.0.0.1', '2017-09-27 16:06:09', '2017-09-27 16:06:09');
INSERT INTO `woke_interface_logs` VALUES ('2466', 'v2.Cart.index', '{\"app_id\":\"63\",\"nonce\":\"23423\",\"sign\":\"35521014937475FB26F1D5D669924BFA\",\"method\":\"v2.Cart.index\",\"user_id\":\"13108\"}', 'OTHER', '{\"status\":false,\"code\":\"1002\",\"msg\":\"[app_id]\\u4e0d\\u5b58\\u5728\\u6216\\u65e0\\u6743\\u9650\"}', '1506499575.1395', '0.02831411361694', '1506499575.1678', '127.0.0.1', '2017-09-27 16:06:15', '2017-09-27 16:06:15');
INSERT INTO `woke_interface_logs` VALUES ('2467', 'v2.Cart.index', '{\"app_id\":\"631911b6a702ba97700dbac572097\",\"nonce\":\"23423\",\"sign\":\"35521014937475FB26F1D5D669924BFA\",\"method\":\"v2.Cart.index\",\"user_id\":\"13108\"}', 'OTHER', '{\"status\":false,\"code\":\"1002\",\"msg\":\"[app_id]\\u4e0d\\u5b58\\u5728\\u6216\\u65e0\\u6743\\u9650\"}', '1506499578.989', '0.03006100654602', '1506499579.0191', '127.0.0.1', '2017-09-27 16:06:19', '2017-09-27 16:06:19');
INSERT INTO `woke_interface_logs` VALUES ('2468', 'v2.Cart.index', '{\"app_id\":\"631911b6a702ba97700dbac572097a5\",\"nonce\":\"23423\",\"sign\":\"35521014937475FB26F1D5D669924BFA\",\"method\":\"v2.Cart.index\",\"user_id\":\"13108\"}', 'OTHER', '{\"status\":false,\"code\":\"200\",\"msg\":\"\\u8bf7\\u6dfb\\u52a0\\u5546\\u54c1\",\"data\":0}', '1506499581.5577', '0.03350090980529', '1506499581.5912', '127.0.0.1', '2017-09-27 16:06:21', '2017-09-27 16:06:21');
INSERT INTO `woke_interface_logs` VALUES ('2469', 'v2.Cart.index', '{\"app_id\":\"631911b6a702ba97700dbac572097a5\",\"nonce\":\"23423\",\"sign\":\"35521014937475FB26F1D5D669924BFA\",\"method\":\"v2.Cart.index\",\"user_id\":\"13108\"}', 'OTHER', '{\"status\":false,\"code\":\"200\",\"msg\":\"\\u8bf7\\u6dfb\\u52a0\\u5546\\u54c1\",\"data\":0}', '1506499796.4643', '0.03156399726867', '1506499796.4959', '127.0.0.1', '2017-09-27 16:09:56', '2017-09-27 16:09:56');
INSERT INTO `woke_interface_logs` VALUES ('2470', 'v2.Cart.index', '{\"app_id\":\"631911b6a702ba97700dbac572097a5\",\"nonce\":\"23423\",\"sign\":\"35521014937475FB26F1D5D669924BFA\",\"method\":\"v2.Cart.index\",\"user_id\":\"13108\"}', 'OTHER', '{\"status\":false,\"code\":\"200\",\"msg\":\"\\u8bf7\\u6dfb\\u52a0\\u5546\\u54c1\",\"data\":0}', '1506499798.6232', '0.02672195434570', '1506499798.65', '127.0.0.1', '2017-09-27 16:09:58', '2017-09-27 16:09:58');
INSERT INTO `woke_interface_logs` VALUES ('2471', 'v2.Cart.index', '{\"app_id\":\"631911b6a702ba97700dbac572097a5\",\"nonce\":\"23423\",\"sign\":\"35521014937475FB26F1D5D669924BFA\",\"method\":\"v2.Cart.index\",\"user_id\":\"13108\"}', 'OTHER', '{\"status\":false,\"code\":\"200\",\"msg\":\"\\u8bf7\\u6dfb\\u52a0\\u5546\\u54c1\",\"data\":0}', '1506499804.4539', '0.02568602561950', '1506499804.4796', '127.0.0.1', '2017-09-27 16:10:04', '2017-09-27 16:10:04');
INSERT INTO `woke_interface_logs` VALUES ('2472', 'v2.Cart.index', '{\"app_id\":\"631911b6a702ba97700dbac572097a5\",\"nonce\":\"23423\",\"sign\":\"35521014937475FB26F1D5D669924BFA\",\"method\":\"v2.Cart.index\",\"user_id\":\"13108\"}', 'OTHER', '{\"status\":false,\"code\":\"200\",\"msg\":\"\\u8bf7\\u6dfb\\u52a0\\u5546\\u54c1\",\"data\":0}', '1506499811.7929', '0.02672696113586', '1506499811.8196', '127.0.0.1', '2017-09-27 16:10:11', '2017-09-27 16:10:11');
INSERT INTO `woke_interface_logs` VALUES ('2473', 'v2.Cart.index', '{\"app_id\":\"631911b6a702ba97700dbac572097a5\",\"nonce\":\"23423\",\"sign\":\"35521014937475FB26F1D5D669924BFA\",\"method\":\"v2.Cart.index\",\"user_id\":\"13108\"}', 'OTHER', '{\"status\":false,\"code\":\"200\",\"msg\":\"\\u8bf7\\u6dfb\\u52a0\\u5546\\u54c1\",\"data\":0}', '1506499826.668', '0.02599096298217', '1506499826.694', '127.0.0.1', '2017-09-27 16:10:26', '2017-09-27 16:10:26');

-- ----------------------------
-- Table structure for woke_market
-- ----------------------------
DROP TABLE IF EXISTS `woke_market`;
CREATE TABLE `woke_market` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `time` char(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '期号',
  `price` decimal(10,2) NOT NULL COMMENT '猜大盘价格',
  `profit` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '收益',
  `option` tinyint(4) NOT NULL COMMENT '1 涨 2 区间 3跌',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1 待出结果 2已完成 ',
  `input_time` varchar(15) COLLATE utf8_unicode_ci NOT NULL COMMENT '结束日期',
  `ip` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0.0.0.0' COMMENT '操作ip',
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  KEY `woke_market_user_id_index` (`user_id`),
  KEY `woke_market_time_index` (`time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of woke_market
-- ----------------------------

-- ----------------------------
-- Table structure for woke_order_action
-- ----------------------------
DROP TABLE IF EXISTS `woke_order_action`;
CREATE TABLE `woke_order_action` (
  `action_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(11) unsigned NOT NULL DEFAULT '0',
  `action_user` varchar(30) NOT NULL DEFAULT '',
  `order_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `shipping_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `pay_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `action_place` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `action_note` varchar(255) NOT NULL DEFAULT '',
  `log_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`action_id`),
  KEY `order_id` (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1469 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of woke_order_action
-- ----------------------------

-- ----------------------------
-- Table structure for woke_order_goods
-- ----------------------------
DROP TABLE IF EXISTS `woke_order_goods`;
CREATE TABLE `woke_order_goods` (
  `rec_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(11) unsigned NOT NULL DEFAULT '0',
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0',
  `goods_name` varchar(120) NOT NULL DEFAULT '',
  `goods_sn` varchar(60) NOT NULL DEFAULT '',
  `product_id` int(11) unsigned NOT NULL DEFAULT '0',
  `goods_number` smallint(5) unsigned NOT NULL DEFAULT '1',
  `market_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `goods_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `goods_attr` text NOT NULL,
  `send_number` smallint(5) unsigned NOT NULL DEFAULT '0',
  `is_real` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `extension_code` varchar(30) NOT NULL DEFAULT '',
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0',
  `is_gift` smallint(5) unsigned NOT NULL DEFAULT '0',
  `goods_attr_id` varchar(255) NOT NULL DEFAULT '',
  `comment_state` tinyint(1) NOT NULL DEFAULT '0',
  `shaidan_state` tinyint(1) NOT NULL DEFAULT '0',
  `package_attr_id` varchar(100) NOT NULL,
  `is_back` tinyint(1) NOT NULL DEFAULT '0',
  `cost_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `promote_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`rec_id`),
  KEY `order_id` (`order_id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=InnoDB AUTO_INCREMENT=784 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of woke_order_goods
-- ----------------------------

-- ----------------------------
-- Table structure for woke_order_info
-- ----------------------------
DROP TABLE IF EXISTS `woke_order_info`;
CREATE TABLE `woke_order_info` (
  `order_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_sn` varchar(20) NOT NULL DEFAULT '',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `order_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `shipping_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `pay_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `consignee` varchar(60) NOT NULL DEFAULT '',
  `country` smallint(5) unsigned NOT NULL DEFAULT '0',
  `province` varchar(20) NOT NULL DEFAULT '0',
  `city` varchar(20) NOT NULL DEFAULT '0',
  `district` varchar(20) NOT NULL DEFAULT '0',
  `address` varchar(255) NOT NULL DEFAULT '',
  `zipcode` varchar(60) NOT NULL DEFAULT '',
  `tel` varchar(60) NOT NULL DEFAULT '',
  `mobile` varchar(60) NOT NULL DEFAULT '',
  `email` varchar(60) NOT NULL DEFAULT '',
  `best_time` varchar(120) NOT NULL DEFAULT '',
  `sign_building` varchar(120) NOT NULL DEFAULT '',
  `postscript` varchar(255) NOT NULL DEFAULT '',
  `shipping_id` tinyint(3) NOT NULL DEFAULT '0',
  `shipping_name` varchar(120) NOT NULL DEFAULT '',
  `shipping_express` varchar(10) NOT NULL COMMENT '快递名称',
  `pay_id` tinyint(3) NOT NULL DEFAULT '0',
  `pay_name` varchar(120) NOT NULL DEFAULT '',
  `defaultbank` varchar(20) NOT NULL COMMENT '银行',
  `how_oos` varchar(120) NOT NULL DEFAULT '',
  `how_surplus` varchar(120) NOT NULL DEFAULT '',
  `pack_name` varchar(120) NOT NULL DEFAULT '',
  `card_name` varchar(120) NOT NULL DEFAULT '',
  `card_message` varchar(255) NOT NULL DEFAULT '',
  `inv_payee` varchar(120) NOT NULL DEFAULT '',
  `inv_content` varchar(120) NOT NULL DEFAULT '',
  `goods_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `shipping_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `insure_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `pay_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `pack_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `card_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `money_paid` decimal(10,2) NOT NULL DEFAULT '0.00',
  `surplus` decimal(10,2) NOT NULL DEFAULT '0.00',
  `integral` int(10) unsigned NOT NULL DEFAULT '0',
  `integral_money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `bonus` decimal(10,2) NOT NULL DEFAULT '0.00',
  `order_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `from_ad` smallint(5) NOT NULL DEFAULT '0',
  `referer` varchar(255) NOT NULL DEFAULT '',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0',
  `confirm_time` int(10) unsigned NOT NULL DEFAULT '0',
  `pay_time` int(10) unsigned NOT NULL DEFAULT '0',
  `shipping_time` int(10) unsigned NOT NULL DEFAULT '0',
  `pack_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `card_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `bonus_id` int(11) unsigned NOT NULL DEFAULT '0',
  `invoice_no` varchar(255) NOT NULL DEFAULT '',
  `extension_code` varchar(30) NOT NULL DEFAULT '',
  `extension_id` int(11) unsigned NOT NULL DEFAULT '0',
  `to_buyer` varchar(255) NOT NULL DEFAULT '',
  `pay_note` varchar(255) NOT NULL DEFAULT '',
  `agency_id` smallint(5) unsigned NOT NULL,
  `inv_type` varchar(60) NOT NULL,
  `tax` decimal(10,2) NOT NULL,
  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0',
  `discount` decimal(10,2) NOT NULL,
  `supplier_id` int(10) unsigned NOT NULL DEFAULT '0',
  `parent_order_id` int(10) unsigned NOT NULL DEFAULT '0',
  `rebate_id` int(10) unsigned NOT NULL DEFAULT '0',
  `rebate_ispay` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '是否订单已经返佣(2:已返,1:未返)',
  `tb_nick` varchar(50) NOT NULL COMMENT '淘宝昵称',
  `froms` char(10) NOT NULL DEFAULT 'pc' COMMENT 'pc:电脑,mobile:手机,app:应用',
  `is_pickup` tinyint(1) NOT NULL,
  `pickup_point` int(11) NOT NULL,
  `vat_inv_company_name` varchar(60) DEFAULT NULL COMMENT '增值税发票单位名称',
  `vat_inv_taxpayer_id` varchar(20) DEFAULT NULL COMMENT '增值税发票纳税人识别号',
  `vat_inv_registration_address` varchar(120) DEFAULT NULL COMMENT '增值税发票注册地址',
  `vat_inv_registration_phone` varchar(60) DEFAULT NULL COMMENT '增值税发票注册电话',
  `vat_inv_deposit_bank` varchar(120) DEFAULT NULL COMMENT '增值税发票开户银行',
  `vat_inv_bank_account` varchar(30) DEFAULT NULL COMMENT '增值税发票银行账户',
  `inv_consignee_name` varchar(60) DEFAULT NULL COMMENT '收票人姓名',
  `inv_consignee_phone` varchar(60) DEFAULT NULL COMMENT '收票人手机',
  `inv_consignee_country` smallint(5) DEFAULT '1' COMMENT '收票人国家',
  `inv_consignee_province` smallint(5) DEFAULT NULL COMMENT '收票人省',
  `inv_consignee_city` smallint(5) DEFAULT NULL COMMENT '收票人市',
  `inv_consignee_district` smallint(5) DEFAULT NULL COMMENT '收票人县/区',
  `inv_consignee_address` varchar(120) DEFAULT NULL COMMENT '收票人详细地址',
  `inv_status` varchar(20) NOT NULL DEFAULT 'unprovided ' COMMENT '发票出票状态',
  `inv_remark` text COMMENT '发票备注',
  `inv_money` decimal(10,2) DEFAULT '0.00' COMMENT '发票金额',
  `inv_payee_type` varchar(20) DEFAULT NULL COMMENT '发票抬头类型',
  `shipping_time_end` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后更新时间',
  PRIMARY KEY (`order_id`),
  UNIQUE KEY `order_sn` (`order_sn`),
  KEY `user_id` (`user_id`),
  KEY `order_status` (`order_status`),
  KEY `shipping_status` (`shipping_status`),
  KEY `pay_status` (`pay_status`),
  KEY `shipping_id` (`shipping_id`),
  KEY `pay_id` (`pay_id`),
  KEY `extension_code` (`extension_code`,`extension_id`),
  KEY `agency_id` (`agency_id`),
  KEY `supplier_id` (`supplier_id`),
  KEY `rebate` (`rebate_id`,`rebate_ispay`)
) ENGINE=InnoDB AUTO_INCREMENT=451 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of woke_order_info
-- ----------------------------

-- ----------------------------
-- Table structure for woke_order_total
-- ----------------------------
DROP TABLE IF EXISTS `woke_order_total`;
CREATE TABLE `woke_order_total` (
  `order_total_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` varchar(40) COLLATE utf8_unicode_ci NOT NULL COMMENT '子订单id',
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `order_total_sn` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '总订单号',
  `order_total_price` decimal(10,2) NOT NULL COMMENT '订单总金额',
  `bonus` decimal(10,2) DEFAULT NULL COMMENT '计算参与分成',
  `integral_money` decimal(10,2) NOT NULL COMMENT '使用酒币',
  `integral` decimal(10,2) NOT NULL COMMENT '返还酒币',
  `pay_status` int(11) NOT NULL DEFAULT '0' COMMENT '支付状态',
  `pay_time` int(11) NOT NULL COMMENT '支付时间',
  `add_time` int(11) NOT NULL COMMENT '订单生成时间',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`order_total_id`)
) ENGINE=MyISAM AUTO_INCREMENT=372 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of woke_order_total
-- ----------------------------

-- ----------------------------
-- Table structure for woke_region
-- ----------------------------
DROP TABLE IF EXISTS `woke_region`;
CREATE TABLE `woke_region` (
  `region_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `region_name` varchar(120) NOT NULL DEFAULT '',
  `region_type` tinyint(1) NOT NULL DEFAULT '2',
  `agency_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`region_id`),
  KEY `parent_id` (`parent_id`),
  KEY `region_type` (`region_type`),
  KEY `agency_id` (`agency_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of woke_region
-- ----------------------------

-- ----------------------------
-- Table structure for woke_shipp_fee
-- ----------------------------
DROP TABLE IF EXISTS `woke_shipp_fee`;
CREATE TABLE `woke_shipp_fee` (
  `shipp_fee_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shipp_fee_name` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '模板名称',
  `shipp_name` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '快递名称',
  `supplier_id` int(11) NOT NULL,
  `is_default` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`shipp_fee_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of woke_shipp_fee
-- ----------------------------

-- ----------------------------
-- Table structure for woke_shipp_fee_extends
-- ----------------------------
DROP TABLE IF EXISTS `woke_shipp_fee_extends`;
CREATE TABLE `woke_shipp_fee_extends` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shipp_fee_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `province` varchar(4000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(8000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `number` int(11) NOT NULL COMMENT '首重',
  `price` decimal(10,2) NOT NULL COMMENT '首重价格',
  `xnumber` int(11) DEFAULT NULL COMMENT '续重',
  `xprice` decimal(10,2) DEFAULT NULL COMMENT '续重价格',
  `free` decimal(8,2) DEFAULT NULL COMMENT '包邮门槛',
  `is_default` int(11) DEFAULT NULL COMMENT '是否默认模板',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of woke_shipp_fee_extends
-- ----------------------------

-- ----------------------------
-- Table structure for woke_show_statistics
-- ----------------------------
DROP TABLE IF EXISTS `woke_show_statistics`;
CREATE TABLE `woke_show_statistics` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `count_show` int(10) NOT NULL DEFAULT '0',
  `count_tag` int(10) NOT NULL DEFAULT '0',
  `count_like` int(10) NOT NULL DEFAULT '0',
  `show_user` int(10) NOT NULL DEFAULT '0',
  `tag_user` int(10) NOT NULL DEFAULT '0',
  `like_user` int(10) NOT NULL DEFAULT '0',
  `createtime` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of woke_show_statistics
-- ----------------------------

-- ----------------------------
-- Table structure for woke_statistics
-- ----------------------------
DROP TABLE IF EXISTS `woke_statistics`;
CREATE TABLE `woke_statistics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `now` int(11) DEFAULT NULL COMMENT '现在时间e',
  `yesterday` int(11) DEFAULT NULL COMMENT '昨天下午五点半s',
  `sum_money` decimal(10,0) DEFAULT NULL COMMENT '酒币总和p',
  `integral_money` decimal(10,0) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of woke_statistics
-- ----------------------------

-- ----------------------------
-- Table structure for woke_supplier
-- ----------------------------
DROP TABLE IF EXISTS `woke_supplier`;
CREATE TABLE `woke_supplier` (
  `supplier_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '申请入驻人id',
  `supplier_name` varchar(255) NOT NULL COMMENT '供货商名称',
  `rank_id` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '店铺等级',
  `type_id` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '店铺类型',
  `company_name` varchar(255) NOT NULL COMMENT '公司名称',
  `country` smallint(5) unsigned NOT NULL COMMENT '公司所在地(国家)',
  `province` smallint(5) unsigned NOT NULL COMMENT '公司所在地(省)',
  `city` smallint(5) unsigned NOT NULL COMMENT '公司所在地(市)',
  `district` smallint(5) unsigned NOT NULL COMMENT '公司所在地(县/区)',
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT '公司详细地址',
  `tel` varchar(50) NOT NULL COMMENT '公司电话',
  `email` varchar(100) NOT NULL COMMENT '电子邮件',
  `guimo` varchar(255) NOT NULL COMMENT '公司规模',
  `company_type` varchar(50) NOT NULL COMMENT '公司类型',
  `bank` varchar(255) NOT NULL,
  `zhizhao` varchar(255) NOT NULL COMMENT '营业执照电子版',
  `contact` varchar(255) NOT NULL,
  `id_card` varchar(20) NOT NULL,
  `contact_back` varchar(255) NOT NULL,
  `contact_shop` varchar(255) NOT NULL,
  `contact_yunying` varchar(255) NOT NULL,
  `contact_shouhou` varchar(255) NOT NULL,
  `contact_caiwu` varchar(255) NOT NULL,
  `contact_jishu` varchar(255) NOT NULL,
  `system_fee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `supplier_bond` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `supplier_rebate` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `supplier_rebate_paytime` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `supplier_remark` varchar(255) NOT NULL DEFAULT '',
  `nav_list` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `add_time` int(10) NOT NULL DEFAULT '0' COMMENT '申请时间',
  `applynum` smallint(1) unsigned NOT NULL DEFAULT '0' COMMENT '申请入驻步骤',
  `contacts_name` varchar(100) NOT NULL DEFAULT '' COMMENT '联系人',
  `contacts_phone` varchar(50) NOT NULL DEFAULT '' COMMENT '联系人电话',
  `business_licence_number` varchar(100) NOT NULL DEFAULT '' COMMENT '营业执照号',
  `business_sphere` text NOT NULL COMMENT '法定经营范围',
  `organization_code` varchar(100) NOT NULL COMMENT '组织机构代码',
  `organization_code_electronic` varchar(255) NOT NULL COMMENT '组织机构代码证电子版',
  `general_taxpayer` varchar(255) NOT NULL COMMENT '一般纳税人证明',
  `bank_account_name` varchar(100) NOT NULL COMMENT '银行开户名',
  `bank_account_number` varchar(100) NOT NULL COMMENT '公司开户行银行账号',
  `bank_name` varchar(100) NOT NULL COMMENT '开户银行支行名称',
  `bank_code` varchar(100) NOT NULL COMMENT '支行联行号',
  `settlement_bank_account_name` varchar(100) NOT NULL COMMENT '银行开户名(结算)',
  `settlement_bank_account_number` varchar(100) NOT NULL COMMENT '公司银行账号(结算)',
  `settlement_bank_name` varchar(100) NOT NULL COMMENT '开户银行支行名称(结算)',
  `settlement_bank_code` varchar(100) NOT NULL COMMENT '支行联行号(结算)',
  `tax_registration_certificate` varchar(100) NOT NULL COMMENT '税务登记证号',
  `taxpayer_id` varchar(100) NOT NULL COMMENT '纳税人识别号',
  `bank_licence_electronic` varchar(255) NOT NULL COMMENT '开户银行许可证电子版',
  `tax_registration_certificate_electronic` varchar(255) NOT NULL COMMENT '税务登记证号电子版',
  `supplier_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '入驻商的佣金',
  `handheld_idcard` varchar(255) NOT NULL COMMENT '手持身份证照片',
  `idcard_front` varchar(255) NOT NULL COMMENT '身份证证明照片',
  `idcard_reverse` varchar(255) NOT NULL COMMENT '身份证反面照片',
  `id_card_no` varchar(20) NOT NULL COMMENT '身份证号码',
  `supple_sort_order` int(10) unsigned NOT NULL DEFAULT '50' COMMENT '排序',
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `supplier_img` varchar(255) DEFAULT NULL COMMENT '店铺照片',
  `content` varchar(200) NOT NULL,
  PRIMARY KEY (`supplier_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of woke_supplier
-- ----------------------------

-- ----------------------------
-- Table structure for woke_users
-- ----------------------------
DROP TABLE IF EXISTS `woke_users`;
CREATE TABLE `woke_users` (
  `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `aite_id` text NOT NULL,
  `email` varchar(60) NOT NULL DEFAULT '',
  `mobile_phone` varchar(20) NOT NULL,
  `user_name` varchar(60) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `ec_salt` varchar(10) DEFAULT NULL,
  `salt` varchar(10) NOT NULL DEFAULT '0',
  `is_surplus_open` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否开启余额支付密码功能',
  `surplus_password` varchar(32) NOT NULL COMMENT '余额支付密码',
  `question` varchar(255) NOT NULL DEFAULT '',
  `answer` varchar(255) NOT NULL DEFAULT '',
  `sex` tinyint(1) unsigned DEFAULT '0',
  `birthday` date NOT NULL DEFAULT '1960-01-01',
  `user_money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `frozen_money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `pay_points` int(10) unsigned NOT NULL DEFAULT '0',
  `rank_points` int(10) unsigned NOT NULL DEFAULT '0',
  `address_id` int(11) unsigned NOT NULL DEFAULT '0',
  `reg_time` int(10) unsigned NOT NULL DEFAULT '0',
  `last_login` int(11) unsigned NOT NULL DEFAULT '0',
  `last_time` datetime NOT NULL DEFAULT '1960-01-01 00:00:00',
  `last_ip` varchar(15) NOT NULL DEFAULT '',
  `visit_count` smallint(5) unsigned NOT NULL DEFAULT '0',
  `user_rank` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `is_special` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `flag` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `alias` varchar(60) NOT NULL,
  `msn` varchar(60) NOT NULL,
  `qq` varchar(20) NOT NULL,
  `office_phone` varchar(20) NOT NULL,
  `home_phone` varchar(20) NOT NULL,
  `is_validated` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `validated` int(11) NOT NULL,
  `credit_line` decimal(10,2) unsigned NOT NULL,
  `passwd_question` varchar(50) DEFAULT NULL,
  `passwd_answer` varchar(255) DEFAULT NULL,
  `real_name` varchar(255) NOT NULL,
  `card` varchar(255) NOT NULL,
  `face_card` varchar(255) NOT NULL,
  `back_card` varchar(255) NOT NULL,
  `country` int(11) NOT NULL,
  `province` int(11) NOT NULL,
  `city` int(11) NOT NULL,
  `district` int(11) NOT NULL,
  `address` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `mediaUID` varchar(50) NOT NULL,
  `mediaID` int(4) NOT NULL,
  `froms` char(10) NOT NULL DEFAULT 'pc' COMMENT 'pc:电脑,mobile:手机,app:应用',
  `headimg` varchar(255) NOT NULL,
  `sina_id` text,
  `weixin_id` text,
  `qq_id` text,
  `follow_size` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '粉丝数量',
  `like_size` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '关注数量',
  `about` varchar(200) DEFAULT NULL,
  `signature` varchar(20) NOT NULL,
  `bg` varchar(100) NOT NULL,
  `is_v` tinyint(4) NOT NULL DEFAULT '0',
  `show_view_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '晒晒浏览量',
  `sort_order` smallint(5) unsigned NOT NULL DEFAULT '50' COMMENT '大师排序',
  `image` varchar(100) NOT NULL COMMENT '大师形象',
  `day_max_view_number` int(11) NOT NULL DEFAULT '5000',
  `day_view_number` int(11) NOT NULL DEFAULT '0',
  `search_sort_order` int(11) unsigned NOT NULL DEFAULT '100',
  `union_id` varchar(200) DEFAULT NULL,
  `alipay_id` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `users_mobile_phone` (`mobile_phone`) USING BTREE,
  KEY `email` (`email`),
  KEY `parent_id` (`parent_id`),
  KEY `flag` (`flag`),
  KEY `mediaUID` (`mediaUID`,`mediaID`)
) ENGINE=InnoDB AUTO_INCREMENT=13247 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of woke_users
-- ----------------------------

-- ----------------------------
-- Table structure for woke_user_address
-- ----------------------------
DROP TABLE IF EXISTS `woke_user_address`;
CREATE TABLE `woke_user_address` (
  `address_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `address_name` varchar(50) NOT NULL DEFAULT '',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `consignee` varchar(60) NOT NULL DEFAULT '',
  `email` varchar(60) NOT NULL DEFAULT '',
  `country` smallint(5) NOT NULL DEFAULT '0',
  `province` varchar(20) NOT NULL DEFAULT '0',
  `city` varchar(20) NOT NULL DEFAULT '0',
  `district` varchar(20) NOT NULL DEFAULT '0',
  `address` varchar(120) NOT NULL DEFAULT '',
  `zipcode` varchar(60) NOT NULL DEFAULT '',
  `tel` varchar(60) NOT NULL DEFAULT '',
  `mobile` varchar(60) NOT NULL DEFAULT '',
  `sign_building` varchar(120) NOT NULL DEFAULT '',
  `best_time` varchar(120) NOT NULL DEFAULT '',
  `is_default` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`address_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=792 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of woke_user_address
-- ----------------------------

-- ----------------------------
-- Table structure for woke_user_alipay
-- ----------------------------
DROP TABLE IF EXISTS `woke_user_alipay`;
CREATE TABLE `woke_user_alipay` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alipay_id` varchar(20) DEFAULT NULL,
  `user_name` varchar(20) DEFAULT NULL,
  `alipay_money` decimal(10,2) DEFAULT '0.00',
  `mobile_phone` varchar(20) DEFAULT NULL,
  `flowing` varchar(40) DEFAULT NULL,
  `type` int(11) DEFAULT NULL COMMENT '1.尚未提现，2.提现中，3.提现完成',
  `mode` int(11) DEFAULT NULL COMMENT '提现方式1.支付宝，2.微信',
  `alipay_time` int(11) DEFAULT NULL,
  `operator` varchar(10) DEFAULT NULL,
  `oper_time` int(11) DEFAULT '0',
  `user_id` int(11) DEFAULT NULL,
  `reason` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of woke_user_alipay
-- ----------------------------

-- ----------------------------
-- Table structure for woke_user_order_award_log
-- ----------------------------
DROP TABLE IF EXISTS `woke_user_order_award_log`;
CREATE TABLE `woke_user_order_award_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL COMMENT '下单用户',
  `bonus` decimal(10,2) NOT NULL COMMENT '参与返现的订单总价',
  `recommendation_user_id` int(10) unsigned DEFAULT NULL,
  `recommendation_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '推荐奖',
  `academy_user_id` int(10) unsigned DEFAULT NULL,
  `academy_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '高专奖',
  `manager_user_id` int(10) unsigned DEFAULT NULL,
  `manager_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '经理奖',
  `breeding_user_id` int(10) unsigned DEFAULT NULL,
  `breeding_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '育成奖',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `woke_user_order_award_log_order_id_index` (`order_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of woke_user_order_award_log
-- ----------------------------

-- ----------------------------
-- Table structure for woke_user_sms
-- ----------------------------
DROP TABLE IF EXISTS `woke_user_sms`;
CREATE TABLE `woke_user_sms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mobile_phone` char(11) NOT NULL,
  `code` char(6) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `add_time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mobile_phone` (`mobile_phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of woke_user_sms
-- ----------------------------

-- ----------------------------
-- Table structure for woke_user_token
-- ----------------------------
DROP TABLE IF EXISTS `woke_user_token`;
CREATE TABLE `woke_user_token` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `token` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ecs_user_token_user_id_index` (`user_id`),
  KEY `ecs_user_token_token_index` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of woke_user_token
-- ----------------------------
