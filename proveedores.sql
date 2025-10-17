/*
Navicat MySQL Data Transfer

Source Server         : localhost_3309
Source Server Version : 50723
Source Host           : localhost:3309
Source Database       : sistema_mvc

Target Server Type    : MYSQL
Target Server Version : 50723
File Encoding         : 65001

Date: 2025-10-15 19:10:31
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `proveedores`
-- ----------------------------
DROP TABLE IF EXISTS `proveedores`;
CREATE TABLE `proveedores` (
  `proveedoresid` bigint(20) NOT NULL AUTO_INCREMENT,
  `claveproveedor` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombreproveedor` varchar(245) COLLATE utf8mb4_unicode_ci NOT NULL,
  `direccion` varchar(145) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `exterior` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `interior` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `colonia` varchar(145) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ciudad` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`proveedoresid`),
  UNIQUE KEY `claveproveedor` (`claveproveedor`),
  KEY `idx_claveproveedor` (`claveproveedor`),
  KEY `idx_nombreproveedor` (`nombreproveedor`),
  KEY `idx_ciudad` (`ciudad`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of proveedores
-- ----------------------------
INSERT INTO `proveedores` VALUES ('1', 'PRO006', 'Juana Pérez García', 'Av. Insurgentes', '123', 'A', 'Centro', 'JUAREZ', 'Chihuahua', '2025-10-02 11:09:08', '2025-10-02 11:10:14');
INSERT INTO `proveedores` VALUES ('2', 'PRO007', 'Marío López Rodríguez', 'Calle Reforma', '456', null, 'Norte', 'Ciudad Juárez', 'Chihuahua', '2025-10-02 11:09:08', '2025-10-02 11:09:08');
INSERT INTO `proveedores` VALUES ('3', 'PRO008', 'Carla Hernández Martínez', 'Blvd. Tecnológico', '789', 'B', 'Industrial', 'Ciudad Juárez', 'Chihuahua', '2025-10-02 11:09:08', '2025-10-02 11:09:08');
INSERT INTO `proveedores` VALUES ('4', 'PRO009', 'Ana Maria Sánchez Torres', 'Av. Lincoln', '321', null, 'Campestre', 'Ciudad Juárez', 'Chihuahua', '2025-10-02 11:09:08', '2025-10-02 11:09:08');
INSERT INTO `proveedores` VALUES ('5', 'PRO0010', 'Angel Roberti Payan Díaz', 'Calle Juárez', '654', 'C', 'Zona Dorada', 'Ciudad Juárez', 'Chihuahua', '2025-10-02 11:09:08', '2025-10-02 11:09:08');
INSERT INTO `proveedores` VALUES ('6', 'PROV001', 'CT INTERNACIONAL', 'SANTOS DUMONT', '1252', '', 'DEL MARQUEZ', 'JUAREZ', 'CHIHUAHUA', '2025-10-02 11:09:59', '2025-10-02 11:09:59');
