<?php
/**
 * Archivo de Configuración General
 * 
 * Define constantes y configuraciones globales del sistema
 * 
 * @author Tu Nombre
 * @version 1.0
 */

// Configuración de zona horaria
date_default_timezone_set('America/Chihuahua');

// URL base del proyecto (modificar según tu servidor)
define('BASE_URL', 'http://localhost/sistema_mvc/');

// Rutas del proyecto
define('ROOT_PATH', dirname(__DIR__) . '/');
define('CONTROLLERS_PATH', ROOT_PATH . 'controllers/');
define('MODELS_PATH', ROOT_PATH . 'models/');
define('VIEWS_PATH', ROOT_PATH . 'views/');
define('CORE_PATH', ROOT_PATH . 'core/');

// Configuración de errores (cambiar en producción)
define('DEVELOPMENT_MODE', true);

if (DEVELOPMENT_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Nombre del sistema
define('SYSTEM_NAME', 'Sistema De Facturacion Y Cotizaciones');

// Versión del sistema
define('SYSTEM_VERSION', '4.1.0');
define('COMPANY_NAME', 'Enlaza Sistemas S.A. de C.V.'); // ← Agregar esta línea

// Controlador y acción por defecto
define('DEFAULT_CONTROLLER', 'ClientesController');
define('DEFAULT_METHOD', 'index');

/**
 * Función para autocargar clases
 * Carga automáticamente las clases cuando se instancian
 * 
 * @param string $className Nombre de la clase a cargar
 * @return void
 */
spl_autoload_register(function ($className) {
    // Buscar en carpeta core
    if (file_exists(CORE_PATH . $className . '.php')) {
        require_once CORE_PATH . $className . '.php';
        return;
    }
    
    // Buscar en carpeta models
    if (file_exists(MODELS_PATH . $className . '.php')) {
        require_once MODELS_PATH . $className . '.php';
        return;
    }
    
    // Buscar en carpeta controllers
    if (file_exists(CONTROLLERS_PATH . $className . '.php')) {
        require_once CONTROLLERS_PATH . $className . '.php';
        return;
    }
    
    // Buscar en carpeta config
    if (file_exists(ROOT_PATH . 'config/' . $className . '.php')) {
        require_once ROOT_PATH . 'config/' . $className . '.php';
        return;
    }
});
?>