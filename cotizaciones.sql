/*
Navicat MySQL Data Transfer

Source Server         : localhost_3309
Source Server Version : 50723
Source Host           : localhost:3309
Source Database       : sistema_mvc

Target Server Type    : MYSQL
Target Server Version : 50723
File Encoding         : 65001

Date: 2025-10-16 08:30:37
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `cotizaciones`
-- ----------------------------
DROP TABLE IF EXISTS `cotizaciones`;
CREATE TABLE `cotizaciones` (
  `cotizacionesid` bigint(20) NOT NULL AUTO_INCREMENT,
  `clientesid` bigint(20) NOT NULL,
  `folio` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha` date NOT NULL,
  `condiciones` enum('Contado','Credito') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Contado',
  `dias_vigencia` int(11) NOT NULL DEFAULT '7',
  `contacto` varchar(245) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dias_entrega` int(11) NOT NULL DEFAULT '7',
  `subtotal` decimal(15,2) NOT NULL DEFAULT '0.00',
  `iva` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` enum('Pendiente','Aprobada','Rechazada','Vencida') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pendiente',
  `observaciones` text COLLATE utf8mb4_unicode_ci,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`cotizacionesid`),
  UNIQUE KEY `folio` (`folio`),
  KEY `idx_clientesid` (`clientesid`),
  KEY `idx_folio` (`folio`),
  KEY `idx_fecha` (`fecha`),
  KEY `idx_status` (`status`),
  CONSTRAINT `cotizaciones_ibfk_1` FOREIGN KEY (`clientesid`) REFERENCES `clientes` (`clientesid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of cotizaciones
-- ----------------------------
INSERT INTO `cotizaciones` VALUES ('1', '1', 'COT-2025-0001', '2025-01-15', 'Credito', '30', 'Juan Pérez', '7', '10000.00', '1600.00', '11600.00', 'Pendiente', null, '2025-10-16 08:12:44', '2025-10-16 08:12:44');
DROP TRIGGER IF EXISTS `before_insert_cotizacion`;
DELIMITER ;;
CREATE TRIGGER `before_insert_cotizacion` BEFORE INSERT ON `cotizaciones` FOR EACH ROW BEGIN
    DECLARE siguiente_numero INT;
    DECLARE nuevo_folio VARCHAR(50);
    
    -- Obtener el último número de folio
    SELECT COALESCE(MAX(CAST(SUBSTRING(folio, 5) AS UNSIGNED)), 0) + 1 
    INTO siguiente_numero
    FROM cotizaciones
    WHERE folio LIKE CONCAT('COT-', YEAR(CURDATE()), '-%');
    
    -- Generar nuevo folio: COT-2025-0001
    SET nuevo_folio = CONCAT('COT-', YEAR(CURDATE()), '-', LPAD(siguiente_numero, 4, '0'));
    
    -- Asignar el folio si está vacío
    IF NEW.folio IS NULL OR NEW.folio = '' THEN
        SET NEW.folio = nuevo_folio;
    END IF;
END
;;
DELIMITER ;
