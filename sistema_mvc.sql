/*
Navicat MySQL Data Transfer

Source Server         : localhost_3309
Source Server Version : 50723
Source Host           : localhost:3309
Source Database       : sistema_mvc

Target Server Type    : MYSQL
Target Server Version : 50723
File Encoding         : 65001

Date: 2025-10-17 09:19:28
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
  `fecha_creacion` date NOT NULL DEFAULT '1990-01-01',
  `fecha_actualizacion` date NOT NULL DEFAULT '1990-01-01',
  PRIMARY KEY (`clientesid`),
  UNIQUE KEY `clavecliente` (`clavecliente`),
  KEY `idx_clavecliente` (`clavecliente`),
  KEY `idx_nombrecliente` (`nombrecliente`),
  KEY `idx_ciudad` (`ciudad`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of clientes
-- ----------------------------
INSERT INTO `clientes` VALUES ('1', 'CLI001', 'Juan Pérez García', 'Av. Insurgentes', '123', 'A', 'Centro', 'Ciudad Juárez', 'Chihuahua', '2025-10-02', '2025-10-15');
INSERT INTO `clientes` VALUES ('2', 'CLI002', 'María López Rodríguez', 'Calle Reforma', '456', null, 'Norte', 'Ciudad Juárez', 'Chihuahua', '2025-10-02', '2025-10-02');
INSERT INTO `clientes` VALUES ('3', 'CLI003', 'Carlos Hernández Martínez', 'Blvd. Tecnológico', '789', 'B', 'Industrial', 'Ciudad Juárez', 'Chihuahua', '2025-10-02', '2025-10-02');
INSERT INTO `clientes` VALUES ('4', 'CLI004', 'Ana Sánchez Torres', 'Av. Lincoln', '321', null, 'Campestre', 'Ciudad Juárez', 'Chihuahua', '2025-10-02', '2025-10-02');
INSERT INTO `clientes` VALUES ('5', 'CLI005', 'Roberto González Díaz', 'Calle Juárez', '654', 'C', 'Zona Dorada', 'Ciudad Juárez', 'Chihuahua', '2025-10-02', '2025-10-02');
INSERT INTO `clientes` VALUES ('6', 'CLI006', 'Juana Pérez García', 'Av. Insurgentes', '123', 'A', 'Centro', 'Ciudad Juárez', 'Chihuahua', '2025-10-02', '2025-10-02');
INSERT INTO `clientes` VALUES ('7', 'CLI007', 'Marío López Rodríguez', 'Calle Reforma', '456', null, 'Norte', 'Ciudad Juárez', 'Chihuahua', '2025-10-02', '2025-10-02');
INSERT INTO `clientes` VALUES ('8', 'CLI008', 'Carla Hernández Martínez', 'Blvd. Tecnológico', '789', 'B', 'Industrial', 'Ciudad Juárez', 'Chihuahua', '2025-10-02', '2025-10-02');
INSERT INTO `clientes` VALUES ('9', 'CLI009', 'Ana Maria Sánchez Torres', 'Av. Lincoln', '321', null, 'Campestre', 'Ciudad Juárez', 'Chihuahua', '2025-10-02', '2025-10-02');
INSERT INTO `clientes` VALUES ('10', 'CLI0010', 'Angel Roberti Payan Díaz', 'Calle Juárez', '654', 'C', 'Zona Dorada', 'Ciudad Juárez', 'Chihuahua', '2025-10-02', '2025-10-02');
INSERT INTO `clientes` VALUES ('11', 'CLI0024', 'Luis Enrique Solorzano Tena', 'Uramex', '8022', '', 'Infonavit Fidelk velazquez', 'Juarez', 'Chihahua', '2025-10-02', '2025-10-02');
INSERT INTO `clientes` VALUES ('12', 'CLI0009', '23', '23', '23', '23', '23', '23', '23', '2025-10-02', '2025-10-02');
INSERT INTO `clientes` VALUES ('13', 'gamez', 'JOSE DE LALUZ GAMEZ BELTRAN', 'DAMASO ALONSO', '', '', 'ING. CASAS GRANDES', 'JUAREZ', 'CHIHUAHUA', '2025-10-15', '2025-10-15');

-- ----------------------------
-- Table structure for `configuracion_empresa`
-- ----------------------------
DROP TABLE IF EXISTS `configuracion_empresa`;
CREATE TABLE `configuracion_empresa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_empresa` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rfc` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `direccion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `colonia` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ciudad` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `codigo_postal` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sitio_web` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'public/images/logoenlaza.jpg',
  `fecha_actualizacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of configuracion_empresa
-- ----------------------------
INSERT INTO `configuracion_empresa` VALUES ('1', 'Luis Enrique Solorzano Tena', 'SOTL750313DE8', 'Walter Gropius  2133', 'HORIZONTES DEL SUR', 'Ciudad Juárez', 'Chihuahua', '32575', '(656) 381 8374', 'enlazasistemas@gmail.com', 'www.enlazasistemas.com', 'public/images/logoenlaza.jpg', '2025-10-16 14:55:51');

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of cotizaciones
-- ----------------------------
INSERT INTO `cotizaciones` VALUES ('1', '1', 'COT-2025-0001', '2025-01-15', 'Credito', '30', 'Juan Pérez', '7', '10000.00', '1600.00', '11600.00', 'Pendiente', null, '2025-10-16 08:12:44', '2025-10-16 08:12:44');
INSERT INTO `cotizaciones` VALUES ('2', '4', 'COT-2025-2026', '2025-10-16', 'Contado', '7', 'Juan perez', '7', '15.00', '2.40', '17.40', 'Pendiente', 'Observaciones a nivel general', '2025-10-16 11:13:44', '2025-10-16 11:13:44');
INSERT INTO `cotizaciones` VALUES ('4', '3', 'COT-2025-2027', '2025-10-16', 'Contado', '7', 'Brianda Salazar', '7', '323.00', '51.68', '374.68', 'Pendiente', 'wew ee w', '2025-10-16 12:39:47', '2025-10-16 12:39:47');

-- ----------------------------
-- Table structure for `inventarios`
-- ----------------------------
DROP TABLE IF EXISTS `inventarios`;
CREATE TABLE `inventarios` (
  `inventariosid` bigint(20) NOT NULL AUTO_INCREMENT,
  `codigoarticulo` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(245) COLLATE utf8mb4_unicode_ci NOT NULL,
  `existencia` double NOT NULL DEFAULT '0',
  `precio_costo` double(15,2) NOT NULL DEFAULT '0.00',
  `precio_venta` double(15,2) NOT NULL DEFAULT '0.00',
  `moneda` enum('PESOS','DOLARES') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PESOS',
  `margen_utilidad` double(5,2) DEFAULT NULL COMMENT 'Porcentaje de utilidad',
  `minimo` double NOT NULL DEFAULT '0',
  `maximo` double NOT NULL DEFAULT '0',
  `unidad` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activo` int(11) NOT NULL DEFAULT '1',
  `fecha_creacion` date NOT NULL DEFAULT '1990-01-01',
  `fecha_actualizacion` date NOT NULL DEFAULT '1990-01-01',
  PRIMARY KEY (`inventariosid`),
  UNIQUE KEY `codigoarticulo` (`codigoarticulo`),
  KEY `idx_codigoarticulo` (`codigoarticulo`),
  KEY `idx_descripcion` (`descripcion`),
  KEY `idx_activo` (`activo`),
  KEY `idx_precio_venta` (`precio_venta`),
  KEY `idx_moneda` (`moneda`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of inventarios
-- ----------------------------
INSERT INTO `inventarios` VALUES ('1', 'ART001', 'Laptop Dell Inspiron 15 \" all in one', '25', '0.00', '0.00', 'PESOS', null, '10', '50', 'PZA', '1', '2025-10-02', '2025-10-02');
INSERT INTO `inventarios` VALUES ('2', 'ART002', 'Mouse Inalámbrico Logitech', '150', '0.00', '0.00', 'PESOS', null, '50', '200', 'PZA', '1', '2025-10-02', '2025-10-02');
INSERT INTO `inventarios` VALUES ('3', 'ART003', 'Teclado Mecánico RGB', '80', '0.00', '0.00', 'PESOS', null, '30', '100', 'PZA', '1', '2025-10-02', '2025-10-02');
INSERT INTO `inventarios` VALUES ('4', 'ART004', 'Monitor LED 24 pulgadas', '45', '0.00', '0.00', 'PESOS', null, '20', '80', 'PZA', '1', '2025-10-02', '2025-10-02');
INSERT INTO `inventarios` VALUES ('5', 'ART005', 'Cable HDMI 2 metros', '200', '0.00', '0.00', 'PESOS', null, '100', '300', 'PZA', '1', '2025-10-02', '2025-10-02');
INSERT INTO `inventarios` VALUES ('6', 'ART006', 'Memoria USB 32GB', '55', '0.00', '0.00', 'PESOS', null, '50', '150', 'PZA', '1', '2025-10-02', '2025-10-02');
INSERT INTO `inventarios` VALUES ('7', 'ART007', 'Disco Duro Externo 1TB', '60', '0.00', '0.00', 'PESOS', null, '25', '100', 'PZA', '1', '2025-10-02', '2025-10-02');
INSERT INTO `inventarios` VALUES ('8', 'ART008', 'Webcam HD 1080p', '35', '0.00', '0.00', 'PESOS', null, '15', '60', 'PZA', '1', '2025-10-02', '2025-10-02');
INSERT INTO `inventarios` VALUES ('9', 'ART009', 'Audífonos Bluetooth', '90', '15.00', '35.00', 'PESOS', null, '40', '120', 'PZA', '1', '2025-10-02', '2025-10-02');
INSERT INTO `inventarios` VALUES ('10', 'ART010', 'Cargador Universal Laptop', '55', '0.00', '0.00', 'PESOS', null, '30', '80', 'PZA', '1', '2025-10-02', '2025-10-02');
INSERT INTO `inventarios` VALUES ('11', 'ART011', 'Laptop Dell Inspiron 14', '25', '0.00', '0.00', 'PESOS', null, '10', '50', 'PZA', '1', '2025-10-02', '2025-10-02');
INSERT INTO `inventarios` VALUES ('12', 'ART012', 'Mouse Inalámbrico Sony', '150', '0.00', '0.00', 'PESOS', null, '50', '200', 'PZA', '1', '2025-10-02', '2025-10-02');
INSERT INTO `inventarios` VALUES ('13', 'ART013', 'Teclado Gamer RGB', '80', '0.00', '0.00', 'PESOS', null, '30', '100', 'PZA', '1', '2025-10-02', '2025-10-02');
INSERT INTO `inventarios` VALUES ('14', 'ART014', 'Monitor HP LED 27 pulgadas', '45', '0.00', '0.00', 'PESOS', null, '20', '80', 'PZA', '1', '2025-10-02', '2025-10-02');
INSERT INTO `inventarios` VALUES ('15', 'ART015', 'Cable HDMI 2.5 metros', '200', '0.00', '0.00', 'PESOS', null, '100', '300', 'PZA', '1', '2025-10-02', '2025-10-02');
INSERT INTO `inventarios` VALUES ('16', 'ART016', 'Memoria USB 64GB sandisk', '5', '0.00', '0.00', 'PESOS', null, '50', '150', 'PZA', '1', '2025-10-02', '2025-10-02');
INSERT INTO `inventarios` VALUES ('17', 'ART017', 'Disco Duro Externo 2TB', '60', '0.00', '0.00', 'PESOS', null, '25', '100', 'PZA', '1', '2025-10-02', '2025-10-02');
INSERT INTO `inventarios` VALUES ('18', 'ART018', 'Webcam HD 1080p externa', '35', '0.00', '0.00', 'PESOS', null, '15', '60', 'PZA', '1', '2025-10-02', '2025-10-02');
INSERT INTO `inventarios` VALUES ('19', 'ART019', 'Audífonos Bluetooth marca JOVY', '90', '0.00', '0.00', 'PESOS', null, '40', '120', 'PZA', '1', '2025-10-02', '2025-10-02');
INSERT INTO `inventarios` VALUES ('20', 'ART020', 'Cargador Universal Laptop Generico para laptop Dell', '55', '0.00', '0.00', 'PESOS', null, '30', '80', 'PZA', '1', '2025-10-02', '2025-10-02');
INSERT INTO `inventarios` VALUES ('21', '72634', 'sind escripcion generico', '150', '0.00', '0.00', 'PESOS', null, '8', '16', 'PZA', '1', '2025-10-02', '2025-10-02');

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
  `observaciones_partida` text COLLATE utf8mb4_unicode_ci,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`partidasid`),
  KEY `idx_cotizacionesid` (`cotizacionesid`),
  KEY `idx_codigoarticulo` (`codigoarticulo`),
  KEY `idx_partida_numero` (`partida_numero`),
  CONSTRAINT `partidas_cotizaciones_ibfk_1` FOREIGN KEY (`cotizacionesid`) REFERENCES `cotizaciones` (`cotizacionesid`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of partidas_cotizaciones
-- ----------------------------
INSERT INTO `partidas_cotizaciones` VALUES ('1', '1', '1', 'ART001', 'Laptop Dell Inspiron 15', '5.00', '2000.00', '10000.00', '7', 'PZA', '16.00', '1600.00', '10000.00', '11600.00', null, '2025-10-16 08:12:44');
INSERT INTO `partidas_cotizaciones` VALUES ('2', '2', '1', 'ART009', 'paleta de colores', '1.00', '15.00', '15.00', '7', 'PZA', '16.00', '2.40', '15.00', '17.40', null, '2025-10-16 11:13:44');
INSERT INTO `partidas_cotizaciones` VALUES ('3', '4', '1', 'art002', 'Mouse Inalámbrico Logitech', '1.00', '323.00', '323.00', '7', 'PZA', '16.00', '51.68', '323.00', '374.68', null, '2025-10-16 12:39:47');

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
  `fecha_creacion` date NOT NULL DEFAULT '1990-01-01',
  `fecha_actualizacion` date NOT NULL DEFAULT '1990-01-01',
  PRIMARY KEY (`proveedoresid`),
  UNIQUE KEY `claveproveedor` (`claveproveedor`),
  KEY `idx_claveproveedor` (`claveproveedor`),
  KEY `idx_nombreproveedor` (`nombreproveedor`),
  KEY `idx_ciudad` (`ciudad`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of proveedores
-- ----------------------------
INSERT INTO `proveedores` VALUES ('1', 'PRO006', 'Juana Pérez García', 'Av. Insurgentes', '123', 'A', 'Centro', 'JUAREZ', 'Chihuahua', '2025-10-02', '2025-10-02');
INSERT INTO `proveedores` VALUES ('2', 'PRO007', 'Marío López Rodríguez', 'Calle Reforma', '456', null, 'Norte', 'Ciudad Juárez', 'Chihuahua', '2025-10-02', '2025-10-02');
INSERT INTO `proveedores` VALUES ('3', 'PRO008', 'Carla Hernández Martínez', 'Blvd. Tecnológico', '789', 'B', 'Industrial', 'Ciudad Juárez', 'Chihuahua', '2025-10-02', '2025-10-02');
INSERT INTO `proveedores` VALUES ('4', 'PRO009', 'Ana Maria Sánchez Torres', 'Av. Lincoln', '321', null, 'Campestre', 'Ciudad Juárez', 'Chihuahua', '2025-10-02', '2025-10-02');
INSERT INTO `proveedores` VALUES ('5', 'PRO0010', 'Angel Roberti Payan Díaz', 'Calle Juárez', '654', 'C', 'Zona Dorada', 'Ciudad Juárez', 'Chihuahua', '2025-10-02', '2025-10-02');
INSERT INTO `proveedores` VALUES ('6', 'PROV001', 'CT INTERNACIONAL', 'SANTOS DUMONT', '1252', '', 'DEL MARQUEZ', 'JUAREZ', 'CHIHUAHUA', '2025-10-02', '2025-10-02');
INSERT INTO `proveedores` VALUES ('7', 'BUJANDA', 'EDGAR IVAN BUJANDA LOZOYA', 'AVE LOPEZ MATEOS', '1542', '', 'PARTIDO IGLESIAS', 'JUAREZ', 'CHIHUAHUA', '1990-01-01', '1990-01-01');
DROP TRIGGER IF EXISTS `before_insert_cotizacion`;
DELIMITER ;;
CREATE TRIGGER `before_insert_cotizacion` BEFORE INSERT ON `cotizaciones` FOR EACH ROW BEGIN
    DECLARE siguiente_numero INT;
    DECLARE nuevo_folio VARCHAR(50);
    DECLARE anio_actual INT;
    
    -- Obtener el año actual
    SET anio_actual = YEAR(CURDATE());
    
    -- Obtener el último número de folio del año actual
    SELECT COALESCE(MAX(CAST(SUBSTRING(folio, 10) AS UNSIGNED)), 0) + 1 
    INTO siguiente_numero
    FROM cotizaciones
    WHERE folio LIKE CONCAT('COT-', anio_actual, '-%');
    
    -- Generar nuevo folio: COT-2025-0001
    SET nuevo_folio = CONCAT('COT-', anio_actual, '-', LPAD(siguiente_numero, 4, '0'));
    
    -- Asignar el folio si está vacío
    IF NEW.folio IS NULL OR NEW.folio = '' THEN
        SET NEW.folio = nuevo_folio;
    END IF;
END
;;
DELIMITER ;
