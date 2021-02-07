/*
 Navicat Premium Data Transfer

 Source Server         : MySql
 Source Server Type    : MySQL
 Source Server Version : 100417
 Source Host           : localhost:3306
 Source Schema         : db_dkm

 Target Server Type    : MySQL
 Target Server Version : 100417
 File Encoding         : 65001

 Date: 06/02/2021 15:34:59
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for delivery_addresses
-- ----------------------------
DROP TABLE IF EXISTS `delivery_addresses`;
CREATE TABLE `delivery_addresses`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `latitude` varchar(24) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `longitude` varchar(24) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `is_default` tinyint(1) NULL DEFAULT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `delivery_addresses_user_id_foreign`(`user_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 17 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of delivery_addresses
-- ----------------------------
INSERT INTO `delivery_addresses` VALUES (1, 'Alias sed consequatur autem quod sit ipsa consequatur non.', '59351 Wolff Course Apt. 709\nAugustside, WV 97715-9293', '-30.154692', '117.420211', 1, 6, '2021-01-07 09:41:45', '2021-01-07 09:41:45');
INSERT INTO `delivery_addresses` VALUES (2, 'Fugit magnam ipsam corporis illum voluptas dolor vel.', '54202 Morar Parkways\nAbshireport, LA 70236', '69.033707', '-137.046627', 0, 1, '2021-01-07 09:41:45', '2021-01-25 14:14:43');
INSERT INTO `delivery_addresses` VALUES (3, 'Fugit magnam ipsam corporis illum voluptas dolor vel.', '393 Morissette Circles Suite 385\nLake Stephen, MS 74389', '70.883917', '55.734969', 0, 3, '2021-01-07 09:41:45', '2021-01-07 09:41:45');
INSERT INTO `delivery_addresses` VALUES (4, 'In sapiente enim quo fuga nam.', '241 Allison Mountains Apt. 660\nJulianaville, WV 24496-5497', '33.020425', '41.011416', 1, 3, '2021-01-07 09:41:45', '2021-01-07 09:41:45');
INSERT INTO `delivery_addresses` VALUES (5, 'Cupiditate facere consequatur sit voluptas.', '853 Casper Junction Apt. 639\nKoepphaven, CT 84224', '83.743001', '-42.936646', 0, 6, '2021-01-07 09:41:45', '2021-01-07 09:41:45');
INSERT INTO `delivery_addresses` VALUES (6, 'Sint assumenda magnam id tempore ipsum accusamus possimus assumenda.', '623 Lynn Island\nKuhlmanville, VT 04329', '-89.449373', '-152.62051', 1, 2, '2021-01-07 09:41:45', '2021-01-07 09:41:45');
INSERT INTO `delivery_addresses` VALUES (7, 'Animi et minima exercitationem animi et.', '79238 Considine Courts\nWest Eltonhaven, MO 73167-9429', '62.308966', '-167.137137', 1, 1, '2021-01-07 09:41:45', '2021-01-07 09:41:45');
INSERT INTO `delivery_addresses` VALUES (8, 'Molestiae quisquam magnam est sunt possimus.', '7996 Keely Valley\nEast Jaquelinemouth, CA 86731', '21.302188', '86.744253', 1, 2, '2021-01-07 09:41:45', '2021-01-07 09:41:45');
INSERT INTO `delivery_addresses` VALUES (9, 'Et voluptas exercitationem recusandae minus occaecati.', '9425 Myrtle Hollow Apt. 364\nHagenesmouth, KS 96354', '27.010845', '164.65571', 0, 4, '2021-01-07 09:41:45', '2021-01-07 09:41:45');
INSERT INTO `delivery_addresses` VALUES (10, 'Qui saepe incidunt qui cumque unde.', '28930 Nettie Course Suite 841\nWest Louisa, KY 46896-6821', '-35.469672', '-99.864108', 0, 1, '2021-01-07 09:41:45', '2021-01-07 09:41:45');
INSERT INTO `delivery_addresses` VALUES (11, 'Dicta doloremque dicta nam praesentium voluptatem dolore quia cumque.', '42576 Tillman Divide\nEliseomouth, NM 32200', '75.234745', '-117.161295', 1, 3, '2021-01-07 09:41:45', '2021-01-07 09:41:45');
INSERT INTO `delivery_addresses` VALUES (12, 'Reprehenderit et incidunt ab adipisci dignissimos hic ex.', '248 Weston Lakes Apt. 156\nNew Emilianoview, MD 88545', '-30.659283', '145.749583', 1, 2, '2021-01-07 09:41:45', '2021-01-07 09:41:45');
INSERT INTO `delivery_addresses` VALUES (13, 'Ullam reprehenderit vel necessitatibus magnam similique rerum optio.', '23048 Julia Isle\nAylachester, SD 17118-5269', '-37.515193', '121.162551', 0, 6, '2021-01-07 09:41:45', '2021-01-07 09:41:45');
INSERT INTO `delivery_addresses` VALUES (14, 'Quisquam qui tempora molestiae est fugit.', '3157 Lora Lock\nNew Forest, CA 04545-9793', '-15.610628', '-82.817567', 1, 2, '2021-01-07 09:41:45', '2021-01-07 09:41:45');
INSERT INTO `delivery_addresses` VALUES (15, 'Qui quasi at dolor ullam qui.', '812 Olson Parks Suite 810\nSchummshire, UT 33070-4012', '-77.843883', '166.36729', 0, 1, '2021-01-07 09:41:45', '2021-01-07 09:41:45');
INSERT INTO `delivery_addresses` VALUES (16, NULL, NULL, '-6.925444666999849', '107.63124883174896', 0, 3, '2021-01-10 04:04:40', '2021-01-18 04:37:34');

-- ----------------------------
-- Table structure for delivery_fee_list
-- ----------------------------
DROP TABLE IF EXISTS `delivery_fee_list`;
CREATE TABLE `delivery_fee_list`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `from_weight` double(10, 2) NULL DEFAULT NULL,
  `to_weight` double(10, 2) NULL DEFAULT NULL,
  `price` double(20, 0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT current_timestamp(0),
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of delivery_fee_list
-- ----------------------------
INSERT INTO `delivery_fee_list` VALUES (1, 0.00, 2.00, 10000, '2021-02-06 10:17:07', NULL);
INSERT INTO `delivery_fee_list` VALUES (2, 3.00, 5.00, 20000, '2021-02-06 10:17:19', NULL);
INSERT INTO `delivery_fee_list` VALUES (3, 5.00, 7.00, 30000, '2021-02-06 10:17:36', NULL);
INSERT INTO `delivery_fee_list` VALUES (4, 7.00, 10.00, 40000, '2021-02-06 10:18:01', NULL);
INSERT INTO `delivery_fee_list` VALUES (5, 10.00, NULL, 50000, '2021-02-06 10:18:17', NULL);

-- ----------------------------
-- Table structure for driver_category
-- ----------------------------
DROP TABLE IF EXISTS `driver_category`;
CREATE TABLE `driver_category`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT current_timestamp(0),
  `updated_at` timestamp(0) NULL DEFAULT current_timestamp(0),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of driver_category
-- ----------------------------
INSERT INTO `driver_category` VALUES (1, 'Reguler', '2021-01-26 12:57:37', '2021-01-26 12:57:37');
INSERT INTO `driver_category` VALUES (2, 'Express', '2021-01-26 12:57:48', '2021-01-26 12:57:48');

-- ----------------------------
-- Table structure for drivers
-- ----------------------------
DROP TABLE IF EXISTS `drivers`;
CREATE TABLE `drivers`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED NOT NULL,
  `driver_category_id` int NULL DEFAULT NULL,
  `delivery_fee` double(5, 2) NOT NULL DEFAULT 0,
  `total_orders` int UNSIGNED NOT NULL DEFAULT 0,
  `earning` double(9, 2) NOT NULL DEFAULT 0,
  `available` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `drivers_user_id_foreign`(`user_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Records of drivers
-- ----------------------------
INSERT INTO `drivers` VALUES (1, 3, 1, 0.00, 0, 0.00, 1, '2021-01-07 09:50:09', '2021-01-18 04:40:49');
INSERT INTO `drivers` VALUES (2, 4, 1, 0.00, 0, 0.00, 0, '2021-01-07 09:50:09', '2021-01-07 09:50:09');
INSERT INTO `drivers` VALUES (3, 19, 1, 0.00, 0, 0.00, 0, NULL, NULL);

-- ----------------------------
-- Table structure for menus
-- ----------------------------
DROP TABLE IF EXISTS `menus`;
CREATE TABLE `menus`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT current_timestamp(0),
  `updated_at` timestamp(0) NULL DEFAULT current_timestamp(0),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 18 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of menus
-- ----------------------------
INSERT INTO `menus` VALUES (1, 'Home', '/', 'mdi-home-city', NULL, NULL);
INSERT INTO `menus` VALUES (3, 'Menus', '/menu', 'mdi-cog-outline', '2021-01-14 13:25:21', '2021-01-14 13:25:21');
INSERT INTO `menus` VALUES (4, 'Profile', '/profile', 'mdi-account', '2021-01-14 16:17:59', '2021-01-14 16:17:59');
INSERT INTO `menus` VALUES (9, 'test22', '/test22', 'mdi-gate-xor', '2021-01-23 14:45:24', '2021-01-23 14:45:24');
INSERT INTO `menus` VALUES (14, 'Test3', '/Test3', 'mdi-gate-xor', '2021-01-25 09:19:35', '2021-01-25 09:19:35');
INSERT INTO `menus` VALUES (16, 'Test3', '/Test3', 'mdi-gate-xor', '2021-01-25 09:19:59', '2021-01-25 09:19:59');
INSERT INTO `menus` VALUES (17, 'Test3', '/Test3', 'mdi-gate-xor', '2021-01-25 09:19:59', '2021-01-25 09:19:59');

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (2, '2021_01_14_040920_menus', 1);
INSERT INTO `migrations` VALUES (3, '2021_01_20_035734_create_users_table', 2);
INSERT INTO `migrations` VALUES (4, '2021_01_24_040120_roles', 3);
INSERT INTO `migrations` VALUES (5, '2021_01_24_040208_permissions', 3);
INSERT INTO `migrations` VALUES (7, '2021_01_24_040741_permission_role', 4);

-- ----------------------------
-- Table structure for order_details
-- ----------------------------
DROP TABLE IF EXISTS `order_details`;
CREATE TABLE `order_details`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(127) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` double(8, 2) NOT NULL DEFAULT 0,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `capacity` double(9, 2) NULL DEFAULT 0,
  `weight` float(10, 2) NULL DEFAULT NULL,
  `volume` float(10, 2) NULL DEFAULT NULL,
  `photo` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `featured` tinyint(1) NULL DEFAULT 0,
  `deliverable` tinyint(1) NULL DEFAULT 1,
  `receiver` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `phone` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `category_id` int UNSIGNED NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `products_category_id_foreign`(`category_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 42 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of order_details
-- ----------------------------
INSERT INTO `order_details` VALUES (14, 'Sapi steak', 100000.00, 'Qui ad fuga non ipsa. Eius qui praesentium voluptatum esse. Veniam eius corrupti expedita nulla in tenetur.', 225.32, 2.30, NULL, NULL, 1, 1, 'dimas', '08728734234', 1, '2021-01-07 09:41:45', '2021-01-10 06:07:26');
INSERT INTO `order_details` VALUES (23, 'Tuna steak', 150000.00, 'Steak Mantap', 82.36, 4.20, NULL, NULL, 1, 1, 'reza', '0823746234', 1, '2021-01-07 09:41:45', '2021-01-10 05:54:40');
INSERT INTO `order_details` VALUES (28, 'Steak Premium', 100000.00, 'Labore molestiae nemo aut reprehenderit vel. Blanditiis eos ad et et. Ut eligendi consequuntur earum ea optio ab rem distinctio.', 407.91, 4.00, NULL, NULL, 1, 1, 'sani', '08263847234', 1, '2021-01-07 09:41:45', '2021-01-10 06:09:23');
INSERT INTO `order_details` VALUES (41, 'Laptop Lenovo', 250.00, '<p>Laptop </p>', 9000.00, 4.00, NULL, NULL, 1, 1, NULL, NULL, 7, '2021-01-10 03:30:48', '2021-01-10 07:03:32');

-- ----------------------------
-- Table structure for order_statuses
-- ----------------------------
DROP TABLE IF EXISTS `order_statuses`;
CREATE TABLE `order_statuses`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `status` varchar(127) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of order_statuses
-- ----------------------------
INSERT INTO `order_statuses` VALUES (1, 'Assigned', '2019-08-30 16:39:28', '2019-10-15 18:03:14');
INSERT INTO `order_statuses` VALUES (2, 'Pickup Proses', '2019-10-15 18:03:50', '2019-10-15 18:03:50');
INSERT INTO `order_statuses` VALUES (3, 'Pickup Finish', '2019-10-15 18:04:30', '2019-10-15 18:04:30');
INSERT INTO `order_statuses` VALUES (4, 'Ready To Deliver', '2019-10-15 18:04:13', '2019-10-15 18:04:13');
INSERT INTO `order_statuses` VALUES (5, 'Delivered', NULL, NULL);
INSERT INTO `order_statuses` VALUES (6, 'Cancel', NULL, NULL);

-- ----------------------------
-- Table structure for orders
-- ----------------------------
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `no_order` int NULL DEFAULT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `order_detail_id` int NOT NULL,
  `order_statuses_id` int UNSIGNED NOT NULL,
  `tax` double(5, 2) NULL DEFAULT 0,
  `delivery_fee` double(5, 0) NULL DEFAULT 0,
  `hint` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `driver_id` int UNSIGNED NULL DEFAULT NULL,
  `delivery_address_id` int UNSIGNED NULL DEFAULT NULL,
  `payment_id` int UNSIGNED NULL DEFAULT NULL,
  `pickup_status` int NULL DEFAULT 0,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `delivered_at` datetime(0) NULL DEFAULT NULL,
  `pickup_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `orders_user_id_foreign`(`user_id`) USING BTREE,
  INDEX `orders_order_status_id_foreign`(`order_statuses_id`) USING BTREE,
  INDEX `orders_driver_id_foreign`(`driver_id`) USING BTREE,
  INDEX `orders_delivery_address_id_foreign`(`delivery_address_id`) USING BTREE,
  INDEX `orders_payment_id_foreign`(`payment_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of orders
-- ----------------------------
INSERT INTO `orders` VALUES (1, 1, 20, 41, 2, 1.00, 10000, NULL, 1, 3, 1, 1, 0, '2021-01-10 03:58:45', '2021-02-06 21:40:20', '2021-01-31 13:26:09', '2021-02-02 14:06:52');
INSERT INTO `orders` VALUES (2, 2, 20, 14, 1, 0.00, 20000, NULL, 1, 3, 2, 2, 0, '2021-01-10 07:22:46', '2021-02-06 21:38:48', '2021-01-31 13:26:06', '2021-02-04 14:32:24');
INSERT INTO `orders` VALUES (3, 3, 20, 23, 1, 0.00, 0, NULL, 1, 3, 3, 3, 0, '2021-01-10 07:30:56', '2021-02-01 17:59:27', NULL, '2021-02-01 10:59:27');
INSERT INTO `orders` VALUES (4, 4, 20, 28, 3, 0.00, 10000, NULL, 1, 3, 15, 4, 1, '2021-01-10 07:35:17', '2021-01-10 07:36:49', NULL, NULL);
INSERT INTO `orders` VALUES (5, 5, 20, 28, 5, 0.00, 10000, NULL, 1, 3, 15, 5, 1, '2021-01-10 09:22:24', '2021-01-30 16:23:02', NULL, NULL);
INSERT INTO `orders` VALUES (6, 6, 20, 28, 5, 0.00, 10000, NULL, 1, 3, 15, 6, 1, '2021-01-18 04:38:22', '2021-01-18 04:40:49', NULL, NULL);

-- ----------------------------
-- Table structure for payment_methods
-- ----------------------------
DROP TABLE IF EXISTS `payment_methods`;
CREATE TABLE `payment_methods`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of payment_methods
-- ----------------------------
INSERT INTO `payment_methods` VALUES (1, 'pay on pickup');
INSERT INTO `payment_methods` VALUES (2, 'cash on delivery');

-- ----------------------------
-- Table structure for payment_status
-- ----------------------------
DROP TABLE IF EXISTS `payment_status`;
CREATE TABLE `payment_status`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT current_timestamp(0),
  `updated_at` timestamp(0) NULL DEFAULT current_timestamp(0),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of payment_status
-- ----------------------------
INSERT INTO `payment_status` VALUES (1, 'unpaid', '2021-01-30 15:13:33', '2021-01-30 15:13:33');
INSERT INTO `payment_status` VALUES (2, 'paid', '2021-01-30 15:13:39', '2021-01-30 15:13:39');

-- ----------------------------
-- Table structure for payments
-- ----------------------------
DROP TABLE IF EXISTS `payments`;
CREATE TABLE `payments`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `price` double(8, 2) NOT NULL DEFAULT 0,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `status` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `payment_method_id` int NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `payments_user_id_foreign`(`user_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of payments
-- ----------------------------
INSERT INTO `payments` VALUES (1, 30.62, 'Order not paid yet', 3, '1', 1, '2021-01-10 03:58:45', '2021-01-10 04:14:22');
INSERT INTO `payments` VALUES (2, 1000.00, 'Order not paid yet', 3, '1', 1, '2021-01-10 07:22:46', '2021-01-10 07:28:32');
INSERT INTO `payments` VALUES (3, 14997.00, 'Order not paid yet', 3, '1', 1, '2021-01-10 07:30:56', '2021-01-10 09:40:33');
INSERT INTO `payments` VALUES (4, 30.32, 'Order not paid yet', 3, '2', 2, '2021-01-10 07:35:17', '2021-01-10 07:36:49');
INSERT INTO `payments` VALUES (5, 252.00, 'Order not paid yet', 3, '2', 2, '2021-01-10 09:22:24', '2021-01-10 09:38:59');
INSERT INTO `payments` VALUES (6, 14997.00, 'Order not paid yet', 3, '2', 2, '2021-01-18 04:38:22', '2021-01-18 04:40:49');

-- ----------------------------
-- Table structure for permission_role
-- ----------------------------
DROP TABLE IF EXISTS `permission_role`;
CREATE TABLE `permission_role`  (
  `permission_id` int UNSIGNED NOT NULL,
  `role_id` int UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`, `role_id`) USING BTREE,
  INDEX `permission_role_role_id_foreign`(`role_id`) USING BTREE,
  CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of permission_role
-- ----------------------------
INSERT INTO `permission_role` VALUES (1, 1);
INSERT INTO `permission_role` VALUES (1, 5);
INSERT INTO `permission_role` VALUES (2, 3);
INSERT INTO `permission_role` VALUES (3, 1);
INSERT INTO `permission_role` VALUES (3, 2);
INSERT INTO `permission_role` VALUES (4, 1);
INSERT INTO `permission_role` VALUES (4, 2);

-- ----------------------------
-- Table structure for permissions
-- ----------------------------
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of permissions
-- ----------------------------
INSERT INTO `permissions` VALUES (1, 'customer', NULL, NULL);
INSERT INTO `permissions` VALUES (2, 'driver', NULL, NULL);
INSERT INTO `permissions` VALUES (3, 'menu', NULL, NULL);
INSERT INTO `permissions` VALUES (4, 'admin', NULL, NULL);

-- ----------------------------
-- Table structure for pickup_addresses
-- ----------------------------
DROP TABLE IF EXISTS `pickup_addresses`;
CREATE TABLE `pickup_addresses`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `latitude` varchar(24) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `longitude` varchar(24) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `is_default` tinyint(1) NULL DEFAULT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `delivery_addresses_user_id_foreign`(`user_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 17 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pickup_addresses
-- ----------------------------
INSERT INTO `pickup_addresses` VALUES (1, 'Alias sed consequatur autem quod sit ipsa consequatur non.', '59351 Wolff Course Apt. 709\nAugustside, WV 97715-9293', '-30.154692', '117.420211', 1, 6, '2021-01-07 09:41:45', '2021-01-07 09:41:45');
INSERT INTO `pickup_addresses` VALUES (2, 'Fugit magnam ipsam corporis illum voluptas dolor vel.', '54202 Morar Parkways\nAbshireport, LA 70236', '69.033707', '-137.046627', 0, 1, '2021-01-07 09:41:45', '2021-01-25 14:14:43');
INSERT INTO `pickup_addresses` VALUES (3, 'Fugit magnam ipsam corporis illum voluptas dolor vel.', '393 Morissette Circles Suite 385\nLake Stephen, MS 74389', '70.883917', '55.734969', 0, 3, '2021-01-07 09:41:45', '2021-01-07 09:41:45');
INSERT INTO `pickup_addresses` VALUES (4, 'In sapiente enim quo fuga nam.', '241 Allison Mountains Apt. 660\nJulianaville, WV 24496-5497', '33.020425', '41.011416', 1, 3, '2021-01-07 09:41:45', '2021-01-07 09:41:45');
INSERT INTO `pickup_addresses` VALUES (5, 'Cupiditate facere consequatur sit voluptas.', '853 Casper Junction Apt. 639\nKoepphaven, CT 84224', '83.743001', '-42.936646', 0, 6, '2021-01-07 09:41:45', '2021-01-07 09:41:45');
INSERT INTO `pickup_addresses` VALUES (6, 'Sint assumenda magnam id tempore ipsum accusamus possimus assumenda.', '623 Lynn Island\nKuhlmanville, VT 04329', '-89.449373', '-152.62051', 1, 2, '2021-01-07 09:41:45', '2021-01-07 09:41:45');
INSERT INTO `pickup_addresses` VALUES (7, 'Animi et minima exercitationem animi et.', '79238 Considine Courts\nWest Eltonhaven, MO 73167-9429', '62.308966', '-167.137137', 1, 1, '2021-01-07 09:41:45', '2021-01-07 09:41:45');
INSERT INTO `pickup_addresses` VALUES (8, 'Molestiae quisquam magnam est sunt possimus.', '7996 Keely Valley\nEast Jaquelinemouth, CA 86731', '21.302188', '86.744253', 1, 2, '2021-01-07 09:41:45', '2021-01-07 09:41:45');
INSERT INTO `pickup_addresses` VALUES (9, 'Et voluptas exercitationem recusandae minus occaecati.', '9425 Myrtle Hollow Apt. 364\nHagenesmouth, KS 96354', '27.010845', '164.65571', 0, 4, '2021-01-07 09:41:45', '2021-01-07 09:41:45');
INSERT INTO `pickup_addresses` VALUES (10, 'Qui saepe incidunt qui cumque unde.', '28930 Nettie Course Suite 841\nWest Louisa, KY 46896-6821', '-35.469672', '-99.864108', 0, 1, '2021-01-07 09:41:45', '2021-01-07 09:41:45');
INSERT INTO `pickup_addresses` VALUES (11, 'Dicta doloremque dicta nam praesentium voluptatem dolore quia cumque.', '42576 Tillman Divide\nEliseomouth, NM 32200', '75.234745', '-117.161295', 1, 3, '2021-01-07 09:41:45', '2021-01-07 09:41:45');
INSERT INTO `pickup_addresses` VALUES (12, 'Reprehenderit et incidunt ab adipisci dignissimos hic ex.', '248 Weston Lakes Apt. 156\nNew Emilianoview, MD 88545', '-30.659283', '145.749583', 1, 2, '2021-01-07 09:41:45', '2021-01-07 09:41:45');
INSERT INTO `pickup_addresses` VALUES (13, 'Ullam reprehenderit vel necessitatibus magnam similique rerum optio.', '23048 Julia Isle\nAylachester, SD 17118-5269', '-37.515193', '121.162551', 0, 6, '2021-01-07 09:41:45', '2021-01-07 09:41:45');
INSERT INTO `pickup_addresses` VALUES (14, 'Quisquam qui tempora molestiae est fugit.', '3157 Lora Lock\nNew Forest, CA 04545-9793', '-15.610628', '-82.817567', 1, 2, '2021-01-07 09:41:45', '2021-01-07 09:41:45');
INSERT INTO `pickup_addresses` VALUES (15, 'Qui quasi at dolor ullam qui.', '812 Olson Parks Suite 810\nSchummshire, UT 33070-4012', '-77.843883', '166.36729', 0, 1, '2021-01-07 09:41:45', '2021-01-07 09:41:45');
INSERT INTO `pickup_addresses` VALUES (16, NULL, NULL, '-6.925444666999849', '107.63124883174896', 0, 3, '2021-01-10 04:04:40', '2021-01-18 04:37:34');

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of roles
-- ----------------------------
INSERT INTO `roles` VALUES (1, 'superadmin', NULL, NULL, NULL);
INSERT INTO `roles` VALUES (2, 'admin', NULL, NULL, NULL);
INSERT INTO `roles` VALUES (3, 'driver reg', NULL, NULL, NULL);
INSERT INTO `roles` VALUES (4, 'driver exp', NULL, NULL, NULL);
INSERT INTO `roles` VALUES (5, 'customer', NULL, NULL, NULL);

-- ----------------------------
-- Table structure for user_profiles
-- ----------------------------
DROP TABLE IF EXISTS `user_profiles`;
CREATE TABLE `user_profiles`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NULL DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `photo` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `created_at` timestamp(0) NULL DEFAULT current_timestamp(0),
  `updated_at` timestamp(0) NULL DEFAULT current_timestamp(0),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 25 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of user_profiles
-- ----------------------------
INSERT INTO `user_profiles` VALUES (1, NULL, '08736363663', 'bandung2', 'avatar.png', '2021-01-26 15:07:07', '2021-01-26 15:07:07');
INSERT INTO `user_profiles` VALUES (2, 7, '08736363665', 'bandung', 'avatar.png', '2021-01-26 15:08:15', '2021-01-26 15:08:15');
INSERT INTO `user_profiles` VALUES (3, 1, '0828377467444', 'Bandung', 'avatar.png', '2021-01-27 14:33:02', '2021-01-27 14:33:02');
INSERT INTO `user_profiles` VALUES (4, 2, '0828377467444', 'Bandung', 'avatar.png', '2021-01-27 14:33:41', '2021-01-27 14:33:41');
INSERT INTO `user_profiles` VALUES (7, 3, '0828377467444', 'Bandung', 'https://img2.pngdownload.id/20180402/ojw/kisspng-united-states-avatar-organization-information-user-avatar-5ac20804a62b58.8673620215226654766806.jpg', '2021-01-27 14:34:48', '2021-01-27 14:34:48');
INSERT INTO `user_profiles` VALUES (13, 10, '0828377467442', 'Bandung', 'https://img2.pngdownload.id/20180402/ojw/kisspng-united-states-avatar-organization-information-user-avatar-5ac20804a62b58.8673620215226654766806.jpg', '2021-02-03 11:54:30', '2021-02-03 11:54:30');
INSERT INTO `user_profiles` VALUES (22, 19, '0828377467442', 'Bandung', NULL, '2021-02-03 13:47:06', '2021-02-03 13:47:06');
INSERT INTO `user_profiles` VALUES (23, 20, '0828377467442', 'Bandung', NULL, '2021-02-03 13:50:53', '2021-02-03 13:50:53');
INSERT INTO `user_profiles` VALUES (24, 21, '08219363487234', 'safasdfasdf', NULL, '2021-02-06 14:33:01', '2021-02-06 14:33:01');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT current_timestamp(0),
  `updated_at` timestamp(0) NULL DEFAULT current_timestamp(0) ON UPDATE CURRENT_TIMESTAMP(0),
  `role_id` int UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `users_email_unique`(`email`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 22 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'Superadmin', 'superadmin@admin.com', '$2y$10$TVOs/MY/tAiSBNHbw6wDYOBiCMQMkOenEcbPLp3BFcda..rnxrdS.', '2021-01-27 14:34:03', '2021-01-27 14:35:15', 1);
INSERT INTO `users` VALUES (2, 'Admin', 'admin@admin.com', '$2y$10$7KxH54pxKuJOCkZT/Sa9Qe8iyRZogDSm6HzSPW6RVYFXpcxflC4dq', '2021-01-27 14:34:11', '2021-01-27 14:34:11', 2);
INSERT INTO `users` VALUES (3, 'Driver REG', 'driver@driver.com', '$2y$10$HFSFzo9r471Rokyxh8RtF.sxhSwJ9uwqtBKtwcCucSpOWqG/VXSci', '2021-01-27 14:34:48', '2021-01-27 14:36:06', 3);
INSERT INTO `users` VALUES (19, 'Driver REG 6', 'driver15@driver.com', '$2y$10$2uuqYtSPgMqH6WGB/EHFJ.63JSlCLijxbw5HTHAbYakOYnCAp9nOO', '2021-02-03 13:47:06', '2021-02-03 13:47:06', 3);
INSERT INTO `users` VALUES (20, 'Customer 1', 'customer@customer.com', '$2y$10$JQhbHfKKtztHx/rP7639ZOEvNPlCLmdcr7ZlYLSbXXsrmuixa7WWe', '2021-02-03 13:50:53', '2021-02-03 13:50:53', 5);
INSERT INTO `users` VALUES (21, 'customer2', 'customer2@gmail.com', '$2y$10$I9epMNXbNe1NnI6UdsKLz.t266WDANoM3rXj47VocgJsoPoGtBQlK', '2021-02-06 14:33:01', '2021-02-06 14:33:01', 5);

-- ----------------------------
-- Table structure for wallet
-- ----------------------------
DROP TABLE IF EXISTS `wallet`;
CREATE TABLE `wallet`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int NULL DEFAULT NULL,
  `begin_balance` double NULL DEFAULT NULL,
  `ending_balance` double NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT current_timestamp(0),
  `update_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of wallet
-- ----------------------------
INSERT INTO `wallet` VALUES (1, 3, 2000000, NULL, '2021-01-27 15:25:21', NULL);
INSERT INTO `wallet` VALUES (2, 4, 0, NULL, '2021-02-02 15:12:13', NULL);
INSERT INTO `wallet` VALUES (9, 19, 0, NULL, '2021-02-03 13:47:06', NULL);

-- ----------------------------
-- Table structure for wallet_transaction
-- ----------------------------
DROP TABLE IF EXISTS `wallet_transaction`;
CREATE TABLE `wallet_transaction`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `wallet_id` int NULL DEFAULT NULL,
  `debit` double(20, 0) NULL DEFAULT NULL,
  `credit` double(20, 0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT current_timestamp(0),
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of wallet_transaction
-- ----------------------------
INSERT INTO `wallet_transaction` VALUES (1, 1, -100000, NULL, '2021-01-31 14:43:28', NULL);
INSERT INTO `wallet_transaction` VALUES (2, 1, -200000, NULL, '2021-01-31 14:43:56', NULL);
INSERT INTO `wallet_transaction` VALUES (3, 1, NULL, 100000, '2021-01-31 14:44:08', NULL);
INSERT INTO `wallet_transaction` VALUES (6, 9, NULL, NULL, '2021-02-03 13:47:06', NULL);

-- ----------------------------
-- View structure for delivery_list
-- ----------------------------
DROP VIEW IF EXISTS `delivery_list`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `delivery_list` AS SELECT
	orders.id,
	orders.no_order,
	orders.order_detail_id,
	orders.delivery_fee,
	orders.pickup_status,
	orders.payment_id,
	orders.created_at,
	orders.pickup_at,
	order_details.`name`,
	order_details.price,
	order_details.description,
	order_details.capacity,
	order_details.weight,
	order_details.volume,
	order_details.photo,
	order_details.receiver,
	order_details.phone,
	payments.payment_method_id,
	payment_methods.method,
	order_statuses.`status`,
	delivery_addresses.address,
	delivery_addresses.description AS desc_add,
	delivery_addresses.latitude,
	delivery_addresses.longitude,
	users.`name` AS sender_name,
	user_profiles.phone AS sender_phone 
FROM
	orders
	INNER JOIN order_details ON orders.order_detail_id = order_details.id
	INNER JOIN payments ON orders.payment_id = payments.id
	INNER JOIN payment_methods ON payments.payment_method_id = payment_methods.id
	INNER JOIN order_statuses ON orders.order_statuses_id = order_statuses.id
	INNER JOIN delivery_addresses ON orders.delivery_address_id = delivery_addresses.id
	INNER JOIN users ON orders.user_id = users.id
	INNER JOIN user_profiles ON users.id = user_profiles.user_id 
WHERE
	orders.order_statuses_id = 3 
	AND orders.pickup_status = 1 ;

-- ----------------------------
-- View structure for history_orders
-- ----------------------------
DROP VIEW IF EXISTS `history_orders`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `history_orders` AS SELECT
	orders.id,
	orders.no_order,
	order_statuses.`status` AS order_status,
	orders.delivery_fee,
	users.`name`,
	orders.created_at AS order_date,
	payment_methods.method,
	payment_status.`status` AS payment_status 
FROM
	orders
	INNER JOIN order_details ON orders.order_detail_id = order_details.id
	INNER JOIN order_statuses ON orders.order_statuses_id = order_statuses.id
	INNER JOIN users ON orders.user_id = users.id
	INNER JOIN payments ON orders.payment_id = payments.id
	INNER JOIN payment_status ON payments.`status` = payment_status.id
	INNER JOIN payment_methods ON payments.payment_method_id = payment_methods.id 
WHERE
	orders.order_statuses_id = 5 
ORDER BY
	orders.id ;

-- ----------------------------
-- View structure for list_orders
-- ----------------------------
DROP VIEW IF EXISTS `list_orders`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `list_orders` AS SELECT
	orders.id, 
	orders.no_order, 
	order_statuses.`status` AS order_status, 
	orders.delivery_fee, 
	users.`name`, 
	orders.created_at AS order_date, 
	payment_methods.method, 
	payment_status.`status` AS payment_status
FROM
	orders
	INNER JOIN
	order_details
	ON 
		orders.order_detail_id = order_details.id
	INNER JOIN
	order_statuses
	ON 
		orders.order_statuses_id = order_statuses.id
	INNER JOIN
	users
	ON 
		orders.user_id = users.id
	INNER JOIN
	payments
	ON 
		orders.payment_id = payments.id
	INNER JOIN
	payment_status
	ON 
		payments.`status` = payment_status.id
	INNER JOIN
	payment_methods
	ON 
		payments.payment_method_id = payment_methods.id
WHERE
	orders.order_statuses_id < 5
ORDER BY
	orders.id ;

-- ----------------------------
-- View structure for order_detail
-- ----------------------------
DROP VIEW IF EXISTS `order_detail`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `order_detail` AS SELECT
	orders.id,
	order_statuses.`status` AS order_status,
	orders.no_order,
	users.`name`,
	user_profiles.phone,
	orders.created_at,
	delivery_addresses.address,
	orders.delivery_fee,
	payment_methods.method,
	payment_status.`status` AS payment_status 
FROM
	orders
	INNER JOIN order_statuses ON orders.order_statuses_id = order_statuses.id
	INNER JOIN users ON orders.user_id = users.id
	INNER JOIN user_profiles ON users.id = user_profiles.user_id
	INNER JOIN delivery_addresses ON orders.delivery_address_id = delivery_addresses.id
	INNER JOIN payments ON orders.payment_id = payments.id
	INNER JOIN payment_status ON payments.`status` = payment_status.id
	INNER JOIN payment_methods ON payments.payment_method_id = payment_methods.id ;

-- ----------------------------
-- View structure for pickup_list
-- ----------------------------
DROP VIEW IF EXISTS `pickup_list`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `pickup_list` AS SELECT
	orders.id,
	orders.no_order,
	orders.order_detail_id,
	orders.delivery_fee,
	orders.pickup_status,
	orders.payment_id,
	orders.created_at,
	order_details.`name`,
	order_details.price,
	order_details.description,
	order_details.capacity,
	order_details.weight,
	order_details.volume,
	order_details.photo,
	order_details.receiver,
	order_details.phone,
	payments.payment_method_id,
	payment_methods.method,
	order_statuses.`status`,
	delivery_addresses.address,
	delivery_addresses.description AS desc_add,
	delivery_addresses.latitude,
	delivery_addresses.longitude,
	users.`name` AS sender_name,
	user_profiles.phone AS sender_phone 
FROM
	orders
	INNER JOIN order_details ON orders.order_detail_id = order_details.id
	INNER JOIN payments ON orders.payment_id = payments.id
	INNER JOIN payment_methods ON payments.payment_method_id = payment_methods.id
	INNER JOIN order_statuses ON orders.order_statuses_id = order_statuses.id
	INNER JOIN delivery_addresses ON orders.delivery_address_id = delivery_addresses.id
	INNER JOIN users ON orders.user_id = users.id
	INNER JOIN user_profiles ON users.id = user_profiles.user_id 
WHERE
	orders.order_statuses_id < 3 
	AND orders.pickup_status NOT LIKE 1 ;

-- ----------------------------
-- View structure for user_list
-- ----------------------------
DROP VIEW IF EXISTS `user_list`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `user_list` AS SELECT
	users.`name`,
	users.email,
	users.created_at,
	user_profiles.phone,
	user_profiles.address,
	user_profiles.photo,
	roles.`name` as role 
FROM
	users
	INNER JOIN user_profiles ON users.id = user_profiles.user_id
	INNER JOIN roles ON users.role_id = roles.id ;

SET FOREIGN_KEY_CHECKS = 1;
