/*
Navicat MySQL Data Transfer

Source Server         : localhost_3309
Source Server Version : 50723
Source Host           : localhost:3309
Source Database       : sistema_mvc

Target Server Type    : MYSQL
Target Server Version : 50723
File Encoding         : 65001

Date: 2025-10-15 19:09:31
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `clientes`
-- ----------------------------
DROP TABLE IF EXISTS `clientes`;
CREATE TABLE `clientes` (
  `clientesid` bigint(20) NOT NULL AUTO_INCREMENT,
  `clavecliente` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombrecliente` varchar(245) COLLATE utf8mb4_unicode_ci NOT NULL,
  `direccion` varchar(145) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `exterior` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `interior` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `colonia` varchar(145) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ciudad` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`clientesid`),
  UNIQUE KEY `clavecliente` (`clavecliente`),
  KEY `idx_clavecliente` (`clavecliente`),
  KEY `idx_nombrecliente` (`nombrecliente`),
  KEY `idx_ciudad` (`ciudad`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of clientes
-- ----------------------------
INSERT INTO `clientes` VALUES ('1', 'CLI001', 'Juan Pérez García', 'Av. Insurgentes', '123', 'A', 'Centro', 'Ciudad Juárez', 'Chihuahua', '2025-10-02 09:11:05', '2025-10-02 09:11:05');
INSERT INTO `clientes` VALUES ('2', 'CLI002', 'María López Rodríguez', 'Calle Reforma', '456', null, 'Norte', 'Ciudad Juárez', 'Chihuahua', '2025-10-02 09:11:05', '2025-10-02 09:11:05');
INSERT INTO `clientes` VALUES ('3', 'CLI003', 'Carlos Hernández Martínez', 'Blvd. Tecnológico', '789', 'B', 'Industrial', 'Ciudad Juárez', 'Chihuahua', '2025-10-02 09:11:05', '2025-10-02 09:11:05');
INSERT INTO `clientes` VALUES ('4', 'CLI004', 'Ana Sánchez Torres', 'Av. Lincoln', '321', null, 'Campestre', 'Ciudad Juárez', 'Chihuahua', '2025-10-02 09:11:05', '2025-10-02 09:11:05');
INSERT INTO `clientes` VALUES ('5', 'CLI005', 'Roberto González Díaz', 'Calle Juárez', '654', 'C', 'Zona Dorada', 'Ciudad Juárez', 'Chihuahua', '2025-10-02 09:11:05', '2025-10-02 09:11:05');
INSERT INTO `clientes` VALUES ('6', 'CLI006', 'Juana Pérez García', 'Av. Insurgentes', '123', 'A', 'Centro', 'Ciudad Juárez', 'Chihuahua', '2025-10-02 09:21:50', '2025-10-02 09:21:50');
INSERT INTO `clientes` VALUES ('7', 'CLI007', 'Marío López Rodríguez', 'Calle Reforma', '456', null, 'Norte', 'Ciudad Juárez', 'Chihuahua', '2025-10-02 09:21:50', '2025-10-02 09:21:50');
INSERT INTO `clientes` VALUES ('8', 'CLI008', 'Carla Hernández Martínez', 'Blvd. Tecnológico', '789', 'B', 'Industrial', 'Ciudad Juárez', 'Chihuahua', '2025-10-02 09:21:50', '2025-10-02 09:21:50');
INSERT INTO `clientes` VALUES ('9', 'CLI009', 'Ana Maria Sánchez Torres', 'Av. Lincoln', '321', null, 'Campestre', 'Ciudad Juárez', 'Chihuahua', '2025-10-02 09:21:50', '2025-10-02 09:21:50');
INSERT INTO `clientes` VALUES ('10', 'CLI0010', 'Angel Roberti Payan Díaz', 'Calle Juárez', '654', 'C', 'Zona Dorada', 'Ciudad Juárez', 'Chihuahua', '2025-10-02 09:21:50', '2025-10-02 09:21:50');
INSERT INTO `clientes` VALUES ('11', 'CLI0024', 'Luis Enrique Solorzano Tena', 'Uramex', '8022', '', 'Infonavit Fidelk velazquez', 'Juarez', 'Chihahua', '2025-10-02 10:10:29', '2025-10-02 10:10:29');
INSERT INTO `clientes` VALUES ('12', 'CLI0009', '23', '23', '23', '23', '23', '23', '23', '2025-10-02 10:10:48', '2025-10-02 10:10:48');
INSERT INTO `clientes` VALUES ('13', 'gamez', 'JOSE DE LALUZ GAMEZ BELTRAN', 'DAMASO ALONSO', '', '', 'ING. CASAS GRANDES', 'JUAREZ', 'CHIHUAHUA', '2025-10-15 17:00:24', '2025-10-15 17:00:24');
