/*
Navicat MySQL Data Transfer

Source Server         : localhost_3309
Source Server Version : 50723
Source Host           : localhost:3309
Source Database       : sistema_mvc

Target Server Type    : MYSQL
Target Server Version : 50723
File Encoding         : 65001

Date: 2025-10-16 08:30:45
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `partidas_cotizaciones`
-- ----------------------------
DROP TABLE IF EXISTS `partidas_cotizaciones`;
CREATE TABLE `partidas_cotizaciones` (
  `partidasid` bigint(20) NOT NULL AUTO_INCREMENT,
  `cotizacionesid` bigint(20) NOT NULL,
  `partida_numero` int(11) NOT NULL,
  `codigoarticulo` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(245) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cantidad` decimal(15,2) NOT NULL,
  `precio_unitario` decimal(15,2) NOT NULL,
  `precio_total` decimal(15,2) NOT NULL,
  `dias_entrega` int(11) NOT NULL DEFAULT '7',
  `unidad_medida` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PZA',
  `porcentaje_iva` decimal(5,2) NOT NULL DEFAULT '16.00',
  `monto_iva` decimal(15,2) NOT NULL DEFAULT '0.00',
  `subtotal_partida` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_partida` decimal(15,2) NOT NULL DEFAULT '0.00',
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`partidasid`),
  KEY `idx_cotizacionesid` (`cotizacionesid`),
  KEY `idx_codigoarticulo` (`codigoarticulo`),
  KEY `idx_partida_numero` (`partida_numero`),
  CONSTRAINT `partidas_cotizaciones_ibfk_1` FOREIGN KEY (`cotizacionesid`) REFERENCES `cotizaciones` (`cotizacionesid`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of partidas_cotizaciones
-- ----------------------------
INSERT INTO `partidas_cotizaciones` VALUES ('1', '1', '1', 'ART001', 'Laptop Dell Inspiron 15', '5.00', '2000.00', '10000.00', '7', 'PZA', '16.00', '1600.00', '10000.00', '11600.00', '2025-10-16 08:12:44');
