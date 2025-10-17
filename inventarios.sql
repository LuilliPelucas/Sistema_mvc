/*
Navicat MySQL Data Transfer

Source Server         : localhost_3309
Source Server Version : 50723
Source Host           : localhost:3309
Source Database       : sistema_mvc

Target Server Type    : MYSQL
Target Server Version : 50723
File Encoding         : 65001

Date: 2025-10-15 19:10:03
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `inventarios`
-- ----------------------------
DROP TABLE IF EXISTS `inventarios`;
CREATE TABLE `inventarios` (
  `inventariosid` bigint(20) NOT NULL AUTO_INCREMENT,
  `codigoarticulo` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(245) COLLATE utf8mb4_unicode_ci NOT NULL,
  `existencia` double NOT NULL DEFAULT '0',
  `minimo` double NOT NULL DEFAULT '0',
  `maximo` double NOT NULL DEFAULT '0',
  `unidad` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activo` int(11) NOT NULL DEFAULT '1',
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`inventariosid`),
  UNIQUE KEY `codigoarticulo` (`codigoarticulo`),
  KEY `idx_codigoarticulo` (`codigoarticulo`),
  KEY `idx_descripcion` (`descripcion`),
  KEY `idx_activo` (`activo`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of inventarios
-- ----------------------------
INSERT INTO `inventarios` VALUES ('1', 'ART001', 'Laptop Dell Inspiron 15 \" all in one', '25', '10', '50', 'PZA', '1', '2025-10-02 09:11:05', '2025-10-02 09:18:10');
INSERT INTO `inventarios` VALUES ('2', 'ART002', 'Mouse Inalámbrico Logitech', '150', '50', '200', 'PZA', '1', '2025-10-02 09:11:05', '2025-10-02 09:11:05');
INSERT INTO `inventarios` VALUES ('3', 'ART003', 'Teclado Mecánico RGB', '80', '30', '100', 'PZA', '1', '2025-10-02 09:11:05', '2025-10-02 09:11:05');
INSERT INTO `inventarios` VALUES ('4', 'ART004', 'Monitor LED 24 pulgadas', '45', '20', '80', 'PZA', '1', '2025-10-02 09:11:05', '2025-10-02 09:11:05');
INSERT INTO `inventarios` VALUES ('5', 'ART005', 'Cable HDMI 2 metros', '200', '100', '300', 'PZA', '1', '2025-10-02 09:11:05', '2025-10-02 09:11:05');
INSERT INTO `inventarios` VALUES ('6', 'ART006', 'Memoria USB 32GB', '55', '50', '150', 'PZA', '1', '2025-10-02 09:11:05', '2025-10-02 09:19:14');
INSERT INTO `inventarios` VALUES ('7', 'ART007', 'Disco Duro Externo 1TB', '60', '25', '100', 'PZA', '1', '2025-10-02 09:11:05', '2025-10-02 09:11:05');
INSERT INTO `inventarios` VALUES ('8', 'ART008', 'Webcam HD 1080p', '35', '15', '60', 'PZA', '1', '2025-10-02 09:11:05', '2025-10-02 09:11:05');
INSERT INTO `inventarios` VALUES ('9', 'ART009', 'Audífonos Bluetooth', '90', '40', '120', 'PZA', '1', '2025-10-02 09:11:05', '2025-10-02 09:11:05');
INSERT INTO `inventarios` VALUES ('10', 'ART010', 'Cargador Universal Laptop', '55', '30', '80', 'PZA', '1', '2025-10-02 09:11:05', '2025-10-02 09:11:05');
INSERT INTO `inventarios` VALUES ('11', 'ART011', 'Laptop Dell Inspiron 14', '25', '10', '50', 'PZA', '1', '2025-10-02 09:21:50', '2025-10-02 09:21:50');
INSERT INTO `inventarios` VALUES ('12', 'ART012', 'Mouse Inalámbrico Sony', '150', '50', '200', 'PZA', '1', '2025-10-02 09:21:50', '2025-10-02 09:21:50');
INSERT INTO `inventarios` VALUES ('13', 'ART013', 'Teclado Gamer RGB', '80', '30', '100', 'PZA', '1', '2025-10-02 09:21:50', '2025-10-02 09:21:50');
INSERT INTO `inventarios` VALUES ('14', 'ART014', 'Monitor HP LED 27 pulgadas', '45', '20', '80', 'PZA', '1', '2025-10-02 09:21:50', '2025-10-02 09:21:50');
INSERT INTO `inventarios` VALUES ('15', 'ART015', 'Cable HDMI 2.5 metros', '200', '100', '300', 'PZA', '1', '2025-10-02 09:21:50', '2025-10-02 09:21:50');
INSERT INTO `inventarios` VALUES ('16', 'ART016', 'Memoria USB 64GB sandisk', '5', '50', '150', 'PZA', '1', '2025-10-02 09:21:50', '2025-10-02 09:37:04');
INSERT INTO `inventarios` VALUES ('17', 'ART017', 'Disco Duro Externo 2TB', '60', '25', '100', 'PZA', '1', '2025-10-02 09:21:50', '2025-10-02 09:21:50');
INSERT INTO `inventarios` VALUES ('18', 'ART018', 'Webcam HD 1080p externa', '35', '15', '60', 'PZA', '1', '2025-10-02 09:21:50', '2025-10-02 09:21:50');
INSERT INTO `inventarios` VALUES ('19', 'ART019', 'Audífonos Bluetooth marca JOVY', '90', '40', '120', 'PZA', '1', '2025-10-02 09:21:50', '2025-10-02 09:21:50');
INSERT INTO `inventarios` VALUES ('20', 'ART020', 'Cargador Universal Laptop Generico para laptop Dell', '55', '30', '80', 'PZA', '1', '2025-10-02 09:21:50', '2025-10-02 09:21:50');
INSERT INTO `inventarios` VALUES ('21', '72634', 'sind escripcion generico', '150', '8', '16', 'PZA', '1', '2025-10-02 09:36:21', '2025-10-02 09:36:42');
